<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();
?>
<div class="btn-group">
    <a href="app.php?page=addsite" class="btn btn-success"><i class="fas fa-plus"></i> <?php lang("new site"); ?></a>
</div>
<table id="cattable" class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-font d-none d-md-inline"></i> <?php lang('site name'); ?></th>
            <th data-priority="2"><i class="fas fa-globe d-none d-md-inline"></i> <?php lang('url'); ?></th>
            <th data-priority="3"><i class="fas fa-paint-brush d-none d-md-inline"></i> <?php lang('theme'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sites = $database->select('sites', [
            'siteid',
            'sitename',
            'url',
            'theme',
            'color'
        ]);
        foreach ($sites as $site) {
            $theme = json_decode(file_get_contents("public/themes/".$site['theme']."/theme.json"), true);
            $themename = $theme["name"];
            ?>
            <tr>
                <td></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="app.php?page=editor&siteid=<?php echo $site['siteid']; ?>"><i class="fas fa-edit"></i> <?php lang("editor"); ?></a>
                    <a class="btn btn-secondary btn-sm" href="app.php?page=sitesettings&siteid=<?php echo $site['siteid']; ?>"><i class="fas fa-cog"></i> <?php lang("settings"); ?></a>
                    <a class="btn btn-info btn-sm" href="<?php echo $site['url']; ?>" target="_BLANK"><i class="fas fa-eye"></i> <?php lang("view"); ?></a>
                </td>
                <td><?php echo $site['sitename']; ?></td>
                <td><?php echo $site['url']; ?></td>
                <td><?php echo $themename; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-font d-none d-md-inline"></i> <?php lang('site name'); ?></th>
            <th data-priority="2"><i class="fas fa-globe d-none d-md-inline"></i> <?php lang('url'); ?></th>
            <th data-priority="3"><i class="fas fa-paint-brush d-none d-md-inline"></i> <?php lang('theme'); ?></th>
        </tr>
    </tfoot>
</table>
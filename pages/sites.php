<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';
require_once __DIR__ . '/../lib/util.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

$showbuttons = true;
if (!$user->hasPermission("SITEWRITER") && !$user->hasPermission("SITEWRITER_EDIT")) {
    $showbuttons = false;
}
?>
<div class="btn-group mb-2">
    <a href="app.php?page=sitesettings" class="btn btn-success"><i class="fas fa-plus"></i> <?php $Strings->get("new site"); ?></a>
</div>
<table class="table table-bordered table-hover table-sm table-responsive-sm">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php $Strings->get('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-font d-none d-md-inline"></i> <?php $Strings->get('site name'); ?></th>
            <th data-priority="2"><i class="fas fa-globe d-none d-md-inline"></i> <?php $Strings->get('url'); ?></th>
            <th data-priority="3" class="d-none d-sm-table-cell"><i class="fas fa-paint-brush d-none d-md-inline"></i> <?php $Strings->get('theme'); ?></th>
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
            $theme = json_decode(file_get_contents("public/themes/" . $site['theme'] . "/theme.json"), true);
            $themename = $theme["name"];
            ?>
            <tr>
                <td></td>
                <td>
                    <?php
                    if ($showbuttons) {
                        ?>
                        <a class="btn btn-primary btn-sm" href="app.php?page=editor&siteid=<?php echo $site['siteid']; ?>"><i class="fas fa-edit"></i> <?php $Strings->get("editor"); ?></a>
                        <a class="btn btn-secondary btn-sm" href="app.php?page=sitesettings&siteid=<?php echo $site['siteid']; ?>"><i class="fas fa-cog"></i> <?php $Strings->get("settings"); ?></a>
                        <?php
                    }
                    ?>
                    <a class="btn btn-info btn-sm" href="<?php echo formatsiteurl($site['url']); ?>" target="_BLANK"><i class="fas fa-eye"></i> <?php $Strings->get("view"); ?></a>
                </td>
                <td><?php echo $site['sitename']; ?></td>
                <td><?php echo $site['url']; ?></td>
                <td class="d-none d-sm-table-cell"><?php echo $themename; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php $Strings->get('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-font d-none d-md-inline"></i> <?php $Strings->get('site name'); ?></th>
            <th data-priority="2"><i class="fas fa-globe d-none d-md-inline"></i> <?php $Strings->get('url'); ?></th>
            <th data-priority="3" class="d-none d-sm-table-cell"><i class="fas fa-paint-brush d-none d-md-inline"></i> <?php $Strings->get('theme'); ?></th>
        </tr>
    </tfoot>
</table>
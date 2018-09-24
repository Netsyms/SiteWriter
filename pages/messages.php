<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

if (!$user->hasPermission("SITEWRITER") && !$user->hasPermission("SITEWRITER_CONTACT")) {
    if ($_GET['msg'] != "no_permission") {
        header("Location: app.php?page=messages&msg=no_permission");
    }
    die();
}
?>
<table id="msgtable" class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php $Strings->get('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-user d-none d-md-inline"></i> <?php $Strings->get('name'); ?></th>
            <th data-priority="2"><i class="fas fa-envelope d-none d-md-inline"></i> <?php $Strings->get('message'); ?></th>
            <th data-priority="3"><i class="fas fa-at d-none d-md-inline"></i> <?php $Strings->get('email'); ?></th>
            <th data-priority="4"><i class="fas fa-globe d-none d-md-inline"></i> <?php $Strings->get('site'); ?></th>
            <th data-priority="5"><i class="fas fa-calendar d-none d-md-inline"></i> <?php $Strings->get('date'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $messages = $database->select('messages', ["[>]sites" => ["siteid"]], [
            'mid',
            'messages.siteid',
            'sites.sitename',
            'name',
            'email',
            'message',
            'date'
        ]);
        foreach ($messages as $m) {
            $mailto = "mailto:"
                    . urlencode($m['name'])
                    . "<" . $m['email'] . ">"
                    . "?subject=" . rawurlencode(htmlspecialchars_decode($m['sitename'] . " contact form reply"))
                    . "&body="
                    . rawurlencode(
                            htmlspecialchars_decode(
                                    "\n\n----------\nOriginal message:\n"
                                    . $m['message']
                            )
            );
            ?>
            <tr>
                <td></td>
                <td>
                    <a
                        class="btn btn-primary btn-sm"
                        href="<?php echo $mailto; ?>"
                        >
                        <i class="fas fa-reply"></i> <?php $Strings->get("reply"); ?>
                    </a>
                    <span
                        class="btn btn-danger btn-sm deletemsgbtn"
                        data-message="<?php echo $m['mid']; ?>"
                        >
                        <i class="fas fa-trash"></i> <?php $Strings->get("delete"); ?>
                    </span>
                </td>
                <td><?php echo $m['name']; ?></td>
                <td><?php echo $m['message']; ?></td>
                <td><?php echo $m['email']; ?></td>
                <td><?php echo $m['sitename']; ?></td>
                <td><?php echo date("M j Y, g:i A", strtotime($m['date'])); ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php $Strings->get('actions'); ?></th>
            <th data-priority="1"><i class="fas fa-user d-none d-md-inline"></i> <?php $Strings->get('name'); ?></th>
            <th data-priority="2"><i class="fas fa-envelope d-none d-md-inline"></i> <?php $Strings->get('message'); ?></th>
            <th data-priority="3"><i class="fas fa-at d-none d-md-inline"></i> <?php $Strings->get('email'); ?></th>
            <th data-priority="4"><i class="fas fa-globe d-none d-md-inline"></i> <?php $Strings->get('site'); ?></th>
            <th data-priority="5"><i class="fas fa-calendar d-none d-md-inline"></i> <?php $Strings->get('date'); ?></th>
        </tr>
    </tfoot>
</table>
<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();
?>
<div class="card-deck">
    <?php
    $visitors = $database->select('analytics', "uuid", ["time[>]" => date("Y-m-d H:i:s", strtotime("-1 day"))]);
    $uuids = [];
    foreach ($visitors as $v) {
        if (!in_array($v, $uuids)) {
            $uuids[] = $v;
        }
    }
    $visits_today = count($uuids);
    $views_today = count($visitors);
    ?>
    <div class="card bg-blue text-light">
        <div class="card-body">
            <h4 class="card-title"><?php lang("today") ?></h4>
            <h1><i class="fas fa-fw fa-users"></i> <?php echo $visits_today; ?> <?php
                if ($visits_today == 1) {
                    lang("visit");
                } else {
                    lang("visits");
                }
                ?></h1>
            <h1><i class="fas fa-fw fa-eye"></i> <?php echo $views_today; ?> <?php
                if ($views_today == 1) {
                    lang("page view");
                } else {
                    lang("page views");
                }
                ?></h1>
        </div>
    </div>
    <?php
    $visitors = $database->select('analytics', "uuid", ["time[>]" => date("Y-m-d H:i:s", strtotime("-1 week"))]);
    $uuids = [];
    foreach ($visitors as $v) {
        if (!in_array($v, $uuids)) {
            $uuids[] = $v;
        }
    }
    $visits_week = count($uuids);
    $views_week = count($visitors);
    ?>
    <div class="card bg-<?php echo ($lowcnt > 0 ? "deep-orange" : "green"); ?> text-light">
        <div class="card-body">
            <h4 class="card-title"><?php lang("this week") ?></h4>
            <h1><i class="fas fa-fw fa-users"></i> <?php echo $visits_week; ?> <?php
                if ($visits_week == 1) {
                    lang("visit");
                } else {
                    lang("visits");
                }
                ?></h1>
            <h1><i class="fas fa-fw fa-eye"></i> <?php echo $views_week; ?> <?php
                if ($views_week == 1) {
                    lang("page view");
                } else {
                    lang("page views");
                }
                ?></h1>
        </div>
    </div>
</div>

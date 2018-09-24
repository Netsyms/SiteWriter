<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

if (!$user->hasPermission("SITEWRITER") && !$user->hasPermission("SITEWRITER_ANALYTICS")) {
    if ($_GET['msg'] != "no_permission") {
        header("Location: app.php?page=analytics&msg=no_permission");
    }
    die();
}

$select_filter = [];

if (isset($VARS['siteid']) && !is_empty($VARS['siteid'])) {
    if ($database->has('sites', ['siteid' => $VARS['siteid']])) {
        $select_filter["analytics.siteid"] = $VARS['siteid'];
    }
}

if (isset($VARS['after']) && !is_empty($VARS['after'])) {
    if (strtotime($VARS['after']) !== FALSE) {
        $select_filter["time[>]"] = date("Y-m-d H:i:s", strtotime($VARS['after']));
    }
}
if (isset($VARS['before']) && !is_empty($VARS['before'])) {
    if (strtotime($VARS['before']) !== FALSE) {
        $select_filter["time[<]"] = date("Y-m-d H:i:s", strtotime($VARS['before']));
    }
}

$where = [];
if (count($select_filter) == 1) {
    $where = $select_filter;
} else if (count($select_filter) > 1) {
    $where = ["AND" => $select_filter];
}

$where["LIMIT"] = 1000;
$where["ORDER"] = ["time" => "DESC"];

$records = $database->select("analytics", [
    "[>]sites" => ["siteid" => "siteid"],
    "[>]pages" => ["pageid" => "pageid"],
        ], [
    "analytics.siteid", "analytics.pageid", "uuid", "country", "region", "city",
    "countrycode", "regioncode",
    "lat", "lon", "time", "pages.title (pagetitle)", "pages.slug (pageslug)",
    "sites.sitename"
        ], $where);

$format = "Y-m-00 00:00:00";
if (count($records) > 1) {
    $max = $records[0];
    $min = $records[count($records) - 1];
    $diff = strtotime($max['time']) - strtotime($min['time']);
} else {
    $diff = 0;
}
if ($diff < 60 * 60) { // 1 hour
    $format = "Y-m-d H:i:00";
} else if ($diff < 60 * 60 * 24 * 3) { // 3 days
    $format = "Y-m-d H:00:00";
} else if ($diff < 60 * 60 * 24 * 60) { // 30 days
    $format = "Y-m-d 00:00:00";
}

$visitors = [];
$viewsovertime = [];
$pages = [];
foreach ($records as $r) {
    if (!array_key_exists($r["uuid"], $visitors)) {
        $visitors[$r["uuid"]] = $r;
    }

    $rf = date($format, strtotime($r['time']));
    if (array_key_exists($rf, $viewsovertime)) {
        $viewsovertime[$rf] ++;
    } else {
        $viewsovertime[$rf] = 1;
    }

    if (array_key_exists($r['pageid'], $pages)) {
        $pages[$r['pageid']]["views"] ++;
    } else {
        $pages[$r['pageid']] = [
            "pagetitle" => $r['pagetitle'],
            "sitename" => $r['sitename'],
            "views" => 1
        ];
    }
}
$pageviews = count($records);
usort($pages, function($a, $b) {
    return $b['views'] <=> $a['views'];
});

require_once __DIR__ . "/../lib/countries_2_3.php";
$countries = [];
$states = [];
$visitsovertime = [];
foreach ($visitors as $r) {
    $rf = date($format, strtotime($r['time']));
    if (array_key_exists($rf, $visitsovertime)) {
        $visitsovertime[$rf] ++;
    } else {
        $visitsovertime[$rf] = 1;
    }
    if (array_key_exists($COUNTRY_CODES[$r['countrycode']], $countries)) {
        $countries[$COUNTRY_CODES[$r['countrycode']]] ++;
    } else {
        $countries[$COUNTRY_CODES[$r['countrycode']]] = 1;
    }
    if ($r['countrycode'] === "US") {
        if (array_key_exists($r['regioncode'], $states)) {
            $states[$r['regioncode']] ++;
        } else {
            $states[$r['regioncode']] = 1;
        }
    }
}
$visits = count($visitors);

$countrymapdata = [];
foreach ($countries as $id => $count) {
    $countrymapdata[] = [$id, $count];
}
$statemapdata = [];
foreach ($states as $id => $count) {
    $statemapdata[] = [$id, $count];
}
?>

<!-- Filter bar -->
<div class="card p-2">
    <form class="form-inline" action="app.php" method="GET">
        <button type="submit" class="btn btn-primary"><i class="fas fa-sync"></i></button>
        <label for="siteid_select" class="sr-only"><?php $Strings->get("filter by site") ?></label>
        <div class="input-group mx-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
            </div>
            <select name="siteid" class="form-control pr-4" id="siteid_select">
                <option value=""><?php $Strings->get("all sites"); ?></option>
                <?php
                $sites = $database->select("sites", ["siteid", "sitename"]);
                foreach ($sites as $s) {
                    $selected = "";
                    if (!empty($select_filter["analytics.siteid"]) && $select_filter["analytics.siteid"] == $s['siteid']) {
                        $selected = "selected";
                    }
                    ?>
                    <option value="<?php echo $s['siteid']; ?>" <?php echo $selected; ?>><?php echo $s['sitename']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <span class="vertline d-none d-lg-inline"></span>

        <label for="date_after" class="sr-only"><?php $Strings->get("filter after date") ?></label>
        <label for="date_before" class="sr-only"><?php $Strings->get("filter before date") ?></label>
        <div class="input-group mx-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
            </div>
            <input type="text" id="date_after" name="after" value="<?php echo htmlspecialchars($VARS['after']); ?>" class="form-control" placeholder="<?php $Strings->get("start date"); ?>" data-toggle="datetimepicker" data-target="#date_after" />
            <div class="input-group-prepend input-group-append">
                <span class="input-group-text"><i class="fas fa-caret-right"></i></span>
            </div>
            <input type="text" id="date_before" name="before" value="<?php echo htmlspecialchars($VARS['before']); ?>" class="form-control" placeholder="<?php $Strings->get("end date"); ?>" data-toggle="datetimepicker" data-target="#date_before" />
        </div>


        <input type="hidden" name="page" value="analytics" />
        <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> <?php $Strings->get("filter"); ?></button>
    </form>
</div>

<!-- Data views -->
<?php
if (count($records) > 0) {
    ?>
    <div class="card-columns mt-4">
        <!-- Overview -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("overview"); ?></h4>
                <?php
                $ratio = round($pageviews / $visits, 1);
                ?>
                <h5>
                    <i class="fas fa-users fa-fw"></i> <?php echo $visits; ?> <?php $Strings->get("visits") ?> <br />
                    <i class="fas fa-eye fa-fw"></i> <?php echo $pageviews; ?> <?php $Strings->get("page views") ?> <br />
                    <i class="fas fa-percent fa-fw"></i> <?php echo $ratio; ?> <?php $Strings->get("views per visit") ?>
                </h5>
            </div>
        </div>

        <!-- Visits Over Time -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("visits over time"); ?></h4>
                <script nonce="<?php echo $SECURE_NONCE; ?>">
                    var visitsOverTimeData = [
    <?php foreach ($visitsovertime as $d => $c) { ?>
                        {
                        x: "<?php echo $d; ?>",
                                y: <?php echo $c; ?>
                        },
    <?php } ?>
                    ];
                </script>
                <div class="w-100 position-relative">
                    <canvas id="visitsOverTime"></canvas>
                </div>
            </div>
        </div>

        <!-- Views Over Time -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("page views over time"); ?></h4>
                <script nonce="<?php echo $SECURE_NONCE; ?>">
                    var viewsOverTimeData = [
    <?php foreach ($viewsovertime as $d => $c) { ?>
                        {
                        x: "<?php echo $d; ?>",
                                y: <?php echo $c; ?>
                        },
    <?php } ?>
                    ];
                </script>
                <div class="w-100 position-relative">
                    <canvas id="viewsOverTime"></canvas>
                </div>
            </div>
        </div>

        <!-- Visitor Map -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("visitor map"); ?></h4>
                <script nonce="<?php echo $SECURE_NONCE; ?>">
                    visitorMap_Countries = <?php echo json_encode($countrymapdata); ?>;
                    visitorMap_States = <?php echo json_encode($statemapdata); ?>;
                </script>
                <div class="w-100" id="visitorMapWorld"></div>
                <div class="w-100" id="visitorMapUSA"></div>
            </div>
        </div>

        <!-- Page Ranking -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("page ranking"); ?></h4>
            </div>
            <div class="list-group">
                <?php
                foreach ($pages as $p) {
                    ?>
                    <div class="list-group-item">
                        <div class="row justify-content-between">
                            <div class="col-6 d-flex flex-column">
                                <span><i class="fas fa-file fa-fw"></i> <?php echo $p["pagetitle"]; ?></span>
                                <span><i class="fas fa-sitemap fa-fw"></i> <?php echo $p["sitename"]; ?></span>
                            </div>
                            <div class="col-6">
                                <span><i class="fas fa-eye fa-fw"></i> <?php $Strings->build("x views", ["views" => $p['views']]); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Recent Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?php $Strings->get("recent actions"); ?></h4>
            </div>
            <div class="list-group list-group-scrolly">
                <?php
                $max = 20;
                $i = 0;
                foreach ($records as $r) {
                    $i++;
                    if ($i > $max) {
                        break;
                    }
                    ?>
                    <div class="list-group-item">
                        <div>
                            <div><i class="fas fa-user fa-fw"></i> <?php echo substr($r["uuid"], 0, 8); ?></div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-12 col-sm-6 d-flex flex-column">
                                <span><i class="fas fa-clock fa-fw"></i> <?php echo date("g:i A", strtotime($r["time"])); ?></span>
                                <span><i class="fas fa-file fa-fw"></i> <?php echo $r["pagetitle"]; ?></span>
                            </div>
                            <div class="col-12 col-sm-6 d-flex flex-column">
                                <span><i class="fas fa-calendar fa-fw"></i> <?php echo date("M j Y", strtotime($r["time"])); ?></span>
                                <span><i class="fas fa-sitemap fa-fw"></i> <?php echo $r["sitename"]; ?></span>
                            </div>
                        </div>
                        <div>
                            <div><i class="fas fa-globe fa-fw"></i> <?php echo $r["country"]; ?></div>
                            <div><i class="fas fa-map-marker fa-fw"></i> <?php echo $r["city"] . ", " . $r["region"]; ?></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="row mt-3 justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-info-circle"></i> <?php $Strings->get("no data"); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
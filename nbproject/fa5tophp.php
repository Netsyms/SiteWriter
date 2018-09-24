<?php

// Script to convert icons.json in the FontAwesome download into a PHP array for SiteWriter

$json = file_get_contents("/home/skylar/Downloads/fontawesome-free-5.3.1-web/metadata/icons.json");
$icons = json_decode($json, true);
$output = [];
foreach ($icons as $icon => $data) {
	$meta = [];
	$meta["label"] = $data["label"];
	$meta["search"] = $data["search"]["terms"];
	foreach ($data["styles"] as $s) {
		$class = "fa";
		switch ($s) {
			case "solid":
				$class = "fas";
				break;
			case "regular":
				$class = "far";
				break;
			case "brands":
				$class = "fab";
				break;
		}
		$output["$class fa-$icon"] = $meta;
	}
}

var_export($output);

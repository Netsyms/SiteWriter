<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . '/../required.php';

dieifnotloggedin();

header('Content-Type: application/json');

Crew\Unsplash\HttpClient::init([
    'applicationId' => UNSPLASH_ACCESSKEY,
    'secret' => UNSPLASH_SECRETKEY,
    'utmSource' => UNSPLASH_UTMSOURCE
]);

$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
}

$per_page = 15;

$results = null;

if (isset($_GET['query']) && $_GET['query'] != "") {
    $results = Crew\Unsplash\Search::photos($_GET['query'], $page, $per_page, null, null);
    $images = $results->getArrayObject();
} else {
    $images = Crew\Unsplash\Photo::all($page, $per_page, 'popular');
}

$images->

$htmlout = "";

if (count($images) == 0) {
    $htmlout = "<div class=\"card text-center\"><div class=\"card-body\"><i class=\"fas fa-search-minus\"></i> " . $Strings->get("no results", false) . "</div></div>";
}

$htmlout .= '<div class="card-columns">';

foreach ($images as $img) {
    $imageid = $img->id;
    $description = $img->description;
    $thumb = $img->urls['thumb'];
    $image = $img->urls['regular'];
    $attribution = '<a href="' . $img->user['links']['html'] . '" target="_BLANK">' . $img->user['name'] . '</a>';
    $card = <<<END
<div class="card m-1 filepicker-unsplashimg">
    <img class="card-img-top" src="$thumb" alt="$description" data-path="$image" data-imageid="$imageid" />
    <div class="card-img-overlay unsplash-attribution">
        <p class="card-text">By $attribution</p>
    </div>
</div>
END;
    $htmlout .= $card;
}
$htmlout .= '</div>';

$jsonout = [
    'total' => null,
    'pages' => null,
    'page' => $page,
    'html' => $htmlout
];

if (!is_null($results)) {
    $jsonout['total'] = $Strings->build("x results", ["results" => $results->getTotal()], false);
    $jsonout['pages'] = $results->getTotalPages();
}

echo json_encode($jsonout);
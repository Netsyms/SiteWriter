<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// List of pages and metadata
define("PAGES", [
    "home" => [
        "title" => "home",
        "navbar" => true,
        "icon" => "fas fa-home"
    ],
    "sites" => [
        "title" => "sites",
        "navbar" => true,
        "icon" => "fas fa-sitemap"
    ],
    "sitesettings" => [
        "title" => "settings",
        "styles" => [
            "static/css/themeselector.css"
        ],
        "scripts" => [
            "static/js/sitesettings.js"
        ]
    ],
    "editor" => [
        "title" => "editor",
        "styles" => [
            "static/css/editorparent.css"
        ],
        "scripts" => [
            "static/js/editorparent.js"
        ]
    ],
    "analytics" => [
        "title" => "analytics",
        "navbar" => true,
        "icon" => "fas fa-chart-bar",
        "styles" => [
            "static/css/tempusdominus-bootstrap-4.min.css",
            "static/css/vertline.css",
            "static/css/sane_columns.css",
            "static/css/analy_reports.css"
        ],
        "scripts" => [
            "static/js/moment.min.js",
            "static/js/Chart.min.js",
            "static/js/topojson.min.js",
            "static/js/d3.min.js",
            "static/js/datamaps.all.min.js",
            "static/js/tempusdominus-bootstrap-4.min.js",
            "static/js/analy_reports.js"
        ]
    ],
    "404" => [
        "title" => "404 error"
    ]
]);

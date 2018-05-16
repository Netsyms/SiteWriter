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
    "files" => [
        "title" => "files",
        "navbar" => true,
        "icon" => "fas fa-folder",
        "styles" => [
            "static/css/files.css"
        ],
        "scripts" => [
            "static/js/files.js"
        ]
    ],
    "sitesettings" => [
        "title" => "settings",
        "styles" => [
            "static/css/sane_columns.css",
            "static/css/themeselector.css",
        ],
        "scripts" => [
            "static/js/sitesettings.js"
        ]
    ],
    "editor" => [
        "title" => "editor",
        "styles" => [
            "static/css/editorparent.css",
            "static/css/iconselector.css",
            "static/css/filepicker.css",
        ],
        "scripts" => [
            "static/js/html5sortable.min.js",
            "static/js/iconselector.js",
            "static/js/filepicker_local.js",
            "static/js/filepicker_unsplash.js",
            "static/js/editorparent.js",
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
    "messages" => [
        "title" => "messages",
        "navbar" => true,
        "icon" => "fas fa-envelope",
        "styles" => [
            "static/css/datatables.min.css",
            "static/css/tables.css",
        ],
        "scripts" => [
            "static/js/datatables.min.js",
            "static/js/messages.js"
        ]
    ],
    "404" => [
        "title" => "404 error"
    ]
]);

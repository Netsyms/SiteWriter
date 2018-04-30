<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function formatsiteurl($url) {
    if (substr($url, 0) != "/") {
        if (strpos($url, "http://") !== 0 && strpos($url, "https://") !== 0) {
            $url = "http://$url";
        }
    }
    if (substr($url, -1) != "/") {
        $url = $url . "/";
    }
    return $url;
}
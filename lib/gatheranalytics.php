<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

use GeoIp2\Database\Reader;

// Override with a valid public IP when testing on localhost
//$_SERVER['REMOTE_ADDR'] = "206.127.96.82";

require_once __DIR__ . "/requiredpublic.php";

if (!$database->has("settings", ["AND" => ["siteid" => getsiteid(), "key" => "analytics", "value" => "off"]]) && !isset($_GET['edit'])) {

    try {

        if (isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] === "1") {
            throw new Exception("Do-Not-Track header detected, skipping analytics");
        }

        $bots = json_decode(file_get_contents(__DIR__ . "/bots.json"), true);
        foreach ($bots as $bot) {
            if (preg_match('/' . $bot['pattern'] . '/', $_SERVER['HTTP_USER_AGENT'])) {
                throw new Exception("Bot/crawler detected, skipping analytics");
            }
        }

        $time = date("Y-m-d H:i:s");

        /**
         * https://stackoverflow.com/a/2040279
         */
        function gen_uuid() {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    // 32 bits for "time_low"
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    // 16 bits for "time_mid"
                    mt_rand(0, 0xffff),
                    // 16 bits for "time_hi_and_version",
                    // four most significant bits holds version number 4
                    mt_rand(0, 0x0fff) | 0x4000,
                    // 16 bits, 8 bits for "clk_seq_hi_res",
                    // 8 bits for "clk_seq_low",
                    // two most significant bits holds zero and one for variant DCE1.1
                    mt_rand(0, 0x3fff) | 0x8000,
                    // 48 bits for "node"
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }

//
// Read/set the cookie
//

        if (isset($_COOKIE['sw-uuid'])) {
            $uuid = $_COOKIE['sw-uuid'];
        } else {
            $uuid = gen_uuid();
        }

        setcookie("sw-uuid", $uuid, time() + 60 * 60 * 1, "/", $_SERVER['HTTP_HOST'], false, true);

//
// Get the user's IP address
//

        $clientip = $_SERVER['REMOTE_ADDR'];

// Check if we're behind CloudFlare and adjust accordingly
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && validateCloudflare()) {
            $clientip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

//
// Lookup IP address
//

        $reader = new Reader($SETTINGS["geoip_db"]);

        $record = $reader->city($clientip);

        $country = $record->country->name;
        $region = $record->mostSpecificSubdivision->name;
        $city = $record->city->name;
        $countrycode = $record->country->isoCode;
        $regioncode = $record->mostSpecificSubdivision->isoCode;
        $lat = $record->location->latitude;
        $lon = $record->location->longitude;

//
// Save the page visit
//

        $database->insert("analytics", [
            "siteid" => getsiteid(),
            "pageid" => getpageid(),
            "uuid" => $uuid,
            "country" => $country,
            "region" => $region,
            "city" => $city,
            "countrycode" => $countrycode,
            "regioncode" => $regioncode,
            "lat" => $lat,
            "lon" => $lon,
            "time" => $time
        ]);
    } catch (GeoIp2\Exception\AddressNotFoundException $e) {
        if ($SETTINGS["debug"]) {
            echo "<!-- The client IP was not found in the GeoIP database. -->";
        }
    } catch (Exception $e) {
        // Silently fail so the rest of the site still works
        if ($SETTINGS["debug"]) {
            echo "<!-- Analytics error: " . $e->getMessage() . " -->";
        }
    }
}
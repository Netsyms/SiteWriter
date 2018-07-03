<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Whether to show debugging data in output.
// DO NOT SET TO TRUE IN PRODUCTION!!!
define("DEBUG", false);

// Database connection settings
// See http://medoo.in/api/new for info
define("DB_TYPE", "mysql");
define("DB_NAME", "sitewriter");
define("DB_SERVER", "localhost");
define("DB_USER", "sitewriter");
define("DB_PASS", "");
define("DB_CHARSET", "utf8");

// Name of the app.
define("SITE_TITLE", "SiteWriter");

define("SMTP_HOST", "");
define("SMTP_AUTH", true);
define("SMTP_SECURITY", "tls"); // tls, ssl, or none
define("SMTP_PORT", 25);
define("SMTP_USERNAME", "");
define("SMTP_PASSWORD", "");
define("SMTP_FROMADDRESS", "sitewriter@example.com");
define("SMTP_FROMNAME", "SiteWriter");

// URL of the AccountHub API endpoint
define("PORTAL_API", "http://localhost/accounthub/api.php");
// URL of the AccountHub home page
define("PORTAL_URL", "http://localhost/accounthub/home.php");
// AccountHub API Key
define("PORTAL_KEY", "123");

// For supported values, see http://php.net/manual/en/timezones.php
define("TIMEZONE", "America/Denver");

// Base URL for site links.
define('URL', '/sitewriter');

// Folder for public files
// This should not be inside the web root for security reasons.
define('FILE_UPLOAD_PATH', __DIR__ . '/public/files');

// Use pretty URLs (requires correct web server configuration)
define('PRETTY_URLS', false);

// Location of MaxMind GeoIP database
//
// I'll just leave this here:
// This product includes GeoLite2 data created by MaxMind, available from
// http://www.maxmind.com
define('GEOIP_DB', __DIR__ . "/GeoLite2-City.mmdb");

// Unsplash photo integration
define('ENABLE_UNSPLASH', false);
define('UNSPLASH_APPID', '');
define('UNSPLASH_ACCESSKEY', '');
define('UNSPLASH_SECRETKEY', '');
define('UNSPLASH_UTMSOURCE', 'SiteWriter');

// Use Captcheck on login screen
// https://captcheck.netsyms.com
define("CAPTCHA_ENABLED", FALSE);
define('CAPTCHA_SERVER', 'https://captcheck.netsyms.com');

// See lang folder for language options
define('LANGUAGE', "en_us");


define("FOOTER_TEXT", "");
define("COPYRIGHT_NAME", "Netsyms Technologies");

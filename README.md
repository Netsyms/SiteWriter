SiteWriter
==========

A public website builder.

Features
--------

__Simple Editor__
Create awesome websites with zero coding or design experience.

__Themes and Templates__
Choose from a variety of website themes, color variations, and page templates.

__Multi-site__
Build and manage multiple websites at the same time

__File Manager__
Upload pictures and files and add them to your sites with a simple file browser tool.

__Analytics__
See visitor location, page views, and more with a built-in analytics dashboard.

__Contact Forms__
Simply create a page with a contact form template and start receiving and replying to messages from a dashboard.


Installing
----------

0. Follow the installation directions for [AccountHub](https://source.netsyms.com/Business/AccountHub), then download this app somewhere.
1. Copy `settings.template.php` to `settings.php`
2. Import `database.sql` into your database server
3. Edit `settings.php` and fill in your database settings
4. Create a folder outside the webroot for FILE_UPLOAD_PATH in settings.php
5. Download the MaxMind GeoLite2 City database from https://dev.maxmind.com/geoip/geoip2/geolite2/ and set GEOIP_DB to its location
6. Set the location of the AccountHub API in `settings.php` (see "PORTAL_API") and enter an API key ("PORTAL_KEY")
7. Set the location of the AccountHub home page ("PORTAL_URL")
8. Set the URL of this app ("URL")
9. Copy webroot.htaccess to your webroot and adjust paths if needed
10. Run `composer install` (or `composer.phar install`) to install dependency libraries
11. Run `git submodule init` and `git submodule update` to install other dependencies via git.

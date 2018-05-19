<!DOCTYPE HTML>
<!--
    Phantom by HTML5 UP
    html5up.net | @ajlkn
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<title><?php get_site_name(); ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--[if lte IE 8]><script src="<?php get_theme_url(); ?>/assets/js/ie/html5shiv.js"></script><![endif]-->
<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/main.css" />
<!--[if lte IE 9]><link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/ie9.css" /><![endif]-->
<!--[if lte IE 8]><link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/ie8.css" /><![endif]-->
<link rel="stylesheet" href="<?php get_fontawesome_css(); ?>" />
<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/sitewriter.css" />

<!-- Wrapper -->
<div id="wrapper">

    <!-- Header -->
    <header id="header">
        <div class="inner">

            <!-- Logo -->
            <a href="<?php get_page_url(true, "index"); ?>" class="logo">
                <!--<span class="symbol"><img src="images/logo.svg" alt="" /></span>-->
                <span class="title"><?php get_site_name(); ?></span>
            </a>

            <!-- Nav -->
            <nav>
                <ul>
                    <li><a href="#menu">Menu</a></li>
                </ul>
            </nav>

        </div>
    </header>

    <!-- Menu -->
    <nav id="menu">
        <h2>Menu</h2>
        <ul>
            <?php get_navigation(); ?>
        </ul>
    </nav>
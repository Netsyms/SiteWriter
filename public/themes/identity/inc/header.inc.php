<!DOCTYPE HTML>
<!--
        Identity by HTML5 UP
        html5up.net | @ajlkn
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<title><?php get_site_name(); ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!--[if lte IE 8]><script src="<?php get_theme_url(); ?>/assets/js/html5shiv.js"></script><![endif]-->
<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/main.css" />
<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/fontawesome-all.min.css" />
<!--[if lte IE 9]><link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/ie9.css" /><![endif]-->
<!--[if lte IE 8]><link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/ie8.css" /><![endif]-->
<?php get_header(); ?>

<noscript><link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/noscript.css" /></noscript>
<div class="is-loading">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <section id="main">
            <header>
                <?php
                $img = get_complex_component("header-img", "index", ["image"]);
                $url = "file.php?file=" . $img['image'];
                ?>
                <span class="avatar sw-complex" data-json="<?php echo get_escaped_json($img); ?>" data-component="header-img">
                    <img src="<?php echo $url; ?>" alt="" />
                </span>
                <h1><?php get_page_clean_title(); ?></h1>
                <p><div class="sw-text" data-component="lead">
                    <?php get_component("lead"); ?>
                </div></p>
            </header>

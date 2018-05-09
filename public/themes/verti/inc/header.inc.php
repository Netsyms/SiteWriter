<?php
header("Link: <" . get_fontawesome_css(false) . ">; rel=preload; as=style", false);
header("Link: <https://static.netsyms.net/fonts/oleo-script/Oleo_Script.css>; rel=preload; as=style", false);
header("Link: <https://static.netsyms.net/fonts/open-sans/Open_Sans.css>; rel=preload; as=style", false);
?>
<!DOCTYPE HTML>
<!--
    Verti by HTML5 UP
    html5up.net | @ajlkn
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<title><?php get_site_name(); ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="<?php get_fontawesome_css(); ?>" />
<link rel="stylesheet" href="<?php get_theme_color_url(); ?>/main.css" />
<?php get_header(); ?>

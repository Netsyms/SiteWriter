<?php include __DIR__ . "/inc/header.inc.php"; ?>
<link rel="stylesheet" href="<?php get_theme_url(); ?>/assets/css/home.css" />
<!-- Wrapper -->
<div id="wrapper">

    <!-- Header -->
    <header id="header">
        <?php
        $icon = get_complex_component("header-icon", null, ['icon']);
        ?>
        <div class="logo sw-complex" data-json="<?php get_escaped_json($icon); ?>" data-component="header-icon">
            <span class="icon <?php echo $icon['icon']; ?>"></span>
        </div>
        <div class="content">
            <div class="inner">
                <h1 class="sw-text" data-component="banner-title"><?php get_component("banner-title"); ?></h1>
                <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
            </div>
        </div>
        <nav>
            <ul>
                <?php get_navigation(); ?>
            </ul>
        </nav>
    </header>

    <!-- Main -->
    <div id="main">
    </div>
    
    <?php include __DIR__ . "/inc/bg-edit.inc.php"; ?>

    <!-- Footer -->
    <footer id="footer">
        <p class="copyright"><?php output_conditional("&copy; " . date('Y') . " [[VAR]].", get_setting("businessname")); ?> Design: <a href="https://html5up.net">HTML5 UP</a> and <a href="https://netsyms.com">Netsyms</a>.</p>
    </footer>

</div>

<?php include __DIR__ . "/inc/footer.inc.php"; ?>
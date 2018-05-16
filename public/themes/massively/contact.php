<?php include __DIR__ . "/inc/head.inc.php"; ?>
<div class="is-loading">

    <!-- Wrapper -->
    <div id="wrapper">

        <?php include __DIR__ . "/inc/header.inc.php"; ?>

        <!-- Main -->
        <div id="main">

            <!-- Post -->
            <section class="post">
                <header class="major">
                    <h1><?php get_page_clean_title(); ?></h1>
                    <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
                </header>
                <div class="sw-editable" data-component="content">
                    <?php get_page_content(); ?>
                </div>
            </section>

        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>

    </div>
</div>
<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
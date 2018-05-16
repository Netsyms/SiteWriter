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
                    <!--<span class="date">April 25, 2017</span>-->
                    <h1><?php get_page_clean_title(); ?></h1>
                    <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
                </header>
                <?php
                if (!is_complex_empty("page-image")) {
                    $image = get_complex_component("page-image", null, ['image']);
                    ?>
                    <div class="image main sw-complex" data-json="<?php get_escaped_json($image); ?>" data-component="page-image"><img src="<?php get_file_url($image['image']); ?>" alt="" /></div>
                    <?php
                }
                ?>

                <div class="sw-editable" data-component="content">
                    <?php get_page_content(); ?>
                </div>
            </section>

        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>

    </div>
</div>
<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
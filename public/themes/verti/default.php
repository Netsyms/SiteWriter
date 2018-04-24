<?php include __DIR__ . "/inc/header.inc.php"; ?>
<div class="no-sidebar">
    <div id="page-wrapper">
        <?php include __DIR__ . "/inc/nav.inc.php"; ?>

        <!-- Main -->
        <div id="main-wrapper">
            <div class="container">
                <div id="content">

                    <!-- Content -->
                    <article>

                        <h2 style="margin-bottom: 10px;"><?php get_page_clean_title(); ?></h2>
                        <h3>
                            <span class="sw-text" data-component="lead">
                                <?php get_component("lead"); ?>
                            </span>
                        </h3>

                        <div class="sw-editable" data-component="content">
                            <?php get_page_content(); ?>
                        </div>

                    </article>

                </div>
            </div>
        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>
    </div>
</div>
<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
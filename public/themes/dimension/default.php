<?php include __DIR__ . "/inc/header.inc.php"; ?>
<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <div id="main">

        <!-- Intro -->
        <article>
            <h2 class="major"><?php get_page_clean_title(); ?></h2>
            <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
            <?php
            if (!is_complex_empty("page-image")) {
                $image = get_complex_component("page-image", null, ['image']);
                ?>
                <span class="image main sw-complex" data-json="<?php get_escaped_json($image); ?>" data-component="page-image"><img src="<?php get_file_url($image['image']); ?>" alt="" /></span>
                <?php
            }
            ?>
            <div class="sw-editable" data-component="content"><?php get_page_content(); ?></div>
            <a class="close" href="<?php get_url_or_slug("index"); ?>">Close</a>
        </article>

    </div>

    <?php include __DIR__ . "/inc/bg-edit.inc.php"; ?>

</div>

<?php include __DIR__ . "/inc/footer.inc.php"; ?>
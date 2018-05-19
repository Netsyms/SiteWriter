<?php include __DIR__ . "/inc/header.inc.php"; ?>

<!-- Main -->
<div id="main">
    <div class="inner">
        <h1><?php get_page_clean_title(); ?></h1>
        <?php
        if (!is_complex_empty("page-image")) {
            $image = get_complex_component("page-image", null, ['image']);
            ?>
            <span class="image main sw-complex" data-json="<?php get_escaped_json($image); ?>" data-component="page-image"><img src="<?php get_file_url($image['image']); ?>" alt="" /></span>
            <?php
        }
        ?>
        <div class="sw-editable" data-component="content">
            <?php get_page_content(); ?>
        </div>
    </div>
</div>

<?php include __DIR__ . "/inc/footer.inc.php"; ?>
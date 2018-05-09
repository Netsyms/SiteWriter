<?php
include __DIR__ . "/inc/head.inc.php";
include __DIR__ . "/inc/header.inc.php";
?>
<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <section id="main" class="wrapper">
        <div class="inner">
            <h1 class="major"><?php get_page_title(); ?></h1>
            <?php
            $image = get_complex_component("page-image", null, ['image']);
            ?>
            <span class="image fit sw-complex" data-json="<?php echo get_escaped_json($image); ?>" data-component="page-image">
                <img src="<?php echo "file.php?file=" . $image['image']; ?>" alt="" />
            </span>
            <div class="sw-editable" data-component="content">
                <?php get_page_content(); ?>
            </div>
        </div>
    </section>

</div>
<?php
include __DIR__ . "/inc/footer.inc.php";
?>
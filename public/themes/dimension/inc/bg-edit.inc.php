<?php
if (getpageslug() == "index") {
    $bg = get_complex_component("page-bg-image", null, ['image']);
    ?>
    <span class="sw-complex" data-json="<?php get_escaped_json($bg); ?>" data-component="page-bg-image"></span>
    <?php
}
?>
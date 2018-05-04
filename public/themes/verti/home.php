<?php include __DIR__ . "/inc/header.inc.php"; ?>
<div class="homepage">
    <div id="page-wrapper">
        <?php include __DIR__ . "/inc/nav.inc.php"; ?>

        <!-- Banner -->
        <div id="banner-wrapper">
            <div id="banner" class="box container">
                <div class="row">
                    <div class="7u 12u(medium)">
                        <h2 class="sw-text" data-component="banner-title"><?php get_component("banner-title"); ?></h2>
                        <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
                    </div>
                    <div class="5u 12u(medium)">
                        <ul>
                            <li>
                                <?php
                                if (!is_complex_empty("banner-btn-1")) {
                                    $btn = get_complex_component("banner-btn-1");
                                    $icon = $btn['icon'];
                                    $link = $btn['link'];
                                    $text = $btn['text'];
                                    ?>
                                    <a href="<?php get_url_or_slug($link); ?>" class="button big icon <?php echo $icon; ?> sw-complex" data-json="<?php echo get_escaped_json($btn); ?>" data-component="banner-btn-1">
                                        <?php echo $text; ?>
                                    </a>
                                <?php } ?>
                            </li>
                            <li>
                                <?php
                                if (!is_complex_empty("banner-btn-2")) {
                                    $btn = get_complex_component("banner-btn-2");
                                    $icon = $btn['icon'];
                                    $link = $btn['link'];
                                    $text = $btn['text'];
                                    ?>
                                    <a href="<?php get_url_or_slug($link); ?>" class="button alt big icon <?php echo $icon; ?> sw-complex" data-json="<?php echo get_escaped_json($btn); ?>" data-component="banner-btn-2">
                                        <?php echo $text; ?>
                                    </a>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div id="features-wrapper">
            <div class="container">
                <div class="row">
                    <?php
                    $count = 0;
                    if (!is_component_empty("cardrow-1")) {
                        $count++;
                    }
                    if (!is_component_empty("cardrow-2")) {
                        $count++;
                    }
                    if (!is_component_empty("cardrow-3")) {
                        $count++;
                    }
                    $width = 4;
                    if ($count > 0) {
                        $width = 12 / $count;
                    }


                    for ($i = 1; $i <= 3; $i++) {
                        $w = $width . "u";
                        $content = <<<END
                        <div class="$w 12u(medium)">
                            <section class="box feature">
                                <div class="inner">
                                    <div class="sw-editable" data-component="cardrow-$i">
                                        [[VAR]]
                                    </div>
                                </div>
                            </section>
                        </div>
END;
                        output_conditional($content, get_component("cardrow-$i", null, false));
                    }
                    ?>

                </div>
            </div>
        </div>

        <!-- Main -->
        <div id="main-wrapper">
            <div class="container">
                <div class="row 200%">
                    <div class="4u 12u(medium)">

                        <!-- Sidebar -->
                        <div id="sidebar">
                            <section class="widget thumbnails">
                                <h3 class="sw-text" data-component="sidebar-header"><?php get_component("sidebar-header"); ?></h3>

                                <div class="sw-editable" data-component="sidebar">
                                    <?php get_component("sidebar"); ?>
                                </div>
                                <?php
                                if (!is_complex_empty("sidebar-btn")) {
                                    $btn = get_complex_component("sidebar-btn");
                                    $icon = $btn['icon'];
                                    $link = $btn['link'];
                                    $text = $btn['text'];
                                    ?>
                                    <a href="<?php get_url_or_slug($link); ?>" class="button icon <?php echo $icon; ?> sw-complex" data-json="<?php echo get_escaped_json($btn); ?>" data-component="sidebar-btn">
                                        <?php echo $text; ?>
                                    </a>
                                <?php } ?>
                            </section>
                        </div>

                    </div>
                    <div class="8u 12u(medium) important(medium)">

                        <!-- Content -->
                        <div id="content">
                            <section class="last">
                                <div class="sw-editable" data-component="two">
                                    <?php get_component("two"); ?>
                                </div>

                                <?php
                                if (!is_complex_empty("two-btn")) {
                                    $btn = get_complex_component("two-btn");
                                    $icon = $btn['icon'];
                                    $link = $btn['link'];
                                    $text = $btn['text'];
                                    ?>
                                    <a href="<?php get_url_or_slug($link); ?>" class="button icon <?php echo $icon; ?> sw-complex" data-json="<?php echo get_escaped_json($btn); ?>" data-component="two-btn">
                                        <?php echo $text; ?>
                                    </a>
                                <?php } ?>
                            </section>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>

    </div>
</div>

<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
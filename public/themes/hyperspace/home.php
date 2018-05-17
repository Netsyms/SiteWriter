<?php
include __DIR__ . "/inc/head.inc.php";
?>

<!-- Sidebar -->
<section id="sidebar">
    <div class="inner">
        <nav>
            <ul>
                <?php
                output_conditional('<li><a href="#intro"><span class="sw-text" data-component="section-intro-title">[[VAR]]</span></a></li>', get_component("section-intro-title", null, false));
                $one = false;
                for ($i = 1; $i <= 3; $i++) {
                    if (!is_component_empty("one-row-header-$i")) {
                        $one = true;
                    }
                }
                if ($one) {
                    output_conditional('<li><a href="#one"><span class="sw-text" data-component="section-one-title">[[VAR]]</span></a></li>', get_component("section-one-title", null, false));
                }
                if (!is_component_empty("two-header") || !is_component_empty("two-lead") || !is_complex_empty("two-btn")) {
                    output_conditional('<li><a href="#two"><span class="sw-text" data-component="section-two-title">[[VAR]]</span></a></li>', get_component("section-two-title", null, false));
                }
                output_conditional('<li><a href="#three"><span class="sw-text" data-component="section-three-title">[[VAR]]</span></a></li>', get_component("section-three-title", null, false));
                ?>
            </ul>
        </nav>
    </div>
</section>

<!-- Wrapper -->
<div id="wrapper">

    <!-- Intro -->
    <section id="intro" class="wrapper style1 fullscreen fade-up">
        <div class="inner">
            <h1 class="sw-text" data-component="banner-title"><?php get_component("banner-title"); ?></h1>
            <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
            <ul class="actions">
                <?php
                if (!is_complex_empty("banner-btn-1")) {
                    $btn = get_complex_component("banner-btn-1", null, ['icon', 'text']);
                    ?>
                    <li>
                        <a href="#one" class="button scrolly sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="banner-btn-1">
                            <i class="<?php echo $btn['icon']; ?>"></i> <?php echo $btn['text']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </section>

    <!-- One -->
    <section id="one" class="wrapper style2 spotlights">
        <?php
        for ($i = 1; $i <= 3; $i++) {
            if (is_component_empty("one-row-header-$i")) {
                continue;
            }
            $image = get_complex_component("one-row-image-$i", null, ['image', 'link']);
            $btn = get_complex_component("one-row-btn-$i", null, ['icon', 'link', 'text']);
            ?>
            <section>
                <a href="<?php get_url_or_slug($image['link']); ?>" class="image">
                    <img src="<?php get_file_url($image['image']); ?>" alt="" data-position="center center" />
                </a>
                <span class="sw-complex" data-json="<?php get_escaped_json($image); ?>" data-component="<?php echo "one-row-image-$i"; ?>"></span>
                <div class="content">
                    <div class="inner">
                        <h2 class="sw-text" data-component="<?php echo "one-row-header-$i"; ?>"><?php get_component("one-row-header-$i"); ?></h2>
                        <p class="sw-text" data-component="<?php echo "one-row-text-$i"; ?>"><?php get_component("one-row-text-$i"); ?></p>
                        <?php
                        if (!is_complex_empty("one-row-btn-$i")) {
                            ?>
                            <ul class="actions">
                                <li>
                                    <a href="<?php get_url_or_slug($btn['link']); ?>" class="button sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="<?php echo "one-row-btn-$i"; ?>">
                                        <i class="<?php echo $btn['icon']; ?>"></i> <?php echo $btn['text']; ?>
                                    </a>
                                </li>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
            <?php
        }
        ?>
    </section>

    <?php
    if (!is_component_empty("two-header") || !is_component_empty("two-lead") || !is_complex_empty("two-btn")) {
        ?>
        <!-- Two -->
        <section id="two" class="wrapper style3 fade-up">
            <div class="inner">
                <h2 class="sw-text" data-component="two-header"><?php get_component("two-header"); ?></h2>
                <p class="sw-text" data-component="two-lead"><?php get_component("two-lead"); ?></p>
                <?php
                $showfeatures = false;
                for ($i = 1; $i <= 6; $i++) {
                    if (!is_complex_empty("two-row-$i")) {
                        $showfeatures = true;
                    }
                }
                if ($showfeatures) {
                    ?>
                    <div class="features">
                        <?php
                        for ($i = 1; $i <= 6; $i++) {
                            if (is_complex_empty("two-row-$i")) {
                                continue;
                            }
                            $row = get_complex_component("two-row-$i", null, ['icon', 'text']);
                            ?>
                            <section>
                                <span class="sw-complex" data-json="<?php get_escaped_json($row); ?>" data-component="<?php echo "two-row-$i"; ?>"></span>
                                <?php
                                if ($row['icon'] != "" || isset($_GET['edit'])) {
                                    ?>
                                    <span class="icon major <?php echo $row['icon']; ?>"></span>
                                    <?php
                                }
                                ?>
                                <h3><?php echo $row['text']; ?></h3>
                                <p class="sw-editable" data-component="two-row-text-<?php echo $i; ?>">
                                    <?php get_component("two-row-text-$i"); ?>
                                </p>
                            </section>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                $btn = get_complex_component("two-btn", null, ['icon', 'link', 'text']);
                if (!is_complex_empty("two-btn")) {
                    ?>
                    <ul class="actions">
                        <li>
                            <a href="<?php get_url_or_slug($btn['link']); ?>" class="button sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="<?php echo "two-btn"; ?>">
                                <i class="<?php echo $btn['icon']; ?>"></i> <?php echo $btn['text']; ?>
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </section>
        <?php
    }
    ?>

    <!-- Three -->
    <section id="three" class="wrapper style1 fade-up">
        <div class="inner">
            <h2 class="sw-text" data-component="contact-heading"><?php get_component("contact-heading"); ?></h2>
            <p class="sw-text" data-component="contact-lead"><?php get_component("contact-lead"); ?></p>
            <div class="split style1">
                <section>
                    <form method="post" action="contact.php">
                        <div class="field half first">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" required />
                        </div>
                        <div class="field half">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required />
                        </div>
                        <div class="field">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="5" required ></textarea>
                        </div>
                        <ul class="actions">
                            <li><button type="submit" class="button submit">Send Message</button></li>
                        </ul>
                    </form>
                </section>
                <section>
                    <ul class="contact">
                        <?php
                        format_special(get_setting("address"), SPECIAL_TYPE_ADDRESS, '<li><h3>Address</h3> <a href="[[CONTENT]]">[[TITLE]]</a></li>');
                        format_special(get_setting("email"), SPECIAL_TYPE_EMAIL, '<li><h3>Email</h3> <a href="[[CONTENT]]">[[TITLE]]</a></li>');
                        format_special(get_setting("phone"), SPECIAL_TYPE_PHONE, '<li><h3>Phone</h3> <a href="[[CONTENT]]">[[TITLE]]</a></li>');
                        ?>
                        <?php
                        $social = get_socialmedia_urls();
                        if (count($social) > 0) {
                            ?>
                            <li>
                                <h3>Social</h3>
                                <ul class="icons">
                                    <?php
                                    foreach ($social as $s) {
                                        ?>
                                        <li>
                                            <a href="<?php echo $s['url']; ?>" class="<?php echo $s['icon']; ?>">
                                                <span class="label"><?php echo $s['name']; ?></span>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </section>
            </div>
        </div>
    </section>

</div>

<?php
include __DIR__ . "/inc/footer.inc.php";
?>
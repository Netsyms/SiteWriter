<?php include __DIR__ . "/inc/head.inc.php"; ?>
<div class="is-loading">

    <!-- Wrapper -->
    <div id="wrapper" class="fade-in">

        <!-- Intro -->
        <div id="intro">
            <h1 class="sw-text" data-component="banner-title"><?php get_component("banner-title"); ?></h1>
            <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
            <ul class="actions">
                <li><a href="#header" class="button icon solo fas fa-arrow-down scrolly">Continue</a></li>
            </ul>
        </div>

        <?php include __DIR__ . "/inc/header.inc.php"; ?>

        <!-- Main -->
        <div id="main">

            <!-- Featured Post -->
            <article class="post featured">
                <header class="major">
                    <h2 class="sw-text" data-component="featured-header"><?php get_component("featured-header"); ?></h2>
                    <p class="sw-text" data-component="featured-text"><?php get_component("featured-text"); ?></p>
                </header>
                <?php
                if (!is_complex_empty("featured-image")) {
                    $image = get_complex_component("featured-image", null, ['image', 'link']);
                    $css = "";
                    if (empty($image['image'])) {
                        $css = "height: 100px;";
                    }
                    ?>
                    <a href="<?php get_url_or_slug($image['link']); ?>" class="image main sw-complex"  data-json="<?php get_escaped_json($image); ?>" data-component="featured-image">
                        <img src="<?php get_file_url($image['image']); ?> " alt="" style="<?php echo $css; ?>" />
                    </a>
                    <?php
                }
                ?>
                <?php
                $btn = get_complex_component("featured-btn", null, ['icon', 'link', 'text']);
                if (!is_complex_empty("featured-btn")) {
                    ?>
                    <ul class="actions">
                        <li>
                            <a href="<?php get_url_or_slug($btn['link']); ?>" class="button big sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="featured-btn">
                                <i class="<?php echo $btn['icon']; ?>"></i> <?php echo $btn['text']; ?>
                            </a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </article>

            <!-- Posts -->
            <section class="posts">
                <?php
                for ($i = 1; $i <= 6; $i++) {
                    if (is_complex_empty("article-$i") && is_component_empty("article-header-$i") && is_component_empty("article-text-$i")) {
                        continue;
                    }
                    $article = get_complex_component("article-$i", null, ['icon', 'image', 'text', 'link']);
                    ?>
                    <article>
                        <header>
                            <h2>
                                <a href="<?php get_url_or_slug($article['link']); ?>" class="sw-text" data-component="<?php echo "article-header-$i"; ?>">
                                    <?php get_component("article-header-$i") ?>
                                </a>
                            </h2>
                        </header>
                        <a href="<?php get_url_or_slug($article['link']); ?>" class="image fit"><img src="<?php get_file_url($article['image']); ?>" alt="" /></a>
                        <span class="sw-complex" data-json="<?php get_escaped_json($article); ?>" data-component="<?php echo "article-$i"; ?>"></span>
                        <div class="sw-editable" data-component="article-text-<?php echo $i; ?>">
                        <p><?php get_component("article-text-$i"); ?></p>
                        </div>
                        <?php
                        if (!empty($article['text'])) {
                            ?>
                            <ul class="actions">
                                <li>
                                    <a href="<?php get_url_or_slug($article['link']); ?>" class="button">
                                        <i class="<?php echo $article['icon']; ?>"></i> <?php echo $article['text']; ?>
                                    </a>
                                </li>
                            </ul>
                            <?php
                        }
                        ?>
                    </article>
                    <?php
                }
                ?>
            </section>
        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>

    </div>
</div>

<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
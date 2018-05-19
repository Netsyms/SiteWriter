<?php include __DIR__ . "/inc/header.inc.php"; ?>

<!-- Main -->
<div id="main">
    <div class="inner">
        <header>
            <h1 class="sw-text" data-component="banner-title">
                <?php get_component("banner-title"); ?>
            </h1>
            <p class="sw-text" data-component="lead">
                <?php get_component("lead"); ?>
            </p>
        </header>
        <section class="tiles">
            <?php
            for ($i = 1; $i <= 12; $i++) {
                if (is_complex_empty("tile-$i")) {
                    continue;
                }
                $tile = get_complex_component("tile-$i", null, ['image', 'link', 'text']);
                $styles = [1, 2, 3, 4, 5, 6, 2, 3, 1, 5, 6, 4];
                ?>
                <article class="style<?php echo $styles[$i-1]; ?>">
                    <span class="image">
                        <img src="<?php get_file_url($tile['image']); ?>" alt=""/>
                    </span>
                    <a href="<?php get_url_or_slug($tile['link']); ?>">
                        <h2><?php echo $tile['text']; ?></h2>
                        <div class="sw-complex" data-json="<?php get_escaped_json($tile); ?>" data-component="<?php echo "tile-$i"; ?>">
                        </div>
                        <div class="content">
                            <p class="sw-text" data-component="<?php echo "tile-text-$i"; ?>">
                                <?php get_component("tile-text-$i"); ?>
                            </p>
                        </div>
                    </a>
                </article>
            <?php } ?>
        </section>
    </div>
</div>

<?php include __DIR__ . "/inc/footer.inc.php"; ?>
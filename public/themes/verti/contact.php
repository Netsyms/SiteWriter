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

                        <form action="<?php get_site_url(); ?>contact.php" method="POST">
                            <div class="row">
                                <div class="6u 12u(medium)">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" placeholder="" required />
                                </div>
                                <div class="6u 12u(medium)">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" placeholder="you@example.com" required />
                                </div>
                                <div class="12u">
                                    <label for="message">Message</label>
                                    <textarea name="message" id="message" placeholder="" required ></textarea>
                                </div>
                            </div>
                            <br />
                            <?php
                            $btn = get_complex_component("submit-btn", null, ["icon", "text"]);
                            $icon = $btn['icon'];
                            $text = $btn['text'];
                            ?>
                            <button type="submit" class="button icon <?php echo $icon; ?> sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="submit-btn">
                                <?php echo $text; ?>
                            </button>
                        </form>

                    </article>

                </div>
            </div>
        </div>

        <?php include __DIR__ . "/inc/footer.inc.php"; ?>
    </div>
</div>
<?php include __DIR__ . "/inc/scripts.inc.php"; ?>
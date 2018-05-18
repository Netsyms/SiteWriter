<?php include __DIR__ . "/inc/header.inc.php"; ?>
<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <div id="main">

        <!-- Contact -->
        <article>
            <h2 class="major"><?php get_page_clean_title(); ?></h2>
            <p class="sw-text" data-component="lead"><?php get_component("lead"); ?></p>
            <form method="post" action="contact.php">
                <div class="field half first">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required />
                </div>
                <div class="field half">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required />
                </div>
                <div class="field">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" rows="4" required ></textarea>
                </div>
                <ul class="actions">
                    <li><input type="submit" value="Send Message" class="special" /></li>
                </ul>
            </form>
            <ul class="icons">
                <?php
                $social = get_socialmedia_urls();
                foreach ($social as $s) {
                    ?>
                    <li>
                        <a href="<?php echo $s['url']; ?>" class="icon alt <?php echo $s['icon']; ?>">
                            <span class="label"><?php echo $s['name']; ?></span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <a class="close" href="<?php get_url_or_slug("index"); ?>">Close</a>
        </article>

    </div>

    <?php include __DIR__ . "/inc/bg-edit.inc.php"; ?>
    
</div>

<?php include __DIR__ . "/inc/footer.inc.php"; ?>
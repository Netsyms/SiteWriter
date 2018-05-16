<!-- Footer -->
<footer id="footer">
    <section>
        <form method="post" action="contact.php">
            <div class="field">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required />
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required />
            </div>
            <div class="field">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="3" required></textarea>
            </div>
            <ul class="actions">
                <li><input type="submit" value="Send Message" /></li>
            </ul>
        </form>
    </section>
    <section class="split contact">
        <?php
        output_conditional('<section class="alt"><h3>Address</h3> <p>[[VAR]]</p></section>', str_replace("\n", "<br />\n", get_setting("address")));
        output_conditional('<section><h3>Email</h3> <p><a href="mailto:[[VAR]]">[[VAR]]</a></p></section>', get_setting("email"));
        output_conditional('<section><h3>Phone</h3> <p><a href="tel:[[VAR]]">' . get_setting("phone") . '</a></p></section>', preg_replace("/[^0-9+]/", "", get_setting("phone")));
        ?>

        <section>
            <h3>Social</h3>
            <ul class="icons alt">
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
        </section>
    </section>
</footer>

<!-- Copyright -->
<div id="copyright">
    <ul>
        <?php output_conditional("<li>&copy; " . date('Y') . " [[VAR]]</li>", get_setting("businessname")); ?>
        <li>Design: <a href="http://html5up.net">HTML5 UP</a> and <a href="https://netsyms.com">Netsyms</a></li>
</div>
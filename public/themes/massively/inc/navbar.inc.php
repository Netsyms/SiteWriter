<nav id="nav">
    <ul class="links">
        <?php
        get_navigation(null, "", "", "active", "", "");
        ?>
    </ul>
    <ul class = "icons">
        <?php
        $social = get_socialmedia_urls();
        foreach ($social as $s) {
            ?>
            <li>
                <a href="<?php echo $s['url']; ?>" class="icon <?php echo $s['icon']; ?>">
                    <span class="label"><?php echo $s['name']; ?></span>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</nav>
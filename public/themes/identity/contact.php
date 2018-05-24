<?php
include __DIR__ . "/inc/header.inc.php";
?>
<hr />
<h2><div class="sw-text" data-component="contact-header">
        <?php get_component("contact-header"); ?>
    </div></h2>
<form method="post" action="contact.php">
    <div class="field">
        <input type="text" name="name" id="name" placeholder="Name" />
    </div>
    <div class="field">
        <input type="email" name="email" id="email" placeholder="Email" />
    </div>
    <div class="field">
        <textarea name="message" id="message" placeholder="Message" rows="4"></textarea>
    </div>
    <ul class="actions">
        <li>
            <?php
            $btn = get_complex_component("submit-btn", null, ["link"]);
            $icon = $btn['icon'];
            $text = $btn['text'];
            ?>
            <button type="submit" class="button sw-complex" data-json="<?php get_escaped_json($btn); ?>" data-component="submit-btn">
                <i class="<?php echo $icon; ?>"></i> <?php echo $text; ?>
            </button>
        </li>
    </ul>
</form>
<hr />
<?php
include __DIR__ . "/inc/footer.inc.php";
?>
<!-- Footer -->
<div id="footer-wrapper">
    <footer id="footer" class="container">
        <div class="row">
            <div class="3u 6u(medium) 12u$(small)">

                <!-- Links -->
                <!--<section class="widget links">
                    <h3>Random Stuff</h3>
                    <ul class="style2">
                        <li><a href="#">Etiam feugiat condimentum</a></li>
                        <li><a href="#">Aliquam imperdiet suscipit odio</a></li>
                        <li><a href="#">Sed porttitor cras in erat nec</a></li>
                        <li><a href="#">Felis varius pellentesque potenti</a></li>
                        <li><a href="#">Nullam scelerisque blandit leo</a></li>
                    </ul>
                </section>-->

            </div>
            <div class="3u 6u$(medium) 12u$(small)">

                <!-- Links -->
                <!--<section class="widget links">
                    <h3>Random Stuff</h3>
                    <ul class="style2">
                        <li><a href="#">Etiam feugiat condimentum</a></li>
                        <li><a href="#">Aliquam imperdiet suscipit odio</a></li>
                        <li><a href="#">Sed porttitor cras in erat nec</a></li>
                        <li><a href="#">Felis varius pellentesque potenti</a></li>
                        <li><a href="#">Nullam scelerisque blandit leo</a></li>
                    </ul>
                </section>-->

            </div>
            <div class="3u 6u(medium) 12u$(small)">

                <!-- Links -->
                <!--<section class="widget links">
                    <h3>Random Stuff</h3>
                    <ul class="style2">
                        <li><a href="#">Etiam feugiat condimentum</a></li>
                        <li><a href="#">Aliquam imperdiet suscipit odio</a></li>
                        <li><a href="#">Sed porttitor cras in erat nec</a></li>
                        <li><a href="#">Felis varius pellentesque potenti</a></li>
                        <li><a href="#">Nullam scelerisque blandit leo</a></li>
                    </ul>
                </section>-->

            </div>
            <div class="3u 6u$(medium) 12u$(small)">

                <!-- Contact -->
                <section class="widget contact">
                    <h3>Contact Us</h3>
                    <ul>
                        <?php
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-facebook"><span class="label">Facebook</span></a></li>', get_setting("facebook"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-twitter"><span class="label">Twitter</span></a></li>', get_setting("twitter"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-youtube"><span class="label">YouTube</span></a></li>', get_setting("youtube"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-instagram"><span class="label">Instagram</span></a></li>', get_setting("instagram"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-snapchat"><span class="label">Snapchat</span></a></li>', get_setting("snapchat"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-google-plus"><span class="label">Google+</span></a></li>', get_setting("google-plus"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-skype"><span class="label">Skype</span></a></li>', get_setting("skype"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-telegram"><span class="label">Twitter</span></a></li>', get_setting("telegram"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-vimeo"><span class="label">Vimeo</span></a></li>', get_setting("vimeo"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-whatsapp"><span class="label">Whatsapp</span></a></li>', get_setting("whatsapp"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>', get_setting("linkedin"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-asterisk"><span class="label">diaspora*</span></a></li>', get_setting("diaspora"));
                        output_conditional('<li><a href="[[VAR]]" class="icon fa-mastodon"><span class="label">Mastodon</span></a></li>', get_setting("mastodon"));
                        ?>
                    </ul>
                    <p>
                        <?php output_conditional('<div style="display: flex;"><div><i class="fa fa-phone fa-fw"></i></div> <div style="font-size: 130%;">[[VAR]]</div></div>', get_setting("phone")); ?>
                        <?php output_conditional('<div style="display: flex;"><div><i class="fa fa-map-marker fa-fw"></i></div> <div>[[VAR]]</div></div>', str_replace("\n", "<br />\n", get_setting("address"))); ?>
                        <?php output_conditional('<div style="display: flex;"><div><i class="fa fa-envelope fa-fw"></i></div> <div>[[VAR]]</div></div>', get_setting("email")); ?>
                        </p>
                </section>

            </div>
        </div>
        <div class="row">
            <div class="12u">
                <div id="copyright">
                    <ul class="menu">
                        <?php output_conditional("<li>&copy; [[VAR]].  All rights reserved.</li>", get_setting("businessname")); ?>
                        <li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
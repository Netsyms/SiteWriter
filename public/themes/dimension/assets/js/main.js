/*
 Dimension by HTML5 UP
 html5up.net | @ajlkn
 Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
 */

(function ($) {

    skel.breakpoints({
        xlarge: '(max-width: 1680px)',
        large: '(max-width: 1280px)',
        medium: '(max-width: 980px)',
        small: '(max-width: 736px)',
        xsmall: '(max-width: 480px)',
        xxsmall: '(max-width: 360px)'
    });

    $(function () {

        var $window = $(window),
                $wrapper = $('#wrapper'),
                $header = $('#header'),
                $footer = $('#footer');

        // Fix: Placeholder polyfill.
        $('form').placeholder();

        // Fix: Flexbox min-height bug on IE.
        if (skel.vars.IEVersion < 12) {
            var flexboxFixTimeoutId;
            $window.on('resize.flexbox-fix', function () {
                clearTimeout(flexboxFixTimeoutId);
                flexboxFixTimeoutId = setTimeout(function () {
                    if ($wrapper.prop('scrollHeight') > $window.height()) {
                        $wrapper.css('height', 'auto');
                    } else {
                        $wrapper.css('height', '100vh');
                    }
                }, 250);
            }).triggerHandler('resize.flexbox-fix');
        }

        // Nav.
        var $nav = $header.children('nav'),
                $nav_li = $nav.find('li');

        // Add "middle" alignment classes if we're dealing with an even number of items.
        if ($nav_li.length % 2 == 0) {
            $nav.addClass('use-middle');
            $nav_li.eq(($nav_li.length / 2)).addClass('is-middle');
        }

        $('.close').on('click', function (evt) {
            evt.preventDefault();
            // If we're in the sw editor
            if (typeof page_slug != 'undefined') {
                return false;
            }
            var url = $(this).attr('href');
            $("article").removeClass("active");
            setTimeout(function () {
                document.location.href = url;
            }, 325);
            return false;
        });

        $("#header nav li a").on('click', function (evt) {
            evt.preventDefault();
            // If we're in the sw editor
            if (typeof page_slug != 'undefined') {
                return false;
            }
            var url = $(this).attr('href');
            $("#wrapper").fadeOut(325, function () {
                document.location.href = url;
            });
            return false;
        });

        $("article").addClass("active");
        $("#wrapper").fadeTo(500, 1);
        window.onpageshow = function () {
            $("#wrapper").fadeIn(325);
        };
    });
})(jQuery);
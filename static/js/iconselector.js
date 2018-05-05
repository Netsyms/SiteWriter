/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function initIconSearch() {
    $("#icon_search").keyup(function () {
        var q = $("#icon_search").val().toLowerCase();
        var icons = $(".icon_bin label");
        if (q == "") {
            icons.removeClass("d-none");
        } else {
            icons.addClass("d-none");
            var matches = icons.filter(function () {
                return $(this).data("search").includes(q);
            });
            matches.removeClass("d-none");
        }
    });

    $(".iconselector_radio").change(function () {
        console.log($(this).val());
        $("#selectedicon").html("<i class=\"" + $(this).val() + " fa-fw\"></i>");
    });
}
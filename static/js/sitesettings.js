/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

$('#name').on('input propertychange paste', function () {
    $('#name_title').text($('#name').val());
});

$("input[type=radio][name=color]").change(function () {
    var theme = $(this).data("theme");
    $("input[type=radio][name=theme][value=" + theme + "]").prop("checked", true);
});
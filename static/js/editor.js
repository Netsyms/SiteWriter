/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

$(".sw-editable").summernote({
    airMode: false,
    toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['para', ['ul', 'ol']],
        ['insert', ['link', 'picture']],
        ['misc', ['undo', 'redo', 'fullscreen', 'codeview']]
    ],
    placeholder: 'Click to edit'
});

function saveEdits() {
    var components = [];
    $(".sw-editable").each(function (e) {
        components[$(this).data("component")] = $(this).html();
    });
    var content = JSON.stringify(components);
    $.post(save_url, {
        action: "saveedits",
        page: "",
        content: content
    });
}
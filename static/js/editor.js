/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

$(document).ready(function () {
    $("body").append("<link href=\"" + static_dir + "/css/editor.css\" rel=\"stylesheet\" />");
    
    $(".sw-editable").each(function () {
        // Remove leading whitespace added by the template
        $(this).html($(this).html().trim());
    });

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

    $(".sw-text").each(function () {
        var text = $(this).text().trim();
        var component = $(this).data("component");
        $(this).html("<input type=\"text\" data-component=\"" + component + "\" class=\"sw-text-input\" value=\"" + text + "\" placeholder=\"Click to edit\">");
    });
});

function saveEdits() {
    var components = [];
    $(".sw-editable").each(function (e) {
        components[$(this).data("component")] = $(this).summernote('code');
    });
    $(".sw-text-input").each(function (e) {
        components[$(this).data("component")] = $(this).val();
    });
    var content = JSON.stringify(components);
    console.log(components);
    $.post(save_url, {
        action: "saveedits",
        page: "",
        content: content
    });
}
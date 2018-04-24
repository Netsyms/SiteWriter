/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function saveEdits() {
    var components = {};
    $(".sw-editable").each(function (e) {
        components[$(this).data("component")] = $(this).summernote('code');
    });
    $(".sw-text-input").each(function (e) {
        components[$(this).data("component")] = $(this).val();
    });
    $(".sw-complex").each(function (e) {
        if (typeof $(this).data("json") === "string") {
            components[$(this).data("component")] = JSON.parse($(this).data("json"));
        } else {
            components[$(this).data("component")] = $(this).data("json");
        }
    });
    var output = {
        slug: page_slug,
        site: site_id,
        content: components
    };
    //console.log(output);
    var json = JSON.stringify(output);
    console.log(output);
    console.log("editor: sent page content");
    parent.postMessage('save ' + json, "*");
}

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
            ['misc', ['undo', 'redo', 'codeview']]
        ],
        placeholder: 'Click to edit'
    });

    $(".sw-text").each(function () {
        var text = $(this).text().trim();
        var component = $(this).data("component");
        $(this).html("<input type=\"text\" data-component=\"" + component + "\" class=\"sw-text-input\" value=\"" + text + "\" placeholder=\"Click to edit\">");
    });

    $(".sw-complex").each(function () {
        $(this).append("<div class=\"sw-editbtn\">Click to edit</div>");
    });

    $(".sw-editbtn").on("click", function () {
        var data = $(this).parent().data("json");
        var send = {"component": $(this).parent().data("component"), "content": data};
        //console.log(send);
        parent.postMessage('editcomplex ' + JSON.stringify(send), "*");
        return false;
    });

    window.addEventListener('message', function (event) {
        console.log("editor: received message: " + event.data);
        if (event.data == "save") {
            saveEdits();
        } else if (event.data.startsWith("complex ")) {
            var json = JSON.parse(event.data.slice(8));
            var comp = json["component"];
            var data = json["content"];
            $(".sw-complex[data-component='" + comp + "']").data("json", JSON.stringify(data));
        }
    });
});
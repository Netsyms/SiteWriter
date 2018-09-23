/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function saveEdits() {
    var components = {};
    $(".sw-editable").each(function (e) {
        components[$(this).data("component")] = tinymce.get($(this).attr("id")).getContent();
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

var filePickerCallback = null;

$(document).ready(function () {
    $('a').click(function (e) {
        e.preventDefault();
    });

    //$("body").append("<link href=\"" + static_dir + "/css/editor.css\" rel=\"stylesheet\" />");

    $(".sw-editable").each(function () {
        // Remove leading whitespace added by the template
        $(this).html($(this).html().trim());
    });

    tinymce.init({
        selector: '.sw-editable',
        inline: true,
        plugins: [
            'autolink lists link image imagetools charmap',
            'searchreplace visualblocks code fullscreen',
            'media table contextmenu paste code'
        ],
        branding: false,
        elementpath: false,
        menubar: 'edit insert view format table tools',
        toolbar: 'insert | undo redo | formatselect | bold italic | bullist numlist outdent indent | removeformat | fullscreen',
        link_list: function (success) {
            success(pages_list);
        },
        file_picker_callback: function (callback, value, meta) {
            filePickerCallback = callback;
            parent.postMessage('browse ' + meta.filetype, "*");
        },
        image_dimensions: false,
        image_class_list: [
            {title: 'Autosizing', value: 'img-responsive img-fluid'}
        ],
        mobile: {
            theme: 'mobile'
        }
    });

    $(".sw-text").each(function () {
        var text = $(this).text().trim().replace(/"/g, "&quot;");
        var component = $(this).data("component");
        $(this).html("<input type=\"text\" data-component=\"" + component + "\" class=\"sw-text-input\" value=\"" + text + "\" placeholder=\"Click to edit\">");
        $(this).closest("a").removeAttr("href"); // Issue #33
    });

    $(".sw-complex").each(function () {
        var eid = $(this).attr("id");
        var eclass = $(this).attr("class");
        var estyle = $(this).attr("style");
        var ecomp = $(this).data("component");
        var ejson = $(this).data("json");
        if (typeof ejson !== "string") {
            ejson = JSON.stringify(ejson);
        }
        $(this).replaceWith(
                $('<div>', {
                    id: eid,
                    class: eclass,
                    style: estyle,
                    html: $(this).html(),
                    "data-component": ecomp,
                    "data-json": ejson
                }));
        $("[data-component=\"" + ecomp + "\"]").append("<div class=\"sw-editbtn\">Click to edit</div>");
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
        } else if (event.data.startsWith("picked ")) {
            var json = JSON.parse(event.data.slice(7));
            console.log(json);
            filePickerCallback(json.path, json.meta);
        }
    });
});
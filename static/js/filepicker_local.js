/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function loadFilePickerFolder(path, type, pickertype) {
    var ty = "";
    switch (type) {
        case "image":
            ty = "image";
            break;
        case "media":
            ty = "audio|video";
            break;
    }

    $.get("lib/filepicker_local.php", {
        path: path,
        type: ty
    }, function (data) {
        $("#uploadedFilesBin").html(data);
        $("#uploadedFilesBin .filepicker-item").click(function () {
            if ($(this).data("type") == "dir") {
                loadFilePickerFolder($(this).data("path"), type, pickertype);
            } else {
                var path = $(this).data("path");
                if (typeof pickertype != 'undefined' && pickertype == 'complex') {
                    $("#imageEdit").data("image", path);
                    $("#imageEdit #selectedimage").attr("src", "public/file.php?file=" + path);
                } else {
                    var data = {
                        path: path,
                        meta: {}
                    };
                    json = JSON.stringify(data);
                    document.getElementById("editorframe").contentWindow.postMessage("picked " + json, "*");
                    $("#fileBrowseModal").modal('hide');
                }
            }
        });
    });
}
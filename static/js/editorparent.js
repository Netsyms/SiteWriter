/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

function save(json) {
    var output = JSON.parse(json);
    console.log(output);
    $.post("action.php", {
        action: "saveedits",
        source: "editor",
        slug: output["slug"],
        site: output["site"],
        content: output["content"]
    }, function (data) {
        if (data.status == "OK") {
            $("#reloadprompt").addClass("d-none");
            document.getElementById("editorframe").contentDocument.location.reload(true);
            $("#savedBadge").removeClass("d-none");
            $("#savedBadge").show();
            setTimeout(function () {
                $("#savedBadge").fadeOut("slow");
            }, 1500);
        } else {
            alert(data.msg);
        }
    });
}

function editComplex(json) {
    var data = JSON.parse(json);
    console.log(data);
    if (typeof data.content === "string") {
        var content = JSON.parse(data.content);
    } else {
        var content = data.content;
    }
    $("#iconEdit").removeClass("d-none");
    $("#imageEdit").removeClass("d-none");
    $("#linkEdit").removeClass("d-none");
    $("#textEdit").removeClass("d-none");
    $("#linkPage").val("");
    $("#linkBox").val("");
    $("#textBox").val("");
    if (typeof content.icon === 'undefined') {
        $("#iconEdit").addClass("d-none");
    } else {
        $("#selectedicon").html("<i class=\"" + content.icon + " fa-fw\"></i>");
        function setSelectedIcon() {
            $('.iconselector_radio[value="' + content.icon + '"]').prop("checked", true);
        }
        if ($("#iconpicker").data("loaded") != "true") {
            $.get("lib/iconpicker.php", [], function (content) {
                $("#iconpicker").html(content);
                initIconSearch();
                $("#iconpicker").data("loaded", "true");
                setSelectedIcon();
            });
        } else {
            setSelectedIcon();
        }
    }
    if (typeof content.image === 'undefined') {
        $("#imageEdit").addClass("d-none");
    } else {
        $("#imageEdit").data("image", content.image);
        if (content.image != "") {
            $("#imageEdit #selectedimage").attr("src", "public/file.php?file=" + content.image);
        }
        function loadComplexImageBrowser(path) {
            $.get("lib/filepicker.php", {
                path: path,
                type: "image"
            }, function (data) {
                $("#imagepicker").html(data);
                $("#imagepicker .filepicker-item").click(function () {
                    if ($(this).data("type") == "dir") {
                        loadComplexImageBrowser($(this).data("path"));
                    } else {
                        var path = $(this).data("path");
                        $("#imageEdit").data("image", path);
                        $("#imageEdit #selectedimage").attr("src", "public/file.php?file=" + path);
                    }
                })
            });
        }
        loadComplexImageBrowser();
    }
    if (typeof content.link === 'undefined') {
        $("#linkEdit").addClass("d-none");
    } else {
        if (content.link.startsWith("http")) {
            $("#linkBox").val(content.link);
        } else {
            $("#linkPage").val(content.link);
        }
    }
    if (typeof content.text === 'undefined') {
        $("#textEdit").addClass("d-none");
    } else {
        $("#textBox").val(content.text);
    }
    $("#editModal").data("component", data.component);
    $("#editModal").modal();
}

function loadFilePickerFolder(path, type) {
    var ty = "";
    switch (type) {
        case "image":
            ty = "image";
            break;
        case "media":
            ty = "audio|video";
            break;
    }
    $.get("lib/filepicker.php", {
        path: path,
        type: ty
    }, function (data) {
        $("#fileBrowseModalBody").html(data);
        $("#fileBrowseModalBody .filepicker-item").click(function () {
            if ($(this).data("type") == "dir") {
                loadFilePickerFolder($(this).data("path"), type);
            } else {
                var path = "file.php?file=" + $(this).data("path");
                var data = {
                    path: path,
                    meta: {}
                };
                json = JSON.stringify(data);
                document.getElementById("editorframe").contentWindow.postMessage("picked " + json, "*");
                $("#fileBrowseModal").modal('hide');
            }
        })
    });
}

function openFilePicker(type) {
    loadFilePickerFolder("/", type);
    $("#fileBrowseModal").modal();
}


$("#editModalSave").on("click", function () {
    var data = {};
    data["component"] = $("#editModal").data("component");
    var content = {};
    content["icon"] = $('input[name="selectedicon"]:checked').val();
    content["image"] = $("#imageEdit").data("image");
    if ($("#linkBox").val() != "") {
        content["link"] = $("#linkBox").val();
    } else {
        content["link"] = $("#linkPage").val();
    }
    content["text"] = $("#textBox").val();
    data["content"] = content;
    var json = JSON.stringify(data);
    document.getElementById("editorframe").contentWindow.postMessage("complex " + json, "*");
    $("#reloadprompt").removeClass("d-none");
    $('#editModal').modal('hide');
});

window.addEventListener('message', function (event) {
    //console.log("parent: received message: " + event.data);
    if (event.data.startsWith("save ")) {
        save(event.data.slice(5));
    } else if (event.data.startsWith("editcomplex ")) {
        editComplex(event.data.slice(12));
    } else if (event.data.startsWith("browse ")) {
        openFilePicker(event.data.slice(7));
    }
});

$("#savebtn").click(function () {
    triggerSave();
});

function triggerSave() {
    document.getElementById("editorframe").contentWindow.postMessage("save", "*");
}

$("#newpagebtn").click(function () {
    $("#newPageModal").modal();
});

$("#pagesettingsbtn").click(function () {
    $("#pageSettingsModal").modal();
});

function updateNavbarSettings() {
    if ($("#innavbarCheckbox").prop("checked")) {
        $("#navbarSettings").removeClass("d-none");
        var title = $("#navbarTitle").val();
        if (title == "") {
            title = pagetitle;
            $("#navbarTitle").val(title);
        }
        $("#navbar-order-list").prepend('<div class="list-group-item" data-pageid="' + pageid + '"><i class="fas fa-sort"></i> ' + title + '</div>');
    } else {
        $("#navbarSettings").addClass("d-none");
        $("#navbar-order-list .list-group-item[data-pageid=" + pageid + "]").remove();
    }
    sortable('#navbar-order-list');
}

$("#navbarTitle").on("keyup", function () {
    $("#navbar-order-list .list-group-item[data-pageid=" + pageid + "]").text($("#navbarTitle").val());
});

if ($("#innavbarCheckbox").prop("checked")) {
    $("#navbarSettings").removeClass("d-none");
}

$("#innavbarCheckbox").change(function () {
    updateNavbarSettings();
});

navbarSortList = sortable('#navbar-order-list', {
    items: ".list-group-item",
    placeholder: '<div class="list-group-item bg-grey">&nbsp;</div>',
    itemSerializer: function (serializedItem, sortableContainer) {
        return {
            position: serializedItem.index + 1,
            pageid: $(serializedItem.html).data("pageid")
        };
    },
    containerSerializer: function () {
        return {};
    }
});

sortable('#navbar-order-list')[0].addEventListener('sortupdate', function(e) {
    var items = e.detail.origin.items;
    var pageids = [];
    for (var i = 0; i < items.length; i++) {
        pageids[i] = $(items[i]).data("pageid");
    }
    var stringy = pageids.join("|");
    $("input[name=navorder").val(stringy);
});
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
    $("#linkEdit").removeClass("d-none");
    $("#textEdit").removeClass("d-none");
    $("#linkPage").val("");
    $("#linkBox").val("");
    $("#textBox").val("");
    if (typeof content.icon === 'undefined') {
        $("#iconEdit").addClass("d-none");
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

$("#editModalSave").on("click", function () {
    var data = {};
    data["component"] = $("#editModal").data("component");
    var content = {};
    content["icon"] = "";
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
    }
});

$("#savebtn").click(function () {
    triggerSave();
});

function triggerSave() {
    document.getElementById("editorframe").contentWindow.postMessage("save", "*");
}
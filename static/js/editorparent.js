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
            alert("Saved");
        } else {
            alert(data.msg);
        }
    });
}

window.addEventListener('message', function (event) {
    //console.log("parent: received message: " + event.data);
    if (event.data.startsWith("save ")) {
        save(event.data.slice(5));
    }
});

$("#savebtn").click(function () {
    triggerSave();
});

function triggerSave() {
    document.getElementById("editorframe").contentWindow.postMessage("save", "*");
}
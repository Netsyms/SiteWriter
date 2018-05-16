/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/*$('#fileBrowserTabs a').on('click', function (e) {
 e.preventDefault();
 $(this).tab('show');
 });*/

var unsplash_page = 1;
var unsplash_query = "";
var unsplash_pickertype = "image";

function loadPhotos() {
    // Disable the "load more" button and the search box
    $("#unsplashLoadMoreBtn").attr("disabled", true);
    $("#unsplashSearch").attr("disabled", true);
    $("#unsplashSearchBtn").attr("disabled", true);
    $.get("lib/filepicker_unsplash.php", {
        page: unsplash_page,
        query: unsplash_query
    }, function (data) {

        // Display total results
        if (data.total != null) {
            $("#unsplashResults").text(data.total);
        } else {
            $("#unsplashResults").text("");
        }

        // Add the new results to the page
        $("#unsplashPhotoBin").append(data.html);

        // Re-enable the search box and button
        $("#unsplashSearch").attr("disabled", false);
        $("#unsplashSearchBtn").attr("disabled", false);

        // Check if we have more results available or if the "load more" button
        // should stay disabled
        if (data.pages != null && data.page >= data.pages) {
            $("#unsplashLoadMoreBtn").attr("disabled", true);
        } else {
            $("#unsplashLoadMoreBtn").attr("disabled", false);
        }
        $("#unsplashPhotoBin .filepicker-unsplashimg .card-img-top").click(function () {
            var path = $(this).data("path");
            if (typeof unsplash_pickertype != 'undefined' && unsplash_pickertype == 'complex') {
                $("#imageEdit").data("image", path);
                $("#imageEdit #selectedimage").attr("src", path);
            } else {
                var data = {
                    path: path,
                    meta: {}
                };
                json = JSON.stringify(data);
                document.getElementById("editorframe").contentWindow.postMessage("picked " + json, "*");
                $("#fileBrowseModal").modal('hide');
            }
            $.post("action.php", {
                action: "unsplash_download",
                imageid: $(this).data("imageid")
            });
        });
    });
}

function searchPhotos(query) {
    unsplash_page = 1;
    unsplash_query = query;
    $("#unsplashPhotoBin").html("");
    loadPhotos();
}

/**
 * Reset the photo browser to the default view (page one of popular photos)
 */
function loadDefaultPhotos() {
    unsplash_page = 1;
    unsplash_query = "";
    $("#unsplashPhotoBin").html("");
    loadPhotos();
}

function loadMorePhotos() {
    unsplash_page++;
    loadPhotos();
}

function setupUnsplash(pickertype) {
    if (typeof pickertype != 'undefined' && pickertype == 'complex') {
        unsplash_pickertype = "complex";
    }
    $("#unsplashLoadMoreBtn").click(function () {
        loadMorePhotos();
    });
    $("#unsplashSearchBtn").click(function () {
        searchPhotos($("#unsplashSearch").val());
    });
    $('#unsplashSearch').on("keypress", function (e) {
        if (e.which == 13) {
            searchPhotos($("#unsplashSearch").val());
        }
    });
    $("#unsplashTab").on("show.bs.tab", function () {
        loadDefaultPhotos();
    });
    loadDefaultPhotos();
}
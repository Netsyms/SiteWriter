/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/*
 * Note: This script is *not* called "analytics.js" because adblockers.
 */


$(function () {
    $('#date_after').datetimepicker({
        format: "MMM D YYYY h:mm A",
        useCurrent: false,
        icons: {
            time: "fas fa-clock",
            date: "fas fa-calendar",
            up: "fas fa-arrow-up",
            down: "fas fa-arrow-down"
        }
    });
    $('#date_before').datetimepicker({
        format: "MMM D YYYY h:mm A",
        useCurrent: true,
        icons: {
            time: "fas fa-clock",
            date: "fas fa-calendar",
            up: "fas fa-arrow-up",
            down: "fas fa-arrow-down"
        }
    });
});

var visitsOverTime = new Chart($("#visitsOverTime"), {
    type: 'line',
    data: {
        datasets: [{
                data: visitsOverTimeData
            }],
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                    type: 'time',
                }],
            yAxes: [{
                    type: 'linear',
                    ticks: {
                        beginAtZero: true,
                        callback: function (value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        },
                    }
                }]
        },
        tooltips: {
            displayColors: false,
            callbacks: {
                title: function (item) {
                    var lbl = item[0].xLabel;
                    if (lbl.endsWith("-00 00:00:00")) {
                        return moment(lbl).format("MMM YYYY");
                    } else if (lbl.endsWith(" 00:00:00")) {
                        return moment(lbl).format("MMM D YYYY");
                    } else if (lbl.endsWith(":00:00")) {
                        return moment(lbl).format("MMM D YYYY ha");
                    } else if (lbl.endsWith(":00")) {
                        return moment(lbl).format("MMM D YYYY h:mma");
                    }
                    return item[0].xLabel;
                },
                label: function (item) {
                    return item.yLabel + " visits";
                }
            }
        },
        elements: {
            line: {
                borderWidth: 2,
                borderColor: "#ff0000",
                backgroundColor: "#ffffff00",
                tension: 0
            }
        }
    }
});



function getVisitorMapData(source) {
    var visitorMapDataset = {};

    var onlyValues = source.map(function (obj) {
        return obj[1];
    });
    var minValue = Math.min.apply(null, onlyValues);
    var maxValue = Math.max.apply(null, onlyValues);

    var paletteScale = d3.scale.linear()
            .domain([minValue, maxValue])
            .range(["#A5D6A7", "#124016"]); // blue color

    source.forEach(function (item) { //
        // item example value ["USA", 70]
        var iso = item[0],
                value = item[1];
        visitorMapDataset[iso] = {numberOfThings: value, fillColor: paletteScale(value)};
    });

    return visitorMapDataset;
}

var visitorMap;

function showVisitorMap(data, scope, containerid) {
    $("visitorMap").html("");
    visitorMap = new Datamap({
        element: document.getElementById(containerid),
        scope: scope,
        responsive: true,
        fills: {defaultFill: '#F5F5F5'},
        data: data,
        geographyConfig: {
            borderColor: '#DEDEDE',
            highlightBorderWidth: 2,
            // don't change color on mouse hover
            highlightFillColor: function (geo) {
                return geo['fillColor'] || '#E8F5E9';
            },
            // only change border
            highlightBorderColor: '#00C853',
            // show desired information in tooltip
            popupTemplate: function (geo, data) {
                // don't show tooltip if country don't present in dataset
                if (!data) {
                    //return;
                }
                // tooltip content
                return ['<div class="hoverinfo">',
                    '<strong>', geo.properties.name, '</strong>',
                    '<br>Visits: <strong>', data.numberOfThings, '</strong>',
                    '</div>'].join('');
            }
        }
    });
}

showVisitorMap(getVisitorMapData(visitorMap_Countries), 'world', 'visitorMapWorld');
showVisitorMap(getVisitorMapData(visitorMap_States), 'usa', 'visitorMapUSA');

$(window).on('resize', function () {
    visitorMap.resize();
});
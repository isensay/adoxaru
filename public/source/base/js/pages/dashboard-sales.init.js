colors = ["#727cf5", "#02a8b5", "#fd7e14"];
(dataColors = $("#marketing-reports").data("colors")) && (colors = dataColors.split(","));
options = {
    chart: { height: 274, type: "radar", toolbar: { show: !1 } },
    series: [
        { name: "Series 1", data: [80, 50, 30, 40, 100, 20] },
        { name: "Series 2", data: [20, 30, 40, 80, 20, 80] },
        { name: "Series 3", data: [44, 76, 78, 13, 43, 10] },
    ],
    stroke: { width: 0 },
    fill: { opacity: 0.4 },
    markers: { size: 0 },
    legend: { show: !1 },
    colors: colors,
    labels: ["2011", "2012", "2013", "2014", "2015", "2016"],
};
(chart = new ApexCharts(document.querySelector("#marketing-reports"), options)).render();
var dataColors;
colors = ["#39afd1", "#ffbc00", "#313a46", "#fa5c7c"];
(dataColors = $("#projections-actuals").data("colors")) && (colors = dataColors.split(","));
var chart;
options = {
    chart: { height: 312, type: "donut" },
    series: [44, 55, 41, 17],
    legend: { show: !0, position: "bottom", horizontalAlign: "center", verticalAlign: "middle", floating: !1, fontSize: "14px", offsetX: 0, offsetY: 7 },
    labels: ["Direct", "Affilliate", "Sponsored", "E-mail"],
    colors: colors,
    responsive: [{ breakpoint: 600, options: { chart: { height: 240 }, legend: { show: !1 } } }],
};
(chart = new ApexCharts(document.querySelector("#projections-actuals"), options)).render(),
    $("#world-map-markers").vectorMap({
        map: "world_mill_en",
        normalizeFunction: "polynomial",
        hoverOpacity: 0.7,
        hoverColor: !1,
        regionStyle: { initial: { fill: "#d4dadd" } },
        markerStyle: { initial: { r: 9, fill: "#1abc9c", "fill-opacity": 0.9, stroke: "#fff", "stroke-width": 7, "stroke-opacity": 0.4 }, hover: { stroke: "#fff", "fill-opacity": 1, "stroke-width": 1.5 } },
        backgroundColor: "transparent",
        markers: [
            { latLng: [41.9, 12.45], name: "Vatican City" },
            { latLng: [43.73, 7.41], name: "Monaco" },
            { latLng: [-0.52, 166.93], name: "Nauru" },
            { latLng: [-8.51, 179.21], name: "Tuvalu" },
            { latLng: [43.93, 12.46], name: "San Marino" },
            { latLng: [47.14, 9.52], name: "Liechtenstein" },
            { latLng: [7.11, 171.06], name: "Marshall Islands" },
            { latLng: [17.3, -62.73], name: "Saint Kitts and Nevis" },
            { latLng: [3.2, 73.22], name: "Maldives" },
            { latLng: [35.88, 14.5], name: "Malta" },
            { latLng: [12.05, -61.75], name: "Grenada" },
            { latLng: [13.16, -61.23], name: "Saint Vincent and the Grenadines" },
            { latLng: [13.16, -59.55], name: "Barbados" },
            { latLng: [17.11, -61.85], name: "Antigua and Barbuda" },
            { latLng: [-4.61, 55.45], name: "Seychelles" },
            { latLng: [7.35, 134.46], name: "Palau" },
            { latLng: [42.5, 1.51], name: "Andorra" },
            { latLng: [14.01, -60.98], name: "Saint Lucia" },
            { latLng: [6.91, 158.18], name: "Federated States of Micronesia" },
            { latLng: [1.3, 103.8], name: "Singapore" },
            { latLng: [0.33, 6.73], name: "SÃ£o TomÃ© and PrÃ­ncipe" },
        ],
    });

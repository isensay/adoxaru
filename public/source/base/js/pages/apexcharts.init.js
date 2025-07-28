colors = ["#f672a7"];
(dataColors = $("#apex-usd").data("colors")) && (colors = dataColors.split(","));
options = {
    chart: { height: 380, type: "line", shadow: { enabled: !1, color: "#bbb", top: 3, left: 2, blur: 3, opacity: 1 } },
    stroke: { width: 3, curve: "smooth" },
    series: [{ name: chartValName, data: chartValUsd }],
    xaxis: {
        categories: chartCatUsd,
        type: 'datetime',
        labels: {
            formatter: function(value, timestamp, opts) {
                // Определяем уровень детализации в зависимости от масштаба
                const range = opts.w.globals.maxX - opts.w.globals.minX;
                const oneYear = 365 * 24 * 60 * 60 * 1000;
                
                if (range > oneYear * 3) { // Если масштаб больше 3 лет
                    return new Date(value).toLocaleDateString(chartLocale, {
                        year: 'numeric'
                    });
                } else if (range > oneYear) { // Если масштаб 1-3 года
                    return new Date(value).toLocaleDateString(chartLocale, {
                        month: 'short',
                        year: 'numeric'
                    });
                } else { // При ближайшем масштабе
                    return new Date(value).toLocaleDateString(chartLocale, {
                        day: 'numeric',
                        month: 'short'
                    });
                }
            }
        },
        tooltip: {
            formatter: function(value) {
                return new Date(value).toLocaleDateString(chartLocale, {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
        }
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return val.toFixed(0);
            }
        },
        tickAmount: 6,
        forceNiceScale: true
    },
    tooltip: {
        enabled: true,
        y: {
            formatter: function(val) {
                return val.toFixed(4);
            }
        }
    },
    title: { text: "", align: "left", style: { fontSize: "14px", color: "#666" } },
    fill: { type: "gradient", gradient: { shade: "dark", gradientToColors: colors, shadeIntensity: 1, type: "horizontal", opacityFrom: 1, opacityTo: 1, stops: [0, 100, 100, 100] } },
    markers: { size: 0, opacity: 0.9, colors: ["#56c2d6"], strokeColor: "#fff", strokeWidth: 2, style: "inverted", hover: { size: 7 } },
    grid: { row: { colors: ["transparent", "transparent"], opacity: 0.2 }, borderColor: "#185a9d" },
    responsive: [{ breakpoint: 600, options: { chart: { toolbar: { show: !1 } }, legend: { show: !1 } } }],
};
(chart = new ApexCharts(document.querySelector("#apex-usd"), options)).render();

colors = ["#f672a7"];
(dataColors = $("#apex-eur").data("colors")) && (colors = dataColors.split(","));
options = {
    chart: { height: 380, type: "line", shadow: { enabled: !1, color: "#bbb", top: 3, left: 2, blur: 3, opacity: 1 } },
    stroke: { width: 3, curve: "smooth" },
    series: [{ name: chartValName, data: chartValEur }],
    xaxis: {
        categories: chartCatEur,
        type: 'datetime',
        labels: {
            formatter: function(value, timestamp, opts) {
                // Определяем уровень детализации в зависимости от масштаба
                const range = opts.w.globals.maxX - opts.w.globals.minX;
                const oneYear = 365 * 24 * 60 * 60 * 1000;
                
                if (range > oneYear * 3) { // Если масштаб больше 3 лет
                    return new Date(value).toLocaleDateString(chartLocale, {
                        year: 'numeric'
                    });
                } else if (range > oneYear) { // Если масштаб 1-3 года
                    return new Date(value).toLocaleDateString(chartLocale, {
                        month: 'short',
                        year: 'numeric'
                    });
                } else { // При ближайшем масштабе
                    return new Date(value).toLocaleDateString(chartLocale, {
                        day: 'numeric',
                        month: 'short'
                    });
                }
            }
        },
        tooltip: {
            formatter: function(value) {
                return new Date(value).toLocaleDateString(chartLocale, {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
        }
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return val.toFixed(0);
            }
        },
        tickAmount: 6,
        forceNiceScale: true
    },
    tooltip: {
        enabled: true,
        y: {
            formatter: function(val) {
                return val.toFixed(4);
            }
        }
    },
    title: { text: "", align: "left", style: { fontSize: "14px", color: "#666" } },
    fill: { type: "gradient", gradient: { shade: "dark", gradientToColors: colors, shadeIntensity: 1, type: "horizontal", opacityFrom: 1, opacityTo: 1, stops: [0, 100, 100, 100] } },
    markers: { size: 0, opacity: 0.9, colors: ["#56c2d6"], strokeColor: "#fff", strokeWidth: 2, style: "inverted", hover: { size: 7 } },
    grid: { row: { colors: ["transparent", "transparent"], opacity: 0.2 }, borderColor: "#185a9d" },
    responsive: [{ breakpoint: 600, options: { chart: { toolbar: { show: !1 } }, legend: { show: !1 } } }],
};
(chart = new ApexCharts(document.querySelector("#apex-eur"), options)).render();
window.foodomia = window.foodomia || {};
window.foodomia.PerformanceEarnings = function($, canvasChart) {
    var _commonChartOptions = {
        height: 250,
        yAxisFormat: canvasChart.CURRENCY_FORMAT_WITH_DECIMALS,
        gridThickness: 1,
        gridColor: "#E9E9E9",
        dataSetColorPalette: ["rgba(190, 44, 44, 0.8)"]
    };

    function centsToDollars(centsAmount, noPrefix) {
        var output = noPrefix ? "" : "$";
        var dollarAmount = parseFloat(centsAmount / 100).toFixed(2);
        if (centsAmount < 0) {
            output += "(" + dollarAmount + ")";
            output = output.replace("-", "")
        } else {
            output += dollarAmount
        }
        return output
    }

    function _formatProjectedChartDataPoints(data) {
        var formattedData = data.slice(0, -3);
        var projectedData = data.slice(-3).map(function(dataPoint) {
            //dataPoint["color"] = canvasChart.BLUE;
            dataPoint["toolTipContent"] = "{y}";
            dataPoint["highlightEnabled"] = true;
            return dataPoint
        });
        return {
            chartData: [{
                type: "column",
                axisYType: "primary",
                toolTipContent: "{y}",
                showInLegend: false,
                dataPoints: formattedData.concat(projectedData)
            }]
        }
    }
    return {
        getChartCallOutCallback: function(callOutContainerSelector, fadeInDelay) {
            var delay = fadeInDelay || 2e3;
            return function showCallOut() {
                setTimeout(function() {
                    $(callOutContainerSelector).fadeIn(delay)
                }, 1e3)
            }
        },
        init: function(params) {
            $(document).ready(function() {
                var $revenueChartContainer = $(params.revenueChartRowSelector);
                var $orderSizeChartContainer = $(params.orderSizeChartRowSelector);
                var $chartSwitchButton = $(params.chartSwitchButtonSelector);
                var $revenueChart = $(params.chartSelectors.revenue);
                var $orderSizeChart = $(params.chartSelectors.averageOrderSize);
                var archivedStatementsParams = params.getArchivedStatementsParams;
                var viewStatementUrl = archivedStatementsParams.urls.viewStatement;
                var $getArchivedStatements = $(archivedStatementsParams.selectors.button);
                var $earningsTableFooter = $(archivedStatementsParams.selectors.tableFooter);
                var $archivedStatementsErrorContainer = $(archivedStatementsParams.selectors.errorContainer);
                var statementRowTemplate = $(archivedStatementsParams.selectors.rowTemplate).text();
                Mustache.parse(statementRowTemplate);
                $chartSwitchButton.click(function() {
                    switch ($(this).data("target-toggle")) {
                        case "earnings":
                            $orderSizeChartContainer.addClass("hidden");
                            $revenueChartContainer.removeClass("hidden");
                            if ($revenueChart.CanvasJSChart()) {
                                $revenueChart.CanvasJSChart().render()
                            }
                           
                    }
                });
                $getArchivedStatements.click(function() {
                    $getArchivedStatements.addClass("disabled");
                    $archivedStatementsErrorContainer.addClass("hidden");
                    $getArchivedStatements.find("i").removeClass("hidden");
                    $.post(archivedStatementsParams.urls.getStatements, function(response) {
                        if (response.errorMessage) {
                            $archivedStatementsErrorContainer.text(response.errorMessage).removeClass("hidden")
                        } else {
                            var statements = JSON.parse(response.statements);
                            statements.forEach(function(statement) {
                                $(archivedStatementsParams.selectors.tableBody).append(Mustache.render(statementRowTemplate, {
                                    periodEnd: moment(statement.periodEnd).format("MMMM DD, YYYY"),
                                    amount: centsToDollars(statement.statementAmount),
                                    viewUrl: viewStatementUrl.replace("--statementId--", statement.statementId)
                                }))
                            });
                            $earningsTableFooter.addClass("hidden")
                        }
                    }).fail(function() {
                        $archivedStatementsErrorContainer.text("Loading older statements encountered an error.").removeClass("hidden")
                    }).always(function() {
                        $getArchivedStatements.removeClass("disabled");
                        $getArchivedStatements.find("i").addClass("hidden")
                    })
                })
            })
        },
        renderRevenueChart: function(params, data) {
            var chartData = _formatProjectedChartDataPoints(data);
            var chartMaximum = chartData.chartData[0].dataPoints[chartData.chartData[0].dataPoints.length - 1].y;
            var chartOptions = {
                yAxisIntervalMaximum: chartMaximum,
                yAxisInterval: chartMaximum / 4
            };
            canvasChart.initChart(params.chartContainerSelector, chartData, $.extend({}, _commonChartOptions, chartOptions), [window.foodomia.PerformanceEarnings.getChartCallOutCallback(params.chartCallOutSelector, 2e3)])
        },
        renderOrderSizeChart: function(params) {
            canvasChart.hideError(params.chartContainerSelector);
            var $chartTitleAmount = $(params.averageOrderSizeAmountSelector);
            $.get(params.dataUrl, function(response) {
                if (!response.error) {
                    $chartTitleAmount.html("$" + ((response.data.averageOrderSize || 0) / 100).toFixed(2));
                    if (response.data.chartData.length > 0) {
                        var chartData = _formatProjectedChartDataPoints(response.data.chartData);
                        var chartMaximum = chartData.chartData[0].dataPoints[chartData.chartData[0].dataPoints.length - 1].y;
                        var chartOptions = {
                            yAxisIntervalMaximum: chartMaximum,
                            yAxisInterval: chartMaximum / 4
                        };
                        canvasChart.initChart(params.chartContainerSelector, chartData, $.extend({}, _commonChartOptions, chartOptions), [window.foodomia.PerformanceEarnings.getChartCallOutCallback(params.chartCallOutSelector, 2e3)])
                    } else {
                        canvasChart.showNoDataError(params.chartContainerSelector)
                    }
                } else {
                    canvasChart.showError(params.chartContainerSelector, response.data.error)
                }
            }).fail(function() {
                canvasChart.showError(params.chartContainerSelector)
            })
        }
    }
}(jQuery, window.foodomia.CanvasCharts);
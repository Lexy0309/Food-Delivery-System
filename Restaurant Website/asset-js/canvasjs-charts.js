window.foodomia = window.foodomia || {};
window.foodomia.CanvasCharts = function($, skipLogging) {
    var _chartSelectorSuffix = "-chart";
    var _spinnerSelector = ".spinner";
    var _errorMessageContainerSelector = ".chart-error";
    var _RED = "#BE2C2C";
    var _BLUE = "#417496";
    var _GREEN = "#64A640";
    var _YELLOW = "#EE9F00";
    var _ORANGE = "#EB4A12";
    var _GRAY = "#999999";
    var _defaultChartDataColors = [_RED, _GRAY, _YELLOW, _BLUE, _ORANGE, _GREEN];
    var _AXIS_GRID_COLOR = "#EDEDED";
    var _STRIP_LINE_COLOR = "#878787";
    var _AXIS_FONT_WEIGHT = "normal";
    var _AXIS_FONT_FAMILY = "Roboto";
    var _AXIS_TICK_SIZE = 0;
    var _AXIS_TITLE_FONT_SIZE = 17;
    var _AXIS_LABEL_FONT_SIZE = 14;
    var _AXIS_LINE_THICKNESS = 2;
    var _AXIS_GRID_THICKNESS = 0;
    var _DECIMAL_FORMAT = "###,###,##0.00";
    var _CURRENCY_FORMAT = "###,###0";
    var _CURRENCY_WITH_DECIMALS_FORMAT = "#,###,##0.00";
    var _TIME_FORMAT = "0:00";
    var _DEFAULT_ERROR_MESSAGE = "The chart could not be displayed. Please try again.";

    function _hideError(chartContainerSelector) {
        var $errorMessageContainer = $(chartContainerSelector).find(_errorMessageContainerSelector);
        var $errorMessageText = $errorMessageContainer.find("span");
        $errorMessageContainer.addClass("hidden");
        $errorMessageText.text("")
    }

    function _showError(chartContainerSelector, message) {
        var $errorMessageContainer = $(chartContainerSelector).find(_errorMessageContainerSelector);
        var $errorMessageText = $errorMessageContainer.find("span");
        $errorMessageText.text(message);
        $errorMessageContainer.removeClass("hidden")
    }

    function _loadChart(chartContainerSelector, jsonUrl, chartOptions, callbacks) {
        var generalErrorText = _DEFAULT_ERROR_MESSAGE;
        var $spinner = $(chartContainerSelector).find(_spinnerSelector);
        _hideError(chartContainerSelector);
        $spinner.removeClass("hidden");
        $.get(jsonUrl, function(response) {
            if (!response.error) {
                if (response.data.chartData.length) {
                    _createChart(chartContainerSelector, response.data, chartOptions, callbacks)
                } else {
                    _showError(chartContainerSelector, "No chart data was found.")
                }
            } else {
                skipLogging.error("Failed to load chart data: " + response.error);
                if (response.data && response.data.errorMessage) {
                    _showError(chartContainerSelector, response.data.errorMessage)
                } else {
                    _showError(chartContainerSelector, generalErrorText)
                }
            }
        }).fail(function() {
            skipLogging.critical("Failed chart data call to url: " + jsonUrl);
            _showError(chartContainerSelector, generalErrorText)
        }).always(function() {
            $spinner.addClass("hidden")
        })
    }

    function _formatChartDataPoints(dataPoints, fullChartOptions) {
        $.each(dataPoints, function(index, dataPoint) {
            if ($.inArray(index, fullChartOptions.dataPointAlternativeColorIndexes) > -1) {
                dataPoint.color = fullChartOptions.dataPointAlternativeColor
            }
            var isAlternateColorbyLabel = $.inArray(dataPoint.label, fullChartOptions.dataPointAlternativeColorLabels) > -1;
            if (isAlternateColorbyLabel) {
                dataPoint.color = fullChartOptions.dataPointAlternativeColor
            }
            if (dataPoint.hasOwnProperty("overThreshold") && dataPoint.overThreshold) {
                if (fullChartOptions.hasOwnProperty("dataPointOverThresholdColor")) {
                    dataPoint.color = fullChartOptions.dataPointOverThresholdColor
                }
                if (isAlternateColorbyLabel && fullChartOptions.hasOwnProperty("dataPointOverThresholdAlternativeColor")) {
                    dataPoint.color = fullChartOptions.dataPointOverThresholdAlternativeColor
                }
            }
        });
        return dataPoints
    }

    function _formatChartData(data, fullChartOptions) {
        $.each(data, function(index, dataDefinition) {
            dataDefinition.color = fullChartOptions.dataSetColorPalette[index % fullChartOptions.dataSetColorPalette.length];
            dataDefinition.xValueFormatString = fullChartOptions.axisX.valueFormatString;
            dataDefinition.markerSize = fullChartOptions.markerSize;
            dataDefinition.lineThickness = fullChartOptions.lineThickness;
            if (dataDefinition.hasOwnProperty("axisYType") && dataDefinition.axisYType === "secondary") {
                dataDefinition.yValueFormatString = fullChartOptions.axisY2.valueFormatString
            } else {
                dataDefinition.yValueFormatString = fullChartOptions.axisY.valueFormatString
            }
            dataDefinition.dataPoints = _formatChartDataPoints(dataDefinition.dataPoints, fullChartOptions)
        });
        return data
    }

    function _getStripLineDefinition(startValue, endValue) {
        return {
            startValue: startValue,
            endValue: endValue,
            color: _STRIP_LINE_COLOR,
            showOnTop: true
        }
    }

    function _getFullAxisDefinition(title, axisDefinition) {
        var definition = axisDefinition || {};
        var defaultDefinition = {
            title: title || "",
            titleFontSize: _AXIS_TITLE_FONT_SIZE,
            labelFontSize: _AXIS_LABEL_FONT_SIZE,
            labelFontWeight: _AXIS_FONT_WEIGHT,
            labelFontFamily: _AXIS_FONT_FAMILY,
            labelFontColor: _GRAY,
            labelAngle: 0,
            tickThickness: _AXIS_TICK_SIZE,
            lineThickness: _AXIS_LINE_THICKNESS,
            gridThickness: _AXIS_GRID_THICKNESS
        };
        return $.extend({}, defaultDefinition, definition)
    }

    function _formatXAxis(title, xAxisFormat, xAxisInterval, lineColor) {
        var xAxisOptions = {
            valueFormatString: xAxisFormat || null,
            interval: xAxisInterval || null,
            lineColor: lineColor || _AXIS_GRID_COLOR
        };
        return _getFullAxisDefinition(title, xAxisOptions)
    }

    function _formatYAxis(title, valueFormatString, interval, intervalMaximum, gridThickness, gridColor, lineColor) {
        var yAxisOptions = {
            valueFormatString: valueFormatString,
            maximum: intervalMaximum,
            interval: interval,
            gridThickness: gridThickness || _AXIS_GRID_THICKNESS,
            lineColor: lineColor || _AXIS_GRID_COLOR,
            gridColor: gridColor || _AXIS_GRID_COLOR
        };
        return _getFullAxisDefinition(title, yAxisOptions)
    }

    function _getFullChartOptions(chartOptions) {
        var options = chartOptions || {};
        var fullChartOptions = {
            width: options.width || 0,
            height: options.height || 0,
            theme: "theme1",
            animationEnabled: true,
            animationDuration: 1500,
            dataPointWidth: chartOptions.dataPointWidth || 75,
            title: {
                text: options.title || "",
                fontSize: 30
            },
            legend: {
                fontSize: 15,
                horizontalAlign: "center",
                verticalAlign: "bottom",
                fontFamily: "Roboto"
            },
            axisX: _formatXAxis(options.xAxisTitle, options.xAxisFormat, options.xAxisInterval),
            axisY: _formatYAxis(options.yAxisTitle, options.yAxisFormat, options.yAxisInterval, options.yAxisIntervalMaximum, options.gridThickness, options.gridColor),
            axisY2: _formatYAxis(options.yAxisTitle2, options.yAxisFormat2, options.yAxisInterval2, options.yAxisIntervalMaximum2, options.gridThickness, options.gridColor),
            xAxisFormat: options.xAxisFormat || null,
            markerSize: options.markerSize || 10,
            lineThickness: options.lineThickness || 3,
            dataSetColorPalette: options.dataSetColorPalette || _defaultChartDataColors,
            dataPointAlternativeColorIndexes: options.dataPointAlternativeColorIndexes || [],
            dataPointAlternativeColorLabels: options.dataPointAlternativeColorLabels || [],
            dataPointAlternativeColor: options.dataPointAlternativeColor || null
        };
        if (options.hasOwnProperty("yAxisStripLineStart") && options.hasOwnProperty("yAxisStripLineEnd")) {
            var yStripLine = _getStripLineDefinition(options.yAxisStripLineStart, options.yAxisStripLineEnd);
            fullChartOptions.axisY.stripLines = [yStripLine]
        }
        if (options.hasOwnProperty("yAxis2StripLineStart") && options.hasOwnProperty("yAxis2StripLineEnd")) {
            var y2StripLine = _getStripLineDefinition(options.yAxis2StripLineStart, options.yAxis2StripLineEnd);
            fullChartOptions.axisY2.stripLines = [y2StripLine]
        }
        if (options.hasOwnProperty("xAxisStripLineStart") && options.hasOwnProperty("xAxisStripLineEnd")) {
            var xStripLine = _getStripLineDefinition(options.xAxisStripLineStart, options.xAxisStripLineEnd);
            fullChartOptions.axisX.stripLines = [xStripLine]
        }
        if (options.hasOwnProperty("dataPointOverThresholdColor")) {
            fullChartOptions.dataPointOverThresholdColor = options.dataPointOverThresholdColor
        }
        if (options.hasOwnProperty("dataPointOverThresholdAlternativeColor")) {
            fullChartOptions.dataPointOverThresholdAlternativeColor = options.dataPointOverThresholdAlternativeColor
        }
        return fullChartOptions
    }

    function _createChart(chartContainerSelector, data, chartOptions, callbacks) {
        var fullChartOptions = _getFullChartOptions(chartOptions);
        fullChartOptions.data = _formatChartData(data.chartData, fullChartOptions);
        $(chartContainerSelector + _chartSelectorSuffix).CanvasJSChart(fullChartOptions);
        if (callbacks && callbacks.length) {
            for (var i = 0; i < callbacks.length; i++) {
                callbacks[i](data)
            }
        }
    }
    return {
        initChart: function(chartContainerSelector, jsonData, chartOptions, callbacks) {
            $(document).ready(function() {
                _createChart(chartContainerSelector, jsonData, chartOptions, callbacks)
            })
        },
        initChartAsync: function(chartContainerSelector, jsonUrl, chartOptions, callbacks) {
            $(document).ready(function() {
                _loadChart(chartContainerSelector, jsonUrl, chartOptions, callbacks)
            })
        },
        getChartCallOutCallback: function(callOutContainerSelector, fadeInDelay) {
            var delay = fadeInDelay || 2e3;
            return function showCallOut() {
                setTimeout(function() {
                    $(callOutContainerSelector).fadeIn(delay)
                }, 1e3)
            }
        },
        getStripLineTextCallback: function(stripLineTextSelector, fadeInDelay) {
            var delay = fadeInDelay || 1e3;
            return function showStripLineText() {
                setTimeout(function() {
                    $(stripLineTextSelector).fadeIn(delay)
                }, 500)
            }
        },
        showNoDataError: function(chartContainerSelector) {
            _showError(chartContainerSelector, "No chart data was found.")
        },
        showError: function(chartContainerSelector, message) {
            _showError(chartContainerSelector, message || _DEFAULT_ERROR_MESSAGE)
        },
        hideError: function(chartContainerSelector) {
            _hideError(chartContainerSelector)
        },
        DECIMAL_FORMAT: _DECIMAL_FORMAT,
        CURRENCY_FORMAT: _CURRENCY_FORMAT,
        CURRENCY_FORMAT_WITH_DECIMALS: _CURRENCY_WITH_DECIMALS_FORMAT,
        TIME_FORMAT: _TIME_FORMAT,
        BLUE: "rgba(65, 116, 150, .20)"
    }
}(jQuery, window.foodomia.Logging);
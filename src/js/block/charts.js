$(() => {
    const src = window.APPROVE_PIE || { team:0, promo:0, deals:1 };

    ['charts', 'chartsModal'].forEach(containerId => {
        Highcharts.chart(containerId, {
            chart: {
                type: 'pie',
                width: 170,
                height: 170,
                custom: {},
                events: {
                    render() {
                        const chart = this,
                            series = chart.series[0];
                        let label = chart.options.chart.custom.label;
                        if (!label) {
                            label = chart.options.chart.custom.label = chart.renderer
                                .label(
                                    `<div class="graphic-title"><strong>$ ${Number(window.BALANCE)
                                        .toLocaleString('ru-RU', { minimumFractionDigits: 0 })}</strong></div>`
                                )
                                .css({ color:'#000', textAnchor:'middle', border:'1px dashed #fff' })
                                .add();
                        }
                        const x = series.center[0] + chart.plotLeft,
                            y = series.center[1] + chart.plotTop - (label.attr('height')/2);
                        label.attr({ x, y }).css({ fontSize: '20px' });
                    }
                }
            },
            tooltip:    { enabled: false },
            legend:     { enabled: false },
            accessibility: { point: { valueSuffix: '%' } },
            zooming:    { enabled: false },
            title:      { text: '', style:{ display:'none' } },
            plotOptions: {
                pie: {
                    size: 163,
                    innerSize: '80%',
                    borderWidth: 0,
                    dataLabels: { enabled: false }
                }
            },
            series: [{
                colorByPoint: true,
                enableMouseTracking: false,
                showInLegend: false,
                data: [
                    { name: 'Команда(red)',  y: src.team  },
                    { name: 'Промо(yellow)', y: src.promo },
                    { name: 'Активы(green)', y: src.deals }
                ]
            }]
        });
    });
});


$(() => {

    Highcharts.chart('line', {

        chart: {
            type: 'line',
            width: 100,
            height: 50,
        },

        title: {

        },

        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },

        subtitle: {

        },

        tooltip: {
            enabled: false
        },

        yAxis: {
            visible: false,
        },

        xAxis: {
            visible: false,
        },

        plotOptions: {

            line: {
                size: 100
            },

            series: {
                marker: {
                    enabled: false
                },
                color: '#30e0a1',
                enableMouseTracking: false,
                showInLegend: false,
            }
        },


        legend: {
            enabled: false
        },

        zooming:{
            enabled: false
        },

        series: [{

        },   {
            name: '',
            data: chartData
        }],

    });

});

$(() => {

    Highcharts.chart('lineRed', {

        chart: {
            type: 'line',
            width: 100,
            height: 50,
        },

        title: {

        },

        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },

        subtitle: {

        },

        tooltip: {
            enabled: false
        },

        yAxis: {
            visible: false,
        },

        xAxis: {
            visible: false,
        },

        plotOptions: {

            line: {
                size: 100
            },

            series: {
                marker: {
                    enabled: false
                },
                color: '#FA2256',
                enableMouseTracking: false,
                showInLegend: false,
            }
        },


        legend: {
            enabled: false
        },

        zooming:{
            enabled: false
        },

        series: [{

        },   {
            name: '',
            data: chartDataTeam
        }],

    });

});

$(() => {

    Highcharts.chart('lineGreen', {

        chart: {
            type: 'line',
            width: 100,
            height: 50,
        },

        title: {

        },

        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },

        subtitle: {

        },

        tooltip: {
            enabled: false
        },

        yAxis: {
            visible: false,
        },

        xAxis: {
            visible: false,
        },

        plotOptions: {

            line: {
                size: 100
            },

            series: {
                marker: {
                    enabled: false
                },
                color: '#e28d44',
                enableMouseTracking: false,
                showInLegend: false,
            }
        },


        legend: {
            enabled: false
        },

        zooming:{
            enabled: false
        },

        series: [{

        },   {
            name: '',
            data: [
                29.9, 30, 40, 75, 60, 24, 11, 148.5, 200, 194.1,
                95.6, 54.4
            ]
        }],

    });

});
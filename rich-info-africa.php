<style>
    * {
    font-family: sans-serif;
}
#wrapper-info {
    height: 500px;
    min-width: 1000px;
    margin: 0 auto;
    padding: 0;
    overflow:visible;
}

#container-rich-info {
    float: left;
    height: 500px; 
    min-width: 700px; 
    margin: 0;
}

#info {
    float: left;
    min-width: 270px;
    padding-left: 20px;
    margin: 100px 0 0 0;
    border-left: 1px solid silver;
}

#info h2 {
    display: inline;
    font-size: 13pt;
}
#info .f32 .flag {
    vertical-align: bottom !important;
}

#info h4 {
    margin: 1em 0 0 0;
}

@media screen and (max-width: 920px) {
    #wrapper-info, #container-rich-info,  #info {
        float: none;
        width: 100%;
        height: auto;
        margin: 0.5em 0;
        padding: 0;
        border: none;
    }
}
</style>
<script src="js/highcharts.js"></script>
<script src="maps/modules/data.js"></script>
<script src="maps/modules/map.js"></script>
<script src="mapdata/custom/world.js"></script>

<!-- Flag sprites service provided by Martijn Lafeber, https://github.com/lafeber/world-flags-sprite/blob/master/LICENSE -->
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags32.css" />


<div id="wrapper-info" class="row">
    <div id="container-rich-info" class="col-lg-6"></div>
    <div id="info" class="col-lg-4">
        <span class="f32"><span id="flag"></span></span>
        <h2></h2>
        <div class="subheader">Click countries to view history</div>
        <div id="country-chart"></div>
    </div>
</div>

<script>
    Highcharts.ajax({
    url: 'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/world-population-history.csv',
    dataType: 'csv',
    success: function (csv) {

        // Parse the CSV Data
        /*Highcharts.data({
            csv: data,
            switchRowsAndColumns: true,
            parsed: function () {
                console.log(this.columns);
            }
        });*/

        // Very simple and case-specific CSV string splitting
        function CSVtoArray(text) {
            return text.replace(/^"/, '')
                .replace(/",$/, '')
                .split('","');
        }

        csv = csv.split(/\n/);

        var countries = {},
            mapChart,
            countryChart,
            numRegex = /^[0-9\.]+$/,
            lastCommaRegex = /,\s$/,
            quoteRegex = /\"/g,
            categories = CSVtoArray(csv[2]).slice(4);

        // Parse the CSV into arrays, one array each country
        csv.slice(3).forEach(function (line) {
            var row = CSVtoArray(line),
                data = row.slice(4);

            data.forEach(function (val, i) {
                val = val.replace(quoteRegex, '');
                if (numRegex.test(val)) {
                    val = parseInt(val, 10);
                } else if (!val || lastCommaRegex.test(val)) {
                    val = null;
                }
                data[i] = val;
            });
            countries[row[1]] = {
                name: row[0],
                code3: row[1],
                data: data
            };
        });

        // For each country, use the latest value for current population
        var data = [];
        for (var code3 in countries) {
            if (Object.hasOwnProperty.call(countries, code3)) {
                var value = null,
                    year,
                    itemData = countries[code3].data,
                    i = itemData.length;

                while (i--) {
                    if (typeof itemData[i] === 'number') {
                        value = itemData[i];
                        year = categories[i];
                        break;
                    }
                }
                data.push({
                    name: countries[code3].name,
                    code3: code3,
                    value: value,
                    year: year
                });
            }
        }

        // Add lower case codes to the data set for inclusion in the tooltip.pointFormat
        var mapData = Highcharts.geojson(Highcharts.maps['custom/world']);
        mapData.forEach(function (country) {
            country.id = country.properties['hc-key']; // for Chart.get()
            country.flag = country.id.replace('UK', 'GB').toLowerCase();
        });
        // mapData = mapData.filter(function (country) {
        //     country.id = country.properties['hc-key']; // for Chart.get()
        //     country.flag = country.id.replace('UK', 'GB').toLowerCase();
        //     if(country.properties['subregion']=="Western Africa")return true;
        //     return false;
        //     // "subregion": "Western Africa",
        // });

        // Wrap point.select to get to the total selected points
        Highcharts.wrap(Highcharts.Point.prototype, 'select', function (proceed) {

            proceed.apply(this, Array.prototype.slice.call(arguments, 1));

            var points = mapChart.getSelectedPoints();
            if (points.length) {
                if (points.length === 1) {
                    document.querySelector('#info #flag')
                        .className = 'flag ' + points[0].flag;
                    document.querySelector('#info h2').innerHTML = points[0].name;
                } else {
                    document.querySelector('#info #flag')
                        .className = 'flag';
                    document.querySelector('#info h2').innerHTML = 'Comparing countries';

                }
                document.querySelector('#info .subheader')
                    .innerHTML = '<h4>Historical population</h4><small><em>Shift + Click on map to compare countries</em></small>';

                if (!countryChart) {
                    countryChart = Highcharts.chart('country-chart', {
                        chart: {
                            height: 250,
                            spacingLeft: 0
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        subtitle: {
                            text: null
                        },
                        xAxis: {
                            tickPixelInterval: 50,
                            crosshair: true
                        },
                        yAxis: {
                            title: null,
                            opposite: true
                        },
                        tooltip: {
                            split: true
                        },
                        plotOptions: {
                            series: {
                                animation: {
                                    duration: 500
                                },
                                marker: {
                                    enabled: false
                                },
                                threshold: 0,
                                pointStart: parseInt(categories[0], 10)
                            }
                        }
                    });
                }

                countryChart.series.slice(0).forEach(function (s) {
                    s.remove(false);
                });
                points.forEach(function (p) {
                    countryChart.addSeries({
                        name: p.name,
                        data: countries[p.code3].data,
                        type: points.length > 1 ? 'line' : 'area'
                    }, false);
                });
                countryChart.redraw();

            } else {
                document.querySelector('#info #flag').className = '';
                document.querySelector('#info h2').innerHTML = '';
                document.querySelector('#info .subheader').innerHTML = '';
                if (countryChart) {
                    countryChart = countryChart.destroy();
                }
            }
        });

        // Initiate the map chart
        mapChart = Highcharts.mapChart('container-rich-info', {

            title: {
                text: 'Population history by country'
            },

            subtitle: {
                text: 'Source: <a href="http://data.worldbank.org/indicator/SP.POP.TOTL/countries/1W?display=default">The World Bank</a>'
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },

            colorAxis: {
                type: 'logarithmic',
                endOnTick: false,
                startOnTick: false,
                min: 50000
            },

            tooltip: {
                footerFormat: '<span style="font-size: 10px">(Click for details)</span>'
            },

            series: [{
                data: data,
                mapData: mapData,
                joinBy: ['iso-a3', 'code3'],
                name: 'Current population',
                allowPointSelect: true,
                cursor: 'pointer',
                states: {
                    select: {
                        color: '#a4edba',
                        borderColor: 'black',
                        dashStyle: 'shortdot'
                    }
                },
                borderWidth: 0.5
            }]
        });

        // Pre-select a country
        mapChart.get('us').select();
    }
});

</script>
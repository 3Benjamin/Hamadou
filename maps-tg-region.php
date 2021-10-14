<style>
  * {
    font-family: sans-serif;
  }

  #container-tg-region {
    height: 700px;
    min-width: 310px;
    max-width: 800px;
    margin: 0 auto;
  }
</style>
<script src="maps/tg-all.js"></script>
<div id="container-tg-region"></div>
<!--Flag sprites service provided by Martijn Lafeber, https://github.com/lafeber/world-flags-sprite/blob/master/LICENSE -->
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags32.css" />



<script>
  function getJSON(url, cb) {
    const request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function () {
      if (this.status < 400) {
        return cb(JSON.parse(this.response));
      }
    };

    request.send();
  }


  const tgData = [];

  Highcharts.maps["countries/tg/tg-all"].features
    .forEach(function (elem) {
      tgData.push({
        id: elem.id,
        country: elem.properties.country,
        lat: elem.properties.latitude,
        lon: elem.properties.longitude,
        capital: elem.properties.name
      })
      // getTemp(elem, countries, capitals);
    });
  // Get temperature for specific localization, and add it to the chart.
  // It takes point as first argument, countries series as second
  // and capitals series as third. Capitals series have to be the
  // 'mappoint' series type, and it should be defined before in the
  // series array.
  function getTemp(point, countries, capitals) {

    const url = 'https://api.met.no/weatherapi/locationforecast/2.0/?lat=' +
      point[1] + '&lon=' + point[2];

    const callBack = json => {

      const temp = json.properties.timeseries[0].data.instant.details
        .air_temperature;
      const colorAxis = countries.chart.colorAxis[0];

      const country = {
        'hc-key': point[0],
        value: parseInt(temp, 10) || null
      };
      const capital = {
        name: point[3],
        lat: point[1],
        lon: point[2],
        color: colorAxis.toColor(temp),
        temp: parseInt(temp, 10) || 'No data'
      };

      countries.addPoint(country);
      capitals.addPoint(capital);
      return temp;
    };

    getJSON(url, callBack);
  }

  // Create the chart
  Highcharts.mapChart('container-tg-region', {
    chart: {
      map: 'countries/tg/tg-all',
      animation: false,
      events: {
        load: function () {
          var countries = this.series[0];
          var capitals = this.series[1];


          tgData.forEach(function (point) {

            // const temp=0;
            // const country = {
            //   'hc-key': point.id,
            //   value: parseInt(temp, 10) || null
            // };
            // const capital = {
            //   name: point.capital,
            //   lat: parseInt(point.lat * 10, 10) / 10,
            //   lon: parseInt(point.lon * 10, 10) / 10,
            //   temp: parseInt(temp, 10) || 'No data'
            // };

            // countries.addPoint(country);
            // capitals.addPoint(capital);
            // getTemp(point, countries, capitals);

          });
        }
      }
    },

    title: {
      text: 'Current temperatures in region of Togo'
    },

    subtitle: {
      text: 'Data source: <a href="https://api.met.no/">https://api.met.no/</a>'
    },

    mapNavigation: {
      enabled: true,
      buttonOptions: {
        verticalAlign: 'bottom'
      }
    },

    colorAxis: {
      min: -25,
      max: 40,
      labels: {
        format: '{value}°C'
      },
      stops: [
        [0, '#0000ff'],
        [0.3, '#6da5ff'],
        [0.6, '#ffff00'],
        [1, '#ff0000']
      ]
    },

    legend: {
      title: {
        text: 'Degrees Celsius'
      }
    },

    tooltip: {
      headerFormat: '<span style="color:{point.color}">\u25CF</span> {point.key}:<br/>',
      pointFormatter: function () {
        // var value = Number.isInteger(this.temp) ? this.temp + '°C' : 'No data';
        var value = 30;
        return 'Temperature: <b>' + value + '</b>';
      }
    },

    series: [{
      name: 'Random data',
      states: {
        hover: {
          color: '#BADA55'
        }
      },
      dataLabels: {
        enabled: true,
        format: '{point.name}'
      }
    }]
  });

</script>
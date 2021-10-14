<style>
  * {
    font-family: sans-serif;
  }

  #container-bf-province {
    height: 700px;
    min-width: 310px;
    max-width: 800px;
    margin: 0 auto;
  }
</style>
<script src="maps/bf-prov.min.js"></script>
<div id="container-bf-province"></div>
<!-- Flag sprites service provided by Martijn Lafeber, https://github.com/lafeber/world-flags-sprite/blob/master/LICENSE -->
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags32.css" />



<script>

  function getJSON(url, cb) {
    const request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function () {
      // if (this.status < 400) {
      //   console.log(this.status)
      return cb(JSON.parse(this.response));
      // }
      // else{
      //   console.log(this.status)
      // }
    };

    request.send();
  }
  const provinceData = [];

  Highcharts.maps["countries/bf/bf-prov"].features
    .forEach(function (elem) {
      provinceData.push({
        id: elem.id,
        country: elem.properties.country,
        lat: elem.properties.latitude,
        lon: elem.properties.longitude,
        capital: elem.properties.name
      })
      // getTemp(elem, countries, capitals);
    });
  // Data structure: [country_code, latitude, longitude, capital_city]

  // Get temperature for specific localization, and add it to the chart.
  // It takes point as first argument, countries series as second
  // and capitals series as third. Capitals series have to be the
  // 'mappoint' series type, and it should be defined before in the
  // series array.

  function getTemp1(point, countries, capitals) {

    const url = 'https://api.met.no/weatherapi/locationforecast/2.0/?lat=' +
      point.lat + '&lon=' + point.lon;

    const callBack = json => {

      const temp = json.properties.timeseries[0].data.instant.details
        .air_temperature;
      const colorAxis = countries.chart.colorAxis[0];
      const country = {
        'hc-key': point.id,
        value: parseInt(temp, 10) || null
      };
      const capital = {
        name: point.capital,
        lat: parseInt(point.lat * 10, 10) / 10,
        lon: parseInt(point.lon * 10, 10) / 10,
        color: colorAxis.toColor(temp),
        temp: parseInt(temp, 10) || 'No data'
      };
      countries.addPoint(country);
      capitals.addPoint(capital);
      return temp;
    };

    getJSON(url, callBack);
  }


  function getTemp(point, countries, capitals) {

    const temp  = Math.floor(Math.random() * 40);
    const country = {
      'hc-key': point.id,
      value: parseInt(temp, 10) || null
    };
      const colorAxis = countries.chart.colorAxis[0];
    const capital = {
      name: point.capital,
      lat: parseInt(point.lat * 10, 10) / 10,
      lon: parseInt(point.lon * 10, 10) / 10,
        color: colorAxis.toColor(temp),
      temp: parseInt(temp, 10) || 'No data'
    };
    countries.addPoint(country);
    capitals.addPoint(capital);

  }


  // Create the chart
  Highcharts.mapChart('container-bf-province', {
    chart: {
      map: 'countries/bf/bf-prov',
      animation: false,
      events: {
        load: function () {
          var countries = this.series[0];
          var capitals = this.series[1];


          provinceData.forEach(function (point) {

            getTemp(point, countries, capitals);

          });
        }
      }
    },

    title: {
      text: 'Current temperatures in province of Burkina Faso'
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
        var value = Number.isInteger(this.temp) ? this.temp + '°C' : 'No data';
        return 'Temperature: <b>' + value + '</b>';
      }
    },

    series: [{
      name: 'Temperatures',
      states: {
        hover: {
          color: '#BADA55'
        }
      },
      dataLabels: {
        enabled: false
      },
      enableMouseTracking: false
    }, {
      name: 'Region of Burkina Faso',
      type: 'mappoint',
      showInLegend: false,
      marker: {
        lineWidth: 1,
        lineColor: '#000'
      },
      dataLabels: {
        crop: true,
        formatter: function () {
          var value = Number.isInteger(this.point.temp) ? this.point.temp + '°C' : 'No data';
          return '<span>' + this.point.name + '</span><br/><span>' + value + '</span>';
        }
      }
    }]
  });

</script>
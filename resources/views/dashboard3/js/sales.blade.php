<script>
	'use strict';

	var rs_sales_variety_options = {
          series: [{
          name: 'Volume',
          data: [<?php foreach($rs_sold_variety_quantity as $item) {
              echo $item . ",";
            }
          ?>]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [<?php foreach($rs_sold_variety as $item) {
              echo "'" . $item . "',";
            }
          ?>]
        },
        yaxis: {
          title: {
            text: 'Volume (kg)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " kg"
            }
          }
        }
        };

  var fs_sales_variety_options = {
          series: [{
          name: 'Volume',
          data: [<?php foreach($fs_sold_variety_quantity as $item) {
              echo $item . ",";
            }
          ?>]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [<?php foreach($fs_sold_variety as $item) {
              echo "'" . $item . "',";
            }
          ?>]
        },
        yaxis: {
          title: {
            text: 'Volume (kg)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " kg"
            }
          }
        }
        };

    var rs_sales_station_options = {
          series: [{
          name: 'Volume',
          data: [<?php foreach($rs_sold_station_quantity as $item) {
              echo $item . ",";
            }
          ?>]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [<?php foreach($rs_sold_station as $item) {
              echo "'" . $item . "',";
            }
          ?>]
        },
        yaxis: {
          title: {
            text: 'Volume (kg)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " kg"
            }
          }
        }
        };

    var fs_sales_station_options = {
          series: [{
          name: 'Volume',
          data: [<?php foreach($fs_sold_station_quantity as $item) {
              echo $item . ",";
            }
          ?>]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [<?php foreach($fs_sold_station as $item) {
              echo "'" . $item . "',";
            }
          ?>]
        },
        yaxis: {
          title: {
            text: 'Volume (kg)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " kg"
            }
          }
        }
        };

    var varieties_sold_pie_options = {
          series: [<?php foreach($varieties_sold_quantity as $item) {
              echo $item . ",";
            }
          ?>],
          chart: {
          width: 500,
          type: 'pie',
        },
        labels: [<?php foreach($varieties_sold as $item) {
              echo "'" . $item . "',";
            }
          ?>],
        responsive: [{
          breakpoint: 300,
          options: {
            chart: {
              width: 100
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

    var top_varieties_sold_options = {
          series: [{
          data: [<?php foreach($top_varieties_sold_quantity as $item) {
              echo $item . ",";
            }
          ?>]
        }],
          chart: {
          type: 'bar'
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: [<?php foreach($top_varieties_sold as $item) {
              echo "'" . $item . "',";
            }
          ?>],
        }
        };

    var rs_sold_variety_chart = new ApexCharts(document.querySelector("#RSSoldVarietyChart"), rs_sales_variety_options);
    rs_sold_variety_chart.render();

    var fs_sold_variety_chart = new ApexCharts(document.querySelector("#FSSoldVarietyChart"), fs_sales_variety_options);
    fs_sold_variety_chart.render();

    var rs_sold_station_chart = new ApexCharts(document.querySelector("#RSSoldStationChart"), rs_sales_station_options);
    rs_sold_station_chart.render();

    var fs_sold_station_chart = new ApexCharts(document.querySelector("#FSSoldStationChart"), fs_sales_station_options);
    fs_sold_station_chart.render();

    var varieties_sold_pie_chart = new ApexCharts(document.querySelector("#VarietiesSoldPieChart"), varieties_sold_pie_options);
    varieties_sold_pie_chart.render();

    var top_varieties_sold_chart = new ApexCharts(document.querySelector("#TopVarietiesSoldChart"), top_varieties_sold_options);
    top_varieties_sold_chart.render();
</script>
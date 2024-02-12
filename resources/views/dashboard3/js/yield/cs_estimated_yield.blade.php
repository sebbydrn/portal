<script>
	@if(isset($cs_estimated_yield_varieties) && isset($cs_estimated_yield_varieties_list) && isset($cs_estimated_yield_varieties_list) && isset($cs_estimated_yield_totals) && isset($cs_estimated_yield_region_data) && isset($cs_estimated_yield_region_months))
		var cs_estimated_yield_varieties = <?php echo json_encode($cs_estimated_yield_varieties) ?>;
		var cs_estimated_yield_varieties_list = <?php echo json_encode($cs_estimated_yield_varieties_list) ?>;
		var cs_estimated_yield_months = <?php echo json_encode($cs_estimated_yield_varieties_list) ?>;
		var cs_estimated_yield_totals = <?php echo json_encode($cs_estimated_yield_totals) ?>;
		var cs_estimated_yield_region_data = <?php echo json_encode($cs_estimated_yield_region_data) ?>;
		var cs_estimated_yield_region_months = <?php echo json_encode($cs_estimated_yield_region_months) ?>;

		var series = []
		var region_series = []

		cs_estimated_yield_varieties_list.forEach((item, value) => {
			var newObj = {}
			newObj.name = item
			var dataArr = []

			for (var monthIndex in cs_estimated_yield_varieties) {
				var varietiesArray = cs_estimated_yield_varieties[monthIndex]

				var isMatched = false

				for (var varietyIndex in varietiesArray) {
					if (varietyIndex == item) {
						dataArr.push(varietiesArray[varietyIndex])
						isMatched = true
					}
				}

				if (isMatched == false) {
					dataArr.push('0.0000')
				}
			}

			newObj.data = dataArr
			series.push(newObj)		
		})

		// cs_estimated_yield_region.forEach((item, value) => {
		// 	// var newObj = {}
		// 	// newObj.name = value['month']
		// 	console.log(item)
		// })
		console.log(cs_estimated_yield_region_data)

		// Stacked column chart
		var options = {
			series: series,
			chart: {
				type: 'bar',
				height: 400,
				stacked: true,
				toolbar: {
					show: true
				},
				zoom: {
					enabled: true
				}
			},
			responsive: [{
				breakpoint: 480,
				options: {
					legend: {
						position: 'bottom',
						offsetX: -10,
						offsetY: 0
					}
				}
			}],
			plotOptions: {
				bar: {
					horizontal: false
				}
			},
			xaxis: {
				categories: <?php echo json_encode($cs_estimated_yield_months) ?>,
				tickPlacement: 'on'
			},
			yaxis: {
		      title: {
		        text: 'Kilograms (kg)'
		      }
		    },
			legend: {
				position: 'bottom',
			},
			fill: {
				opacity: 1
			},
			tooltip: {
			  y: {
			    formatter: function (val) {
			      return val.toLocaleString("en-US") + " kg"
			    }
			  },
			},
		};

		var chart = new ApexCharts(document.querySelector("#cs_estimated_yield"), options);
		chart.render();

		// Line chart with column chart
		var options2 = {
			series: [
				{
					name: 'Estimated Yield (kg)',
					data: cs_estimated_yield_totals
				}
			],
			chart: {
				height: 350,
				type: 'line',
				stacked: false
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'straight'
			},
			xaxis: {
				categories: <?php echo json_encode($cs_estimated_yield_months) ?>
			},
			yaxis: [
				{
					title: {
						text: "Kilograms (kg)"
					},
					tooltip: {
						enabled: true
					}
				}
			],
			tooltip: {
				fixed: {
					enabled: true,
					position: 'topLeft',
					offsetY: 30,
					offsetY: 60
				},
				y: {
					formatter: function (val) {
						return val.toLocaleString("en-US") + " kg"
					}
				}
			},
			legend: {
				horizontalAlign: 'left',
				offsetX: 40
			},
			colors: ['#00a04c']
		};

		var chart2 = new ApexCharts(document.querySelector("#cs_estimated_yield_line"), options2);
		chart2.render();

		// Line chart for regions
		var options3 = {
			series: [
				cs_estimated_yield_region_data.reg_1,
				cs_estimated_yield_region_data.reg_2,
				cs_estimated_yield_region_data.reg_3,
				cs_estimated_yield_region_data.reg_4a,
				cs_estimated_yield_region_data.mimaropa,
				cs_estimated_yield_region_data.reg_5,
				cs_estimated_yield_region_data.reg_6,
				cs_estimated_yield_region_data.reg_7,
				cs_estimated_yield_region_data.reg_8,
				cs_estimated_yield_region_data.reg_9,
				cs_estimated_yield_region_data.reg_10,
				cs_estimated_yield_region_data.reg_11,
				cs_estimated_yield_region_data.reg_12,
				cs_estimated_yield_region_data.reg_13,
				cs_estimated_yield_region_data.car,
				cs_estimated_yield_region_data.ncr,
				cs_estimated_yield_region_data.barmm
			],
			chart: {
				height: 350,
				type: 'area',
				stacked: false
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'straight'
			},
			xaxis: {
				categories: cs_estimated_yield_region_months
			},
			yaxis: [
				{
					title: {
						text: "Kilograms (kg)"
					},
					tooltip: {
						enabled: true
					}
				}
			],
			tooltip: {
				fixed: {
					enabled: true,
					position: 'topLeft',
					offsetY: 30,
					offsetY: 60
				},
				y: {
					formatter: function (val) {
						return val.toLocaleString("en-US") + " kg"
					}
				}
			},
			legend: {
				horizontalAlign: 'center',
				offsetX: 40
			},
			colors: ['#ff1493', '#2e8b57', '#008000', '#808000', '#7f007f', '#ff0000', '#ff8c00', '#ffff00', '#0000cd', '#00ff00', '#00fa9a', '#ff6823', '#4169e1', '#00ffff', '#00bfff', '#ff00ff', '#dda0dd']
		};

		var chart3 = new ApexCharts(document.querySelector("#cs_estimated_yield_region_line"), options3);
		chart3.render();
	@endif
</script>
<script>
	@if(isset($rs_varieties_applied_program_data) && $rs_varieties_applied_program_data != null && !empty($rs_varieties_applied_program_data))
		var rs_varieties_applied_program_data = <?php echo json_encode($rs_varieties_applied_program_data) ?>

		var series = []
		rs_varieties_applied_program_data.forEach((item, index) => {
			var newObj = {}
			newObj.name = item.variety
			newObj.data = [
				item.rcef.toLocaleString("en-US"),
				item.nrp.toLocaleString("en-US"),
				item.golden_rice.toLocaleString("en-US"),
				item.none.toLocaleString("en-US"),
				item.others.toLocaleString("en-US")
			]
			series.push(newObj)
		})

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
				categories: ['RCEF', 'NRP', 'GOLDEN RICE', 'NONE', 'OTHERS'],
				tickPlacement: 'on'
			},
			yaxis: {
		      title: {
		        text: 'Hectares (ha)'
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
			      return val.toLocaleString("en-US") + " ha"
			    }
			  },
			},
		};

		var chart = new ApexCharts(document.querySelector("#varieties_applied_per_program"), options);
		chart.render();
	@endif
</script>
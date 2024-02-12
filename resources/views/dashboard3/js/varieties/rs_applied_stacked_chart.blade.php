<script>
	@if(isset($rs_varieties_applied_region_data) && $rs_varieties_applied_region_data != null && !empty($rs_varieties_applied_region_data))
		var rs_varieties_applied_region_data = <?php echo json_encode($rs_varieties_applied_region_data) ?>;

		var series = []
		rs_varieties_applied_region_data.forEach((item, index) => {
			var newObj = {}
			newObj.name = item.variety
			newObj.data = [
				item.reg_1.toLocaleString("en-US"),
				item.reg_2.toLocaleString("en-US"),
				item.reg_3.toLocaleString("en-US"),
				item.reg_4a.toLocaleString("en-US"),
				item.mimaropa.toLocaleString("en-US"),
				item.reg_5.toLocaleString("en-US"),
				item.reg_6.toLocaleString("en-US"),
				item.reg_7.toLocaleString("en-US"),
				item.reg_8.toLocaleString("en-US"),
				item.reg_9.toLocaleString("en-US"),
				item.reg_10.toLocaleString("en-US"),
				item.reg_11.toLocaleString("en-US"),
				item.reg_12.toLocaleString("en-US"),
				item.reg_13.toLocaleString("en-US"),
				item.ncr.toLocaleString("en-US"),
				item.car.toLocaleString("en-US"),
				item.barmm.toLocaleString("en-US"),
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
				categories: ['Region I', 'Region II', 'Region III', 'Region IV-A', 'MIMAROPA', 'Region V', 'Region VI', 'Region VII', 'Region VIII', 'Region IX', 'Region X', 'Region XI', 'Region XII', 'Region XIII', 'NCR', 'CAR', 'BARMM'],
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

		var chart = new ApexCharts(document.querySelector("#rs_varieties_applied_stacked_chart"), options);
		chart.render();
	@endif
</script>
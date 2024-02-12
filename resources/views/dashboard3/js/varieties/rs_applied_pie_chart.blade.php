<script>
	@if(isset($rs_varieties_applied_data_arr) && $rs_varieties_applied_data_arr != null && !empty($rs_varieties_applied_data_arr))
		var rs_varieties_applied_data_varieties = '<?php echo json_encode($rs_varieties_applied_data_arr['rs_varieties_applied_data_varieties']) ?>'
		var rs_varieties_applied_data_area = '<?php echo json_encode($rs_varieties_applied_data_arr['rs_varieties_applied_data_area']) ?>'
		rs_varieties_applied_data_varieties = JSON.parse(rs_varieties_applied_data_varieties)
		rs_varieties_applied_data_area = JSON.parse(rs_varieties_applied_data_area)

		var series = []
		rs_varieties_applied_data_area.forEach((item, index) => {
			series.push(parseFloat(item))
		})

		var options = {
			series: series,
			chart: {
				width: '100%',
				type: 'pie'
			},
			labels: rs_varieties_applied_data_varieties,
			dataLabels: {
				enabled: true
			},
			tooltip: {
			  y: {
			    formatter: function (val) {
			      return val.toLocaleString("en-US") + " ha"
			    }
			  },
			},
			legend: {
				show: true,
				formatter: function(seriesName, opts) {
					var percent = opts.w.globals.seriesPercent[opts.seriesIndex]
					percent = parseFloat(percent)
					return seriesName + " - " + percent.toFixed(2) + "%"
				},
			}
		};

		var chart = new ApexCharts(document.querySelector("#rs_varieties_applied"), options);
		chart.render();
	@endif
</script>
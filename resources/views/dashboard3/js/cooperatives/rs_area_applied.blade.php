<script>
	@if(isset($rs_area_applied_coop_data) && $rs_area_applied_coop_data != null && !empty($rs_area_applied_coop_data))
		var rs_area_applied_coop_data = <?php echo json_encode($rs_area_applied_coop_data) ?>

		var series = []
		var series_data = {}
		series_data.name = "Area Applied for Seed Certification"
		var data = []
		var categories = []
		rs_area_applied_coop_data.forEach((item, index) => {
			data.push(item.total.toLocaleString("en-US"))
			categories.push(item.cooperative)
		})
		series_data.data = data
		series.push(series_data)

		var options = {
			series: series,
			chart: {
				type: 'bar',
				height: 500
			},
			plotOptions: {
				bar: {
					horizontal: true,
					columnWidth: '75%',
					endingShape: 'rounded'
				},
			},
			dataLabels: {
				enabled: false,
			},
			stroke: {
				show: true,
				width: 2,
				colors: ['transparent']
			},
			xaxis: {
				categories: categories,
				title: {
					text: 'Hectares (ha)'
				}
			},
			fill: {
				opacity: 1
			},
			tooltip: {
				y: {
					formatter: function (val) {
						return val.toLocaleString("en-US") + " ha"
					}
				}
			},
			colors: ['#00a04c']
		};

		var chart = new ApexCharts(document.querySelector("#rs_area_applied_coops"), options);
		chart.render();
	@endif
</script>
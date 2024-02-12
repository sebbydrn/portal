<script type="text/javascript">
	var options = {
		series: [
			{
				name: 'Fresh',
				data: [10, 10, 10, 10]
			},
			{
				name: 'Dried',
				data: [20, 20, 20, 20]
			},
			{
				name: 'Cleaned',
				data: [30, 30, 30, 30]
			},
			{
				name: 'Tagged',
				data: [40, 40, 40, 40]
			}
		],
		chart: {
			type: 'bar',
			height: 500
		},
		plotOptions: {
			bar: {
				horizontal: true,
				dataLabels: {
					position: 'top'
				},
			}
		},
		dataLabels: {
			enabled: true,
			offsetX: -6,
			style: {
				fontSize: '12px',
				colors: ['#fff']
			}
		},
		stroke: {
			show: true,
			colors: ['#fff']
		},
		tooltip: {
			shared: true,
			intersect: false
		},
		xaxis: {
			categories: ["Purchased", "Planted", "Prelim Inspection", "Final Inspection"]
		}
	}

	var productionVolumeChart = new ApexCharts(document.querySelector("#productionVolumeChart"), options)
	productionVolumeChart.render()

</script>
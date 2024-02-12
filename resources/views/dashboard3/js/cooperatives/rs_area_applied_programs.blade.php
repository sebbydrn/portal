<script>
	@if(isset($rs_area_applied_coop_data) && $rs_area_applied_coop_data != null && !empty($rs_area_applied_coop_data))
		var rs_area_applied_coop_data = <?php echo json_encode($rs_area_applied_coop_data) ?>

		var series = []
		var rcefObj = {}
		rcefObj.name = 'RCEF'
		rcef_data = []
		var nrpObj = {}
		nrpObj.name = 'NRP'
		nrp_data = []
		var goldenRiceObj = {}
		goldenRiceObj.name = 'GOLDEN RICE'
		golden_rice_data = []
		var noneObj = {}
		noneObj.name = 'NONE'
		none_data = []
		var othersObj = {}
		othersObj.name = 'OTHERS'
		others_data = []

		rs_area_applied_coop_data.forEach((item, index) => {
			rcef_data.push(item.rcef.toLocaleString("en-US"))
			nrp_data.push(item.nrp.toLocaleString("en-US"))
			golden_rice_data.push(item.golden_rice.toLocaleString("en-US"))
			none_data.push(item.none.toLocaleString("en-US"))
			others_data.push(item.others.toLocaleString("en-US"))
		})

		rcefObj.data = rcef_data
		nrpObj.data = nrp_data
		goldenRiceObj.data = golden_rice_data
		noneObj.data = none_data
		othersObj.data = others_data

		series.push(rcefObj)
		series.push(nrpObj)
		series.push(goldenRiceObj)
		series.push(noneObj)
		series.push(othersObj)

		var options = {
			series: series,
			chart: {
				type: 'bar',
				height: 500,
				stacked: true,
				toolbar: {
					show: true
				},
				zoom: {
					enabled: true
				}
			},
			plotOptions: {
				bar: {
					horizontal: true
				},
			},
			dataLabels: {
				enabled: true,
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
			legend: {
				position: 'top'
			}
		};

		var chart = new ApexCharts(document.querySelector("#rs_area_applied_coop_programs"), options);
		chart.render();
	@endif
</script>
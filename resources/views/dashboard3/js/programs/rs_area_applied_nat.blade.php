<script>
	@if(isset($rs_area_per_program) && $rs_area_per_program != null && !empty($rs_area_per_program))
		var rcef = "{{$rs_area_per_program->rcef}}"
		var nrp = "{{$rs_area_per_program->nrp}}"
		var golden_rice = "{{$rs_area_per_program->golden_rice}}"
		var none = "{{$rs_area_per_program->none}}"
		var others = "{{$rs_area_per_program->others}}"

		var options = {
			series: [{
				name: 'Area Applied For Seed Certification (RS-CS)',
				data: [
					rcef.toLocaleString("en-US"),
					nrp.toLocaleString("en-US"),
					golden_rice.toLocaleString("en-US"),
					none.toLocaleString("en-US"),
					others.toLocaleString("en-US")
				]
			}],
			chart: {
				type: 'bar',
				height: 350
			},
			plotOptions: {
				bar: {
					horizontal: false,
					columnWidth: '75%',
					endingShape: 'rounded'
				}
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
				categories: ['RCEF', 'NRP', 'GOLDEN RICE', 'NONE', 'OTHERS']
			},
			yaxis: {
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

		var chart = new ApexCharts(document.querySelector("#rs_area_applied_per_program_column"), options);
		chart.render();

		var options_pie = {
			series: [parseFloat(rcef), parseFloat(nrp), parseFloat(golden_rice), parseFloat(none), parseFloat(others)],
			chart: {
				width: '100%',
				type: 'pie'
			},
			labels: ['RCEF', 'NRP', 'GOLDEN RICE', 'NONE', 'OTHERS'],
			dataLabels: {
				enabled: true
			},
			tooltip: {
				y: {
					formatter: function (val) {
						return val.toLocaleString("en-US") + " ha"
					}
				}
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

		var chart_pie = new ApexCharts(document.querySelector("#rs_area_applied_per_program_pie"), options_pie);
		chart_pie.render();
	@endif
</script>
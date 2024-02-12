<script>
	@if(isset($rs_area_applied_region) && $rs_area_applied_region != null && !empty($rs_area_applied_region))
		var area_passed_prelim_reg_1 = "{{$rs_area_insp_passed_data['prelim']->reg_1}}"
		var area_passed_prelim_reg_2 = "{{$rs_area_insp_passed_data['prelim']->reg_2}}"
		var area_passed_prelim_reg_3 = "{{$rs_area_insp_passed_data['prelim']->reg_3}}"
		var area_passed_prelim_reg_4a = "{{$rs_area_insp_passed_data['prelim']->reg_4a}}"
		var area_passed_prelim_mimaropa = "{{$rs_area_insp_passed_data['prelim']->mimaropa}}"
		var area_passed_prelim_reg_5 = "{{$rs_area_insp_passed_data['prelim']->reg_5}}"
		var area_passed_prelim_reg_6 = "{{$rs_area_insp_passed_data['prelim']->reg_6}}"
		var area_passed_prelim_reg_7 = "{{$rs_area_insp_passed_data['prelim']->reg_7}}"
		var area_passed_prelim_reg_8 = "{{$rs_area_insp_passed_data['prelim']->reg_8}}"
		var area_passed_prelim_reg_9 = "{{$rs_area_insp_passed_data['prelim']->reg_9}}"
		var area_passed_prelim_reg_10 = "{{$rs_area_insp_passed_data['prelim']->reg_10}}"
		var area_passed_prelim_reg_11 = "{{$rs_area_insp_passed_data['prelim']->reg_11}}"
		var area_passed_prelim_reg_12 = "{{$rs_area_insp_passed_data['prelim']->reg_12}}"
		var area_passed_prelim_reg_13 = "{{$rs_area_insp_passed_data['prelim']->reg_13}}"
		var area_passed_prelim_ncr = "{{$rs_area_insp_passed_data['prelim']->ncr}}"
		var area_passed_prelim_car = "{{$rs_area_insp_passed_data['prelim']->car}}"
		var area_passed_prelim_barmm = "{{$rs_area_insp_passed_data['prelim']->barmm}}"
		var area_passed_final_reg_1 = "{{$rs_area_insp_passed_data['final']->reg_1}}"
		var area_passed_final_reg_2 = "{{$rs_area_insp_passed_data['final']->reg_2}}"
		var area_passed_final_reg_3 = "{{$rs_area_insp_passed_data['final']->reg_3}}"
		var area_passed_final_reg_4a = "{{$rs_area_insp_passed_data['final']->reg_4a}}"
		var area_passed_final_mimaropa = "{{$rs_area_insp_passed_data['final']->mimaropa}}"
		var area_passed_final_reg_5 = "{{$rs_area_insp_passed_data['final']->reg_5}}"
		var area_passed_final_reg_6 = "{{$rs_area_insp_passed_data['final']->reg_6}}"
		var area_passed_final_reg_7 = "{{$rs_area_insp_passed_data['final']->reg_7}}"
		var area_passed_final_reg_8 = "{{$rs_area_insp_passed_data['final']->reg_8}}"
		var area_passed_final_reg_9 = "{{$rs_area_insp_passed_data['final']->reg_9}}"
		var area_passed_final_reg_10 = "{{$rs_area_insp_passed_data['final']->reg_10}}"
		var area_passed_final_reg_11 = "{{$rs_area_insp_passed_data['final']->reg_11}}"
		var area_passed_final_reg_12 = "{{$rs_area_insp_passed_data['final']->reg_12}}"
		var area_passed_final_reg_13 = "{{$rs_area_insp_passed_data['final']->reg_13}}"
		var area_passed_final_ncr = "{{$rs_area_insp_passed_data['final']->ncr}}"
		var area_passed_final_car = "{{$rs_area_insp_passed_data['final']->car}}"
		var area_passed_final_barmm = "{{$rs_area_insp_passed_data['final']->barmm}}"

		var options = {
			series: [{
				name: 'Area Passed Prelim Inspection',
				data: [
					{
						x: 'Region I',
						y: area_passed_prelim_reg_1.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_1.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region II',
						y: area_passed_prelim_reg_2.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_2.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region III',
						y: area_passed_prelim_reg_3.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_3.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region IV-A',
						y: area_passed_prelim_reg_4a.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_4a.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'MIMAROPA',
						y: area_passed_prelim_mimaropa.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: mimaropa.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region V',
						y: area_passed_prelim_reg_5.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_5.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region VI',
						y: area_passed_prelim_reg_6.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_6.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region VII',
						y: area_passed_prelim_reg_7.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_7.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region VIII',
						y: area_passed_prelim_reg_8.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_8.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region IX',
						y: area_passed_prelim_reg_9.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_9.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region X',
						y: area_passed_prelim_reg_10.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_10.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region XI',
						y: area_passed_prelim_reg_11.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_11.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region XII',
						y: area_passed_prelim_reg_12.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_12.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'Region XIII',
						y: area_passed_prelim_reg_13.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: reg_13.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'NCR',
						y: area_passed_prelim_ncr.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: ncr.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'CAR',
						y: area_passed_prelim_car.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: car.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
					{
						x: 'BARMM',
						y: area_passed_prelim_barmm.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Applied thru GrowApp', 
								value: barmm.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#005dbc'
							}
						]
					},
				]
			}, {
				name: 'Area Passed Final Inspection',
				data: [
					{
						x: 'Region I',
						y: area_passed_final_reg_1.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_1.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region II',
						y: area_passed_final_reg_2.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_2.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region III',
						y: area_passed_final_reg_3.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_3.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region IV-A',
						y: area_passed_final_reg_4a.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_4a.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'MIMAROPA',
						y: area_passed_final_mimaropa.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_mimaropa.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region V',
						y: area_passed_final_reg_5.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_5.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region VI',
						y: area_passed_final_reg_6.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_6.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region VII',
						y: area_passed_final_reg_7.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_7.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region VIII',
						y: area_passed_final_reg_8.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_8.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region IX',
						y: area_passed_final_reg_9.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_9.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region X',
						y: area_passed_final_reg_10.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_10.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region XI',
						y: area_passed_final_reg_11.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_11.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region XII',
						y: area_passed_final_reg_12.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_12.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'Region XIII',
						y: area_passed_final_reg_13.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_reg_13.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'NCR',
						y: area_passed_final_ncr.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_ncr.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'CAR',
						y: area_passed_final_car.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_car.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
					{
						x: 'BARMM',
						y: area_passed_final_barmm.toLocaleString("en-US"),
						goals: [
							{
								name: 'Area Passed Prelim Inspection', 
								value: area_passed_prelim_barmm.toLocaleString("en-US"), // from variable in rs_applied_column_chart
								strokeHeight: 3,
	                    		strokeColor: '#00a04c'
							}
						]
					},
				]
			}],
			chart: {
				type: 'bar',
				height: 350,
				toolbar: {
					show: true
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
					horizontal: false,
					columnWidth: '80%',
	        		endingShape: 'rounded'
				},
			},
			dataLabels: {
		      enabled: false,
		    },
		    stroke: {
		      show: true,
		      width: 4,
		      colors: ['transparent']
		    },
			colors: ['#00a04c', '#00cdd0'],
			legend: {
				show: true,
				position: 'bottom',
				showForSingleSeries: true,
				customLegendItems: ['Area Passed Prelim Inspection', 'Area Passed Final Inspection'],
				markers: {
					fillColors: ['#00a04c', '#00cdd0']
				}
			},
			fill: {
				opacity: 1
			},
			yaxis: {
		      title: {
		        text: 'Hectares (ha)'
		      }
		    },
		    tooltip: {
		      y: {
		        formatter: function (val) {
		          return val.toLocaleString("en-US") + " ha"
		        }
		      }
		    },
		};

		var chart = new ApexCharts(document.querySelector("#rs_area_passed_prelim_final_column_chart"), options);
		chart.render();
	@endif
</script>
<script>
	@if(isset($rs_area_applied_region) && $rs_area_applied_region != null && !empty($rs_area_applied_region))
		(async () => {
		const topology = await fetch("{{url("/").'/public/map/phl_admbnda_adm1_psa_namria_20200529_simplified.json'}}").then(response => response.json())

		const data = [
			{'region': 'Region I', 'code': 'Region I', 'value': parseFloat("{{$rs_area_applied_region->reg_1}}")},
			{'region': 'Region II', 'code': 'Region II', 'value': parseFloat("{{$rs_area_applied_region->reg_2}}")},
			{'region': 'Region III', 'code': 'Region III', 'value': parseFloat("{{$rs_area_applied_region->reg_3}}")},
			{'region': 'Region IV-A', 'code': 'Region IV-A', 'value': parseFloat("{{$rs_area_applied_region->reg_4a}}")},
			{'region': 'MIMAROPA', 'code': 'Region IV-B', 'value': parseFloat("{{$rs_area_applied_region->mimaropa}}")},
			{'region': 'Region V', 'code': 'Region V', 'value': parseFloat("{{$rs_area_applied_region->reg_5}}")},
			{'region': 'Region VI', 'code': 'Region VI', 'value': parseFloat("{{$rs_area_applied_region->reg_6}}")},
			{'region': 'Region VII', 'code': 'Region VII', 'value': parseFloat("{{$rs_area_applied_region->reg_7}}")},
			{'region': 'Region VIII', 'code': 'Region VIII', 'value': parseFloat("{{$rs_area_applied_region->reg_8}}")},
			{'region': 'Region IX', 'code': 'Region IX', 'value': parseFloat("{{$rs_area_applied_region->reg_9}}")},
			{'region': 'Region X', 'code': 'Region X', 'value': parseFloat("{{$rs_area_applied_region->reg_10}}")},
			{'region': 'Region XI', 'code': 'Region XI', 'value': parseFloat("{{$rs_area_applied_region->reg_11}}")},
			{'region': 'Region XII', 'code': 'Region XII', 'value': parseFloat("{{$rs_area_applied_region->reg_12}}")},
			{'region': 'Region XIII', 'code': 'Region XIII', 'value': parseFloat("{{$rs_area_applied_region->reg_13}}")},
			{'region': 'NCR', 'code': 'National Capital Region', 'value': parseFloat("{{$rs_area_applied_region->ncr}}")},
			{'region': 'CAR', 'code': 'Cordillera Administrative Region', 'value': parseFloat("{{$rs_area_applied_region->car}}")},
			{'region': 'BARMM', 'code': 'Autonomous Region in Muslim Mindanao', 'value': parseFloat("{{$rs_area_applied_region->barmm}}")}
		]

		// Create the chart
	    Highcharts.mapChart('rs_area_applied_per_region_map', {
	        chart: {
	            map: topology
	        },

	        title: {
	        	text: ''
	        },

	        mapNavigation: {
	            enabled: true,
	            buttonOptions: {
	                verticalAlign: 'top'
	            }
	        },

	        colorAxis: {
				min: 0,
			    stops: [
			    	[0, 'rgba(0, 140, 80, 0.1)'],
			    	[0.25, 'rgba(0, 140, 80, 0.5)'],
			      	[0.5, 'rgba(0, 140, 80, 0.75)'],
			      	[0.75, 'rgba(0, 140, 80, 0.85)'],
			      	[1, 'rgba(0, 140, 80, 1)']
			    ],
			    
			},

	        series: [{
	            data: data,
	            name: 'Area Applied for Seed Certification (ha)',
	            joinBy: ['ADM1_EN', 'code'],
	            states: {
	                hover: {
	                    color: '#00a04c'
	                }
	            },
	            dataLabels: {
	                enabled: true,
	                format: '{point.region}'
	            }
	        }],
	    });
	})();
	@endif
</script>
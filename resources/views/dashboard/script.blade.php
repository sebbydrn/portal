<script type="text/javascript">

	/*HoldOn js config*/
	var holdon_options = {
		theme: "sk-circle",
		message: "Loading... Please Wait",
		textColor: "white"
	}

	// Leaflet map marker custom
	var redIcon = new L.Icon({
		iconUrl: 'public/assets/markers/img/marker-icon-2x-red.png',
		shadowUrl: 'public/assets/markers/img/marker-shadow.png',
		iconSize: [25, 41],
		iconAnchor: [12, 41],
		popupAnchor: [1, -34],
		shadowSize: [41, 41]
	});
	
	$(document).ready(function() {
		/* ZingChart */
		// Seed production volume estimates
		$.ajax({
			type: "POST",
			url: "dashboard/production_volume",
			data: {
				_token: _token,
				region: "all",
				year: 0,
				sem: 0
			},
			dataType: 'json',
			success: (result) => {
				// Config
				var productionVolumeChartConfig = {
					type: "bar",
					title: {
						text: "Seed Production Volume Estimates",
						'adjust-layout': true
					},
					plotarea: {
						'adjust-layout': true
					},
					'scale-y': {
						label: {
							text: "TONS"
						}
					},
					'scale-x': {
						labels: [
							'Purchased',
							'Planted',
							'Preliminary Inspection',
							'Final Inspection'
						]
					},
					series: [
						{ 
							values: [
								parseFloat(result.purchased_seeds_fresh),
								parseFloat(result.planted_seeds_fresh),
								parseFloat(result.SPI_fresh),
								parseFloat(result.SPFI_fresh)], 
							text: 'Fresh',
							'legend-text': 'Fresh',
							'background-color': '#10817a'
						},
						{ 
							values: [
								parseFloat(result.purchased_seeds_dried),
								parseFloat(result.planted_seeds_dried),
								parseFloat(result.SPI_dried),
								parseFloat(result.SPFI_dried)],
							text: 'Dried',
							'legend-text': 'Dried',
							'background-color': '#3e9b5e'
						},
						{
							values: [
								parseFloat(result.purchased_seeds_cleaned),
								parseFloat(result.planted_seeds_cleaned),
								parseFloat(result.SPI_cleaned),
								parseFloat(result.SPFI_cleaned)],
							text: 'Cleaned',
							'legend-text': 'Cleaned',
							'background-color': '#96ab27'
						},
						{
							values: [
								parseFloat(result.purchased_seeds_tagged),
								parseFloat(result.planted_seeds_tagged),
								parseFloat(result.SPI_tagged),
								parseFloat(result.SPFI_tagged)],
							text: 'Tagged',
							'legend-text': 'Tagged',
							'background-color': '#ffa600'
						}
					],
					legend: {
						align: 'center',
						'vertical-align': 'top',
						'adjust-layout': true
					},
					plot: {
						'value-box': {

						}
					}
				}

				// Drill down
				zingchart.node_click = function(e) {
					if (e.id === "production_chart") {
						HoldOn.open(holdon_options)
						// use e.key and e.plotindex to get the column
						$.ajax({
							type: "POST",
							url: "dashboard/production_volume_dd",
							data: {
								_token: _token,
								key: e.key,
								plotindex: e.plotindex,
								year: 0,
								sem: 0
							},
							dataType: "json",
							success: (result) => {
								// Config
								var productionVolumeChartDDConfig = {
									type: "bar",
									title: {
										text: "Seed Production Volume Estimates",
										'adjust-layout': true
									},
									plotarea: {
										'adjust-layout': true
									},
									'scale-y': {
										'label': {
											text: "TONS"
										}
									},
									'scale-x': {
										labels: result.labels
									},
									series: [
										{
											values: result.values,
											'legend-text': result.legend,
											'background-color': result.bg
										}
									],
									legend: {
										'align': "center",
										'vertical-align': "top"
									},
									plot: {
										'value-box': {

										}
									},
									shapes: [{
										type: "triangle",
										backgroundColor: "#C0C0C0",
										size: 10,
										angle: -90,
										x: 20,
										y: 20,
										cursor: "hand",
										id: "backwards"
									}]
								}

								zingchart.exec('production_chart', 'destroy')

								$('#production_chart').hide()
								$('#production_chart_dd').css('display', 'block')

								zingchart.render({
									id: "production_chart_dd",
									data: productionVolumeChartDDConfig
								})

								HoldOn.close()
							}
						})
					}
				}

				zingchart.shape_click = function(e) {
					HoldOn.open(holdon_options)

					var shapeId = e.shapeid;

					switch (shapeId) {
						case 'forwards':
						case 'backwards':
						case 'default':
							zingchart.exec('production_chart_dd', 'destroy')

							$('#production_chart').show()
							$('#production_chart_dd').css('display', 'none')

							zingchart.render({
								id: "production_chart",
								data: productionVolumeChartConfig
							})

							HoldOn.close()
							
							break;
						default:
							break;
					}
				}

				zingchart.exec('production_chart', 'destroy')

				// Render chart
				zingchart.render({
					id: 'production_chart',
					data: productionVolumeChartConfig
				})
			}
		})

		/* Leaflet */
		// Geotagged seed production area of seed producers

		// Map tiles
		var googleHybrid = L.tileLayer('http://mt0.google.com/vt/lyrs=y&hl=en&x={x}&y={y}&z={z}&s=Ga',{
			maxZoom: 20,
			subdomains:['mt0','mt1','mt2','mt3']
		})

		var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
	    	maxZoom: 20,
	    	subdomains:['mt0','mt1','mt2','mt3']
		})

		var googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',{
	    	maxZoom: 20,
	    	subdomains:['mt0','mt1','mt2','mt3']
		})

		var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
	    	maxZoom: 20,
	    	subdomains:['mt0','mt1','mt2','mt3']
		})

		// Map creation
		var mymap = L.map('production_area', {
	  		layers: [googleHybrid]
		}).setView([12.4690082, 123.2575014], 6)

		layerGroup = L.layerGroup().addTo(mymap)

		mymap.scrollWheelZoom.disable()

		// Base layers definition and addition
		var baseLayers = {
		  	"Hybrid": googleHybrid,
		  	"Satellite": googleSat,
		  	"Terrain": googleTerrain,
		  	"Street": googleStreets
		}

		// Add control layers to map
		L.control.layers(baseLayers).addTo(mymap)

		var marker
		var popup

		$.ajax({
			url: 'dashboard/production_area',
			type: 'POST',
			data: {
				_token: _token
			},
			dataType: 'json',
			success: function(result) {
				$.each(result, function( key, value ) {
					marker = new L.marker([value.latitude, value.longitude], {icon: redIcon}).addTo(layerGroup);

					var content = '<div id="content">'
					content += '<table class="table table-bordered">'
					content += '<tr>'
					content += '<td>Serial No.</td>'
					content += '<td>'+value.serial_num+'</td>'
					content += '</tr>'
					content += '<tr>'
					content += '<td>Variety</td>'
					content += '<td>'+value.variety+'</td>'
					content += '</tr>'
					content += '<tr>'
					content += '<td>Seed Class</td>'
					content += '<td>'+value.seed_class+'</td>'
					content += '</tr>'
					content += '<tr>'
					content += '<td>Estimated Harvest</td>'
					content += '<td>'+(parseFloat(value.area_planted) * (parseFloat(value.ave_yield))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+' Tons</td>'
					content += '</tr>'
					content += '</table>'
					content += '</div>'

					marker.bindPopup(content);
				})
			}
		})

	})

	// Filter seed production volume estimates
	$('#btnFilterEstimates').on('click', () => {
		HoldOn.open(holdon_options)

		var year = $('#estimatesYear').val()
		var sem = $('#estimatesSem').val()

		// ZingChart
		// Seed production volume estimates with filters
		$.ajax({
			type: "POST",
			url: "dashboard/production_volume",
			data: {
				_token: _token,
				year: year,
				sem: sem
			},
			dataType: "json",
			success: (result) => {
				// Config
				var productionVolumeChartConfig = {
					type: "bar",
					title: {
						text: "Seed Production Volume Estimates",
						'adjust-layout': true
					},
					plotarea: {
						'adjust-layout': true
					},
					'scale-y': {
						'label': {
							text: "TONS"
						}
					},
					'scale-x': {
						labels: ["Purchased", "Planted", "Preliminary Inspection", "Final Inspection"]
					},
					series: [
						{ 
							values: [
								parseFloat(result.purchased_seeds_fresh),
								parseFloat(result.planted_seeds_fresh),
								parseFloat(result.SPI_fresh),
								parseFloat(result.SPFI_fresh)], 
							text: 'Fresh',
							'legend-text': 'Fresh',
							'background-color': '#10817a'
						},
						{ 
							values: [
								parseFloat(result.purchased_seeds_dried),
								parseFloat(result.planted_seeds_dried),
								parseFloat(result.SPI_dried),
								parseFloat(result.SPFI_dried)],
							text: 'Dried',
							'legend-text': 'Dried',
							'background-color': '#3e9b5e'
						},
						{
							values: [
								parseFloat(result.purchased_seeds_cleaned),
								parseFloat(result.planted_seeds_cleaned),
								parseFloat(result.SPI_cleaned),
								parseFloat(result.SPFI_cleaned)],
							text: 'Cleaned',
							'legend-text': 'Cleaned',
							'background-color': '#96ab27'
						},
						{
							values: [
								parseFloat(result.purchased_seeds_tagged),
								parseFloat(result.planted_seeds_tagged),
								parseFloat(result.SPI_tagged),
								parseFloat(result.SPFI_tagged)],
							text: 'Tagged',
							'legend-text': 'Tagged',
							'background-color': '#ffa600'
						}
					],
					legend: {
						'align': "center",
						'vertical-align': "top",
						'adjust-layout': true
					},
					plot: {
						'value-box': {

						}
					}
				}

				zingchart.exec('production_chart', 'destroy')

				// Render the chart
				zingchart.render({
					id: 'production_chart',
					data: productionVolumeChartConfig
				})

				HoldOn.close()
			}
		})
	})
		
</script>
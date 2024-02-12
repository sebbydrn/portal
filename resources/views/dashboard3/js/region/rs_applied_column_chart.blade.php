<script>
  @if(isset($rs_area_applied_region) && $rs_area_applied_region != null && !empty($rs_area_applied_region))
  	var reg_1 = "{{$rs_area_applied_region->reg_1}}"
  	var reg_2 = "{{$rs_area_applied_region->reg_2}}"
  	var reg_3 = "{{$rs_area_applied_region->reg_3}}"
  	var reg_4a = "{{$rs_area_applied_region->reg_4a}}"
  	var mimaropa = "{{$rs_area_applied_region->mimaropa}}"
  	var reg_5 = "{{$rs_area_applied_region->reg_5}}"
  	var reg_6 = "{{$rs_area_applied_region->reg_6}}"
  	var reg_7 = "{{$rs_area_applied_region->reg_7}}"
  	var reg_8 = "{{$rs_area_applied_region->reg_8}}"
  	var reg_9 = "{{$rs_area_applied_region->reg_9}}"
  	var reg_10 = "{{$rs_area_applied_region->reg_10}}"
  	var reg_11 = "{{$rs_area_applied_region->reg_11}}"
  	var reg_12 = "{{$rs_area_applied_region->reg_12}}"
  	var reg_13 = "{{$rs_area_applied_region->reg_13}}"
  	var ncr = "{{$rs_area_applied_region->ncr}}"
  	var car = "{{$rs_area_applied_region->car}}"
  	var barmm = "{{$rs_area_applied_region->barmm}}"

  	var options = {
        series: [{
        name: 'RS Area Applied in GrowApp',
        data: [
        	reg_1.toLocaleString("en-US"),
        	reg_2.toLocaleString("en-US"),
        	reg_3.toLocaleString("en-US"), 
        	reg_4a.toLocaleString("en-US"),
        	mimaropa.toLocaleString("en-US"),
        	reg_5.toLocaleString("en-US"),
        	reg_6.toLocaleString("en-US"),
        	reg_7.toLocaleString("en-US"),
        	reg_8.toLocaleString("en-US"),
        	reg_9.toLocaleString("en-US"),
        	reg_10.toLocaleString("en-US"),
        	reg_11.toLocaleString("en-US"),
        	reg_12.toLocaleString("en-US"),
        	reg_13.toLocaleString("en-US"),
        	ncr.toLocaleString("en-US"),
        	car.toLocaleString("en-US"),
        	barmm.toLocaleString("en-US")]
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
        categories: ['Region I', 'Region II', 'Region III', 'Region IV-A', 'MIMAROPA', 'Region V', 'Region VI', 'Region VII', 'Region VIII', 'Region IX', 'Region X', 'Region XI', 'Region XII', 'Region XIII', 'NCR', 'CAR', 'BARMM'],
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

      var chart = new ApexCharts(document.querySelector("#rs_area_applied_region"), options);
      chart.render();
  @endif
</script>
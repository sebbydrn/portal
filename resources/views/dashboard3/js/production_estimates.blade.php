<script>
	var purchased_fresh = "{{$production_estimates->purchased_fresh}}"
	var purchased_dried = "{{$production_estimates->purchased_dried}}"
	var purchased_cleaned = "{{$production_estimates->purchased_cleaned}}"
	var applied_fresh = "{{$production_estimates->applied_fresh}}"
	var applied_dried = "{{$production_estimates->applied_dried}}"
	var applied_cleaned = "{{$production_estimates->applied_cleaned}}"
	var prelim_fresh = "{{$production_estimates->prelim_fresh}}"
	var prelim_dried = "{{$production_estimates->prelim_dried}}"
	var prelim_cleaned = "{{$production_estimates->prelim_cleaned}}"
	var final_fresh = "{{$production_estimates->final_fresh}}"
	var final_dried = "{{$production_estimates->final_dried}}"
	var final_cleaned = "{{$production_estimates->final_cleaned}}"
	var sampled_cleaned = "{{$production_estimates->sampled_cleaned}}"
	var certified_cleaned = "{{$production_estimates->certified_cleaned}}"

	var options = {
      series: [{
      name: 'Fresh',
      data: [
      	purchased_fresh.toLocaleString("en-US"), 
      	applied_fresh.toLocaleString("en-US"), 
      	prelim_fresh.toLocaleString("en-US"), 
      	final_fresh.toLocaleString("en-US"), 
      	0, 
      	0]
    }, {
      name: 'Dried',
      data: [
      	purchased_dried.toLocaleString("en-US"), 
      	applied_dried.toLocaleString("en-US"), 
      	prelim_dried.toLocaleString("en-US"), 
      	final_dried.toLocaleString("en-US"), 
      	0, 
      	0]
    }, {
      name: 'Cleaned',
      data: [
      	purchased_cleaned.toLocaleString("en-US"), 
      	applied_cleaned.toLocaleString("en-US"), 
      	prelim_cleaned.toLocaleString("en-US"), 
      	final_cleaned.toLocaleString("en-US"), 
      	sampled_cleaned.toLocaleString("en-US"), 
      	certified_cleaned.toLocaleString("en-US")]
    }],
      chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '85%',
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
      categories: ['Purchased', 'Applied for Seed Certification', 'Prelim Inspection', 'Final Inspection', 'Sampled', 'Passed'],
    },
    yaxis: {
      title: {
        text: 'Kilograms (kg)'
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val.toLocaleString("en-US") + " kg"
        }
      }
    },
    colors: ['#00a04c', '#00cdd0', '#0194de']
    };

    var chart = new ApexCharts(document.querySelector("#seed_prod_estimates"), options);
    chart.render();
</script>
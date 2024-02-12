<script>
	var purchased_fresh = "{{$data['purchased_fresh']}}"
	var purchased_dried = "{{$data['purchased_dried']}}"
	var purchased_cleaned = "{{$data['purchased_cleaned']}}"
	var applied_fresh = "{{$data['applied_fresh']}}"
	var applied_dried = "{{$data['applied_dried']}}"
	var applied_cleaned = "{{$data['applied_cleaned']}}"
	var prelim_fresh = "{{$data['prelim_fresh']}}"
	var prelim_dried = "{{$data['prelim_dried']}}"
	var prelim_cleaned = "{{$data['prelim_cleaned']}}"
	var final_fresh = "{{$data['final_fresh']}}"
	var final_dried = "{{$data['final_dried']}}"
	var final_cleaned = "{{$data['final_cleaned']}}"
	var sampled_cleaned = "{{$data['sampled_cleaned']}}"
	var certified_cleaned = "{{$data['certified_cleaned']}}"

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
        columnWidth: '55%',
        endingShape: 'rounded'
      },
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
      categories: ['Purchased', 'Applied for Seed Certification', 'Prelim Inspection', 'Final Inspection', 'Sampled', 'Certified'],
    },
    yaxis: {
      title: {
        text: 'Kilograms'
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
    }
    };

    var chart = new ApexCharts(document.querySelector("#seed_prod_estimates"), options);
    chart.render();
</script>
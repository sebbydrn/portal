<script>
	function show_data() {
		var year = document.getElementById('year').value;
		var sem = document.getElementById('sem').value;

		console.log(year + ' ' + sem);

		window.location.href = "{{route('dashboard3.index')}}/filter/"+year+"/"+sem;
	}
</script>
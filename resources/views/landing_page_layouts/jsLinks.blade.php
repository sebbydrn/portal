<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('public/assets/bootstrap-4.4.1/js/bootstrap.min.js')}}"></script>
<!-- Datatables -->
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
{{-- HoldOn.js --}}
<script src="{{asset('public/assets/Holdon/HoldOn.min.js')}}"></script>

{{-- Datepicker --}}
<script src="{{asset('public/assets/gijgo-combined-1.9.13/js/gijgo.min.js')}}"></script>

<!-- Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- ZingChart -->
<script src="{{asset('public/assets/zingchart/zingchart.min.js')}}"></script>

<!-- Leaflet -->
<script src="{{asset('public/assets/leaflet/leaflet.js')}}"></script>
<script src="{{asset('public/assets/leaflet/leaflet.draw.js')}}"></script>
<!-- Load Esri Leaflet from CDN.  it has no .css stylesheet of its own, only .js -->
<script src="https://unpkg.com/esri-leaflet@2.2.3/dist/esri-leaflet.js" integrity="sha512-YZ6b5bXRVwipfqul5krehD9qlbJzc6KOGXYsDjU9HHXW2gK57xmWl2gU6nAegiErAqFXhygKIsWPKbjLPXVb2g==" crossorigin=""></script>

<!-- apex charts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- highmaps -->
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

<!-- lock.js -->
@auth
	<script src="{{asset('public/js/lock.js')}}"></script>
@endauth
<script type="text/javascript">
	let _token = "<?php echo csrf_token() ?>";
    let base_route = "<?php echo url('/') ?>";
    let user_logged_in = "<?php echo (Auth::user()) ? true : false ?>";

	$(document).ready(()=>{
		// Cookies
		if (localStorage.getItem('cookie_seen') != 'shown') {
			$('#cookies').fadeIn()
		}

		$('#close_cookies').click(function() {
		  	localStorage.setItem('cookie_seen','shown')
		  	$('#cookies').fadeOut()
		})

		$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
			if (!$(this).next().hasClass('show')) {
			$(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
			}
			var $subMenu = $(this).next('.dropdown-menu');
			$subMenu.toggleClass('show');


			$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
			$('.dropdown-submenu .show').removeClass('show');
			});


			return false;
		});
	})
</script>

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P5317MPZ7Y"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-P5317MPZ7Y');
</script>


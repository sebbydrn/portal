<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/dist/js/adminlte.min.js')}}"></script>
<!-- Sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- HoldOn -->
<script src="{{asset('public/assets/Holdon/HoldOn.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('public/assets/AdminLTE-3.0.0/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- main.js -->
<script src="{{asset('public/js/main.js')}}"></script>
<!-- lock.js -->
<script src="{{asset('public/js/lock.js')}}"></script>
<!-- CSRF Token -->
<script type="text/javascript">
    let _token = "<?php echo csrf_token() ?>";
    let base_route = "<?php echo url('/') ?>";
</script>

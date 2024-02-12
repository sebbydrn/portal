<script type="text/javascript">
    let image = document.getElementById('sample_image');
    let cropper;
	$.ajaxSetup({headers:{'X-CSRF-Token': $('input[name="_token"]').val()}});
        dates =$('.date_from, .date_to, .birthday').datepicker({
            autoclose:true,
            uiLibrary: 'bootstrap4'
        })
        $('.input_mask').inputmask()
    user_id = {{Auth::id()}}
    username = $("#username").val();
    firstname = $("#firstname").val();
    middlename = $("#middlename").val();
    lastname = $("#lastname").val();
    extname = $("#extname").val();
    sex = $("#sex").val();
    email = $("#email").val();
    secondaryemail = $("#secondaryemail").val();
	dates =$('.date_from, .date_to').datepicker({
            autoclose:true
        })

    let monitoring_table = $('#monitoring_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: {
            url:'seedsale/datatable',
            type: 'POST',
            data:function(d){
                d.status = $('#status').val();
                d.date_from = $('.date_from').val();
                d.date_to = $('.date_to').val();
                d.station_code = $('#station').val();
                d.region_id = $('#region').val();
                d.province_id = $('#province').val();
                d.municipality_id = $('#municipality').val();
            }
        },
        columns: [
            { data: 'order_id', name: 'order_id'},
            @permission(['view_national_data','view_regional_data','view_provincial_data','view_municipal_data'])
                {data: 'seedgrower', name: 'seedgrower'},
            @endpermission
            { data: 'variety', name: 'variety'},
            { data: 'status', name: 'status' },
            { data: 'device', name: 'device'},
            { data: 'browser', name: 'browser'},
            { data: 'ip_address', name: 'ip_address'},
            { data: 'timestamp', name: 'timestamp'},   
        ],
        oLanguage: {
            sProcessing: '<img src="public/images/loading.gif">'
        },
        order: [[0, 'asc']],
        
    })

    let logaccess_table = $('#logaccess_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url:'logaccess/datatable',
            type: 'POST',
            data:function(d){
                d.activity = $('#dropdownActivity').val();
                d.date_from = $('.date_from').val();
                d.date_to = $('.date_to').val();
                d.region_id = $('#region').val();
                d.province_id = $('#province').val();
                d.municipality_id = $('#municipality').val();
            }
        },
        columns: [
            { data: 'activity', name: 'activity'},
            @permission(['view_national_data','view_regional_data','view_provincial_data','view_municipal_data'])
                {data: 'user', name: 'user'},
            @endpermission
            { data: 'device', name: 'device' },
            { data: 'browser', name: 'browser' },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'timestamp', name: 'timestamp'},
        
        ],
        oLanguage: {
            sProcessing: '<img src="public/images/loading.gif">'
        },
        order: [[0, 'asc']],
        
    })

    let logaction_table = $('#logaction_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: {
            url:'logaction/datatable',
            type: 'POST',
            data:function(d){
                d.activity = $('#dropdownActivity').val();
                d.date_from = $('.date_from').val();
                d.date_to = $('.date_to').val();
                d.region_id = $('#region').val();
                d.province_id = $('#province').val();
                d.municipality_id = $('#municipality').val();
            }
        },
        columns: [
            { data: 'activity', name: 'activity'},
            @permission(['view_national_data','view_regional_data','view_provincial_data','view_municipal_data'])
                {data: 'user', name: 'user'},
            @endpermission
            { data: 'new_value', name:'new_value'},
            { data: 'device', name: 'device' },
            { data: 'browser', name: 'browser' },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'timestamp', name: 'timestamp'},
        
        ],
        oLanguage: {
            sProcessing: '<img src="public/images/loading.gif">'
        },
        order: [[0, 'asc']],
        
    })


    //Button to remove all the readonly 
	$('body').on('click','#editProfile',function(){
		$('.editable').prop({
			disabled: false,
			readonly: false,
		})
	})

    //button to save all changes


    $('body').on('click','.filter', function() {
        from = $('.date_from').val();
        to = $('.date_to').val();

        console.log($(this).data('id'));

        if(from && !to) {
            alert('Date To cannot be empty')
        }
        else if(from > to){
            alert('Date From cannot be higher than Date To')
        }

        if(from <= to)
        {
            if($(this).data('id') == "filter_logaccess"){
              logaccess_table.draw();  
            }
            
            if($(this).data('id') == "filter_seedsale"){
              monitoring_table.draw();  
            }

            if($(this).data('id') == "filter_logaction"){
                logaction_table.draw();
            }
        }
    })

    $('#reset').on('click',function(){
        $('.date_from, .date_to').val("");
    })

    function updateProfile(){
        $.ajax({
            url: '../admin/users/'+user_id,
            type: 'PATCH',
            data: {
                firstname : firstname
            },
            success:function(response){
                alert('s')
            }
        })  
    }
    // export to CSV 
    $('#exportSeedSale').on('click',function(){
        query = {
            status : $('#status').val(),
            date_from : $('.date_from').val(),
            date_to : $('.date_to').val(),
            station_code : $('#station').val(),
            region_id : $('#region').val(),
            province_id : $('#province').val(),
            municipality_id : $('#municipality').val(),
        }
        let url = "{{url('seedsaleReport')}}?" + $.param(query)
        window.location = url;
        /*$.ajax({
            type:'POST',
            url: 'seedsaleReport',
            data:function(d){
                d.status = $('#status').val();
                d.date_from = $('.date_from').val();
                d.date_to = $('.date_to').val();
                d.region_id = $('#region').val();
                d.province_id = $('#province').val();
                d.municipality_id = $('#municipality').val();
            },
            success:function(response){
                console.log('success');
            }
        })*/
    })

    $('#exportLogAccess').on('click',function(){
        query = {
            activity : $('#dropdownActivity').val(),
            date_from : $('.date_from').val(),
            date_to : $('.date_to').val(),
            region_id : $('#region').val(),
            province_id : $('#province').val(),
            municipality_id : $('#municipality').val(),
        }
        let url = "{{url('logaccessReport')}}?" + $.param(query)
        window.location = url;
    })

    $('#exportLogAction').on('click',function(){
        query = {
            activity : $('#dropdownActivity').val(),
            date_from : $('.date_from').val(),
            date_to : $('.date_to').val(),
            region_id : $('#region').val(),
            province_id : $('#province').val(),
            municipality_id : $('#municipality').val(),
        }
        let url = "{{url('logactionReport')}}?" + $.param(query)
        window.location = url;
    })

    // On change region dropdown in zingcharts filter
    $('#region').on('change', function() {
        let region_id = $(this).val()

        $('#province').empty()
        $('#province').append('<option selected disabled>Loading...</option>')

        $('#municipality').empty()
        $('#municipality').append('<option value="0" selected>Municipality</option>')
        $.ajax({
            type: 'POST',
            url: 'provinces',
            data: {_token: _token, region_id: region_id},
            dataType: 'json',
            success: function(result) {
                let options = '<option value="0" selected>Province</option>'
                $.each(result, function(key, value) {
                    options += '<option value="'+value.province_id+'">'+value.name+'</option>'
                })
                $('#province').empty()
                $('#province').append(options)
            }
        })
    })

    $('#province').on('change', function() {
        let province_id = $(this).val()

        $('#municipality').empty()
        $('#municipality').append('<option selected disabled>Loading...</option>')

        $.ajax({
            type: 'POST',
            url: 'municipalities',
            data: {_token: _token, province_id: province_id},
            dataType: 'json',
            success: function(result) {
                let options = '<option value="0" selected>Municipality</option>'
                $.each(result, function(key, value) {
                    options += '<option value="'+value.municipality_id+'">'+value.name+'</option>'
                })
                $('#municipality').empty()
                $('#municipality').append(options)
            }
        })
    })

    //FOR UPDATING THE USER DATA
    // Get region when selected province
    $('#provinceUpdate').on('change', ()=>{
        let region_id = $('#provinceUpdate option:selected').attr('region_id')
        let province_id = $('#provinceUpdate option:selected').attr('province_id')

        $('#municipalityUpdate').empty() // empty municipality
        $('#municipalityUpdate').append(`<option selected disabled>Loading...</option>`)

        // Get region code
        $.ajax({
            type: 'POST',
            url: "{{route('users.regions.region_code')}}",
            data: {
                _token: _token,
                region_id: region_id
            },
            dataType: 'json',
            success: (res)=>{
                $('#region').val(res)
            }
        })

        // Get municipalities
        $.ajax({
            type: 'POST',
            url: "{{route('users.municipalities')}}",
            data: {
                _token: _token,
                province_id: province_id
            },
            dataType: 'json',
            success: (res)=>{
                // console.log(res)
                $('#municipalityUpdate').empty() // empty municipality
                let options = `<option value="0" selected disabled>Select Municipality</option>`
                res.forEach((item)=> {
                    options += `<option value="`+item.mun_code+`">`+item.name+`</option>`
                })
                $('#municipalityUpdate').append(options)
            }
        })
    })

    /*$('body').on('click','.uploadBtn',function(){
        $('#uploadImage').trigger('change');
        emptyImageDiv();
    })*/


    $('body').on('click','#saveAvatar',function(){
        let image = $('.imageDiv img').attr('src');
        let profile_id = $('#profilepic').data('id');
        HoldOn.open({
             theme:"sk-cube-grid"
        });
        
        
    })
    $('#uploadImage').change(function(event){
        let files = event.target.files;

        let done = function(url){
            image.src = url;
            $('#crop').attr('disabled',false);
            $('#crop').html('Crop');
            $('#modal').modal('show');
        };

        if(files && files.length > 0)
        {
            reader = new FileReader();
            reader.onload = function(event)
            {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
        /*for (let i = 0; i < files.length; i++) {
            let file = files[i];
            //let imageType = /image.;
            
            if (!file.type.match(imageType)) {
                continue;
            }
            let img = document.createElement("img");
            img.height = "180"
            img.width ="150"
            img.classList.add("obj");
            img.file = file;
            $(fileInput).after(img);
            $('.imageDiv').append(img);
     
            let reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
            $('#saveAvatar').prop({
                disabled :false,

            }).css('cursor','pointer')
        }  */
    })
    $('#modal').on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview:'.preview'
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });

    $('#crop').click(function(){
        $('#crop').attr('disabled','disabled');
        $('#crop').html('<i class="fa fa-circle-o-notch fa-spin"></i> Wait...');
        canvas = cropper.getCroppedCanvas({
            width:400,
            height:400
        });

        canvas.toBlob(function(blob){
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            let profile_id = $('#profilepic').data('id');
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result;
                $.ajax({
                    url:'profile/addAvatar',
                    method:'POST',
                    data:{image:base64data,profile_id:profile_id},
                    success:function(data)
                    {
                        if(data == "success")
                        {
                            window.location.reload()
                        }
                    }
                });
            };
        });
    });
    function emptyImageDiv(){
        $('.imageDiv').empty();
    }
</script>
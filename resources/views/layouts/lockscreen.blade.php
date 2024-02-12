<!-- Automatic element centering -->
<div class="lockscreen-wrapper elevation-3" style="display: none; padding: 20px;">
	<div class="lockscreen-logo">
		<img src="{{asset('public/images/logo4.png')}}" alt="" style="height: 200px;">
	</div>
	<!-- Username -->
	<div class="lockscreen-name" style="text-align: center; margin-bottom: 40px;"><strong>{{Auth::user()->firstname . ' ' . Auth::user()->lastname}}</strong></div>

	<!-- START LOCK SCREEN ITEM -->
	<div class="lockscreen-item" style="border-style: solid; border-width: 1px; border-color: #c5c5c5;">
		<!-- lockscreen image -->
		<div class="lockscreen-image" style="border-style: solid; border-width: 1px; border-color: #c5c5c5;">
			<img src="{{asset('public/assets/AdminLTE-3.0.0/dist/img/user1-128x128.jpg')}}" alt="">
		</div>
		<!-- /.lockscreen-image -->

		<!-- lockscreen credentials (contains the form) -->
	    <form class="lockscreen-credentials" id="unlock">
	      <div class="input-group">
	        <input type="password" class="form-control" placeholder="Password" id="password">

	        <div class="input-group-append">
	          <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
	        </div>
	      </div>
	    </form>
	    <!-- /.lockscreen credentials -->
	</div>
	<!-- /.lockscreen-item -->
	<div class="help-block text-center">
    	Enter your password to retrieve your session
  	</div>
  	<div class="text-center">
    	<a href="{{route('logout')}}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">Or log in as a different user</a>
  	</div>
  	<div class="lockscreen-footer text-center">
    	Copyright &copy; 2020 <b><a href="{{url('../portal')}}" class="text-black">RSIS</a></b><br>
    	All rights reserved
  	</div>
</div>
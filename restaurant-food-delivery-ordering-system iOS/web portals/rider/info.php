<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "rider" ){ ?>

<h2 class="title">Info</h2>

<script>
$( function() {
	$( "#tabs" ).tabs();
} );
</script>

<div id="tabs">
  <ul class="tbs">
    <li><a href="#tabs-1">Dashboard</a></li>
    <li><a href="#tabs-2">Deliveries</a></li>
    <li><a href="#tabs-3">Schedule</a></li>
    <li><a href="#tabs-4">Earnings/Feedbacks</a></li>
  </ul>
  <div id="tabs-1">

	<h2 class="title textcenter">Dashboard</h2>
	<h3 class="ttl textcenter">Getting started with the app</h3>
	
  	<div class="tabsect">
  		<div class="col50 left">
  			<ul class="twcl">
  				<li>
  					<div class="left col20 textcenter">
  						<i class="fa fa-tachometer"></i>
  						<span>Dashboard</span>
  					</div>
  					<div class="right col80">Check in for shifts, view and manage upcoming shifts or check in while off-shift to let us know you can work.</div>
  					<div class="clear"></div>
  				</li>
  				<li>
  					<div class="left col20 textcenter">
  						<i class="fa fa-calendar-o"></i>
  						<span>Schedule</span>
  					</div>
  					<div class="right col80">View your shifts, pick up available shifts, and set your availability.</div>
  					<div class="clear"></div>
  				</li>
  				<li>
  					<div class="left col20 textcenter">
  						<i class="fa fa-money"></i>
  						<span>Earnings</span>
  					</div>
  					<div class="right col80">View your weekly earnings and feedback.</div>
  					<div class="clear"></div>
  				</li>
  				<li>
  					<div class="left col20 textcenter">
  						<i class="fa fa-user-circle-o"></i>
  						<span>Profile</span>
  					</div>
  					<div class="right col80">Provide app feedback, update your information, view app tutorials, and change settings.</div>
  					<div class="clear"></div>
  				</li>
  			</ul>
  		</div>
  		<div class="col50 right textcenter">
  			<img src="https://couriers.skipthedishes.com/static/img/help-app-instructions-v4/dashboard1.png" alt="" class="mobile" />
  		</div>
  		<div class="clear"></div>
  	</div>

  </div>
  <div id="tabs-2">
    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
  </div>
  <div id="tabs-3">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
  <div id="tabs-4">
    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  </div>
</div>

<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
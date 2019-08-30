<div id="flip">
 <p>menu</p>
</div>


<ul class="login_leftsidebar" id="dropdownmenu"> 

	<?php if( $_SESSION['user_type'] == "hotel" ) { //hotel ?>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_order" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=hotel_order&page=liveOrders">My Orders</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_edit_profile" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=hotel_edit_profile&page=accountSetting">Settings</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "couponcodes" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=couponcodes">Coupon Codes</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "deals" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=deals">Deals</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "bankinfo" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=bankinfo">Withdraw & Bank Information</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=changepassword">Change Password</a></li>

	<?php } //hotel = end

	else { } ?>

</ul>


<ul class="login_leftsidebar"> 

	<?php if( $_SESSION['user_type'] == "hotel" ) { //hotel ?>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_order" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=hotel_order&page=liveOrders">My Orders</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "earning" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=earning">Earning</a></li>
        
		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_edit_profile" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=hotel_edit_profile&page=profileSetting">Settings</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "couponcodes" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=couponcodes">Coupon Codes</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "bankinfo" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=bankinfo">Withdraw & Bank Information</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=changepassword">Change Password</a></li>

	<?php } //hotel = end

	else { } ?>

</ul>
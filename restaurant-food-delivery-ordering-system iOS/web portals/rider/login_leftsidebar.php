<ul class="login_leftsidebar"> 

	<?php if( $_SESSION['user_type'] == "rider" ) { //rider ?>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "summary" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=summary">Summary</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "account" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=account">My Account</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "bankinfo" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=bankinfo">Withdraw & Bank Information</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=changepassword">Change Password</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "doc" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=doc">Documents</a></li>

		<li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "info" ) {
			echo 'class="active"';
		} } ?> ><a href="dashboard.php?p=info">Info</a></li>

	<?php } //rider = end

	else { } ?>

</ul>
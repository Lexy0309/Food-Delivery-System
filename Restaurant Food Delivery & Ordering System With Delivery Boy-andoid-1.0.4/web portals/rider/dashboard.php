<?php require_once("header.php"); ?>

<?php if(!isset($_SESSION['id'])){ 
    @header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
} ?>

<div class="section mini dashboardscreen"><div class="wdth">
	<div class="col25 left">
		<?php require_once("login_leftsidebar.php"); ?> 
	</div>
	<div class="col75 right contentside">
		<?php if( !isset($_GET['p']) ) { //dashboard
			?>
				<h2 class="title">Dashboard</h2>
				<p>Main dashboard here.. </p>
			<?php
		} //dashboard = end
		else { //inner pages

			if( $_SESSION['user_type'] == "rider" ) { //rider 

				if( $_GET['p'] == "summary" ) { //order
					include("rider_order.php");
				} //order = end

				if( $_GET['p'] == "account" ) { //account
					include("account.php");
				} //account = end

				if( $_GET['p'] == "bankinfo" ) { //bank info
					include("rider_bankinfo.php");
				} //bank info  = end

				if( $_GET['p'] == "changepassword" ) { //changepassword
					include("changepassword.php");
				} //changepassword = end

				if( $_GET['p'] == "doc" ) { //doc
					include("documents.php");
				} //doc = end

				if( $_GET['p'] == "info" ) { //info
					include("info.php");
				} //info = end

			} //rider = end

			else { }

		} //inner pages = end ?>
	</div>
	<div class="clear"></div>
</div></div>

<?php require_once("footer.php"); ?>
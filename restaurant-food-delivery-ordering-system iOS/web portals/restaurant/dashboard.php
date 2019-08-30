<?php require_once("header.php"); ?>

<?php if(!isset($_SESSION['id'])){ 
    @header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
} ?>


<div class="section mini dashboardscreen" style="padding-bottom:0px;"><div class="wdth">
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

			if( $_SESSION['user_type'] == "hotel" ) { //hotel

				if( $_GET['p'] == "hotel_order" ) { //order
					include("hotel_order.php");
				} //order = end

				if( $_GET['p'] == "manage_menu" ) { //manage menu
					include("manage_menu.php");
				} //manage menu = end
				
				if( $_GET['p'] == "earning" ) { //manage menu
					include("earning.php");
				} //manage menu = end

				if( $_GET['p'] == "hotel_edit_profile" ) { //add menu
					include("hotel_edit_profile.php");
				} //add menu = end

				if( $_GET['p'] == "couponcodes" ) { //coupon code
					include("couponcodes.php");
				} //coupon code = end

				if( $_GET['p'] == "bankinfo" ) { //bank info
					include("hotel_bankinfo.php");
				} //bank info = end

				if( $_GET['p'] == "changepassword" ) { //changepassword
					include("changepassword.php");
				} //changepassword = end

				if( $_GET['p'] == "deals" ) { //deals
					include("hotel_deals.php");
				} //deals = end

			} //hotel = end 

			else { }

		} //inner pages = end ?>
	</div>
	<div class="clear"></div>
</div></div>

<?php require_once("footer.php"); ?>
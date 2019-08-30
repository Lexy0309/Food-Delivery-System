<?php require_once("./config.php");
$pgname = explode(".php", basename($_SERVER['PHP_SELF']));
if($pgname[0] == "index") {
    $pagename="home";
} else {
    $pagename= $pgname[0];
}
//echo $pagename;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Foodies Food Delivery & Take Out</title>
	<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>" />
	<link rel="stylesheet" type="text/css" href="rs-plugin/css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="fontawesome/css/font-awesome.css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	<link rel="shortcut icon" href="img/fav.png">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>
	<script src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script src="js/jquery.inputmask.bundle.js"></script>
	<script src="js/inputmask.numeric.extensions.js"></script>
	<script src="js/phone.js"></script>
	<script src="js/jquery.timepicker.js"></script>
	<script src="js/pagination.js"></script>
	<script src="js/custom.js?<?php echo time(); ?>"></script>
	<script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="cbc0c321-bdce-4a9a-9baa-f3ddc945d35e";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>
	<script src="https://maps.google.com/maps/api/js?key=AIzaSyAEDq8M6WsXVmo_08lPapjlqYCFVRBt6ro&libraries=places"></script>
	<script src="js/locationpicker.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="slick/slick.css">
	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css">
	<script src="slick/slick.js" type="text/javascript" charset="utf-8"></script>
	<script>
	jQuery(document).ready(function(){

		jQuery(".quote ul").slick({
		    dots: true,
			arrows: false,
		    infinite: true,
		    centerMode: false,
		    autoplay: true,
			autoplaySpeed: 5000,
		    slidesToShow: 1,
		    slidesToScroll: 1
		});

	});

	//toggle menu
	function openNav() {
		jQuery('#opensidemenu').toggleClass('change');
		jQuery('#mySidenav').toggleClass('toggle');
	}
	</script>
    <script> 
$(document).ready(function(){
    $("#flip").click(function(){
        $("#dropdownmenu").slideToggle("slow");
    });
});
</script>
	</head>
	<body class="<?php echo $pagename; ?>">
<script>
   $(window).load(function() {
     $('#status').fadeOut();
     $('#preloader').delay(350).fadeOut('slow');
     $('body').delay(350).css({'overflow':'visible'});
   })
  </script>
	<div id="preloader" align="center">
      <div id="loading"> <img src="img/loader.gif" alt="Loading.." height="140" /> </div>
    </div>
<?php if( isset($_SESSION['id']) ){ ?>

<!-- toggle menu -->
<div id="mySidenav" class="sidenav">
      <ul>
    <?php if( isset($_SESSION['id']) ){ ?>
    <li><a><span class="myacc">My Account</span> <span class="nameontop"><?php echo $_SESSION['name']; ?></span></a>
          <ul class="sub-menu">
        <?php if( $_SESSION['user_type'] == "hotel" ) { //hotel ?>
        <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) {
							echo 'class="active"';
						} } ?> ><a href="dashboard.php?p=hotel_order&page=liveOrders">My Orders <span class="blok">View order & their details</span></a></li>
        <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "manage_menu" ) {
							echo 'class="active"';
						} } ?> ><a href="dashboard.php?p=manage_menu">Manage Menu <span class="blok">Add & view hotel menus</span></a></li>
        <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_edit_profile" ) {
							echo 'class="active"';
						} } ?> ><a href="dashboard.php?p=hotel_edit_profile">Edit Profile <span class="blok">Edit hotel information</span></a></li>
        <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "couponcodes" ) {
							echo 'class="active"';
						} } ?> ><a href="dashboard.php?p=couponcodes">Coupon Codes <span class="blok">Add or View coupon codes</span></a></li>
        <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
							echo 'class="active"';
						} } ?> ><a href="dashboard.php?p=changepassword">Change Password <span class="blok">Change your account password</span></a></li>
        <?php } //hotel = end 

					else { } ?>
        <li class="logout"><a href="index.php?log=out">Log Out</a></li>
      </ul>
        </li>
    <?php } else { ?>
    <li><a href="javascript:;" onClick="popup('login')">Login / Sign Up</a></li>
    <?php } ?>
  </ul>
    </div>
<!-- toggle menu -->
<?php } ?>
<header class="siteheader">
      <div class="wdth">
    <div class="logo left"> <a href="index.php"><img src="img/3.png?time=<?php echo time(); ?>" alt="" /></a> </div>
    <div class="right navbar">
          <?php if( isset($_SESSION['id']) ){ ?>
          <span class="menu-icon opensidemenu" id="opensidemenu" onClick="openNav()"> <span class="bar1"></span> <span class="bar2"></span> <span class="bar3"></span> </span>
          <?php } else { /* ?>
		<ul class="nav-menu mnav">
			<li><a href="javascript:;" onClick="popup('login')"><i class="fa fa-user-circle"></i></a></li>
		</ul>
		<?php */ } ?>
          <ul class="nav-menu dnav">
        <?php if( isset($_SESSION['id']) ){ ?>
        <li class="lastmenu"><a><span class="myacc">Account</span> <span class="nameontop"><?php echo $_SESSION['name']; ?> <i class="fa fa-caret-down"></i></span></a>
              <ul class="submenu">
            <?php if( $_SESSION['user_type'] == "hotel" ) { //hotel ?>
            <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "order" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=hotel_order&page=liveOrders">My Orders <span class="blok">View order & their details</span></a></li>
            <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "manage_menu" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=manage_menu">Manage Menu <span class="blok">Add & view hotel menus</span></a></li>
            <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "hotel_edit_profile" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=hotel_edit_profile">Edit Profile <span class="blok">Edit hotel information</span></a></li>
            <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "couponcodes" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=couponcodes">Coupon Codes <span class="blok">Add or View coupon codes</span></a></li>
            <li <?php if(isset($_GET['p'])) { if( $_GET['p'] == "changepassword" ) {
								echo 'class="active"';
							} } ?> ><a href="dashboard.php?p=changepassword">Change Password <span class="blok">Change your account password</span></a></li>
            <?php } //hotel = end 

						else { } ?>
            <li class="logout"><a href="index.php?log=out">Log Out</a></li>
          </ul>
            </li>
        <?php } else { ?>
        <?php } ?>
      </ul>
        </div>
    <div class="clear"></div>
  </div>
    </header>

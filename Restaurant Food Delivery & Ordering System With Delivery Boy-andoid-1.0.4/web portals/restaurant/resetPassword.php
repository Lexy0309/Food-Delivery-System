<?php
	require_once("./config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Foodomia Food Delivery & Take Out | Reset Password</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?1519304873" />
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
	<script src="js/custom.js?1519304873"></script>
	
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

</head>
<body class="resetPassword">

<script>
   $(window).load(function() {
     $('#status').fadeOut();
     $('#preloader').delay(350).fadeOut('slow');
     $('body').delay(350).css({'overflow':'visible'});
   })
  </script>

<div id="preloader" align="center">
	<div id="loading">
		<img src="img/loader.gif" alt="Loading.." height="140" />
	</div>
</div>

<div class="section mini dashboardscreen1">

	<div class="wdth section mini" style="text-align:center;">
		<div class="col30 marginauto section mini ">
			<br>
			<div class="logo">
				<p><img src="img/2.png" alt="" /></p>
			</div>
			<div class="form">
				<div class="col100">
					<div class="form">
						<form action="resetPassword.php?resetPassword=ok" id="resetpass" method="post" novalidate="novalidate">
							<p><input placeholder="New Password" name="pass" id="pass" type="password"></p>
							<p><input placeholder="Re-type New Password" name="Newpass" id="Newpass" type="password"></p>
							<input value="<?php echo @$_GET['email']?>" name="email" id="email" type="text" style=" display:none;">
							<input value="<?php echo @$_GET['token']?>" name="token" id="token" type="text" style=" display:none;" >
							<p><input value="Update Password" type="submit" style="background:#BE2C2C; color:white; border:none; cursor:pointer;"></p>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
	</div>
</div>



<?php //reset password
if(isset($_GET['resetPassword']) && !empty($_POST['Newpass']) && !empty($_POST['pass']) && !empty($_POST['email']) && !empty($_POST['token']) ) {
		
		if($_POST['Newpass']==$_POST['pass'])
		{
			
			$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
			$token = htmlspecialchars($_POST['token'], ENT_QUOTES);
			$Newpass = htmlspecialchars($_POST['Newpass'], ENT_QUOTES);
		
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);
	
			$data = array(
				"email" => $email,
				"token" => $token,
				"password" => $Newpass
			);
			//echo json_encode($data);
			$ch = curl_init( $baseurl.'/resetPassword' );
	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
			$return = curl_exec($ch);
	
			$json_data = json_decode($return, true);
			var_dump($json_data);
	
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
			//echo $json_data['code'];
			//die;
	
			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
				@header("Location: resetPassword.php?action=error");
					echo "<script>window.location='resetPassword.php?action=error'</script>";
	
			} else {
				//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
				@header("Location: resetPassword.php?p=action=success");
					echo "<script>window.location='resetPassword.php?action=success'</script>";
			}
	
			curl_close($ch);
		}
		else
		{
			@header("Location: resetPassword.php?action=error");
					echo "<script>window.location='resetPassword.php?action=error'</script>";
		}
		
		
}
//remove resetpass = end ?>


<?php 
if( isset($_GET['action']) ) {
	if( $_GET['action'] == "error" ) {
		echo "<div class='notification'><div class='wdth'><div class='alert alert-error'>Some error, try again.. </div></div></div>";
		?>
		<script>setTimeout(function() {
		    $('#mydiv').fadeOut('fast');
		}, 1000); // <-- time in milliseconds</script>
		<?php
	}
	if( $_GET['action'] == "success" ) {
		echo "<div class='notification'><div class='wdth'><div class='alert alert-success'>Saved successfully.. </div></div></div>";
		?>
		<script>
			setTimeout(function() {
		    	$('.notification').fadeOut();
			}, 5000); 
		</script>
		<?php
	}
} //
?>



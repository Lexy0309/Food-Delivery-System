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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TICOTOGO</title>

    <!-- CSS -->
    <link rel="shortcut icon" href="images/ticotogo.ico"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <link rel="stylesheet" href="fonts/stylesheet.css">
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.css">
	<!-- <link rel="stylesheet" href="css/dashboard.css"> -->
	<link rel="stylesheet" href="css/core.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="stylesheet" href="css/animate.css">	
    
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

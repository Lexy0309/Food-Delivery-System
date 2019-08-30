<?php
error_reporting(0);
 require_once("config.php");
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once("meta.php"); ?>

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<!--<link rel="stylesheet" href="assets/css/neon-theme.css">-->
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" href="assets/css/custom-datatables.css">
	<script src="assets/js/jquery-1.11.0.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
	
   <script>
   $(window).load(function() {
	  
     $('#preloader').delay(350).fadeOut('slow');
     $('body').delay(350).css({'overflow':'visible'});
   })
  </script>
	    <script src="jquery.min.js"></script>
   
    <!-- This is data table -->
    <script src="jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
	
	
    <!-- end - This is for export functionality only -->
	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
</head>
<body class="page-body" data-url="http://neon.dev">

<?php
if($_GET['status']=="ok")
{
    ?>
        <div class='alert alert-success' align="center" style="background: green;border:  none;color:  white;">Action Successful</div>
    <?php
}
else
if($_GET['status']=="error")
{
    ?>
        <div class='alert alert-danger' align="center">Something went wrong</div>
    <?php
}
?>    



<div id="preloader" align="center">
	<div id="loading">
		<img src="http://bringthings.com/foodies/admin/assets/images/loader.gif" alt="Loading.." height="140">
	</div>
</div>


<style>
#preloader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255,255,255,0.5);
    z-index: 9999999;
	height: 100%;
    width: 100%;
}
#loading {
    width: 380px;
    height: 200px;
    position: absolute;
    left: 43%;
    top: 50%;
    margin: -104px 0 0 -100px;
}
</style>


		
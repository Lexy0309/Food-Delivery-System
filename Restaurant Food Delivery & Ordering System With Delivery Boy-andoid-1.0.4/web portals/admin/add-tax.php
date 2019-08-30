<?php require_once("config.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once("meta.php"); ?>
	
	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.0.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	<h2 class="toast-title">Add Tax</h2>
   <div class="panel-body">
		<?php 
            if(isset($_GET['insert'])){
                if($_GET['insert']=="ok") {

					$city = $_POST['city'];
					$state = $_POST['state'];
					$country = $_POST['country'];
					$tax = $_POST['tax'];
					$delivery_fee_per_mile = $_POST['deliveryfee'];
					$country_code = $_POST['countrycode'];

					   $headers = array(
					    "Accept: application/json",
					    "Content-Type: application/json"
					   );
					   $data = array(
					   	"city" => $city, 
					   	"state" => $state, 
					   	"country" => $country, 
					   	"tax" => $tax,
					   	"delivery_fee_per_mile" => $delivery_fee_per_mile,
					   	"country_code" => $country_code
						);

					   $ch = curl_init( $baseurl.'/addTax' );

					   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					   $return = curl_exec($ch);

					   $curl_error = curl_error($ch);
					   $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

					   curl_close($ch);

					   // do some checking to make sure it sent
					   //var_dump($http_code);
					   //die;

					   if($http_code !== 200){
					   	 echo "<div class='alert alert-danger'>".$curl_error."</div>";
					   
					   }else{
					   	 echo "<div class='alert alert-success'>Successfully submitted..</div>";
					   	echo "<script>window.location='tax.php';</script>";
					   }

						
                }
            }	
        ?>
        
        <form role="form" method="post" action="add-tax.php?insert=ok" class="form-horizontal form-groups-bordered">

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">City</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="city" placeholder="City">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">State</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="state" placeholder="State">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">Country</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="country" placeholder="Country">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">Tax</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="tax" placeholder="Tax">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">Delivery Fee (per mile)</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="deliveryfee" placeholder="Delivery fee (per mile)">
	            </div>
	        </div>

	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">Country Code</label>
	            
	            <div class="col-sm-6">
	                <input type="text" class="form-control" name="countrycode" placeholder="Country code">
	            </div>
	        </div>

	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-6">
	                <input type="submit" class="btn btn-primary" value="Add Tax">
	            </div>
	        </div>
        
        </form>
    </div>
    

<!-- Footer -->
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>




	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
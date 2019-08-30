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
	<h2 class="toast-title">Add App Slider</h2>
   <div class="panel-body">
		<?php 
            if(isset($_GET['insert'])){
                if($_GET['insert']=="ok") {

					$user_id = $_SESSION['id'];

					$image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
    				$image = base64_encode($image_base);

					   $headers = array(
					    "Accept: application/json",
					    "Content-Type: application/json"
					   );
					   $data = array(
					   	"user_id" => $user_id, 
					   	"image" => array("file_data" => $image)
						);

					   $ch = curl_init( $baseurl.'/addAppSliderImage' );

					   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					   $return = curl_exec($ch);

					   $curl_error = curl_error($ch);
					   $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                       
                       //var_dump($http_code);
                       
					   curl_close($ch);

					   if($http_code !== 200){
					   	 echo "<div class='alert alert-danger'>".$curl_error."</div>";
					   
					   }else{
					   	 echo "<div class='alert alert-success'>Successfully submitted..</div>";
					   	 echo "<script>window.location='appslider.php';</script>";
					   }

						
                }
            }	
        ?>
        
        <form action="add-appslider.php?insert=ok" id="docfrm" method="post" enctype="multipart/form-data">
	        <div class="form-group">
	            <label for="field-1" class="col-sm-2 control-label">Image</label>
	            
	            <div class="col-sm-6">
	                
	                <div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
							<img src="http://placehold.it/200x150" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
						<div>
							<span class="btn btn-white btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="upload_image" accept="image/*">
							</span>
							<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>

	            </div>
	        </div>

	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-6">
	                <input type="submit" class="btn btn-primary" value="Add App Slider">
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
	<script src="assets/js/fileinput.js"></script>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
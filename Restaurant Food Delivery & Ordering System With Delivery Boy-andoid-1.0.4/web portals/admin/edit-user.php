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
	<h2 class="toast-title">Edit User</h2>
   <div class="panel-body">
        <?php 
        if(isset($_GET['id'])) {
        	$uid = $_GET['id'];
        } else {
        	$uid = "";
        }

	    if(isset($_GET['upd'])) { //update user
	    	if($_GET['upd']=="ok") {

	    		$user_id = $uid;
	    		$first_name = htmlspecialchars($_POST['fname'], ENT_QUOTES);
	    		$last_name = htmlspecialchars($_POST['lname'], ENT_QUOTES);
	    		$email = htmlspecialchars($_POST['eml'], ENT_QUOTES);

	    		$headers = array(
				    "Accept: application/json",
				    "Content-Type: application/json"
				);

				$data = array(
				    "user_id" => $user_id,
				    "first_name" => $first_name,
				    "last_name" => $last_name,
				    "email" => $email
				);

				$ch = curl_init( $baseurl.'/editUserProfile' );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$return = curl_exec($ch);

				$json_data = json_decode($return, true);

				$curl_error = curl_error($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if($json_data['code'] !== 200){
				    echo "<div class='alert alert-danger'>Error in updating information, try again later..</div>";
				} else {
				    echo "<div class='alert alert-success'>Successfully information updated..</div>";
				    echo "<script>window.location='users.php';</script>";
				}

				curl_close($ch);

	    	}
	    } //update user = end
        ?>

        <?php 
		$user_id = $uid;

		$headers = array(
		    "Accept: application/json",
		    "Content-Type: application/json"
		);

		$data = array(
		    "user_id" => $user_id
		);

		$ch = curl_init( $baseurl.'/showUserDetail' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($json_data['code'] !== 200){
		    //echo "<div class='alert alert-danger'>Error in updating account information, try again later..</div>";
		} else {
		    //echo "<div class='alert alert-success'>Successfully account information updated..</div>";
		    	//var_dump( $json_data['msg'] );
		    	?>
		    	<form role="form" method="post" action="edit-user.php?id=<?php echo $uid; ?>&upd=ok" class="form-horizontal form-groups-bordered">
			        <div class="form-group">
			            <label for="field-1" class="col-sm-2 control-label">First Name</label>
			            <div class="col-sm-6">
			                <input type="text" class="form-control" name="fname" placeholder="First Name" value="<?php echo $json_data['msg']['UserInfo']['first_name']; ?>">
			            </div>
			        </div>

			        <div class="form-group">
			            <label for="field-1" class="col-sm-2 control-label">Last Name</label>
			            <div class="col-sm-6">
			                <input type="text" class="form-control" name="lname" placeholder="Last Name" value="<?php echo $json_data['msg']['UserInfo']['last_name']; ?>">
			            </div>
			        </div>

			        <div class="form-group">
			            <label for="field-1" class="col-sm-2 control-label">Email Address</label>
			            <div class="col-sm-6">
			                <input type="email" class="form-control" name="eml" placeholder="Email Address" value="<?php echo $json_data['msg']['User']['email']; ?>" readonly>
			            </div>
			        </div>

			        <div class="form-group">
			            <div class="col-sm-offset-2 col-sm-6">
			                <input type="submit" class="btn btn-primary" value="Update User">
			            </div>
			        </div>
		        </form>
		    	<?php
		}

		curl_close($ch);
		?> 
    </div>
    

<!-- lets do some work here... --><!-- Footer -->

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
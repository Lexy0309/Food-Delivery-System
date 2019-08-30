<?php 
	require_once("config.php");
	if(!isset($_SESSION['id'])) {
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
<body class="page-body login-page login-form-fall" data-url="http://neon.dev">


<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
var baseurl = '';
</script>

<div class="login-container" style="padding-top:150px;">
	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<a href="login.php" class="logo">
				<img src="assets/images/2.png?time=<?php echo time(); ?>" width="100" alt="logo" />
			</a>
			
			<p class="description" style="color:black !important;">Dear user, log in to access the admin area!</p>
			
			<!-- progress bar indicator 
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>-->
			</div>
		</div>
		
	</div>
	
	<!--<div class="login-progressbar">
		<div></div>
	</div>-->
	
	<div class="login-form_dead" style="padding-top: 0px;">
		
		<div class="login-content">
			
			<div class="form-login-error">
				<h3>Invalid login</h3>
				<p>Enter correct username and password.</p>
			</div>
			<?php //echo $_SESSION['user_id'] ; ?>
			<form method="post" role="form" id="form_login">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login" style="">
						<i class="entypo-login"></i>
						Log In
					</button>
				</div>
                
			</form>
			
			
			
			
		</div>
		
	</div>
	
</div>


	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-login.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
<?php 
} 
else 
{
	if(isset($_GET['log'])) 
	{
		if($_GET['log']=="out") 
		{
			@session_destroy();
            @header("Location: index.php");
		}
	}
	@header("Location: index.php");
} 

?>



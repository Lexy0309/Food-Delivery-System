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

<div class="profile-env">
	
	<header class="row">
		
		<div class="col-sm-2">
			
			<a href="#" class="profile-picture">
				<img src="assets/images/profile-picture.jpg" class="img-responsive img-circle" />
			</a>
			
		</div>
		
		<div class="col-sm-7">

			<ul class="profile-info-sections">
				<li>
					<div class="profile-name">
						<strong>
							<?php echo $_SESSION['name']; ?>
                        </strong>
						<span><?php echo $_SESSION['email']; ?></span>
					</div>
				</li>
				
			</ul>
			
		</div>

	</header>
	
	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="col-sm-offset-2 col-sm-10">
				
				<ul class="user-details">
					<li>
						<a href="#">
							<i class="entypo-user"></i>
							<?php echo $_SESSION['user_type']; ?>
						</a>
					</li>
				</ul>
				
				
				<!-- tabs for the profile links -->
				<ul class="nav nav-tabs">
					<li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
					<li><a href="#profile-edit" data-toggle="tab">Edit</a></li>
                    <li><a href="#change-password" data-toggle="tab">Change Password</a></li>
				</ul>
				
			</div>
			
		</div>
		
	</section>
	<section class="profile-feed">
    	<div class="tab-content">
        
			<div class="tab-pane active" id="profile">
            	
                <h1 class="toast-title margin-bottom">User Profile</h1>
                <div class="row">
                	<div class="col-sm-2 label-default padding-lg text-right">Full Name</div>
                    <div class="col-sm-10 padding-lg "><?php echo $_SESSION['name']; ?></div>
                </div>
                <div class="row border-top">
                	<div class="col-sm-2 label-default padding-lg text-right">Username</div>
                    <div class="col-sm-10 padding-lg "><?php echo $_SESSION['email']; ?></div>
                </div>
                <div class="row border-top">
                	<div class="col-sm-2 label-default padding-lg text-right">Email Address</div>
                    <div class="col-sm-10 padding-lg "><?php echo $_SESSION['email']; ?></div>
                </div>
                <div class="row border-top">
                	<div class="col-sm-2 label-default padding-lg text-right">User Type</div>
                    <div class="col-sm-10 padding-lg "><?php echo $_SESSION['user_type']; ?></div>
                </div>
			
            </div>
			<div class="tab-pane" id="profile-edit">
            
                <h1 class="toast-title margin-bottom">Edit Profile</h1>
                <div class="panel-body">
                	
                    <form role="form" method="post" action="#" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">Full Name</label>
						
						<div class="col-sm-6">
							<input type="text" class="form-control" name="name" placeholder="Full Name" value="<?php echo $_SESSION['name']; ?>">
						</div>
					</div>
                    
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">Username</label>
						
						<div class="col-sm-6">
							<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $_SESSION['email']; ?>">
						</div>
					</div>
                    
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">Email Address</label>
						
						<div class="col-sm-6">
							<input type="email" class="form-control" name="email" placeholder="Email Address" value="<?php echo $_SESSION['email']; ?>">
						</div>
					</div>
                    
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">User Type</label>
						
						<div class="col-sm-6">
							<input type="text" class="form-control" placeholder="User Type" value="<?php echo $_SESSION['user_type']; ?>" disabled>
						</div>
					</div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<input type="submit" class="btn btn-primary" value="Update Profile">
						</div>
					</div>
                    
                    </form>
                </div>
                
            </div>
            
            
            <div class="tab-pane" id="change-password">
            
                <h1 class="toast-title margin-bottom">Change Password</h1>
                <div class="panel-body">

                    <form role="form" method="post" action="#" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">New Password</label>
						
						<div class="col-sm-6">
							<input type="password" class="form-control" name="password" placeholder="New Password">
						</div>
					</div>
                    
                    <div class="form-group">
						<label for="field-1" class="col-sm-2 control-label">Re-type Password</label>
						
						<div class="col-sm-6">
							<input type="password" class="form-control" name="retype_pasword" placeholder="Re-type Password">
						</div>
					</div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-2 col-sm-6">
							<input type="submit" class="btn btn-primary" value="Update Password">
						</div>
					</div>
                    
                    </form>
                    
                </div>
                
            </div>
            
		</div>
    </section>
	
	
</div>

<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize()
{
	var $ = jQuery,
		map_canvas = $("#sample-checkin");
	
	var location = new google.maps.LatLng(36.738888, -119.783013),
		map = new google.maps.Map(map_canvas[0], {
		center: location,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false
	});
	
	var marker = new google.maps.Marker({
		position: location,
		map: map
	});
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
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
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>

<?php } else {
	@header('Location: login.php');
} ?>
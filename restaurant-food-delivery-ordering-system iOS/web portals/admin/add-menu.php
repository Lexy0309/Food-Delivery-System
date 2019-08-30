<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>
<body class="page-body" data-url="http://neon.dev">
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	<h2 class="toast-title">Add Menu</h2>
   <div class="panel-body">
		<?php 
            if( isset($_GET['id']) && $_GET['id'] != "" ) {
                $id = $_GET['id'];
            } else {
                $id = "";
            }

            if( isset($_GET['usrid']) && $_GET['usrid'] != "" ) {
                $usrid = $_GET['usrid'];
            } else {
                $usrid = "";
            }

            if(isset($_GET['insert'])){
                if($_GET['insert']=="ok") {

					$user_id = $_POST['user_id'];
					$restaurant_id = $_POST['restaurant_id'];
					$name = $_POST['name'];
					$description = $_POST['description'];
					$image = $_POST['image'];

					   $headers = array(
					    "Accept: application/json",
					    "Content-Type: application/json"
					   );
					   $data = array(
					   	"user_id" => $user_id, 
					   	"restaurant_id" => $restaurant_id, 
					   	"name" => $name, 
					   	"description" => $description, 
					   	"image" => $image
						);

					   $ch = curl_init( $baseurl.'/addMenu' );

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
					   	echo "<script>window.location='restaurants.php';</script>";
					   }

						
                }
            }	
        ?>
        
        <form role="form" method="post" action="add-menu.php?<?php if( isset($_GET['id']) ) { echo "id=".$_GET['id']."&"; } ?><?php if( isset($_GET['usrid']) ) { echo "usrid=".$_GET['usrid']."&"; } ?>insert=ok" class="form-horizontal form-groups-bordered">
        <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">User ID</label>
            
            <div class="col-sm-6">
                <input type="text" class="form-control" name="user_id" placeholder="User ID" value="<?php echo $usrid; ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Restaurant ID</label>
            
            <div class="col-sm-6">
                <input type="text" class="form-control" name="restaurant_id" placeholder="Restaurant ID" value="<?php echo $id; ?>" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Name</label>
            
            <div class="col-sm-6">
                <input type="text" class="form-control" name="name" placeholder="Name">
            </div>
        </div>
        
        <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Description</label>
            
            <div class="col-sm-6">
                <textarea class="form-control" name="description" rows="6" placeholder="Description"></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Image</label>
            
            <div class="col-sm-6">
                <input type="text" class="form-control" name="image" placeholder="image">
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-6">
                <input type="submit" class="btn btn-primary" value="Add Menu">
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
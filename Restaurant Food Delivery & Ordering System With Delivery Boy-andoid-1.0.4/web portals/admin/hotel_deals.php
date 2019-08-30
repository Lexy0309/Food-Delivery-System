	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/css/style.css?1522155548" />
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/rs-plugin/css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/rs-plugin/css/settings.css" media="screen" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/css/jquery.timepicker.css" />

	<script src="https://restaurants.foodomia.pk/js/jquery-1.12.4.js"></script>
	<script src="https://restaurants.foodomia.pk/js/jquery-ui.js"></script>
	<script src="https://restaurants.foodomia.pk/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
	<script src="https://restaurants.foodomia.pk/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
	<script src="https://restaurants.foodomia.pk/js/jquery.validate.min.js"></script>
	<script src="https://restaurants.foodomia.pk/js/jquery.inputmask.bundle.js"></script>
	<script src="https://restaurants.foodomia.pk/js/inputmask.numeric.extensions.js"></script>
	<script src="https://restaurants.foodomia.pk/js/phone.js"></script>
	<script src="https://restaurants.foodomia.pk/js/jquery.timepicker.js"></script>
	<script src="https://restaurants.foodomia.pk/js/pagination.js"></script>
	<script src="https://restaurants.foodomia.pk/js/custom.js?1526046769"></script>
	<script src="https://maps.google.com/maps/api/js?key=AIzaSyAEDq8M6WsXVmo_08lPapjlqYCFVRBt6ro&libraries=places"></script>
	<script src="https://restaurants.foodomia.pk/js/locationpicker.jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/slick/slick-theme.css">
	<script src="https://restaurants.foodomia.pk/slick/slick.js" type="text/javascript" charset="utf-8"></script>
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



	
	<style>
		body
		{
			margin: 0 auto;
			width: 80%;
		}
		input[type="submit"]
		{
			background: #be2c2c;
    		color: white;
    		border: none;
		}
		input[type="button"]
		{
			background: #F2F2F2;
    		color: black;
    		border: none;
		}
	</style>

<?php 

require_once("config.php"); 
$baseurl = "http://api.foodomia.pk/publicSite";

$resturentid=$_GET['resid'];
$userid=$_GET['userid'];


if( isset($_SESSION['id']) ){ ?>

<div class="left">
	<h2 class="title">Hotel Deals</h2>
</div>
<div class="right">
	<a href="javascript:;" onClick="jQuery('#adddeals').toggle();" class="filtericon"><i class="fa fa-plus"></i> <span>Add Deals</span></a>
</div>
<div class="clear"></div>

<script>
function Upload_image() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("image");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 90 && width == 90) {
                        
						//alert('Done image');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 90x90");
						document.getElementById("image").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}


function Upload_cover_image() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("image1");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 90 && width == 90) {
                        
						//alert('Done image');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 90x90");
						document.getElementById("image1").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}
</script>

<?php 
if(isset($_GET['add'])) {
	if($_GET['add']=="inf") {

		$user_id = $userid;
		$name = htmlspecialchars( $_POST['nm'], ENT_QUOTES );
		$description = htmlspecialchars( $_POST['dsc'], ENT_QUOTES );
		$starting_datetime = htmlspecialchars( $_POST['starting_datetime'], ENT_QUOTES );
		$ending_datetime = htmlspecialchars( $_POST['ending_datetime'], ENT_QUOTES );
		$price = htmlspecialchars( $_POST['prce'], ENT_QUOTES );

		$image_original = file_get_contents($_FILES['image']['tmp_name']);
		$image = base64_encode($image_original);

		$image1_original = file_get_contents($_FILES['image1']['tmp_name']);
		$cover_image = base64_encode($image1_original);

		if( !empty($name) && !empty($description) && !empty($starting_datetime) && !empty($ending_datetime) && !empty($price) ) {

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"user_id" => $user_id,
				"name" => $name,
				"description" => $description,
				"starting_datetime" => $starting_datetime,
				"ending_datetime" => $ending_datetime,
				"price" => $price,
				"image" => array("file_data" => $image),
				"cover_image" => array("file_data" => $cover_image)
			);
			//echo json_encode($data);
			$ch = curl_init( $baseurl.'/addDeal' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200)
			{
				echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=error'</script>";

			} else 
			{
				echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=success'</script>";
			}

			curl_close($ch);

		} else 
		{
			echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=success'</script>";
		} //

	} //menu = end
}
?>

<div id="adddeals" class="popup">
	<div class="popup_container col40">
		
		<a href="javascript:;" onClick="jQuery('#adddeals').hide();" id="close">&times;</a>
		<div class="paddingallsides form">
			<h2 class="title">Add Deals</h2>
			<form action="hotel_deals.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=deals&add=inf" id="hoteldealsfrm" method="post" enctype="multipart/form-data"> 
				<p><input type="text" name="nm" id="nm" required=""><label alt="Name" placeholder="Name"></label></p>
				<p><textarea class="textarea" name="dsc" id="dsc" placeholder="description"></textarea></p>
				<p><input type="text" name="starting_datetime" id="datepicker" required="" >
                <label alt="Starting Time" placeholder="Starting Time"></label>
                </p>
				<p><input type="text" name="ending_datetime" id="datepicker1" required="" >
                 <label alt="Ending Time" placeholder="Ending Time"></label>
                </p>
				<p><input type="text" name="prce" id="prce" required="">
                 <label alt="Pricee" placeholder="Price"></label>
                </p>

				<p>Image <br><br> <input type="file" name="image" id="image" onchange="return Upload_image()" /></p>
				<p>Cover Image <br><br> <input type="file" name="image1" id="image1" onchange="return Upload_cover_image()" /></p>
				<p><input type="submit" value="Add Deal" name=""></p>
			</form>
		</div>

	</div>
</div>

<?php //hotel deals delete
if( isset($_GET['del']) && !empty($_GET['id']) ) {
	if($_GET['del']=="ok"){

		$user_id = $_GET['userid'];
		$idd = $_GET['id'];

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
		);

		$data = array(
			"user_id" => $user_id,
			"id" => $idd
		);

		$ch = curl_init( $baseurl.'/deleteDeal' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    //var_dump($json_data);
        
        //echo $json_data['key'];
        
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $json_data['code'];
		//die;

		if($json_data['code'] !== 200)
		{
			echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=error'</script>";

		} else 
		{
			echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=success'</script>";
		}

		curl_close($ch);

	}
} //hotel deals delete = end
?>


<div class="form">
	<?php 
		$user_id = $userid;

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json",
		);

		$data = array(
			"user_id" => $user_id
		);

		$ch = curl_init( $baseurl.'/showRestaurantDeals' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		$json_data = json_decode($return, true);
	    //var_dump($json_data);
        
        //echo $json_data['key'];
        
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $json_data['code'];
		//die;

		if($json_data['code'] !== 200){
			echo "<div class='alert alert-danger'>Error in fetching payment method, try again later..</div>";
			//@header("Location: dashboard.php?p=payment");

		} else {
			//echo "<div class='alert alert-success'>Successfully payment method updated..</div>";
			//@header("Location: dashboard.php?p=payment");
			
			echo '<div class="paymentlist couponlist">';
			foreach( $json_data['msg'] as $stttr => $vaaal ) {
				//var_dump($vaaal);
				?>
				<div class="left col30" style="margin-right: 10px;">
					<?php /*<img src="<?php echo $image_baseurl.$vaaal['Deal']['cover_image']; ?>">	*/ ?>
					<div class="itm"> 
						<div class="cardnm col100"><img src="<?php echo $image_baseurl.$vaaal['Deal']['cover_image']; ?>" width="100%" height="150px"></div>
						<br>
						<div class="cardnm left col60"><strong><?php echo ucwords($vaaal['Deal']['name']); ?></strong></div>
						<div class="cardtype right col40 textright"><strong><?php echo $vaaal['Restaurant']['Currency']['symbol']; echo $vaaal['Deal']['price']; ?></strong></div>
						<div class="clear" style="margin: 0; height: 5px;"></div>
						<div class="descptn">
							<?php echo $vaaal['Deal']['description']; ?>
						</div>
						<div class="clear" style="margin: 0; height: 5px;"></div>
						<div class="expirydate" style="font-size: 11px; ">
							Starting Time: <?php echo $vaaal['Deal']['starting_time'].'&nbsp;&nbsp;&nbsp;';?>
								<br>
								Ending Time:
								<?php echo $vaaal['Deal']['ending_time'];?>
						</div>
						<div class="clear" style="margin: 0;"></div>
						
						<div align="right" style="margin-top:10px;">
							<a style="cursor: pointer;" data-id="<?php echo $vaaal['Deal']['id']; ?>" data-name="<?php echo $vaaal['Deal']['name']; ?>" data-price="<?php echo $vaaal['Deal']['price']; ?>" data-description="<?php echo $vaaal['Deal']['description']; ?>" data-expiry="<?php $exppr = explode(" ", $vaaal['Deal']['starting_time']); echo $exppr[0]; ?>" data-image="<?php echo $image_baseurl.$vaaal['Deal']['image']; ?>" data-cover="<?php echo $image_baseurl.$vaaal['Deal']['cover_image']; ?>" class="edit_icn" id="editit">Edit </a> 
							| 
							<a href="hotel_deals.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=deals&del=ok&id=<?php echo $vaaal['Deal']['id']; ?>" onclick="return confirm('Do you really want to delete hotel deal?');" >
								Delete
							</a>
						</div>
							
						
					</div>
				</div>
				<?php
			} //
			echo '</div>';
		}

		curl_close($ch);
	?>
</div>


<?php ///edit ?>

<script>
function Upload_imagee() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("imagee");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 90 && width == 90) {
                        
						//alert('Done image');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 90x90");
						document.getElementById("imagee").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}


function Upload_cover_imagee() {
    //Get reference of FileUpload.
    var fileUpload = document.getElementById("imagee1");
 	
	//Check whether the file is valid Image.
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
 
        //Check whether HTML5 is supported.
        if (typeof (fileUpload.files) != "undefined") {
            //Initiate the FileReader object.
            var reader = new FileReader();
            //Read the contents of Image File.
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function (e) {
                //Initiate the JavaScript Image object.
                var image = new Image();
 
                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;
                       
                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;
					//alert(height);
					//alert(width);
                    if (height == 90 && width == 90) {
                        
						//alert('Done image');
						return true; 
						
                    }
					else
					{
                    	//alert("Uploaded image has valid Height and Width.");
						alert("Height & Width Should be 90x90");
						document.getElementById("imagee1").value = "";
                    	return false;
					}
                };
 
            }
        } else {
            alert("This browser does not support HTML5.");
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        return false;
    }
}


jQuery(document).ready(function(){
	jQuery(".edit_icn").on("click", function(){
		
		var idd = jQuery(this).attr("data-id");
		var nme = jQuery(this).attr("data-name");
		var dsc = jQuery(this).attr("data-description");
		var prc = jQuery(this).attr("data-price");
		var exp = jQuery(this).attr("data-expiry");

		var p = jQuery(this).attr("data-image");
		if(p != "") {
			var pimg = "<img style='height:200px;' src='"+jQuery(this).attr("data-image")+"' alt='' /><br>";
		}
		var c = jQuery(this).attr("data-cover");
		if(c != "") {
			var cimg = "<img style='height:200px;' src='"+jQuery(this).attr("data-cover")+"' alt='' /><br>";
		}

		jQuery('#edde').html('<form action="hotel_deals.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=deals&edit=inf" id="hoteldealsfrm" method="post" enctype="multipart/form-data"> <input type="hidden" value="'+idd+'" name="idd"> <p><input type="text" name="nm" id="nm" value="'+nme+'" required=""><label alt="Name" placeholder="Name"></label></p> <p><textarea class="textarea" name="dsc" id="dsc" placeholder="description">'+dsc+'</textarea></p> <p><input type="text" name="starting_datetime" id="datepicker" value="'+exp+'" required="e"><label alt="Starting Dat" placeholder="Starting Dat"></label></p> <p><input type="text" name="ending_datetime" id="datepicker1" value="'+exp+'" required=""><label alt="Ending Date" placeholder="Ending Date"></label></p> <p><input type="text" name="prce" id="prce" required="" value="'+prc+'"><label alt="Price" placeholder="Price"></label></p> <p>Image <br><br>'+pimg+'<br><input type="file" name="image" id="imagee" onchange="return Upload_imagee()" /></p> <p>Cover Image <br><br>'+cimg+'<br> <input type="file" name="image1" id="imagee1" onchange="return Upload_cover_imagee()" /></p> <p><input type="submit" value="Update Deal" name=""></p> </form>');

		jQuery('#editdeals_popup').show();

	});
});
</script>

<?php 
if(isset($_GET['edit'])) {
	if($_GET['edit']=="inf") {

		$id = $_POST['idd'];
		$user_id = $userid;
		$name = htmlspecialchars( $_POST['nm'], ENT_QUOTES );
		$description = htmlspecialchars( $_POST['dsc'], ENT_QUOTES );
		$starting_datetime = htmlspecialchars( $_POST['starting_datetime'], ENT_QUOTES );
		$ending_datetime = htmlspecialchars( $_POST['ending_datetime'], ENT_QUOTES );
		$price = htmlspecialchars( $_POST['prce'], ENT_QUOTES );

		/*$image_original = file_get_contents($_FILES['image']['tmp_name']);
		$image = base64_encode($image_original);

		$image1_original = file_get_contents($_FILES['image1']['tmp_name']);
		$cover_image = base64_encode($image1_original);*/

		if( !empty($name) && !empty($description) && !empty($starting_datetime) && !empty($ending_datetime) && !empty($price) ) {

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			//with image & cover image
		    if( isset($_FILES['image']) && $_FILES["image"]["name"] != "" && isset($_FILES['image1']) && $_FILES["image1"]["name"] != "" ) {
		    	$image_original = file_get_contents($_FILES['image']['tmp_name']);
				$image = base64_encode($image_original);

				$image1_original = file_get_contents($_FILES['image1']['tmp_name']);
				$cover_image = base64_encode($image1_original);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description,
					"starting_datetime" => $starting_datetime,
					"ending_datetime" => $ending_datetime,
					"price" => $price,
					"image" => array("file_data" => $image),
					"cover_image" => array("file_data" => $cover_image)
				);
				//
		    }

		    //with image
		    else if( isset($_FILES['image']) && $_FILES["image"]["name"] != "" ) {
		    	$image_original = file_get_contents($_FILES['image']['tmp_name']);
				$image = base64_encode($image_original);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description,
					"starting_datetime" => $starting_datetime,
					"ending_datetime" => $ending_datetime,
					"price" => $price,
					"image" => array("file_data" => $image)
				);
				//
		    }

		    //with cover image
		    else if( isset($_FILES['image1']) && $_FILES["image1"]["name"] != "" ) {
		    	$image1_original = file_get_contents($_FILES['image1']['tmp_name']);
				$cover_image = base64_encode($image1_original);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description,
					"starting_datetime" => $starting_datetime,
					"ending_datetime" => $ending_datetime,
					"price" => $price,
					"cover_image" => array("file_data" => $cover_image)
				);
				//
		    }

		    //without image & cover image
		    else {
		    	$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description,
					"starting_datetime" => $starting_datetime,
					"ending_datetime" => $ending_datetime,
					"price" => $price
				);
				
				//echo json_encode($data);
				//
		    }
			
			

			/*$data = array(
				"id" => $id,
				"user_id" => $user_id,
				"name" => $name,
				"description" => $description,
				"expire_date" => $expire_date,
				"price" => $price,
				"image" => $image,
				"cover_image" => $cover_image
			);*/

			$ch = curl_init( $baseurl.'/addDeal' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);
		    //var_dump($json_data);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;

			if($json_data['code'] !== 200)
			{
				echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=error'</script>";

			} else 
			{
				echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=success'</script>";
			}

			curl_close($ch);

		} 
		else 
		{
			echo "<script>window.location='hotel_deals.php?userid=".$_GET['userid']."&resid=".$_GET['resid']."&action=success'</script>";
		} //

	} //menu = end
}
?>

<div id="editdeals_popup" class="popup">
	<div class="popup_container col40">
		
		<a href="javascript:;" onClick="jQuery('#editdeals_popup').hide(function(){ jQuery('#edde').html(''); });" id="close">&times;</a>
		<div class="paddingallsides form">
			<h2 class="title">Edit Deals</h2>
			<div id="edde"></div>
		</div>

	</div>
</div>





<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
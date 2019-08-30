
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/css/style.css?1522155548" />
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/rs-plugin/css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/rs-plugin/css/settings.css" media="screen" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://restaurants.foodomia.pk/css/jquery.timepicker.css" />


	<style>
		body
		{
			margin: 0 auto;
			width: 60%;
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

	<script>
    
	    function updateindex($menuID)
	    {
	    	document.getElementById($menuID+"_buttonBox").innerHTML='<input type="button" value="...." style="width: 50px; padding: 5px; margin: 2px;">';
	    	var indexValue=document.getElementById($menuID+'_index').value;
	        //alert($menuID);
	        //alert(indexValue);
	        var xmlhttp;
	        if(window.XMLHttpRequest)
	          {// code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttp=new XMLHttpRequest();
	          }
	        else
	          {// code for IE6, IE5
	            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	          }
	          
	          xmlhttp.onreadystatechange=function()
	          {
	            if(xmlhttp.readyState==4 && xmlhttp.status==200)
	            {
	               // alert(xmlhttp.responseText);
	                document.getElementById($menuID+"_buttonBox").innerHTML=xmlhttp.responseText;
	            }
	          }
	        xmlhttp.open("GET","ajex-events.php?update=index&menuID="+$menuID+"&index="+indexValue);
	        xmlhttp.send();
	        //alert(str1);
	    }
	    
	</script>
<?php 


if(@$_GET['action']=="success")
{
	?>
		<div style="color: white;background:green;padding: 20px;text-align: center;position:  fixed;width:  100%; left: 0px;z-index:  99;">
			Access Successful
		</div>
	<?php
}
else
if(@$_GET['action']=="error")
{
	?>
		<div style="color: white;background:#be2c2c;padding: 20px;text-align: center;position:  fixed;width:  100%; left: 0px;z-index:  99;">
			Something Wrong
		</div>
	<?php
}

$resturentid=$_GET['resid'];
$userid=$_GET['userid'];

if( isset($resturentid)==$_GET['resid']){ ?>

<?php 
require_once("config.php"); 
//$baseurl = "http://api.foodomia.pk/superAdmin";
?>


<h2 class="title">Manage Menu</h2>
<?php 
//print_r($_SESSION);


if(isset($_SESSION['id'])) 
{

	if(isset($_GET['p']) && isset($_GET['add'])) {
		if($_GET['p']=="manage_menu" && $_GET['add']=="menu") {
			$user_id = $userid;
			//$user_id = "5";
			//$restaurant_id = $resturentid;
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$image = "";

			if( !empty($name) && !empty($description) ) { 
			

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description
					//"image" => $image
				);

				$ch = curl_init( $baseurl.'/addMenu' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
	                 
				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);
			} 
			else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&add=menu&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu = end


		if($_GET['add']=="menuitem") {
	        $user_id = $userid;
	        //$user_id = htmlspecialchars($resturentid, ENT_QUOTES);
			$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			

			if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"restaurant_menu_id" => $restaurant_menu_id,
					"name" => $name,
					"description" => $description,
					"price" => $price,
					"out_of_order" => "0"
				);

				$ch = curl_init( $baseurl.'/addMenuItem' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					//?userid=51&resid=3&action=success
					echo "<script>window.location='manage_menu.php?resid=".$resturentid."&userid=".$userid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu item = end


		if($_GET['add'] == "menuextrasection") {
			$user_id = $userid;

			//$restaurant_id = htmlspecialchars($_POST['restoid'], ENT_QUOTES);
		    $user_id = $userid;
			$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
			$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
			
			if(isset($_POST['require_items'])) {
				$required = "1";
			} else {
				$required = "0";
			}

			if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"user_id" => $user_id,
					"name" => $name,
					"restaurant_menu_item_id" => $restaurant_menu_item_id,
					"required" => $required
				);

				$ch = curl_init( $baseurl.'/addMenuExtraSection' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra section = end


		if($_GET['add'] == "menuextraitem") {
			//$user_id = $userid;

			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

			if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"name" => $name,
					"price" => $price,
					"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
				);

				$ch = curl_init( $baseurl.'/addMenuExtraItem' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} 
			else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra item = end
	}

/// add section  =end

	if(isset($_GET['edit'])) {
		if($_GET['edit']=="menu") {
			//$user_id = $userid;
			
			$id = $_POST['rid'];
			$user_id = $userid;
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);

			if( !empty($name) && !empty($description) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"description" => $description
				);

				$ch = curl_init( $baseurl.'/addMenu' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu = end


		if($_GET['edit']=="menuitem") {
			$user_id = $userid;
	        
	        $id = $_POST['rid'];
			$restaurant_menu_id = htmlspecialchars($_POST['menuid'], ENT_QUOTES);
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$description = htmlspecialchars($_POST['menu_dsc'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$outofstock = htmlspecialchars(@$_POST['outofstock'], ENT_QUOTES);
			if($outofstock=="")
			{
				$outofstock = "0";
			}
		
			if( !empty($restaurant_menu_id) && !empty($name) && !empty($description) && !empty($price) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"id" => $id,
					"restaurant_menu_id" => $restaurant_menu_id,
					"name" => $name,
					"description" => $description,
					"price" => $price,
					"out_of_order" => $outofstock
				);
				//json_encode($data);
				$ch = curl_init( $baseurl.'/addMenuItem' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu added..</div>";
					///@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu item = end


		if($_GET['edit'] == "menuextrasection") {
			$user_id = $userid;

			$id = $_POST['rid'];
		    $user_id = $userid;
			$name = htmlspecialchars($_POST['sec_name'], ENT_QUOTES);
			$restaurant_menu_item_id = htmlspecialchars($_POST['restomenuitem'], ENT_QUOTES);
			
			if(isset($_POST['require_items'])) {
				$required = "1";
			} else {
				$required = "0";
			}
			
			if( !empty($name) && !empty($restaurant_menu_item_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"id" => $id,
					"user_id" => $user_id,
					"name" => $name,
					"restaurant_menu_item_id" => $restaurant_menu_item_id,
					"required" => $required
				);

				$ch = curl_init( $baseurl.'/addMenuExtraSection' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding menu extra section, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully menu extra section added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=erroraction=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra section = end


		if($_GET['edit'] == "menuextraitem") {
			$user_id = $userid;
			
			$id = $_POST['rid'];
			$name = htmlspecialchars($_POST['menu_name'], ENT_QUOTES);
			$price = htmlspecialchars($_POST['menu_price'], ENT_QUOTES);
			$restaurant_menu_extra_section_id = htmlspecialchars($_POST['menu_extra_sectionid'], ENT_QUOTES);

			if( !empty($name) && !empty($restaurant_menu_extra_section_id) ) { 

				$headers = array(
					"Accept: application/json",
					"Content-Type: application/json"
				);

				$data = array(
					"id" => $id,
					"name" => $name,
					"price" => $price,
					"restaurant_menu_extra_section_id" => $restaurant_menu_extra_section_id
				);

				$ch = curl_init( $baseurl.'/addMenuExtraItem' );

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

				if($json_data['code'] !== 200){
					//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";

				} else {
					//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
					//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
					echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=success'</script>";
				}

				curl_close($ch);

			} else {
				//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
				echo "<script>window.location='manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error'</script>";
			} //

		} //menu extra item = end
		
		
	}
	
	else
	if(@$_GET['removeMenu']=="ok")
		{
				
				$user_id = $userid;
			
				$menu_id = $_GET['menuID'];
				$restaurant_id = $_GET['resid'];
				$active = $_GET['active'];
	
				
	
					$headers = array(
						"Accept: application/json",
						"Content-Type: application/json"
					);
	
					$data = array(
						"menu_id" => $menu_id,
						"restaurant_id" => $restaurant_id,
						"active" => $active
					);
	
					$ch = curl_init( $baseurl.'/deleteMainMenu' );
	
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
						//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";
	
					} else 
					{
						//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
					}
	
					curl_close($ch);
		}
		else
		if(@$_GET['removeMenuItem']=="ok")
		{
				
				$user_id = $userid;
			
				$menu_id = $_GET['menuItemID'];
				$restaurant_id = $_GET['resid'];
				$active = $_GET['active'];
	
				
	
					$headers = array(
						"Accept: application/json",
						"Content-Type: application/json"
					);
	
					$data = array(
						"menu_item_id" => $menu_id,
						"restaurant_id" => $restaurant_id,
						"active" => $active
					);
	
					$ch = curl_init( $baseurl.'/deleteMenuItem' );
					
					//print_r(json_encode($data));
					//die();
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
						//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";
	
					} else 
					{
						//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
					}
	
					curl_close($ch);
		}
		else
		if(@$_GET['removeMenuSection']=="ok")
		{
				
				$user_id = $userid;
			
				$menu_extra_section_id = $_GET['sectionID'];
				$restaurant_id = $_GET['resid'];
				$active = $_GET['active'];
	
				
	
					$headers = array(
						"Accept: application/json",
						"Content-Type: application/json"
					);
	
					$data = array(
						"menu_extra_section_id" => $menu_extra_section_id,
						"restaurant_id" => $restaurant_id,
						"active" => $active
					);
	
					$ch = curl_init( $baseurl.'/deleteMenuExtraSection' );
					
					//print_r(json_encode($data));
					//die();
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
						//echo "<div class='alert alert-danger'>Error in adding extra menu item, try again later..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&action=error");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=error'</script>";
	
					} else 
					{
						//echo "<div class='alert alert-success'>Successfully extra menu item added..</div>";
						//@header("Location: manage_menu.php?userid=".$userid."&resid=".$resturentid."&p=manage_menu&action=success");
						echo "<script>window.location='manage_menu.php?userid=".$_GET['userid']."&resid=".$restaurant_id."&action=success'</script>";
					}
	
					curl_close($ch);
		}
//edit section  = end



	$user_id = $userid;

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$data = array(
		"user_id" => $user_id
	);
	//$data;
	$ch = curl_init( $baseurl.'/showMainMenus' );

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

	if($json_data['code'] !== 200){
		//echo "<div class='alert alert-danger'>Error in fetching menus, try again later..</div>";
		?>

<div style="margin: 70px 0;" class="textcenter"> <img src="img/nomenu.png" style="display: inline-block;" alt="" />
  <h3 style="font-size: lighter; font-size: 25px; color: #aaa;">Whoops!</h3>
</div>
<?php
	} else {
		///show restaurant menu  .. 
		echo "<ul class='mainmenus'>";
		foreach( $json_data['msg'] as $str => $val ) {
			//var_dump($val);
			$cc = count($val['RestaurantMenu']);
			$currency=$val['Currency']['symbol'];
			foreach ($val['RestaurantMenu'] as $menukey => $menuvalue) {
				//var_dump($menuvalue);

				$restaurant_id = $menuvalue['restaurant_id'];
				?>
<li>
  <div class="inrdiv">
    <div class="left col50">
      <h3><?php echo $menuvalue['name']; ?> <span class="editlink"><a href="javascript:;" data-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-name="<?php echo $menuvalue['name']; ?>" data-menu-description="<?php echo $menuvalue['description']; ?>" class="main_menu_edit"><i class="fa fa-pencil"></i></a></span></h3>
      <p><?php echo $menuvalue['description']; ?></p>
      <p>
		<lable>Index:</lable>
      	<input type="text" name="<?php echo $menuvalue['id']; ?>_index" id="<?php echo $menuvalue['id']; ?>_index" style="width: 50px; padding: 4px; margin: 8px;" value="<?php echo $menuvalue['index']; ?>" >

      	<span id="<?php echo $menuvalue['id']; ?>_buttonBox">
      		<input type='button' value="Update" style="width: 50px; padding: 5px; margin: 2px;" onclick="updateindex(<?php echo $menuvalue['id']; ?>)">
      	</span>
      </p>
    </div>
    <div class="right textcenter">
    	
	  <p>
	  	
		<?php
			if($menuvalue['active']=="1")
			{
				?>
					<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuID=<?php echo $menuvalue['id']; ?>&active=0&removeMenu=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Deactive</a>
				<?php
			}
			else
			{
				?>
					<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuID=<?php echo $menuvalue['id']; ?>&active=1&removeMenu=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Active</a>
				<?php
			}
		?>
	  
	  </p>
      <p class="icon" onClick="openmenu(<?php echo $menuvalue['id']; ?>)" style="margin-top: 20px;"><img src="down-arrow.png" width="20" alt="" /></p>
    </div>
    <div class="clear"></div>
  </div>
  <div id="main_menu_edit_div_<?php echo $menuvalue['id']; ?>"></div>
  <?php //show restaurant menu item .. ?>
  <ul class="mainmenus_items" id="<?php echo $menuvalue['id']; ?>">
    <?php $totalrows = count($menuvalue['RestaurantMenuItem']);
						foreach ($menuvalue['RestaurantMenuItem'] as $key => $value) {
							//var_dump($value);
							?>
    <li>
      <div class="left col80">
        <h3><?php echo $value['name']; ?> <span class="editlink"> <a href="javascript:;" data-main-menu-id="<?php echo $menuvalue['id']; ?>" data-menu-id="<?php echo $value['id']; ?>" data-menu-name="<?php echo $value['name']; ?>" data-menu-description="<?php echo $value['description']; ?>" data-menu-price="<?php echo $value['price']; ?>" data-out-of-stock="<?php echo $value['out_of_order']; ?>" class="main_menu_item_edit"><i class="fa fa-pencil"></i></a> </span></h3>
        <p><?php echo $value['description']; ?></p>
		
      </div>
      <div class="right textcenter">
	  	<p>
			<?php
				if($value['active']=="1")
				{
					?>
						<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuItemID=<?php echo $value['id']; ?>&active=0&removeMenuItem=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Deactive</a>
					<?php
				}
				else
				{
					?>
						<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&menuItemID=<?php echo $value['id']; ?>&active=1&removeMenuItem=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Active</a>
					<?php
				}
			?>
		</p>
        <p class="price"> <?php echo $currency; echo $value['price']; ?>
          <?php $outofstock = $value['out_of_order'];
									
									if($outofstock == 1){
										
										echo "<br><p style='color:red;'>"."Out Of Stock"."</p>";
										} else{
											
											echo "<br><p style='color:green;'>"."Available"."</p>";
											
											}
									
									 ?>
        </p>
      </div>
      <div class="clear"></div>
      <div id="main_menu_item_edit_div_<?php echo $value['id']; ?>"></div>
      <ul class="menuextrasection_ul">
        <?php //show restaurant extra menu section
								foreach ($value["RestaurantMenuExtraSection"] as $sectionkey => $sectionvalue) {
									//var_dump($sectionvalue);
									?>
        <li>
          <h4><?php echo $sectionvalue['name']; if($sectionvalue['required']=='1') { echo "<span style='margin-left:10px; font-size: 14px; color: #aaa;'>(Required)</span>"; } ?> <span class="editlink"> <a href="javascript:;" data-section-id="<?php echo $sectionvalue['id']; ?>" data-section-name="<?php echo $sectionvalue['name']; ?>" data-section-req="<?php echo $sectionvalue['required']; ?>" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>" class="main_menu_item_section_edit"><i class="fa fa-pencil"></i></a> </span>
		  </h4>
          <p style="text-align: right; margin-top: -30px; margin-bottom: 21px;">
				<?php
					if($sectionvalue['active']=="1")
					{
						?>
							<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&sectionID=<?php echo $sectionvalue['id']; ?>&active=0&removeMenuSection=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Deactive</a>
						<?php
					}
					else
					{
						?>
							<a href="?resid=<?php echo @$_GET['resid']; ?>&userid=<?php echo @$_GET['userid']; ?>&sectionID=<?php echo $sectionvalue['id']; ?>&active=1&removeMenuSection=ok" onclick="return confirm('Are you sure?')" style="color:red; text-decoration:none; display:none;">Active</a>
						<?php
					}
				?>
			</p>
		  <div id="main_menu_item_section_edit_div_<?php echo $sectionvalue['id']; ?>"></div>
          <ul class="menuextrasection_item">
            <?php foreach ($sectionvalue['RestaurantMenuExtraItem'] as $keyy => $valuee) {
												?>
            <li>
              <div class="left col70"><?php echo $valuee['name']; ?> <span style="margin-top:2px;" class="editlink"> <a href="javascript:;" data-menu-section-item-id="<?php echo $valuee['id']; ?>" data-menu-section-item-name="<?php echo $valuee['name']; ?>" data-menu-section-item-price="<?php echo $valuee['price']; ?>" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>" class="main_menu_item_section_item_edit"><i class="fa fa-pencil"></i></a> </span></div>
              <div class="right col30 textright"><?php echo $currency; echo $valuee['price']; ?></div>
              <div class="clear"></div>
              <div id="main_menu_item_section_item_edit_div_<?php echo $valuee['id']; ?>"></div>
            </li>
            <?php
											} ?>
            <li style="padding: 0;">
              <div class="addmenu" style="margin-top: 0;">
                <h3 class="addnewmenu_extraitem" data-menu-extra-section-id="<?php echo $sectionvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Section Extra Item</h3>
              </div>
            </li>
          </ul>
          <div class="clear"></div>
        </li>
        <?php
								}
								?>
        <li style="padding: 0;">
          <div class="addmenu" style="margin-top: 0;">
            <h3 class="addnewmenu_extrasection" data-restaurant-id="<?php echo $restaurant_id; ?>" data-menu-item-id="<?php echo $value['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add Menu Extra Section</h3>
          </div>
        </li>
      </ul>
      <div class="clear"></div>
      <?php //show restaurant extra menu section = end ?>
    </li>
    <?php
						} ?>
    <li style="padding: 0;">
      <div class="addmenu" style="margin-top: 0;">
        <h3 class="addnewmenu_item" data-menu-id="<?php echo $menuvalue['id']; ?>"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu Item</h3>
      </div>
    </li>
  </ul>
  <?php //}
					//show restaurant menu item = end... ?>
</li>
<?php
			}
		}
		echo "</ul>";
		?>
<div class="addmenu">
  <h3 id="addnewmenu"><i class="fa fa-plus-circle" style="margin-right: 5px;"></i> Add New Menu</h3>
</div>
<?php
		///show restaurant menu = end  .. 
	}

	curl_close($ch);

} 
else 
{

	//echo "adf";
	@header("Location: index.php");
   echo "<script>window.location='index.php'</script>";
   die;
    
} 


?>
<script src="https://restaurants.foodomia.pk/js/jquery-1.12.4.js"></script>
<script src="https://restaurants.foodomia.pk/js/jquery-ui.js"></script>
<script>
function openmenu(menuid) {
	jQuery(".mainmenus_items").slideUp();
	jQuery(".mainmenus_items#"+menuid).slideDown();
}


jQuery(document).ready(function(){
	//add new menu
	jQuery("#addnewmenu").on("click", function(){
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add New Menu</h3><form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&add=menu' method='post' class='form addmenuform' id='adnewmenfrm'><div class='cl33'><div class='col33 left'> <p><input type='text' name='menu_name' id='menu_name' placeholder='Name'></p> </div> <div class='col33 left'> <p><input type='text' name='menu_dsc' id='menu_dsc' placeholder='Description'></p> </div> <div class='col33 left'> <p><input type='submit' value='Add Menu'></p> </div> <div class='clear'></div></div></form>");
	});

	//edit main menu
	jQuery(".main_menu_edit").on("click", function(){
		var menuid = jQuery(this).attr("data-menu-id");
		var menuname = jQuery(this).attr("data-menu-name");
		var menudesc = jQuery(this).attr("data-menu-description");
	
		jQuery("#main_menu_edit_div_"+menuid).html("<form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&edit=menu' method='post' class='form addmenuform' id='adnewmenfrm'><input type='hidden' name='rid' id='rid' value='"+menuid+"'><div class='cl33'><div class='col33 left'> <p><input type='text' name='menu_name' id='menu_name' value='"+menuname+"' required=''><label alt='Name' placeholder='Name'></label></p> </div> <div class='col33 left'> <p><input type='text' name='menu_dsc' id='menu_dsc' value='"+menudesc+"' required=''><label alt='Description' placeholder='Description'></label></p> </div> <div class='col33 left'> <p><input type='submit' value='Update Menu'></p> </div> <div class='clear'></div></div></form>");
	});

	//add new menu item
	jQuery(".addnewmenu_item").on("click", function(){
		var menuid = jQuery(this).attr("data-menu-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add New Menu Item</h3><form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&add=menuitem' method='post' class='form addmenuform' id='adnewmenuitmfrm'><input type='hidden' name='menuid' id='menuid' value='"+menuid+"'><div class='col50 left twocll'> <p><input type='text' name='menu_name' id='menu_name' placeholder='Name'></p> </div> <div class='col50 right twocll'> <p><input type='text' name='menu_dsc' id='menu_dsc' placeholder='Description'></p> </div> <div class='col50 left twocll'> <p><input type='text' name='menu_price' id='menu_price' placeholder='Price'></p> </div> <div class='col50 right twocll'> <p><input type='submit' value='Add Menu Item'></p> </div> <div class='clear'></div></form>");
	});

	//edit main menu item
	jQuery(".main_menu_item_edit").on("click", function(){
		var mainmenuidd = jQuery(this).attr("data-main-menu-id");
		var menuid = jQuery(this).attr("data-menu-id");
		var menuname = jQuery(this).attr("data-menu-name");
		var menudesc = jQuery(this).attr("data-menu-description");
		var menuprce = jQuery(this).attr("data-menu-price");
		var menuoutofstock = jQuery(this).attr("data-out-of-stock");
		if(menuoutofstock=="1")
		{
			var menuoutofstock = "checked";
		}
		
		jQuery("#main_menu_item_edit_div_"+menuid).html("<form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&edit=menuitem' method='post' class='form addmenuform' id='adnewmenuitmfrm'><input type='hidden' name='rid' id='rid' value='"+menuid+"'><input type='hidden' name='menuid' id='menuid' value='"+mainmenuidd+"'><div class='col50 left twocll'> <p><input type='text' name='menu_name' id='menu_name' value='"+menuname+"' required='Name'><label alt='Name' placeholder='Name'></label></p> </div> <div class='col50 right twocll'> <p><input type='text' name='menu_dsc' id='menu_dsc' value='"+menudesc+"' required=''><label alt='Description' placeholder='Description'></label></p> </div> <div class='col50 left twocll'> <p><input type='text' name='menu_price' value='"+menuprce+"' id='menu_price' required='Price'><label alt='Price' placeholder='Price'></label><p style='margin-top: 0px !important'><input type='checkbox' name='outofstock' id='require_items' value='1' "+menuoutofstock+" /> Out Of Stock</p></p> </div> <div class='col50 right twocll'> <p><input type='submit' value='Update Menu Item'></p></div> <div class='clear'></div></form>");
	});

	//add new menu extra section
	jQuery(".addnewmenu_extrasection").on("click", function(){
		var restoid = jQuery(this).attr("data-restaurant-id");
		var restomenuitem = jQuery(this).attr("data-menu-item-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addnewmenu_extrasection'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Menu Extra Section</h3><form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&add=menuextrasection' method='post' class='form addmenuform' id='adextrsctfrm'> <input type='hidden' name='restoid' id='restoid' value='"+restoid+"'> <input type='hidden' name='restomenuitem' id='restomenuitem' value='"+restomenuitem+"'> <p> <input type='text' name='sec_name' id='sec_name' placeholder='Section Name'> </p> <p style='text-align:left;'> <input type='checkbox' name='require_items' id='require_items' value='1' /> Required? <span style='display:block;font-size:11px;margin-top:5px;color:#aaa;'>If checked, this section will require to fill.</span></p> <p> <input type='submit' value='Add Extra Section'> </p></form>");
	});

	//edit main menu item section
	jQuery(".main_menu_item_section_edit").on("click", function(){
		var restoid = jQuery(this).attr("data-restaurant-id");
		var restomenuitem = jQuery(this).attr("data-menu-item-id");
		
		var sectidd = jQuery(this).attr("data-section-id");
		var sectnmmm = jQuery(this).attr("data-section-name");
		var sectreq = jQuery(this).attr("data-section-req");
		if( sectreq == "1" ) {
			var sectr = "checked";
		} else {
			var sectr = "";
		}
	
		jQuery("#main_menu_item_section_edit_div_"+sectidd).html("<form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&edit=menuextrasection' method='post' class='form addmenuform' id='adextrsctfrm'> <input type='hidden' name='rid' id='rid' value='"+sectidd+"' /> <input type='hidden' name='restoid' id='restoid' value='"+restoid+"'> <input type='hidden' name='restomenuitem' id='restomenuitem' value='"+restomenuitem+"'> <p> <input type='text' name='sec_name' id='sec_name' value='"+sectnmmm+"' required=''> <label alt='Section Name' placeholder='Section Name'></label></p> <p style='text-align:left;'> <input type='checkbox' name='require_items' id='require_items' "+sectr+" value='1' /> Required? <span style='display:block;font-size:11px;margin-top:5px;color:#aaa;'>If checked, this section will require to fill.</span></p> <p> <input type='submit' value='Update Extra Section'> </p></form>");
	});

	//add new menu section extra item
	jQuery(".addnewmenu_extraitem").on("click", function(){
		var menuextrasectionid = jQuery(this).attr("data-menu-extra-section-id");
		//alert(menuid);
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addnewmenu_extraitem'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Section Extra Item</h3><form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&add=menuextraitem' method='post' class='form addmenuform' id='adextrsctitmfrm'><input type='hidden' name='menu_extra_sectionid' id='menu_extra_sectionid' value='"+menuextrasectionid+"'> <p> <input type='text' name='menu_name' id='menu_name' placeholder='Name'> </p> <p> <input type='text' name='menu_price' id='menu_price' placeholder='Price'></p><p> <input type='submit' value='Add Section Extra Item'> </p></form>");
	});

	//edit main menu item section item
	jQuery(".main_menu_item_section_item_edit").on("click", function(){
		var menuextrasectionid = jQuery(this).attr("data-menu-extra-section-id");
		
		var sect_item_idd = jQuery(this).attr("data-menu-section-item-id");
		var sect_item_name = jQuery(this).attr("data-menu-section-item-name");
		var sect_item_price = jQuery(this).attr("data-menu-section-item-price");
	
		jQuery("#main_menu_item_section_item_edit_div_"+sect_item_idd).html("<form action='manage_menu.php?resid=<?php echo $resturentid; ?>&userid=<?php echo $userid; ?>&p=manage_menu&edit=menuextraitem' method='post' class='form addmenuform' id='adextrsctitmfrm'><input type='hidden' name='rid' id='rid' value='"+sect_item_idd+"'><input type='hidden' name='menu_extra_sectionid' id='menu_extra_sectionid' value='"+menuextrasectionid+"'> <p> <input type='text' name='menu_name' id='menu_name' value='"+sect_item_name+"' required=''><label alt='Name' placeholder='Name'></label> </p> <p> <input type='text' name='menu_price' id='menu_price' value='"+sect_item_price+"' required=''><label alt='Price' placeholder='Price'></label></p><p> <input type='submit' value='Update Section Extra Item'> </p></form>");
	});

	//add instructions during purchase
	jQuery(".instructn_heading").on("click", function(){
		jQuery(this).hide();
		jQuery(this).parent().append("<h3 class='addmenuheading'><i class='fa fa-plus-circle' style='margin-right: 5px;'></i> Add Instructions</h3><p><input type='text' name='instructions' id='instructions' placeholder='Instructions'></p>");
	});
	
});
</script>

<?php
}
else 
{
	@header('Location: login.php');
}
?>
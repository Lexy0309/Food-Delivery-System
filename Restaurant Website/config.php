<?php 
ini_set('session.gc_maxlifetime',12*60*60);
ini_set('session.cookie_lifetime',12*60*60);
//echo phpinfo();
		
@session_start();

//put api link here eg:http://abc.com/mobileapp_api/publicSite
$baseurl = "http://localhost/mobileapp_api/publicSite";

//put api link here eg:http://abc.com/mobileapp_api/
$image_baseurl = "http://localhost/mobileapp_api/";
// echo($_GET['log']);
//log = end

if( isset($_GET['reg']) ) { //register user

	$restarant_name = htmlspecialchars($_POST['restoname'], ENT_QUOTES);
    $contact_name = htmlspecialchars($_POST['contname'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['phne'], ENT_QUOTES);
	$phone = str_replace("(","",$phone);
	$phone = str_replace(")","",$phone);
	$phone = str_replace("-","",$phone);
	$phone = str_replace("_","",$phone);
	
    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);
    $restarant_address = htmlspecialchars($_POST['restaddr'], ENT_QUOTES);
    $anything_else = htmlspecialchars($_POST['anythingelse'], ENT_QUOTES);

	if( !empty($restarant_name) && !empty($contact_name) && !empty($phone) && !empty($email) && !empty($restarant_address) && !empty($anything_else) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email, 
			"restaurant_name" => $restarant_name,
			"contact_name" => $contact_name, 
			"address" => $restarant_address, 
			"phone" => $phone, 
			"description" => $anything_else
		);

		$ch = curl_init( $baseurl.'/restaurantRequest' );

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
			//echo "<div class='alert alert-danger'>Error in registering user, try again later..</div>";
			@header("Location: index.php?action=error");
	   		echo "<script>window.location='index.php?action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully registered..</div>";
			@header("Location: index.php?action=success");
	   		echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: index.php?action=error");
	   	echo "<script>window.location='index.php?action=error'</script>";
	} //

} //register user = end

if( isset($_GET['forget']) ) { //forget password

    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);

	$device_token = "";
	$role = "user";

	if( !empty($email) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email
		);

		$ch = curl_init( $baseurl.'/forgotPassword' );

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
			//echo "<div class='alert alert-danger'>Some error occured, try again later..</div>";
			@header("Location: index.php?action=error");
	   		echo "<script>window.location='index.php?action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully registered..</div>";
			@header("Location: index.php?action=success");
	   		echo "<script>window.location='index.php?action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: index.php?action=error");
   		echo "<script>window.location='index.php?action=error'</script>";
	} //

} //forget password = end


function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//echo generateRandomString(6);
?>
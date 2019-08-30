<?php 
ini_set('session.gc_maxlifetime',12*60*60);
ini_set('session.cookie_lifetime',12*60*60);
//echo phpinfo();
		
@session_start();

//change api link here  eg:http://abc.com/mobileapp_api/publicSite
$baseurl = "http://domain.com/mobileapp_api/publicSite";

//change image access link here  eg:http://abc.com/mobileapp_api/mobileapp_api/
$image_baseurl = "http://domain.com/mobileapp_api/";

if( isset($_GET['log']) ) { //log

	if( $_GET['log'] == "in" ) { //login user

		$email = htmlspecialchars($_POST['eml'], ENT_QUOTES);
	    $password = htmlspecialchars($_POST['pswd'], ENT_QUOTES);

	    if( !empty($email) && !empty($password) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"email" => $email, 
				"password" => $password,
				"role" => "rider"
			);

			$ch = curl_init( $baseurl.'/login' );

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$return = curl_exec($ch);

			$json_data = json_decode($return, true);

			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//echo $json_data['code'];
			//die;
			curl_close($ch);

			if($json_data['code'] !== 200){
				//echo "<div class='alert alert-danger'>Error in login user, try again later..</div>";
				@header("Location: index.php?action=error");
	   			echo "<script>window.location='index.php?action=error'</script>";

			} else {

				if( $json_data['msg']['User']['role'] == "rider" ) { //rider
					
					$_SESSION['id'] = $json_data['msg']['UserInfo']['user_id'];
					$_SESSION['first_name'] = $json_data['msg']['UserInfo']['first_name'];
					$_SESSION['last_name'] = $json_data['msg']['UserInfo']['last_name'];
					$_SESSION['name'] = $json_data['msg']['UserInfo']['first_name']." ".$json_data['msg']['UserInfo']['last_name'];
					$_SESSION['phone'] = $json_data['msg']['UserInfo']['phone'];
					$_SESSION['device_token'] = $json_data['msg']['UserInfo']['device_token'];
					$_SESSION['online'] = $json_data['msg']['UserInfo']['online'];
					$_SESSION['email'] = $json_data['msg']['User']['email'];
					$_SESSION['active'] = $json_data['msg']['User']['active'];
					$_SESSION['user_type'] = $json_data['msg']['User']['role'];

					@header("Location: dashboard.php?p=summary");
		   			echo "<script>window.location='dashboard.php?p=summary'</script>";

				} //rider = end
				else {
					@header("Location: index.php?action=error");
		   			echo "<script>window.location='index.php?action=error'</script>";
	   			}

			}

		} else {
			@header("Location: index.php?action=error");
   			echo "<script>window.location='index.php?action=error'</script>";
		} //

	} //login user = end


	if( $_GET['log'] == "out" ) { //logout user

		@session_destroy();
		@header("Location: index.php");
   		echo "<script>window.location='index.php'</script>";

	} //logout user = end

} //log = end

if( isset($_GET['reg']) ) { //register user

	$first_name = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $last_name = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['emailaddr'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['phne'], ENT_QUOTES);
	$phone = str_replace("_","",$phone);
	$phone = str_replace("-","",$phone);
	$phone = str_replace("(","",$phone);
	$phone = str_replace(")","",$phone);
    $city = htmlspecialchars($_POST['city'], ENT_QUOTES);
    $state = htmlspecialchars($_POST['state'], ENT_QUOTES);
    $country = htmlspecialchars($_POST['country'], ENT_QUOTES);
    $addrtostart = htmlspecialchars($_POST['addrtostart'], ENT_QUOTES);
    $howuhear = htmlspecialchars($_POST['howuhear'], ENT_QUOTES);

	if( !empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone) && !empty($addrtostart) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"email" => $email, 
			"first_name" => $first_name, 
			"last_name" => $last_name, 
			"address" => $addrtostart, 
			"phone" => $phone, 
			"city" => $city,
			"state" => $state, 
			"country" => $country
		);

		$ch = curl_init( $baseurl.'/riderRequest' );

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

function convertDateTimetoFullMonthAndDayNameWithYear($datetime){

    $date = new DateTime($datetime);
    $new_date_format = $date->format('F D d,Y');
    return $new_date_format; //February Tue 13,2018

}

//echo generateRandomString(6);
?>
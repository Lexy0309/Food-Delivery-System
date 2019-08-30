<?php 
	require_once("../config.php");
?>
<?php
/*
	Sample Processing of Forgot password form via ajax
	Page: extra-register.html
*/

# Response Data Array
$resp = array();


// Fields Submitted
$username = $_POST["username"];
$password = $_POST["password"];


// This array of data is returned for demo purpose, see assets/js/neon-forgotpassword.js
$resp['submitted_data'] = $_POST;


// Login success or invalid login data [success|invalid]
// Your code will decide if username and password are correct
$login_status = 'invalid';

//$query = mysqli_query($db, "select * from users_table where username = '".$username."' and password = '".$password."'");

$email = $username;
$password = $password;

$headers = array(
	"Accept: application/json",
	"Content-Type: application/json"
);
$data = array(
	"email" => $email, 
	"password" => $password
);

$ch = curl_init( $baseurl.'/login' );

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$return = curl_exec($ch);

$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

// do some checking to make sure it sent
$return_json = json_decode($return, true);

if( $return_json['code'] !== 200 ) {
	echo "user not found";

} else {
	//echo "<strong>".$return_json['msg']['UserInfo']['first_name']." ".$return_json['msg']['UserInfo']['last_name']."</strong> user logged in";
	//var_dump($return);

	$_SESSION['id'] = $return_json['msg']['UserAdmin']['id'];
	$_SESSION['name'] = $return_json['msg']['UserAdmin']['first_name']." ".$return_json['msg']['UserAdmin']['last_name'];
	$_SESSION['phone'] = $return_json['msg']['UserAdmin']['phone'];
	//$_SESSION['device_token'] = $return_json['msg']['UserAdmin']['device_token'];
	//$_SESSION['online'] = $return_json['msg']['UserInfo']['online'];
	$_SESSION['email'] = $return_json['msg']['UserAdmin']['email'];
	$_SESSION['role'] = $return_json['msg']['UserAdmin']['role'];
	$_SESSION['UserAdmin'] = $return_json['msg']['UserAdmin']['role_name'];

	$login_status = 'success';
}
//if($query)
/*if( isset($username) && isset($password) )
{
	/*$fetchquery = mysql_fetch_array($query);
	$_SESSION['id'] = $fetchquery['id'];
	$_SESSION['name'] = $fetchquery['name'];
	$usernamee = $fetchquery['username'];
	$_SESSION['email'] = $fetchquery['email'];
	$passwordd = $fetchquery['password'];
	$_SESSION['user_type'] = $fetchquery['user_type'];***
	$_SESSION['id'] = "1";
	$_SESSION['name'] = "Mr Admin";
	$_SESSION['username'] = "admin";
	$_SESSION['email'] = "info@example.com";
	$_SESSION['password'] = "123";
	$_SESSION['user_type'] = "admin";

	$usernamee = $_SESSION['username'];
	$passwordd = $_SESSION['password'];
}
else {
	//echo mysqli_error($db);
} */

$resp['login_status'] = $login_status;


// Login Success URL
if($login_status == 'success')
{
	
	// If you validate the user you may set the user cookies/sessions here
		#setcookie("logged_in", "id");
		#$_SESSION["logged_user"] = "id";
	
	// Set the redirect url after successful login
	$resp['redirect_url'] = 'login.php';
}


echo json_encode($resp);
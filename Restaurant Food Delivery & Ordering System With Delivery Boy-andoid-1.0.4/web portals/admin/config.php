<?php
@session_start();

$db = "";

date_default_timezone_set('America/New_York');

//Put your API link here eg:http://abc.com/mobileapp_api/superAdmin
$baseurl = "http://domain.com/mobileapp_api/superAdmin";

//Put your API link here for images access eg:http://abc.com/mobileapp_api/
$image_baseurl = "http://domain.com/mobileapp_api/";


function convertintotime($datetime){

 $date = new DateTime($datetime);
 $new_date_format = $date->format('Y-m-d h:i:s A');
 return $new_date_format;

}

// function convertintotime($datetime)
// {

// 	$dateTime = new DateTime($datetime, new DateTimeZone('Asia/Karachi')); 
// 	return $dateTime->format("d-m-y  H:i A");
// }

?>
<?php
if(isset($_GET['data'])) {
	$data = $_GET['data'];

	$result = htmlspecialchars_decode($data, ENT_QUOTES);
	 
	$data = json_decode($result, true);
	var_dump($data);
}
else {
	echo "some error occured, try again later.. ";
}
<?php

require_once("config.php");
if(isset($_SESSION["id"])) {
	header("Location: dashboard.php");
	require_once("dashboard.php");
}
else {
	header("Location: login.php");
	require_once("login.php");
}
?>
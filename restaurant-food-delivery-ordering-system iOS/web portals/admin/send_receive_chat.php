<?php
//send chat
if(isset($_GET['sendchat'])) {

	$receiver_id = $_GET['receiver_id'];
	$sender_id = $_GET['sender_id'];
	$message = $_GET['message'];
	$created = $_GET['created'];

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);
	$data = array(
		"receiver_id" => $receiver_id, 
		"sender_id" => $sender_id, 
		"message" => $message, 
		"created" => $created
	);

	$ch = curl_init( $baseurl.'/chat' );

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

	if($http_code !== 200) {
		echo "<div class='alert alert-danger'>".$curl_error."</div>";

	} else {
		echo "<div class='alert alert-success' style='position: absolute;z-index: 999; width: 100%; text-align: center; clear: both; padding: 5px;'>Message sent..</div>";
	}

} //send chat = end


//show chat
if(isset($_GET['showchat'])) { 

	$receiver_id11 = $_GET['receiver_id'];
	$sender_id11 = $_GET['sender_id'];
	$user_id11 = $_GET['user_id'];

	$headers11 = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);
	$data11 = array(
		"receiver_id" => $receiver_id11,
		"sender_id" => $sender_id11,
		"user_id" => $user_id11
	);

	$ch11 = curl_init( $baseurl.'/getConversation' );

	curl_setopt($ch11, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch11, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch11, CURLOPT_POSTFIELDS, json_encode($data11));
	curl_setopt($ch11, CURLOPT_HTTPHEADER, $headers11);
	$result11 = curl_exec($ch11);

	$curl_error11 = curl_error($ch11);
	$http_code11 = curl_getinfo($ch11, CURLINFO_HTTP_CODE);

	$json_data11 = json_decode($result11, true);
	//var_dump($json_data11['msg']);

	foreach ($json_data11['msg'] as $key => $data) {
		//var_dump($data);
		?>
			<div class="row chatrow">
				<div class="col-md-4"><strong><?php echo $data['Chat']['sender_id']; ?></strong> 
					<small style="display: block; font-size: 11px;"><?php echo $data['Chat']['created']; ?></small></div>
				<div class="col-md-8"><?php echo $data['Chat']['message']; ?></div>
				<div class="clearfix"></div>
			</div>
		<?php
	}

	curl_close($ch11);

} //show chat = end
<!-- Footer -->
<footer class="main">
	
	&copy; <?php echo date('Y'); ?> Copyright. All rights reserved.
	
</footer>

<div id="chat" class="fixed" data-current-user="<?php echo $_SESSION['name']; ?>" data-order-by-status="1" data-max-chat-history="25">
	
	<div class="chat-inner">
	
		
		<h2 class="chat-header">
			<a href="#" class="chat-close" data-animate="1"><i class="entypo-cancel"></i></a>
			
			<i class="entypo-users"></i>
			Chat
			<?php /**<span class="badge badge-success is-hidden">0</span>**/ ?>
		</h2>
		
		
		<div class="chat-group" id="group-1">
			<?php 
				$user_id = "8";

				$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
				);
				$data = array(
				"user_id" => $user_id
				);

				$ch = curl_init( $baseurl.'/chatInbox' );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);

				$curl_error = curl_error($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				$json_data = json_decode($result, true);
                //var_dump($json_data);
                $i=0;
                foreach($json_data['msg'] as $str => $data) {
                    //var_dump($data);
                    if(!empty($data['UserInfo']['user_id'])) {

                    	/*$receiver_id11 = $data['UserInfo']['user_id'];
						$sender_id11 = "8";
						$user_id11 = "8";

						$headers11 = array(
						    "Accept: application/json",
						    "Content-Type: application/json"
						   );
						   $data11 = array(
						    "receiver_id" => $receiver_id11,
						    "sender_id" => $sender_id11,
						    "user_id" => $user_id11
						    );

						   $ch11 = curl_init('http://foodomia.dinosoftlabs.com/superAdmin/getConversation');

						   curl_setopt($ch11, CURLOPT_RETURNTRANSFER, true);
						   curl_setopt($ch11, CURLOPT_CUSTOMREQUEST, 'POST');
						   curl_setopt($ch11, CURLOPT_POSTFIELDS, json_encode($data11));
						   curl_setopt($ch11, CURLOPT_HTTPHEADER, $headers11);
						   $result11 = curl_exec($ch11);

						   $curl_error11 = curl_error($ch11);
						   $http_code11 = curl_getinfo($ch11, CURLINFO_HTTP_CODE);

						   $json_data11 = json_decode($result11, true);
						    //var_dump($json_data11['msg']);

						   curl_close($ch11);*/

                    	?>
	                    	<?php /**<a href="#" id="<?php echo $data['UserInfo']['user_id']; ?>" data-conversation-history="<?php echo htmlspecialchars($result11, ENT_QUOTES); ?>"><span class="user-status is-online"></span> <em><?php echo $data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']; ?></em></a>**/ ?>
	                    	<a href="chat.php?rec_id=<?php echo $data['UserInfo']['user_id']; ?>" id="<?php echo $data['UserInfo']['user_id']; ?>"><span class="user-status is-online"></span> <em><?php echo $data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']; ?></em></a>
	                    <?php
                    } 
                    $i++;
                }

				curl_close($ch);
	        ?>
		</div>
	
	</div>
	
	<!-- conversation template -->
	<div class="chat-conversation">
		
		<div class="conversation-header">
			<a href="#" class="conversation-close"><i class="entypo-cancel"></i></a>
			
			<span class="user-status"></span>
			<span class="display-name"></span> 
			<small></small>
		</div>
		
		<ul class="conversation-body">	
		</ul>
		
		<div class="chat-textarea">
			<textarea class="form-control autogrow" placeholder="Type your message"></textarea>
		</div>
		
	</div>
	
</div>
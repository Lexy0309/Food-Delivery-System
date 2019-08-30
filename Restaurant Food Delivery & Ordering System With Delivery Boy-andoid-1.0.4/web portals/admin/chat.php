<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>

<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	<h2 class="toast-title">Chat Messages</h2>
   

   <div class="row" style="margin-top: 30px;">
   	<div class="col-md-4 col-sm-12">
   		<div id="record-group"></div>
   		<ul class="list-group">
	   		<?php 
				/*$user_id = "8";

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
						?>
	                    	<li class="list-group-item"><a href="chat.php?rec_id=<?php echo $data['UserInfo']['user_id']; ?>" id="<?php echo $data['UserInfo']['user_id']; ?>"><span class="user-status is-online"></span> <em><?php echo $data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']; ?></em></a></li>
	                    <?php
	                } 
	                $i++;
	            }

				curl_close($ch);*/
	        ?>
	    </ul>

   	</div>
   	<div class="col-md-8 col-sm-12">
   			<div class="col-sm-12 conversation">
        

        <!-- Message Box -->
        <div class="row message" id="conversation">
          <div class="row message-body">
            <div class="col-sm-12 message-main-receiver" id="message-main-receiver">
              
			  
            </div>
			<div class="col-sm-10 col-xs-10 reply-main">
				<textarea class="form-control" rows="2" id="comment"></textarea>
			</div>
			<div class="col-sm-2 col-xs-2 ">
				<button class="btn btn-primary" id="sendMsg">Send</button>
			</div>
          </div>
        </div>
      </div>
   			<div class="">
				<?php 
		            if( isset($_GET['rec_id']) && $_GET['rec_id'] != "" ) {
		                $rec_id = $_GET['rec_id'];
		            } else {
		                $rec_id = "";
		            }

		            /*if(isset($_GET['insert'])){
		                if($_GET['insert']=="ok") {


							$receiver_id = $_POST['receiver_id'];
							$sender_id = $_POST['sender_id'];
							$message = $_POST['message'];
							$created = $_POST['created'];

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

							   $ch = curl_init('http://foodomia.dinosoftlabs.com/superAdmin/chat');

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

							   if($http_code !== 200){
							   	 echo "<div class='alert alert-danger'>".$curl_error."</div>";
							   
							   }else{
							   	 echo "<div class='alert alert-success'>Successfully submitted..</div>";
							   	echo "<script>window.location='chat.php";
							   	if( isset($_GET['rec_id']) ) { echo "?rec_id=".$_GET['rec_id']; } 
							   	echo "';</script>";
							   }

								
		                }
		            }*/
		        ?>
		        
		        <div class="row">
		        	<div class="col-md-12 col-sm-12">
		        		<style type="text/css">
		        			.chatrow {
		        				padding:15px; 
		        				background: #eee;
		        				border-bottom: 1px solid #ddd;
		        			}
		        			.chatrow:nth-child(even) {
		        				background: #fefefe;
		        			}
		        			.chatrow:last-child {
		        				border-bottom: none;
		        			}

		        			.refreshbtn {
		        				position: fixed; 
		        				margin: 10px; 
		        				float: right; 
		        				z-index: 9;
		        				right: 40px;
		        				opacity: 0.3;
		        				transition: all .2s;
		        			}
		        			.refreshbtn:hover {
		        				opacity: 1;
		        			}
		        		</style>
		        		<div id="resp" style="position: relative;"></div>
		        		<div class="chatbox" style="position:relative; height: 350px; border:1px solid #eee; border-radius: 3px; overflow-x: hidden;">
		        			<?php if( isset($_GET['rec_id']) ) { ?>
		        				
		        				<button type="button" onClick="showchat()" class="btn btn-primary btn-sm refreshbtn">Refresh</button>
		        				<div id="chatbox"><?php 
			        				/*$receiver_id11 = $rec_id;
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

								   curl_close($ch11);*/
			        			?></div>

		        			<?php } else { ?>
		        				<!--<em style="padding:20px;display: block;">Please select some chat from left sidebar..</em>-->
		        			<?php } ?>
		        		</div>
		        	</div>
		        	<div class="col-md-12 col-sm-12" style="margin-top: 20px;">
		        		<?php /*<form role="form" method="post" action="<?php if( isset($_GET['rec_id']) ) { ?>chat.php?<?php echo "rec_id=".$_GET['rec_id']."&"; ?>insert=ok<?php } else { echo "#"; } ?>" class="form-horizontal form-groups-bordered" onSubmit="sendchat()">*/?>
		        		
		        			<?php if( isset($_GET['rec_id']) ) { ?>

			        			<input type="hidden" id="receiver_id" value="<?php echo $_GET['rec_id']; ?>" name="receiver_id" />
			        			<input type="hidden" id="sender_id" value="8" name="sender_id" />
			        			<input type="hidden" id="user_id" value="8" name="user_id" />
			        			<input type="hidden" id="created" value="<?php echo date("Y-m-d H:m:s"); ?>" name="created" />
						        <div class="">
						            <div class="col-md-9 col-sm-12" style="padding: 0;">
						                <input type="text" id="message" class="form-control" name="message" placeholder="Write message..">
						            </div>

						            <div class="col-md-3 col-sm-12" style="padding-right: 0;">
							            <input type="button" class="btn btn-primary" value="Reply" onClick="sendchat()" style="width: 100%;">
						            </div>
						            <div class="clearfix"></div>
						        </div> 

					        <?php } else { ?>

						        <!--<div class="">-->
						        <!--    <div class="col-md-9 col-sm-12" style="padding: 0;">-->
						        <!--        <input type="text" class="form-control" name="message" placeholder="Write message.." disabled>-->
						        <!--    </div>-->

						        <!--    <div class="col-md-3 col-sm-12" style="padding-right: 0;">-->
							       <!--     <input type="button" class="btn btn-primary" value="Reply" style="width: 100%;">-->
						        <!--    </div>-->
						        <!--</div>-->

					        <?php } ?>

				        <?php /*</form>*/ ?>

		        	</div>
		        </div>
		        
		    </div>

   	</div>
   	<div class="clearfix"></div>
   </div>
    

<!-- Footer -->
<script>
var wage = document.getElementById("message");
wage.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        sendchat();
    }
});

function sendchat() {

	var receiver_id = document.getElementById('receiver_id').value;
	var sender_id = document.getElementById('sender_id').value;
	var created = document.getElementById('created').value;
	var message = document.getElementById('message').value;

	if( message != "" ) {
		$.ajax({
	        type: "GET",
	        url: "send_receive_chat.php?sendchat=ok",
	        data: {
	            'receiver_id' : receiver_id,
	            'sender_id' : sender_id,
	            'created' : created,
	            'message' : message
	        },
	        success: function(response)
	        {
	            //alert(response);
	            document.getElementById('resp').innerHTML = response;
	            document.getElementById('message').value = "";
	            showchat();
	        }
	    });
    } else {
    	alert("Message field not be empty");
    }

} //send chat = end


function showchat() {

	var receiver_id = document.getElementById('receiver_id').value;
	var sender_id = document.getElementById('sender_id').value;
	var user_id = document.getElementById('user_id').value;

	if( receiver_id != "" && sender_id != "" && user_id != "" ) {
		$.ajax({
	        type: "GET",
	        url: "send_receive_chat.php?showchat=ok",
	        data: {
	            'receiver_id' : receiver_id,
	            'sender_id' : sender_id,
	            'user_id' : user_id
	        },
	        success: function(response)
	        {
	        	document.getElementById('resp').innerHTML = "";
	            document.getElementById('chatbox').innerHTML = response;
	        }
	    });
    } else {
    	alert("some problem occured, try again later..");
    }

} //show chat = end
</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>




	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
	<script src="https://www.gstatic.com/firebasejs/4.3.0/firebase.js"></script>
	<script>
	
	var config = {
                apiKey: "AIzaSyAfsPgi7NSGKcVmOgy6LcVKt3_0bv0HYTY",
                authDomain: "foodomia-2d1d3.firebaseapp.com",
                databaseURL: "https://foodomia-2d1d3.firebaseio.com/",
                storageBucket: "foodomia-2d1d3.appspot.com"
            };
            firebase.initializeApp(config);
			var ref = firebase.database().ref("Chat");
			ref.on("value", goData, errData)
			function goData(snapshot){
			    
				var childKey = snapshot.val();
				var key = Object.keys(childKey);
				$(".message-main-receiver .row").remove();
				$('#comment').attr('thread', key[0]);
				for (var key2 in childKey[key[0]]) {
					var receiver_id = snapshot.val()[key[0]][key2].receiver_id;
					var sender_id = snapshot.val()[key[0]][key2].sender_id;
					var message = snapshot.val()[key[0]][key2].text;
					var timestamp = snapshot.val()[key[0]][key2].timestamp;
					var sender_name = snapshot.val()[key[0]][key2].sender_name;
					$(".message-main-receiver").append('<div class="row"><div class="receiver '+sender_name+'"><div class="message-text">'+message+'</div><span class="message-time pull-right">'+timestamp+'</span></div></div>');
				}
				$(document).on('click', '#record-group .sideBar-body', function() {
					var id = $(this).attr('id');
					var addclass = $('#record-group .sideBar-body').css({'background':'transparent'});
					var addclass = $(this).css({'background':'#f2f2f2'});
					var keyChat = $(this).attr('key');
					$('#comment').attr('thread', keyChat);
					var refReload = firebase.database().ref("Chat");
					refReload.on("value", againData, errData)
					function againData(snapshot){
						var childKey = snapshot.val();
						var key = Object.keys(childKey);
						$(".message-main-receiver .row").remove();
						$('#comment').attr('thread', key[id]);
						for (var key2 in childKey[key[id]]) {
							var receiver_id = snapshot.val()[key[id]][key2].receiver_id;
							var sender_id = snapshot.val()[key[id]][key2].sender_id;
							var message = snapshot.val()[key[id]][key2].text;
							var timestamp = snapshot.val()[key[id]][key2].timestamp;
							var sender_name = snapshot.val()[key[id]][key2].sender_name;
							$(".message-main-receiver").append('<div class="row"><div class="receiver '+sender_name+' "><div class="message-text">'+message+'</div><span class="message-time pull-right">'+timestamp+'</span></div></div>');
						}
					}
					var childKey = snapshot.val();
					var key = Object.keys(childKey);
					$(".message-main-receiver .row").remove();
					for (var key2 in childKey[key[id]]) {
						var message = snapshot.val()[key[id]][key2].text;
						var sender_name = snapshot.val()[key[id]][key2].sender_name;
						var timestamp = snapshot.val()[key[id]][key2].timestamp;
						$(".message-main-receiver").append('<div class="row"><div class="receiver '+sender_name+' "><div class="message-text">'+message+'</div><span class="message-time pull-right">'+timestamp+'</span></div></div>');
					}
				});
			};
            var list = firebase.database().ref("Chat");
			list.on('value', getData, errData);
			function getData (data) {
				var count = 0;
				$("#record-group .sideBar-body").remove();
				$("#record-group span").remove();
				data.forEach(function(childRecord){
					var childData = childRecord.val();
					var childKey = childRecord.key;
					$("#record-group").append('<div class="row sideBar-body" key="'+childKey+'" id="'+count+'"><div class="col-sm-2 col-xs-2 sideBar-avatar"><div class="avatar-icon"><img src="assets/images/avatar.png"></div></div><div class="col-sm-9 col-xs-9 sideBar-main"><div class="row"><div class="col-sm-8 col-xs-8 sideBar-name" id="user_name"><div class="name-meta" id="user_'+childKey+'">'+childKey+'</div></div></div></div></div>');
					var last = Object.keys(childData)[Object.keys(childData).length-1];
					var lastMessage = childRecord.val()[last].text;
					var sender_name = childRecord.val()[last].sender_name;
					$('#user_'+childKey).append('<div id="'+count+'" class="time-meta"><span style="color:#BE2C2C; font-weight:600;">'+sender_name+': &nbsp;</span>'+lastMessage+'</div>');
					count = count + 1;
				});
			}
			function errData() {
				alert('Errors');
			}
			$(document).on('click', '#sendMsg', function() {
				var thread = $('#comment').attr('thread');
				arr = thread.split('-');
				function formatDate(date) {
					var currentDate = date.getDate();
					var currentMonth = date.getMonth()+1;
					var currentYear = date.getFullYear();
					var hours = date.getHours();
					var minutes = date.getMinutes();
					var seconds = date.getSeconds();
					minutes = minutes < 10 ? '0'+minutes : minutes;
					var strTime = hours + ':' + minutes + ':' + seconds;
					return  currentDate + "-" + currentMonth + "-" + currentYear + " " + strTime;
				}
				var d = new Date();
				var currentDate = formatDate(d);
				var commentVal = $('#comment').val();
				var push = firebase.database().ref("Chat/"+thread);
				var userRef = push.push();
				var userRef = push.push({
					 receiver_id: arr[0],
					 sender_id: arr[1],
					 sender_name: 'Admin',
					 text: commentVal,
					 timestamp: currentDate,
				});
				$('#comment').val('');
			});
			
			$(document).ready(function() {
	    $('#record-group .sideBar-body:first-child').css({'background':'#f2f2f2'});
	    
	    $('#sendMsg').click(function(){
	        
	        var height = 0;
$('#message-main-receiver .row').each(function(i, value){
    height += parseInt($(this).height());
});

height += '';

$('#message-main-receiver').animate({scrollTop: height});
	       
	    });
	});
	window.onload = function() {
	    setTimeout(function(){ 
	        
	         var height = 0;
$('#message-main-receiver .row').each(function(i, value){
    height += parseInt($(this).height());
});

height += '';

$('#message-main-receiver').animate({scrollTop: height});
	        
	    }, 2000);
 
};
			</script>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
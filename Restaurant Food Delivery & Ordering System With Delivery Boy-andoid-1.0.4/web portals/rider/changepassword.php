<?php if( isset($_SESSION['id']) ){ ?>

<h2 class="title">Change Password</h2>
<div class="form">
	<div class="left col40">
		<?php 
			if( isset($_GET['upd']) ) {

				$user_id = $_SESSION['id'];
				$old_password = htmlspecialchars($_POST['oldpas'], ENT_QUOTES);
				$new_password = htmlspecialchars($_POST['newpas'], ENT_QUOTES);
				$renewpas = htmlspecialchars($_POST['renewpas'], ENT_QUOTES);

				if( !empty($old_password) && !empty($new_password) ) {

					if( $new_password == $renewpas ) { //validate new pass with re new pass

						$headers = array(
							"Accept: application/json",
							"Content-Type: application/json"
						);

						$data = array(
							"user_id" => $user_id,
							"old_password" => $old_password,
							"new_password" => $new_password
						);

						$ch = curl_init( $baseurl.'/changePassword' );

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
							//echo "<div class='alert alert-danger'>Error in updating password, try again later..</div>";
							@header("Location: dashboard.php?p=changepassword&action=error");
	   						echo "<script>window.location='dashboard.php?p=changepassword&action=error'</script>";

						} else {
							//echo "<div class='alert alert-success'>Successfully password updated..</div>";
							@header("Location: dashboard.php?p=changepassword&action=success");
	   						echo "<script>window.location='dashboard.php?p=changepassword&action=success'</script>";
						}

						curl_close($ch);

					} //validate new pass with re new pass = end

				} else {
					@header("Location: dashboard.php?p=address&edit=info&id=".$id."&action=error");
   					echo "<script>window.location='dashboard.php?p=address&edit=info&id=".$id."&action=error'</script>";
				} //

			}
		?>
		<div class="form">
			<form action="dashboard.php?p=changepassword&upd=ok" id="changepass" method="post">
				<p><input type="password" placeholder="Old Password" id="oldpas" name="oldpas"></p>
				<p><input type="password" placeholder="New Password" id="newpas" name="newpas"></p>
				<p><input type="password" placeholder="Re-type New Password" id="renewpas" name="renewpas"></p>
				<p><input type="submit" value="Update Password"></p>
			</form>
		</div>
	</div>
	<div class="right col40">
		
	</div>
	<div class="clear"></div>
</div>

<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
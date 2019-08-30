<?php if( isset($_SESSION['id']) ){ ?>

<h2 class="title">My Account</h2>
<div class="form">
	<div class="left col40">
		<?php 
			if( isset($_GET['upd']) ) {

				$user_id = $_SESSION['id'];
				$first_name = htmlspecialchars($_POST['fname'], ENT_QUOTES);
				$last_name = htmlspecialchars($_POST['lname'], ENT_QUOTES);
				$email = $_SESSION['email'];

				if( !empty($first_name) && !empty($last_name) ) {

					$headers = array(
						"Accept: application/json",
						"Content-Type: application/json"
					);

					$data = array(
						"user_id" => $user_id,
						"first_name" => $first_name,
						"last_name" => $last_name,
						"email" => $email
					);

					$ch = curl_init( $baseurl.'/editUserProfile' );

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
						//echo "<div class='alert alert-danger'>Error in updating account information, try again later..</div>";
						@header("Location: dashboard.php?p=account&action=error");
	   					echo "<script>window.location='dashboard.php?p=account&action=error'</script>";

					} else {
						//echo "<div class='alert alert-success'>Successfully account information updated..</div>";
						$_SESSION['first_name'] = $first_name;
						$_SESSION['last_name'] = $last_name;
						$_SESSION['name'] = $first_name." ".$last_name;
						$_SESSION['email'] = $email;
						@header("Location: dashboard.php?p=account&action=success");
	   					echo "<script>window.location='dashboard.php?p=account&action=success'</script>";
					}

					curl_close($ch);

				} else {
					@header("Location: dashboard.php?p=account&action=error");
	   				echo "<script>window.location='dashboard.php?p=account&action=error'</script>";
				} //

			}
		?>
		<div class="form">
			<form action="dashboard.php?p=account&upd=ok" id="accoutinfo" method="post">
				<p><input type="text" placeholder="First Name" name="fname" id="fname" value="<?php echo $_SESSION['first_name']; ?>"></p>
				<p><input type="text" placeholder="Last Name" name="lname" id="lname" value="<?php echo $_SESSION['last_name']; ?>"></p>
				<p><input type="text" placeholder="Phone Number" name="num" id="num" value="<?php echo $_SESSION['phone']; ?>" readonly></p>
				<p><input type="email" placeholder="Email Address" name="eml" id="eml" value="<?php echo $_SESSION['email']; ?>" readonly></p>
				<p><input type="submit" value="Update Information"></p>
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
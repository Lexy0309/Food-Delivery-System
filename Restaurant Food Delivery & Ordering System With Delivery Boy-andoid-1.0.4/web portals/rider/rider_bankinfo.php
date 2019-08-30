<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "rider" ){ ?>
	
<h2 class="title">Bank Information</h2>
<?php 
if(isset($_GET['add'])) {
	if($_GET['add']=="inf") {

		$name = htmlspecialchars( $_POST['nm'], ENT_QUOTES );
		$user_id = $_SESSION['id'];
		$transit_no = htmlspecialchars( $_POST['trnum'], ENT_QUOTES );
		$bank_no = htmlspecialchars( $_POST['bnkno'], ENT_QUOTES );
		$account_no = htmlspecialchars( $_POST['accno'], ENT_QUOTES );

		if( !empty($name) && !empty($transit_no) && !empty($bank_no) && !empty($account_no) ) { 

			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);

			$data = array(
				"name" => $name,
				"user_id" => $user_id,
				"transit_no" => $transit_no,
				"bank_no" => $bank_no,
				"account_no" => $account_no
			);

			$ch = curl_init( $baseurl.'/addBankingInfo' );

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
				//echo "<div class='alert alert-danger'>".$json_data['msg']."</div>";
				@header("Location: dashboard.php?p=bankinfo&action=error");
				echo "<script>window.location='dashboard.php?p=bankinfo&action=error'</script>";

			} else {
				//echo "<div class='alert alert-success'>".$json_data['msg']."</div>";
				@header("Location: dashboard.php?p=bankinfo&action=success");
				echo "<script>window.location='dashboard.php?p=bankinfo&action=success'</script>";
			}

			curl_close($ch);

		} else {
			@header("Location: dashboard.php?p=bankinfo&action=error");
			echo "<script>window.location='dashboard.php?p=bankinfo&action=error'</script>";
		} //

	} //menu = end
}

	
	$user_id = $_SESSION['id'];

	$data = array(
		"user_id" => $user_id
	);

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$ch = curl_init( $baseurl.'/showBankingInfo' );

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$return = curl_exec($ch);

	$json_data = json_decode($return, true);
    //var_dump($json_data);
    //die;

	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	//echo $json_data['code'];
	//die;

	if($json_data == NULL) {
		//echo "emptyy";

		?>
		<div class="form col50 left">
			<form action="dashboard.php?p=bankinfo&add=inf" id="hotelbnkinffrm" method="post">
				<p><input type="text" name="nm" id="nm" placeholder="Name"></p>
				<p><input type="text" name="trnum" id="trnum" placeholder="Transit Number"></p>
				<p><input type="text" name="bnkno" id="bnkno" placeholder="Bank Number"></p>
				<p><input type="text" name="accno" id="accno" placeholder="Account Number"></p>
				<p><input type="submit" value="Add Bank info" name=""></p>
			</form>
		</div>
		<div class="right col50">
			<img src="img/blankcheque_canadian.svg" alt="" />
		</div>
		<div class="clear"></div>
		<?php

	} else {
			
		if($json_data['code'] !== 200){
			echo "<div class='alert alert-danger'>".$json_data['msg']."</div>";

		} else {
			if( empty($json_data['msg']) ) {
				//echo "empttyyyy";
				?>
				<div class="form col50 left">
					<form action="dashboard.php?p=bankinfo&add=inf" id="hotelbnkinffrm" method="post">
						<p><input type="text" name="nm" id="nm" placeholder="Name"></p>
						<p><input type="text" name="trnum" id="trnum" placeholder="Transit Number"></p>
						<p><input type="text" name="bnkno" id="bnkno" placeholder="Bank Number"></p>
						<p><input type="text" name="accno" id="accno" placeholder="Account Number"></p>
						<p><input type="submit" value="Add Bank info" name=""></p>
					</form>
				</div>
				<div class="right col50">
					<img src="img/blankcheque_canadian.svg" alt="" />
				</div>
				<div class="clear"></div>
				<?php
				//
			} else {
				//echo "not emptyyyy";
				foreach( $json_data['msg'] as $str => $val ) {
					//var_dump($val);
					$bankinfo_id = $val['BankingInfo']['id'];
					
					if(isset($_GET['edit'])) {
						if($_GET['edit']=="inf") {

							$id = $bankinfo_id;
							$name = htmlspecialchars( $_POST['nm'], ENT_QUOTES );
							$user_id = $_SESSION['id'];
							$transit_no = htmlspecialchars( $_POST['trnum'], ENT_QUOTES );
							$bank_no = htmlspecialchars( $_POST['bnkno'], ENT_QUOTES );
							$account_no = htmlspecialchars( $_POST['accno'], ENT_QUOTES );

							if( !empty($name) && !empty($transit_no) && !empty($bank_no) && !empty($account_no) ) { 

								$headers = array(
									"Accept: application/json",
									"Content-Type: application/json"
								);

								$data = array(
									"id" => $id,
									"name" => $name,
									"user_id" => $user_id,
									"transit_no" => $transit_no,
									"bank_no" => $bank_no,
									"account_no" => $account_no
								);

								$ch = curl_init( $baseurl.'/addBankingInfo' );

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
									//echo "<div class='alert alert-danger'>".$json_data['msg']."</div>";
									@header("Location: dashboard.php?p=bankinfo&action=error");
									echo "<script>window.location='dashboard.php?p=bankinfo&action=error'</script>";

								} else {
									//echo "<div class='alert alert-success'>".$json_data['msg']."</div>";
									@header("Location: dashboard.php?p=bankinfo&action=success");
									echo "<script>window.location='dashboard.php?p=bankinfo&action=success'</script>";
								}

								curl_close($ch);

							} else {
								@header("Location: dashboard.php?p=bankinfo&action=error");
								echo "<script>window.location='dashboard.php?p=bankinfo&action=error'</script>";
							} //

						}
					}
					?>
					<div class="form col50 left">
						<form action="dashboard.php?p=bankinfo&edit=inf" id="hotelbnkinffrm" method="post">
							<p><input type="text" name="nm" id="nm" placeholder="Name" value="<?php echo $val['BankingInfo']['name']; ?>"></p>
							<div class="col50 left">
								<p><input type="text" name="trnum" id="trnum" placeholder="Transit Number" value="<?php echo $val['BankingInfo']['transit_no']; ?>"></p>
							</div>
							<div class="col50 right">
								<p><input type="text" name="bnkno" id="bnkno" placeholder="Bank Number" value="<?php echo $val['BankingInfo']['bank_no']; ?>"></p>
							</div>
							<div class="clear" style="height: 15px;"></div>
							<p><input type="text" name="accno" id="accno" placeholder="Account Number" value="<?php echo $val['BankingInfo']['account_no']; ?>"></p>
							<p><input type="submit" value="Update Bank info" name=""></p>
						</form>
					</div>
					<div class="right col50">
						<img src="img/blankcheque_canadian.svg" alt="" />
					</div>
					<div class="clear"></div>
					<?php

				}
			}
			///
		}

		//not null...
	}

	curl_close($ch);
?>


<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
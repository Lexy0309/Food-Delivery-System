<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "hotel" ){ ?>


<div class="left">
	<h2 class="title">Coupon Codes</h2>
</div>
<div class="right">
	<a href="javascript:;" onClick="jQuery('#addcoupon').toggle();" class="filtericon"><i class="fa fa-plus"></i> <span>Add Coupon</span></a>
</div>
<div class="clear"></div>

<?php 
$couponcode = generateRandomString(6);
if( isset($_GET['add']) ) {

	$coupon_code = htmlspecialchars($_POST['coupon_code'], ENT_QUOTES);
	$user_id = $_SESSION['id'];
	$discount = htmlspecialchars($_POST['discount'], ENT_QUOTES);
	$expire_date = $_POST['expire_date'];
	$limit =  htmlspecialchars($_POST['limit'], ENT_QUOTES);
	$type =  $_POST['type'];

	if( !empty($coupon_code) && !empty($discount) && !empty($expire_date) ) { 

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"coupon_code" => $coupon_code,
			"user_id" => $user_id,
			"discount" => $discount,
			"expire_date" => $expire_date,
			"limit_users" => $limit,
			"type"=>$type
		);

		$ch = curl_init( $baseurl.'/addRestaurantCoupon' );

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
			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
			@header("Location: dashboard.php?p=couponcodes&action=error");
				echo "<script>window.location='dashboard.php?p=couponcodes&action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
			@header("Location: dashboard.php?p=couponcodes&action=success");
				echo "<script>window.location='dashboard.php?p=couponcodes&action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: dashboard.php?p=couponcodes&action=error");
		echo "<script>window.location='dashboard.php?p=couponcodes&action=error'</script>";
	} //

}
?>

<div id="addcoupon" class="popup">
	<div class="popup_container col40">
		
		<a href="javascript:;" onClick="jQuery('#addcoupon').hide();" id="close">&times;</a>
		<div class="paddingallsides form">
			<h2 class="title">Add Coupon Code</h2>
			<form action="dashboard.php?p=couponcodes&add=ok" id="couponcdefrm" method="post">
				<p><input type="text" required="" value="<?php echo $couponcode; ?>" name="coupon_code" id="coupon_code" ><label alt="Coupon Code" placeholder=""></p>
                <br/>
				<p><select name="discount" id="discount">
					<option>Select discount %</option>
					<?php for($i=1; $i<=100; $i++) {
						echo "<option value='".$i."'>".$i."%</option>";
					} ?>
				</select></p>
				<p>
				    <select class="form-control" name="type" placeholder="type" required>
                          <option value="">Select Plateform</option> 
                          <option value="web">Web</option> 
                          <option value="ios">iOS</option> 
                          <option value="android">Android</option> 
                    </select>
				</p>
				<p><input type="text" required="" name="expire_date" id="datepicker" readonly>
                <label alt="Expiry Date" placeholder="Expiry Date">
                </p>
				<p><input type="text" required="" name="limit" id="limit">
                <label alt="Coupon Used Limit" placeholder="Coupon Used Limit">
                </p>
				<?php //echo date("Y-m-d"); ?>
				<p><input type="submit" value="Add Coupon Code"></p>
			</form>
		</div>

	</div>
</div>

<?php //remove coupon code
if(isset($_GET['del']) && !empty($_GET['id'])) {
	if($_GET['del']=="cc") {

		$user_id = $_SESSION['id'];
		$coupon_id = $_GET['id'];

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"user_id" => $user_id,
			"coupon_id" => $coupon_id
		);

		$ch = curl_init( $baseurl.'/deleteRestaurantCoupon' );

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
			//echo "<div class='alert alert-danger'>Error in adding coupon code, try again later..</div>";
			@header("Location: dashboard.php?p=couponcodes&action=error");
				echo "<script>window.location='dashboard.php?p=couponcodes&action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
			@header("Location: dashboard.php?p=couponcodes&action=success");
				echo "<script>window.location='dashboard.php?p=couponcodes&action=success'</script>";
		}

		curl_close($ch);

	}
}
//remove coupon code = end ?>

<div class="form">
	<?php 
		$user_id = $_SESSION['id'];

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"user_id" => $user_id
		);

		$ch = curl_init( $baseurl.'/showRestaurantCoupons' );

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
			echo "<div class='alert alert-danger'>Error in fetching payment method, try again later..</div>";
			//@header("Location: dashboard.php?p=payment");

		} else {
			//echo "<div class='alert alert-success'>Successfully payment method updated..</div>";
			//@header("Location: dashboard.php?p=payment");
			echo '<div class="paymentlist couponlist">';
			foreach( $json_data['msg'] as $stttr => $vaaal ) {
				//var_dump($vaaal);
				$cc_id = $vaaal['RestaurantCoupon']['id']; 
				?>
				<div class="cl3 left col30" style="margin-right: 10px;">
					<div class="itm"> 
						<div class="cardnm left col60"><strong>Code:</strong> <?php echo $vaaal['RestaurantCoupon']['coupon_code']; ?>
						<br> 
						<strong>Expiry:</strong> <?php echo $vaaal['RestaurantCoupon']['expire_date']; ?>
						<strong>Limit:</strong> <?php echo $vaaal['RestaurantCoupon']['limit_users']; ?>
						<strong>Plateform:</strong> <?php echo $vaaal['RestaurantCoupon']['type']; ?>
						</div>
						<div class="cardtype right col40 textright"><strong>Discount</strong><br> <?php echo $vaaal['RestaurantCoupon']['discount']; ?> %</div> 
						<div class="clear" style="margin: 0;"></div>
					</div>
				</div>
				<?php
			} //
			echo '<div class="clear"></div></div>';
		}

		curl_close($ch);
	?>
	<div class="clear"></div>
</div>

<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
<?php if( isset($_SESSION['id']) ){ ?>


<div class="left">
	<h2 class="title">Documents</h2>
</div>
<div class="right">
	<a href="javascript:;" onClick="jQuery('#adddoc').toggle();" class="filtericon"><i class="fa fa-plus"></i> <span>Add Document</span></a>
</div>
<div class="clear"></div>

<?php 
if( isset($_GET['add']) ) {
	
	$user_id = $_SESSION['id'];
	$description = htmlspecialchars($_POST['docdesc'], ENT_QUOTES);

	if( !empty($description) ) {
	   	 
	    $bin_string = file_get_contents($_FILES['docfile']['tmp_name']);
	    $hex_string = base64_encode($bin_string);
	 
	   	$headers = array(
			"Accept: application/json",
			"Content-Type: application/json"
		);

		$data = array(
			"user_id" => $user_id,
			"description" => $description,
			"image" => array("file_data" => $hex_string)
		);
	   
		$ch = curl_init( $baseurl.'/addUserDocument' );

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
			@header("Location: dashboard.php?p=doc&action=error");
				echo "<script>window.location='dashboard.php?p=doc&action=error'</script>";

		} else {
			//echo "<div class='alert alert-success'>Successfully coupon code added..</div>";
			@header("Location: dashboard.php?p=doc&action=success");
				echo "<script>window.location='dashboard.php?p=doc&action=success'</script>";
		}

		curl_close($ch);

	} else {
		@header("Location: dashboard.php?p=doc&action=error");
		echo "<script>window.location='dashboard.php?p=doc&action=error'</script>";
	} //

}
?>

<div id="adddoc" class="popup">
	<div class="popup_container col40">
		
		<a href="javascript:;" onClick="jQuery('#adddoc').hide();" id="close">&times;</a>
		<div class="paddingallsides form">
			<h2 class="title">Add Document</h2>
			<form action="dashboard.php?p=doc&add=ok" id="docfrm" method="post" enctype="multipart/form-data">
				<p><input type="text" name="docdesc" id="docdesc" placeholder="Description" /></p>
				<p><input type="file" name="docfile" id="docfile" /></p>
				<p><input type="submit" value="Add Document"></p>
			</form>
		</div>

	</div>
</div>

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

		$ch = curl_init( $baseurl.'/showUserDocuments' );

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
			echo '<div class="documentlist">';
			foreach( $json_data['msg'] as $stttr => $vaaal ) {
				//var_dump($vaaal);
				if(!empty($vaaal['VerificationDocument']['file'])){
					$ff = $image_baseurl.$vaaal['VerificationDocument']['file'];
				} else {
					$ff = "./img/placeholder.svg";
				}
				$ds = $vaaal['VerificationDocument']['description'];

				$st = $vaaal['VerificationDocument']['status'];
				if( $st == "0" ) {
					$status = "Pending";
				} else if( $st == "1" ) {
					$status = "Approved";
				} else if( $st == "2" ) {
					$status = "Rejected";
				} else {
					$status = "";
				}

				?>
				<div class="cl3 img-caption" data-lightbox="<?php echo $ff; ?>" style=" background: #F9F9F9; border-color: #F9F9F9; cursor:pointer;">
					<div class="itm bgimage" style="background-image: url(<?php echo $ff; ?>);"></div>
					<div class="description">
						<?php if( strlen($ds) > 100 ) { echo substr($ds, 0, 100)." ..."; } else { echo $ds; } ?>
						<span class="status">(<?php echo $status; ?>)</span>
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

<div class="popup pp_overlay" id="ppimg_box">
	<div class="popup_container textcenter pp_img">
		<a href="javascript:;" onClick="jQuery('#ppimg_box').hide();" id="close">&times;</a>
		<img src="" alt="" class="img_zoom" />
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('.img-caption').click(function(e){
        var imgurl = jQuery(this).attr('data-lightbox');
        jQuery(".popup_container .img_zoom").attr("src", imgurl);
        jQuery('.pp_overlay').fadeIn();
        e.preventDefault();
    });

});    
</script>


<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
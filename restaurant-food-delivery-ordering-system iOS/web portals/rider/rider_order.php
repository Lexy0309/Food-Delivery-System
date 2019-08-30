<?php if( isset($_SESSION['id']) && $_SESSION['user_type'] == "rider" ){ ?>

<div class="left">
	<h2 class="title">Summary</h2>
</div>
<div class="right">
	<a href="javascript:;" onClick="jQuery('#filtertoggle').toggle();" class="filtericon"><i class="fa fa-filter"></i> <span>Filter</span></a>
</div>
<div class="clear"></div>
<div id="filtertoggle" class="popup">
	<div class="popup_container">
		
		<a href="javascript:;" onClick="jQuery('#filtertoggle').hide();" id="close">&times;</a>
		<div class="paddingallsides">
			<h2 class="title">Filter Search</h2>
			<form action="dashboard.php" id="get">
				<input type="hidden" name="p" value="summary" /><input type="hidden" name="filter" value="search" />
				<p><input type="text" name="start_date" placeholder="Start Date" id="datepicker" name="" /></p>
				<p><input type="text" name="end_date" placeholder="End Date" id="datepicker2" name="" /></p>
				<p><input type="submit" value="Filter Search" /></p>
			</form>
		</div>

	</div>
</div>

<?php 
	if( isset($_GET['filter']) && !empty($_GET['start_date']) && !empty($_GET['end_date']) ) {

		$user_id = $_SESSION['id'];
		$starting_date = $_GET['start_date'];
		$ending_date = $_GET['end_date']; 

		$data = array(
			"user_id" => $user_id,
			"starting_date" => $starting_date,
			"ending_date" => $ending_date
		);

	} //filter = end
	else {
	
		$user_id = $_SESSION['id'];

		$data = array(
			"user_id" => $user_id
		);

	} //no filter = end

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$ch = curl_init( $baseurl.'/showRiderCompletedOrders' );

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
	
	//print_r($json_data);
	//echo $json_data['code'];
	//die;

	if($json_data['code'] != "200")
	{
		//echo "<div class='alert alert-danger'>".$json_data['msg']."</div>";
		?>
		<div class="textcenter nothingelse">
			<img src="img/noorder.png" alt="" />
			<h3>Whoops!</h3>
		</div>
		<?php

	} 
	else 
	{
		?>
		<script>
        jQuery(document).ready(function(){
        	jQuery('#myTable_row').pageMe({pagerSelector:'#myPager', showPrevNext:true, hidePageNumbers:false, perPage:20});
        });
        </script>
		<?php $rows = count($json_data['msg']);
			if( $rows == 0 ) 
			{
				?>
				<div class="textcenter nothingelse">
					<img src="img/noorder.png" alt="" />
					<h3>Whoops!</h3>
				</div>
				<?php
				die();
			}
		echo "<table class='order_table' border='0' cellpadding='0' cellspacing='0' id='myTable'>
		<thead>
		<tr>
			<td><strong>Order No</strong></td>
			<td><strong>Restaurant Name</strong></td>
			<td><strong>Order Price</strong></td>
			<td><strong>Rider Tip</strong></td>
			<td><strong>Feedback</strong></td>
			<td><strong>Order Date</strong></td>
		</tr>
		</thead>
		<tbody id='myTable_row'>
		";
		foreach( $json_data['msg'] as $str => $val ) {
			//var_dump($val);
			$currency = $val['Order']['Restaurant']['Currency']['symbol'];
			?>
			<tr>
				<td>#<?php echo $val['Order']['id']; ?></td>
				<td><?php echo $val['Order']['Restaurant']['name']; ?></td>
				<td><?php echo "<span class='blok' style='margin:0; color:green; font-weight:500;'>".$currency.' '.$val['Order']['price']."</span>"; ?></td>
				<td><?php echo "<span class='blok' style='margin:0; color:green; font-weight:500;'>".$currency.' '.$val['Order']['rider_tip']."</span>"; ?></td>
				<td title="<?php echo $val['RiderRating']['comment']; ?>">
					<div class="rate">
						<?php
							if($val['RiderRating']['star']!="")
							{
								$star=5-$val['RiderRating']['star'];
								for($i=0; $i<$val['RiderRating']['star'];$i++)
								{
									?>
										<i class="fa fa-star" style="color:#BE2C2C;"></i>
									<?php
								}
								if($star>0)
								{
									for($i=0; $i<$star; $i++)
									{
										?>
											<i class="fa fa-star-o" style="color:#BE2C2C;"></i>
										<?php
									}
								}
							}
							else
							{
								echo"pending";
							}
							
							
						?>
						
					</div>
					
				</td>
				<td><?php echo convertDateTimetoFullMonthAndDayNameWithYear($val['Order']['created']); ?></td>
			</tr>
			<?php
			
		}
		echo "</tbody>
		</table> <nav><ul class='pagination pagination-sm' id='myPager'></ul></nav>";
		///
	}

	curl_close($ch);
?>

<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
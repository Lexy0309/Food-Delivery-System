<?php
require_once("config.php");

	if(isset($_GET['userid'])){

	$resid = $_GET['userid'];?>
        <table class="display nowrap table table-hover table-striped table-bordered" id="table-11">
            <thead>
                <tr>
				 	<th>ID</th>
                    <th>Coupon Code</th>
                    <th>Discount</th>
                    <th>Expire Date</th>
                    <th>Limit Users</th>
                    <td>Used</td>
                </tr>    
			</thead>
			<tbody>
					 
				<?php 
				
				
	$user_id = $resid;

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$data = array(
		"user_id" => $user_id
	);
	//$data;
	$ch = curl_init( $baseurl.'/showRestaurantCoupons' );			
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$return = curl_exec($ch);
	$json_data = json_decode($return, true);
	$curl_error = curl_error($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// var_dump($json_data);
			foreach($json_data['msg'] as $str => $rd) { 
			if(!empty($rd['RestaurantCoupon']['id'])) {?>
				<tr>
				<td><?php echo $rd['RestaurantCoupon']['id'];?></td>
				<td><?php echo $rd['RestaurantCoupon']['coupon_code'];?></td>
				<td><?php echo $rd['RestaurantCoupon']['discount'];?></td>
				<td><?php echo $rd['RestaurantCoupon']['expire_date'];?></td>
				<td><?php echo $rd['RestaurantCoupon']['limit_users'];?></td>
                <td><?php echo count($rd['CouponUsed']);?></td>
				 </tr>
                   
					<?php
						
						}
				}
                     ?>   
               
            </body>
			</table>
			 
        <script type="text/javascript">
       		
	    $('#table-11').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
        </script>
			<?php
}
else  if(isset($_GET['resid'])){
	
	echo $resid=$_GET['resid'];
	
	?>
	
	<form 
	
<?php	
}
else
{
	
}
			?>
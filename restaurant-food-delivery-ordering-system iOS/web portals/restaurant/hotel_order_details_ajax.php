<?php 
require_once("config.php");
if( isset($_GET['uid']) && isset($_GET['orderID']))
{ 
	
	if( isset($_GET['orderID']) ) 
	{ //details page 
	
			$order_id = $_GET['orderID'];
			$user_id = $_GET['uid'];
	
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);
	
			$data = array(
				"order_id" => $order_id,
				"user_id" => $user_id
			);
	
			$ch = curl_init( $baseurl.'/showOrderDetail' );
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
	
				echo "Error in fetching content";
	
			} else {
	
				foreach( $json_data['msg'] as $str => $val ) {
	
					//var_dump($val);
					$hotel_accepted = $val['Order']['hotel_accepted'];
					$currency=$val['Restaurant']['Currency']['symbol'];
					$tax=$val['Restaurant']['Tax']['tax'];
					?>
	
					<div class="orderinformation">
						
						<h1 align="center" style="margin: 0px 0 20px 0;">Order #<?php echo $order_id; ?></h1>
						<div class="sect">
	
							<h3>Buyer Details</h3>
	
							<p><i class="fa fa-user"></i> <?php echo $val['UserInfo']['first_name']." ".$val['UserInfo']['last_name']; ?></p>
	
							<p><i class="fa fa-phone"></i> <?php echo $val['UserInfo']['phone']; ?></p>
	
							<p><i class="fa fa-map-marker"></i> <?php echo $val['Address']['street']." ".$val['Address']['apartment'].", ".$val['Address']['city'].", ".$val['Address']['country']; ?></p>
	
						</div>
						
						<?php if( isset($val['RiderOrder']['Rider']) ) { ?>
	
						<div class="sect">
	
							<h3>Rider Details</h3>
	
							<p><i class="fa fa-user"></i> <?php echo $val['RiderOrder']['Rider']['first_name']." ".$val['RiderOrder']['Rider']['last_name']; ?></p>
	
							<p><i class="fa fa-phone"></i> <?php echo $val['RiderOrder']['Rider']['phone']; ?></p>
	
						</div>
						
						<?php } ?>
	
						<div class="sect">
	
							<h3>Restaurant Details</h3>
	
							<p><i class="fa fa-adjust"></i> <?php echo $val['Restaurant']['name']; ?></p>
	
							<p><i class="fa fa-map-marker"></i> <?php 
	
								$stret = $val['Restaurant']['RestaurantLocation']['street'];
	
								if( !empty($stret) ) {
	
									echo $stret.", ";
	
								}
	
								$city = $val['Restaurant']['RestaurantLocation']['city'];
	
								if( !empty($city) ) {
	
									echo $city.", ";
	
								}
	
								$country = $val['Restaurant']['RestaurantLocation']['country'];
	
								if( !empty($country) ) {
	
									echo $country;
	
								}
	
							?></p>
	
						</div>
	
						<div class="sect">
	
							<h3>Instructions</h3>
	
							<p style=" color:#be2c2c;"><i class="fa fa-exclamation-circle"></i> <?php echo $val['Order']['instructions']; ?></p>
	
						</div>
	
	
						<div class="sect">
	
							<div class="menutable_div">
	
								<table width="100%" class="menutable" cellpadding="0" cellspacing="0">
	
									<tr height="50px">
	
										<td><h4>Menu Item</h4></td>
	
										<td width="100" class="textcenter"><h4>Qty.</h4></td>
	
										<td width="100" class="textcenter"><h4>Price</h4></td>
	
									</tr>
								   
									<?php 
	
										foreach ($val['OrderMenuItem'] as $key => $value) {
	
											//var_dump($value);
	
											?>
	
											<tr bgcolor="#efefef">
	
												<td style="padding: 15px 15px;"><p style="margin:0 0 5px;"><strong><?php echo $value['quantity']."X &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo $value['name']; ?></strong></p></td>
	
												<td style="padding: 15px 15px;" class="textcenter"><?php echo $value['quantity']; ?></td>
	
												<td style="padding: 15px 15px;" class="textcenter"><?php echo $currency; echo $value['price']; ?></td>
	
											</tr>
	
											<?php
	
	
	
												if( count($value['OrderMenuExtraItem']) > 0 ) {
	
													foreach ($value['OrderMenuExtraItem'] as $key11 => $value11) {
	
														//var_dump($value11);
	
														echo "<tr height='0px'>
																<td colspan='3' style='padding:15px 15px 15px;'>
																	<span style='display:inline-block;padding-bottom:5px;border-bottom:0.5px solid #ccc;'>".
																		$value11['name']; ?> &nbsp;&nbsp; + 
																		<?php echo $currency; 
																		echo $value11['price']."
																	</span>
																</td>
															</tr>";
	
													} 
	
												}
	
	
	
										}
	
									?>
	
								</table>
	
							</div>
	
	
	
							<hr>
	
							<table width="100%" class="totalcad_table" cellpadding="0" cellspacing="0">
	
								
								<tr height="30px">
	
									<td style="padding:5px 0;"><strong>Tax <span style="color:grey; font-size:12px;">(<?php echo $tax; ?>%)</span></strong></td>
	
									<td style="padding:5px 0;" width="200" class="textright">&nbsp;</td>
	
									<td style="padding:5px 0;" width="200" class="textright"><?php echo $currency; echo $val['Order']['tax'];?>  </td>
	
								</tr>
	
		
								<tr height="30px">
	
									<td style="padding:5px 0 0;"><strong>Payment Method</strong></td>
	
									<td style="padding:5px 0 0;" width="200" class="textright">&nbsp;</td>
	
									<td style="padding:5px 0 0;" width="200" class="textright">
										<?php 
										
											if( $val['Order']['payment_method_id'] != "0" )
											{
			
												echo "Credit Card";
			
											}
											else 
											if( $val['Order']['payment_method_id'] == "0" )
											{
			
												echo "Cash on Delivery (COD)";
			
											} 
										?>
									</td>
	
								</tr>
								
								<tr height="40px">
	
									<td style="padding:5px 0;"><strong>Total <span style="color:grey; font-size:12px;">(You Earned)</span></strong></td>
	
									<td style="padding:5px 0;" width="200" class="textright">&nbsp;</td>
	
									<td style="padding:5px 0;" width="200" class="textright"><strong><?php echo $currency; echo $val['Order']['sub_total']; ?></strong></td>
	
								</tr>
	
								
								
	
							</table>
	
						</div>
	
					</div>
                    
                    
                    <div class="buttonsgroup">
                      <ul>
                        <?php
							if($val['Order']['hotel_accepted']=="0")
							{
								?>
									<li>
                                      <a href="javascript:;" onClick="jQuery('#adddeals2').toggle();" class="filtericon acceptb"><span>Accept</span></a>
                                    </li>
                                    <li>
                                      <a href="javascript:;" onClick="jQuery('#reg').toggle();" class="filtericon acceptb"><span style="background: gray;">Reject</span></a>
                                    </li>
								<?php
							}
						?>
                    	
                      </ul>
                      
                      <div id="adddeals2" class="popup">
                        <div class="popup_container col40"> <a href="javascript:;" onClick="jQuery('#adddeals2').hide();" id="close">&times;</a>
                          <div class="paddingallsides form">
                            <h2 class="title" style="text-align:center">Rider Instructions</h2>
                            <form action="dashboard.php?p=hotel_order&detail=<?php echo $_GET['orderID']; ?>&order=accept" id="hoteldealsfrmd" method="post">
                              <p>
                                <textarea class="textarea" name="reason" id="dsc" placeholder="description"></textarea>
                              </p>
                              <p>
                                <input type="submit" value="Submit" name="">
                              </p>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
					
                    <div id="reg" class="popup">
                        <div class="popup_container col40"> <a href="javascript:;" onClick="jQuery('#reg').hide();" id="close">&times;</a>
                          <div class="paddingallsides form">
                            <h2 class="title" style="text-align:center">Reject Reason</h2>
                            <form action="dashboard.php?p=hotel_order&detail=<?php echo $_GET['orderID']; ?>&order=reject" id="hoteldealsfrmd" method="post">
                              <p>
                                <textarea class="textarea" name="reason" id="dsc" placeholder="description"></textarea>
                              </p>
                              <p>
                                <input type="submit" value="Submit" name="">
                              </p>
                            </form>
                          </div>
                        </div>
                  	</div>
					<?php
	
				}
	
				///
	
			}
	
	
	
			curl_close($ch); 
	
	
	
	 } //details page = end
	  
}
else 
if( isset($_SESSION['id']) && isset($_GET['orderID_notificaiton']))
{
	$user_id = $_SESSION['id'];
	$status = "1";
	$data = array(
		"user_id" => $user_id,
		"status" => $status
	);
	
	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	);

	$ch = curl_init( $baseurl.'/showRestaurantOrders' );
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
	
	//$json_data['msg'];
	//die;
	
	$orderCount=count($json_data['msg']);
	$pastCount=$_SESSION['orderCount'];
	
	$_SESSION['orderCount'] = $orderCount;
	if($orderCount!=$pastCount)
	{
		?>
            <audio controls autoplay>
              <source src="horse.ogg" type="audio/ogg">
              <source src="img/noti.mp3" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio> 
		<?php
	}
	else
	{
		echo "not found";
	}
	
	
	/*$count="0";
	foreach( $json_data['msg'] as $str => $val ) {
	
			//var_dump($val);

			foreach ($val['OrderMenuItem'] as $key => $value) {

				//var_dump($value);

				if($key==0) {
					$count+=1;
				}
			}
	}
	echo $count;*/
	
}
else
{

	echo"Error in getting content";

} 

?>
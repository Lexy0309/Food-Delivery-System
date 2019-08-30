<?php
@require_once("config.php");
date_default_timezone_set('Asia/Karachi');

if(@$_GET['checkOrder']=="ok") 
{

	$user_id = @$_SESSION['id'];
    $headers2 = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );

    $data2 = array(
                  "status" => "1"
                );

    $ch2 = curl_init( $baseurl.'/showAllOrdersAutoLoad');

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data2));
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);

    $return2 = curl_exec($ch2);

    $json_data2 = json_decode($return2, true);
    //var_dump($json_data2);

    $curl_error2 = curl_error($ch2);
    $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

    //echo $json_data['code'];
    //die;

    if($json_data2['code'] !== 200) 
    {
    	echo "Something went wrong";
    } 
    else 
    {
        $lastOrder=$json_data2['msg'][0]['Order']['id'];
        
        //$afterExplode=explode(" " ,convertintotime($lastOrder));
        //echo $afterExplode[1];

       
        $oldOrder=@$_SESSION['currentOrder'];
        $newOrder=$lastOrder; 

        if($oldOrder < $newOrder)
        {
            echo "<a href='orders.php?OrderID=$newOrder&reload=ok' class='edituser btn btn-default btn-sm' style='color:white !important; background:#be2c2c;'>New Order Received <span style='background:white; color:#be2c2c; padding:2px 5px; border-radius:15px;'>1</span></a>";
            //$_SESSION['currentOrder'] = $lastOrder;
            ?>
                <audio controls autoplay style="display: none;">
                  <source src="assets/noti.mp3" type="audio/ogg">
                  <source src="assets/noti.mp3" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio> 
            <?php
        }
        else
        {
            echo "<a href='orders.php?OrderID=$newOrder&reload=ok' class='edituser btn btn-default btn-sm'>No New Order Received</a>";
            
        }  
        
    }

    curl_close($ch2);

}
else
if(@$_GET['update']=="index") 
{
    

    $menuID = @$_GET['menuID'];
    $index = @$_GET['index'];

    $headers2 = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );

    $data2 = '{"menu":[{"menu_id": '.$menuID.',"index":'.$index.'}]}';

    $ch2 = curl_init( $baseurl.'/editMainMenuIndex');

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $data2);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);

    $return2 = curl_exec($ch2);

    $json_data2 = json_decode($return2, true);
    //var_dump($json_data2);

    $curl_error2 = curl_error($ch2);
    $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

    //echo $json_data['code'];
    //die;

    if($json_data2['code'] !== 200) 
    {
        echo "Something went wrong";
    } 
    else 
    {
        ?>
            <input type='button' value="Done" style="width: 50px; padding: 5px; margin: 2px;" onclick="updateindex(<?php echo $menuID; ?>)">
        <?php
    }

    curl_close($ch2);
}
else
if(@$_GET['getHotelDetails']=="ok") 
{
    

    $id = @$_GET['resID'];

    $headers2 = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );

    $data2 = '{"id":"'.$id.'"}';

    $ch2 = curl_init( $baseurl.'/showRestaurantDetail');

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $data2);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);

    $return2 = curl_exec($ch2);

    $json_data2 = json_decode($return2, true);
    //var_dump($json_data2);

    $curl_error2 = curl_error($ch2);
    $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

    //echo $json_data['code'];
    //die;

    if($json_data2['code'] !== 200) 
    {
        echo "Something went wrong";
    } 
    else 
    {   
        ?>
            <div class="row">

                <script type="text/javascript">
        
                    var table = $('#table-1').DataTable();
                    // column order
                     table
                      .column( '0:visible' )
                      .order( 'desc' )
                      .draw();
            
                </script>

                <?php
                    foreach($json_data2['msg'] as $key => $data) 
                    {
                        
                                    foreach($data as $key1 => $data1) 
                                    {
                                           
                                           ?>
                                           <h3>
                                               <?php  echo $key1; ?>
                                           </h3>
                                           <table class="display nowrap table table-hover table-striped table-bordered"  id="table-1">
                                               
                                                <thead style="font-size: 12px;">
                                                    <tr>
                                                        <?php
                                                            foreach($data1 as $item=>$list){
                                                                ?>
                                                                    <th><?php echo $item;?></th>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="font-size: 12px;">
                                                    <tr>
                                                        <?php
                                                            foreach($data1 as $item=>$list){
                                                                ?>
                                                                    <td>
																		<?php 
																			//echo $list; 
																			if(count($list)=="5")
																			{
																				//echo "extra data";
																				//print_r($list);
																				echo $list['day'];
																				echo"<br> <b>Starting Time</b> </br>";
																				echo $list['opening_time'];
																				echo"<br> <b>Ending Time</b> </br>";
																				echo $list['closing_time'];
																			}
																			else
																			{
																				echo $list; 
																			}
																		?>
																	</td>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tr> 
                                                </tbody>

                                            </table>   
                                          <?php
                                       
                                        
                                    }
                                
                    }   
                ?>

            </div>    
        <?php
        //print_r($json_data2);
    }

    curl_close($ch2);
}
else
if(@$_GET['editHotelDetails']=="ok") 
{
    

    $id = @$_GET['resID'];

    $headers2 = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );

    $data2 = '{"id":"'.$id.'"}';

    $ch2 = curl_init( $baseurl.'/showRestaurantDetail');

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $data2);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);

    $return2 = curl_exec($ch2);

    $json_data2 = json_decode($return2, true);
    //var_dump($json_data2);

    $curl_error2 = curl_error($ch2);
    $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

    //echo $json_data['code'];
    //die;

    if($json_data2['code'] !== 200) 
    {
        echo "Something went wrong";
    } 
    else 
    {   
        ?>
            <div class="row">

                <script type="text/javascript">
        
                    var table = $('#table-1').DataTable();
                    // column order
                     table
                      .column( '0:visible' )
                      .order( 'desc' )
                      .draw();
            
                </script>

                <?php
                    foreach($json_data2['msg'] as $key => $data) 
                    {
                        
                        ?>

                            <form role="form" method="post" action="restaurants.php?editRestaurant=ok&restaurant_id=<?php echo $id; ?>" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
        
                                    <div class="row" style="margin: 0 auto; width: 50%;">
										<div class="col-sm-12">
										
											<div class="col-sm-6">
												<div class="fileinput fileinput-new" data-provides="fileinput">
													<div class="fileinput-new thumbnail" style="width: auto; height: auto; border-radius: 100%; " data-trigger="fileinput">
														<img src="<?php echo $image_baseurl.$data['Restaurant']['image']; ?>" alt="preview" style="border-radius: 100%; width: 90px; height: 90px;">
													</div>
													<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
													<div>
														<span class="btn btn-white btn-file">
															<span class="fileinput-new">Select Logo</span>
															<span class="fileinput-exists">Change</span>
															<input type="file" name="upload_image" accept="image/*">
														</span>
														<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
													</div>
												</div>
											</div>
												
											<div class="col-sm-6">
												 <div class="form-group">
													
														<div class="col-sm-6">
															<div class="fileinput fileinput-new" data-provides="fileinput">
																<div class="fileinput-new thumbnail" style="width: auto; height: auto; border-radius: 100%;" data-trigger="fileinput">
																	<img src="<?php echo $image_baseurl.$data['Restaurant']['cover_image']; ?>" alt="preview" style="border-radius: 100%; width:90px; height:90px;">
																</div>
																<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
																<div>
																	<span class="btn btn-white btn-file">
																		<span class="fileinput-new">Select Cover image</span>
																		<span class="fileinput-exists">Change</span>
																		<input type="file" name="Cover_upload_image" accept="image/*">
																	</span>
																	<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
																</div>
															</div>
													   </div>
												   
												 </div> 
											</div>
												
										</div> 
									</div>
                                            
                                            
                                            
                                            
                                            
                                            
                                    <div class="row" style="margin: 0 auto; width: 50%;">
                                        <div>
                                            <div class="">

                                                <div class="form-group width">
                                                    <label for="field-1" class="control-label">Name</label>
                                                     <input type="text" class="form-control" name="name" value="<?php echo $data['Restaurant']['name']; ?>" placeholder="Name">
                                                </div>
                                            </div>

                                            <div class="">    
                                            
                                                <div class="form-group width">
                                                    <label for="field-1" class=" control-label">Slogan</label>
                                                  <input type="text" class="form-control" name="slogan" value="<?php echo $data['Restaurant']['slogan']; ?>" placeholder="Slogan">
                                                </div>
                                            </div>

                                            <div class="">    
                                                <div class="form-group width">
                                                    <label for="field-1" class="control-label">About</label>
                                                        <textarea class="form-control" name="about" rows="3" placeholder="About"><?php echo $data['Restaurant']['about']; ?></textarea>
                                                    </div>
                                                </div> 
                                            </div> 
                                        </div> 

                                    <div class="row" style="margin: 0 auto; width: 50%;">
                                    <div>
                                    <div class="">    
                                      <div class="form-group width">
                                                <label for="field-1" class="control-label"> Speciality </label>
                                                    
                                                     <input type="text" class="form-control" name="speciality" value="<?php echo $data['Restaurant']['speciality']; ?>" placeholder="speciality">
                                                </div>
                                            
                                    </div>     
                                          
                                          
                                            
                                            <?php /*<div class="form-group">
                                                <label for="field-1" class="col-sm-2 control-label">Delivery Fee</label>
                                                
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" data-mask="fdecimal" name="delivery_fee" placeholder="Delivery Fee" data-dec="," data-rad="." maxlength="10">
                                                </div>
                                            </div>*/ ?>
                                            
                                    <div class="">
                                            
                                            <div class="form-group width">
                                                <label for="field-1" class=" control-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo $data['Restaurant']['phone']; ?>" data-mask="phone">
                                            </div>
                                            </div>
                                            
                                        <div class="">
                                            <div class="form-group width">
                                                <label for="field-1" class="control-label">Timezone</label>
                                                
                                              
                                                    <select name="timezone" class="form-control">
                                                        <option value="+05:00">Pakistan (UTC+05:00)</option>
                                                   </select>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    </div>
                                               
                                            
                                    <div class="row" style="margin: 0 auto; width: 50%;">
                                    <div>
                                    <div>
                                     <div class="form-group width">
                                                <label for="field-1" class=" control-label">State</label>
                                                    <?php /*<input type="text" class="form-control" name="state" placeholder="State">*/ ?>
                                                    <select name="state" class="form-control">
                                                        <option value="">Select state</option> 
                                                        <?php 
                                                        $url = $baseurl."/showCountries";
                                                        $params = "";
                                                        $ch = curl_init($url);
                                                        curl_setopt($ch, CURLOPT_POST, 1);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        $result = curl_exec($ch);
                                                        $json_data = json_decode($result, true);
                                                        //var_dump($json_data);
                                                        foreach($json_data['states'] as $cntry) {
                                                            if($data['Tax']['state']==$cntry['Tax']['state'])
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Tax']['state']; ?>" selected>
                                                                        <?php echo $cntry['Tax']['state']; ?>
                                                                    </option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Tax']['state']; ?>">
                                                                        <?php echo $cntry['Tax']['state']; ?>
                                                                    </option>
                                                                <?php
                                                            }    
                                                        }
                                                        curl_close($ch);
                                                        ?>
                                                    </select>
                                                 </div>
                                            </div>
                                            <div class="">
                                             <div class="form-group width">
                                                <label for="field-1" class="control-label">Menu Style</label>
                                                    <select name="menu_style" class="form-control">
                                                        <option value="1">Style 1</option>
                                                        <option value="2">Style 2</option>
                                                        <option value="3">Style 3</option>
                                                        <option value="4">Style 4</option>
                                                    </select>
                                            </div>
                                            </div>
                                            
                                            <div class="">
                                            <div class="form-group width">
                                                <label for="field-1" class="control-label">Promoted</label>
                                                    <select name="promoted" class="form-control">
                                                        <option value="0" <?php if($data['Restaurant']['promoted']=="0"){ echo"selected";}?>>No</option>
                                                        <option value="1" <?php if($data['Restaurant']['promoted']=="1"){ echo"selected";}?>>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                              </div>
                                            </div>
                                         
                                    <div class="row" style="margin: 0 auto; width: 50%;">
                                    <div class="">
                                    <div class="">
                                     <div class="form-group width">
                                                <label for="field-1" class="control-label">City</label>
                                                    <?php /*<input type="text" class="form-control" name="city" placeholder="City">*/ ?>
                                                    <select name="city" class="form-control">
                                                        <option value="">Select city</option> 
                                                        <?php 
                                                        $json_data = json_decode($result, true);
                                                        //var_dump($json_data);
                                                        foreach($json_data['cities'] as $cntry) {
                                                            if($data['RestaurantLocation']['city']==$cntry['Tax']['city'])
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Tax']['city']; ?>" selected>
                                                                        <?php echo $cntry['Tax']['city']; ?>
                                                                    </option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                 ?>
                                                                    <option value="<?php echo $cntry['Tax']['city']; ?>">
                                                                        <?php echo $cntry['Tax']['city']; ?>
                                                                    </option>
                                                                <?php
                                                            }    
                                                        }
                                                        curl_close($ch);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                    <div class="">      
                                          <div class="form-group width">
                                                <label for="field-1" class="control-label">Country</label>
                                                    <select name="country" class="form-control">
                                                        <option value="">Select country</option> 
                                                        <?php 
                                                        
                                                        $json_data = json_decode($result, true);
                                                        //var_dump($json_data);
                                                        foreach($json_data['countries'] as $cntry) {
                                                            
                                                            if($data['Tax']['country']==$cntry['Tax']['country'])
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Tax']['country']; ?>" selected>
                                                                        <?php echo $cntry['Tax']['country']; ?>
                                                                    </option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                 ?>
                                                                    <option value="<?php echo $cntry['Tax']['country']; ?>">
                                                                        <?php echo $cntry['Tax']['country']; ?>
                                                                    </option>
                                                                <?php 
                                                            }    
                                                        }
                                                        curl_close($ch);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class=""> 
                                             <div class="form-group width">
                                                <label for="field-1" class="control-label">Currency</label>
                                                    <select name="currency_id" class="form-control">
                                                        <option value="">Select Currency</option> 
                                                        <?php 
                                                        $url = $baseurl."/showCurrencies";
                                                        $params = "";
                                                        $ch = curl_init($url);
                                                        curl_setopt($ch, CURLOPT_POST, 1);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        $result = curl_exec($ch);
                                                        $json_data = json_decode($result, true);
                                                        //var_dump($json_data);
                                                        foreach($json_data['msg'] as $cntry) {
                                                            if($data['Currency']['id']==$cntry['Currency']['id'])
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Currency']['id']; ?>" selected>
                                                                        <?php echo $cntry['Currency']['code']; ?>
                                                                    </option>
                                                                <?php
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <option value="<?php echo $cntry['Currency']['id']; ?>">
                                                                        <?php echo $cntry['Currency']['code']; ?>
                                                                    </option>
                                                                <?php
                                                            }    

                                                        }
                                                        curl_close($ch);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                       </div>
                                            
                                    <div class="row" style="margin: 0 auto; width: 50%;">
                                        
                                        <div class="">
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Tax</label>
                                                        <select name="tax_id" class="form-control">
                                                            <option value="">Select Tax</option>    
                                                                <?php 
                                                                    $url = $baseurl."/showAllTaxes";
                                                                    $params = "";
                                                                    $ch = curl_init($url);
                                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                    $result = curl_exec($ch);
                                                                    $json_data = json_decode($result, true);
                                                                    //var_dump($json_data);
                                                                    $i=0;
                                                                    foreach($json_data['msg'] as $str => $taxxx) 
                                                                    {
                                                                        //var_dump($data);
                                                                        if($data['Tax']['city']==$taxxx['Tax']['city'])
                                                                        {
                                                                            ?>
                                                                                <option value="<?php echo $taxxx['Tax']['id']; ?>" selected>
                                                                                    <?php echo $taxxx['Tax']['city']; ?> (<?php echo $taxxx['Tax']['tax']; ?>%)
                                                                                </option>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                                <option value="<?php echo $taxxx['Tax']['id']; ?>">
                                                                                    <?php echo $taxxx['Tax']['city']; ?> (<?php echo $taxxx['Tax']['tax']; ?>%)
                                                                                </option>
                                                                            <?php
                                                                        }
                                                                        
                                                                   }
                                                                   curl_close($ch);
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class=" control-label">ZIP</label>
                                                        <input type="text" class="form-control" name="zip" value="<?php echo $data['RestaurantLocation']['zip']; ?>" placeholder="ZIP">
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Lattitude</label>
                                                        <input type="text" class="form-control" name="lat" value="<?php echo $data['RestaurantLocation']['lat']; ?>" placeholder="Lattitude" <?php if($_SESSION['role']=="1"){echo"1readonly";}?>>
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Longitude</label>
                                                        <input type="text" class="form-control" name="long"  value="<?php echo $data['RestaurantLocation']['long']; ?>" placeholder="Longitude" <?php if($_SESSION['role']=="1"){echo"1readonly";}?>>
                                                    </div>
                                                </div>
                                        </div>


                                        <div class="">
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">min order price</label>
                                                        <input type="text" class="form-control" name="min_order_price" value="<?php echo $data['Restaurant']['min_order_price']; ?>" placeholder="min order price">
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class=" control-label">delivery free range</label>
                                                        <input type="text" class="form-control" name="delivery_free_range" value="<?php echo $data['Restaurant']['delivery_free_range']; ?>" placeholder="delivery free range">
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">preparation time</label>
                                                        <input type="text" class="form-control" name="preparation_time" value="<?php echo $data['Restaurant']['preparation_time']; ?>" placeholder="preparation time">
                                                    </div>
                                                </div>
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Tax implementation</label>
                                                        <select name="tax_free" class="form-control">
                                                            <option value="1"  <?php if($data['Restaurant']['tax_free']=="1"){ echo"selected";}?>>No Tax will implement</option>    
                                                            <option value="0" <?php if($data['Restaurant']['tax_free']=="0"){ echo"selected";}?>>Tax will implement</option>    
                                                        </select>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="">
                                                
                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Google tracking code</label>
                                                        <input type="text" class="form-control" name="google_analytics" value="<?php echo $data['Restaurant']['google_analytics']; ?>" placeholder="Analytic tracking code">
                                                    </div>
                                                </div>


                                                <div class="">
                                                    <div class="form-group width">
                                                        <label for="field-1" class="control-label">Notes</label>
                                                        <textarea type="text" class="form-control" name="notes" value="<?php echo $data['Restaurant']['notes']; ?>" placeholder="Notes (Write what ever you want!)"></textarea>
                                                    </div>
                                                </div>

                                                
                                               
                                        </div>


                                    </div>

                                    <div id="clonediv" class="1" style="">    
										<div class="row" style="margin: 0 auto; width: 50%;">
										
											<div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Monday start-->
												<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">
													<div class="form-group width">
														<input type="text" class="form-control" name="day[]" placeholder="Day" value="Monday" readonly>
													</div>
												</div>
													
												<div class="col-lg-4 col-md-4 col-sm-4">
													 <div class="form-group width">
														
													  	<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time"  value="<?php echo $data['RestaurantTiming'][0]['opening_time']; ?>" />
													</div>
												</div>
													
												<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">
													<div class="form-group width">
														
														<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][0]['closing_time']; ?>" placeholder="Closing Time" />
													</div>
												</div>
												<div style="clear:both;"></div>
											</div><!--Monday end-->
												   
											<div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Tuesday start-->
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">   
														<div class="form-group width">
														   <input type="text" class="form-control" name="day[]" placeholder="Day" value="Tuesday" readonly>
														</div>
													</div>  
												   
												   <div class="col-lg-4 col-md-4 col-sm-4">   
														<div class="form-group width">
															
														   <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][1]['opening_time']; ?>" placeholder="Opening Time" />
														</div>
													</div>
													
												   <div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">   
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][1]['closing_time']; ?>" placeholder="Closing Time" />
														</div>
													</div>
											 </div><!--Tuesday end-->
											  
											 <div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Wednesday start-->
													
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">
														<div class="form-group width">
															 <input type="text" class="form-control" name="day[]" placeholder="Day" value="Wednesday" readonly>
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4">   
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][2]['opening_time']; ?>"placeholder="Opening Time" />
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">     
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][2]['closing_time']; ?>"placeholder="Closing Time" />
														</div>
													</div>
											  </div><!--Wednesday end-->
														
												
	
											  <div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Thursday start-->
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">   
														<div class="form-group width">
															<input type="text" class="form-control" name="day[]" placeholder="Day" value="Thursday" readonly>
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4">    
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][3]['opening_time']; ?>"placeholder="Opening Time" />
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">     
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][3]['closing_time']; ?>"placeholder="Closing Time" />
														</div>
													</div>
													
												</div><!--Thursday end-->
	
												<div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Friday start-->
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">   
														<div class="form-group width">
															<input type="text" class="form-control" name="day[]" placeholder="Day" value="Friday" readonly>
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4">   
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][4]['opening_time']; ?>"placeholder="Opening Time" />
														</div>
													</div>
													
												  
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">     
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][4]['closing_time']; ?>"placeholder="Closing Time" />
														</div>
													</div> 
													 
												 </div><!--Friday end-->
	
											   <div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Saturday start-->
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">    
														<div class="form-group width">
															<input type="text" class="form-control" name="day[]" placeholder="Day" value="Saturday" readonly>
														</div>
													</div>
												 
													<div class="col-lg-4 col-md-4 col-sm-4">   
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][5]['opening_time']; ?>"placeholder="Opening Time" />
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">      
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][5]['closing_time']; ?>"placeholder="Closing Time" />
														</div>
													</div>
													
												</div><!--Saturday end-->
												
												<div class="col col-lg-12 col-md-12 col-sm-12" style="padding:0px;"><!--Sunday start-->
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding:0px;">   
														<div class="form-group width">
															<input type="text" class="form-control" name="day[]" placeholder="Day" value="Sunday" readonly>
														</div>
													</div>
													
													<div class="col-lg-4 col-md-4 col-sm-4">    
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]"   value="<?php echo $data['RestaurantTiming'][6]['opening_time']; ?>"placeholder="Opening Time" />
														</div>
													</div>
	
													<div class="col-lg-4 col-md-4 col-sm-4" style="padding: 0px;">   
														<div class="form-group width">
															
															<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]"   value="<?php echo $data['RestaurantTiming'][6]['closing_time']; ?>"placeholder="Closing Time" />
														</div>
													</div>
												   
												  </div> <!--Sunday end-->  
													
										 </div><!--end row-->    
                                     </div>
                                          
                                            
                                           
									<div class="row" style="margin: 0 auto; width: 50%;">
										<div >
											<input type="submit" class="btn btn-primary" value="Update Restaurant" style=" width:100%;">
										</div>
									</div>
                                            
                                            
                               </form>

                        <?php
                                
                    }   
                ?>

            </div>    
        <?php
        //print_r($json_data2);
    }

    curl_close($ch2);
}
else
if(@$_GET['getOrderDetails']=="ok") 
{
    

    $id = @$_GET['orderID'];

    $headers2 = array(
        "Accept: application/json",
        "Content-Type: application/json"
    );

    $data2 = array(

            "order_id" => $id

           // "user_id" => "65"

        );

    $ch2 = curl_init( $baseurl.'/showOrderDetail');
    //$ch2 = curl_init('https://api.foodomia.pk/publicSite/showOrderDetail');

    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data2));
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);

    $return2 = curl_exec($ch2);

    $json_data = json_decode($return2, true);
    //var_dump($json_data2);

    $curl_error2 = curl_error($ch2);
    $http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

    //echo $json_data['code'];
    //die;

    if($json_data['code'] !== 200) 
    {
        echo "Something went wrong";
    } 
    else 
    {   
        ?>
            <div class="row">

                <script type="text/javascript">
        
                    var table = $('#table-1').DataTable();
                    // column order
                     table
                      .column( '0:visible' )
                      .order( 'desc' )
                      .draw();
            
                </script>
                <style>
                    .sect {
                        padding: 10px;
                        box-shadow: 0 0 1px 0 rgba(0,0,0,0.2);
                        background: #f7f7f7;
                        border-radius: 1px;
                        margin-bottom: 1px;
                    }
                    .sect p {
                        margin: 0 0 10px;
                    }

                    .orderinformation p {
                        margin: 0 0 10px;
                        padding: 0;
                    }

                    .menutable_div {
                        padding: 10px 0 5px;
                    }

                    .orderinformation hr {
                        background: #ddd;
                        position: relative;
                        height: 1px;
                        width: 100%;
                        border: none;
                        margin: 10px 0;
                    }
                    table tr td {
                        text-align: left !important;
                    }
                </style>
                <?php
                    foreach( $json_data['msg'] as $str => $val ) {

                        //var_dump($val);

                        $hotel_accepted = $val['Order']['hotel_accepted'];

                        $currency=$val['Restaurant']['Currency']['symbol'];

                        $tax=$val['Restaurant']['Tax']['tax'];

                        ?>

                        <div class="orderinformation">
                        
                        <h1 align="center" style="margin: 0px 0 20px 0;">Order #<?php echo $val['Order']['id']; ?></h1>
                        <div class="sect">
    
                            <h3>Buyer Details</h3>
    
                            <p><i class="fa fa-user"></i> <?php echo $val['UserInfo']['first_name']." ".$val['UserInfo']['last_name']; ?></p>
    
                            <p><i class="fa fa-phone"></i> <?php echo $val['UserInfo']['phone']; ?></p>
    
                            <p><i class="fa fa-map-marker"></i> <?php echo $val['Address']['street']." ".$val['Address']['city'].""; ?></p>
    
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
                            <p><i class="fa fa-phone"></i> <?php echo $val['Restaurant']['phone']; ?></p>

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

                            <p><i class="fa fa-note"></i> <?php echo $val['Restaurant']['notes']; ?></p>
    
                        </div>

                        <div class="sect">
    
                            <h3>Coupon Details</h3>
                            
                            <?php
								if($val['CouponUsed']['RestaurantCoupon']['coupon_code']!=NULL)
                                {
                                    ?>
                                        <p><?php echo @$val['CouponUsed']['RestaurantCoupon']['coupon_code']; ?> (<?php echo @$val['CouponUsed']['RestaurantCoupon']['discount']; ?>%) Discount</p>
                                    <?php  
                                }
                            ?>
                            
                            
                            

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
    
                                    <td style="padding:5px 0;">
                                    	<strong>Tax 
                                        	<span style="color:grey; font-size:12px;">
                                    			<?php 
													if($val['Restaurant']['tax_free']=="0")
													{
														?>
															(<?php echo $tax; ?>%)
														<?php
													}
												?>
                                                
                                            </span>
                                        </strong>
                                    </td>
    
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
    
                                    <td style="padding:5px 0;"><strong>Delivery Fee <span style="color:grey; font-size:12px;"></span></strong></td>
    
                                    <td style="padding:5px 0;" width="200" class="textright">&nbsp;</td>
    
                                    <td style="padding:5px 0;" width="200" class="textright"><strong><?php echo $currency; echo $val['Order']['delivery_fee']; ?></strong></td>
    
                                </tr>

                                <tr height="40px">
    
                                    <td style="padding:5px 0;"><strong>SubTotal <span style="color:grey; font-size:12px;"></span></strong></td>
    
                                    <td style="padding:5px 0;" width="200" class="textright">&nbsp;</td>
    
                                    <td style="padding:5px 0;" width="200" class="textright"><strong><?php echo $currency; echo $val['Order']['sub_total']; ?></strong></td>
    
                                </tr>

                                <tr height="40px">
    
                                    <td style="padding:5px 0;"><strong>Total <span style="color:grey; font-size:12px;"></span></strong></td>
    
                                    <td style="padding:5px 0;" width="200" class="textright">&nbsp;</td>
    
                                    <td style="padding:5px 0;" width="200" class="textright"><strong><?php echo $currency; echo $val['Order']['price']; ?></strong></td>
    
                                </tr>
    
                                
                                
    
                            </table>
    
                        </div>
    
                    </div>

                        <?php

                    }
                ?>

            </div>    
        <?php
        //print_r($json_data2);
    }

    curl_close($ch2);
}
else
if(@$_GET['addCoupan']=="ok") 
{
	$resID=@$_GET['resID'];
	?>
	
			<form role="form" method="post" action="Coupons.php?addCoupan=ok" class="form-horizontal form-groups-bordered">
               <div class="row">
			           <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-6 col-md-6 col-sm-6">				
	                   <div class="form-group width">
                        <label for="field-1" class="control-label">Coupon Code</label>
                        <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" required>
  					            <input type="hidden" class="form-control" name="restaurant_id" value="<?php echo $resID; ?>" required>
							       </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-6">
	                    <div class="form-group width">
                        <label for="field-1" class="control-label">Discount</label>
                        <select class="form-control" name="discount" placeholder="Discount" required>
                            <option value="">0%</option>
                						<?php 
                  						$varDiscount=1;
                  						$val=1;
                  						$percentage=1;
                  						for($varDiscount <=100;  $val<=100;  $percentage <=100){
                  					    echo $varDiscount='<option value='.$val ++ .'>'.$percentage++.'%'.'</option>';
                  						
                  						}
                						?>
						            </select>
						
                      </div>
                  </div> 
					     </div> 
					  </div> 

  					<div class="row">
  					   <div class="col col-lg-12 col-md-12 col-sm-12">	
      					<div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="form-group width">
                      <label for="field-1" class="control-label">Expire Date</label>
                       <input type="date"  class="form-control" name="expire_date" placeholder="YYYY-MM-DD = 2018-05-25" required>
                      </div>
                  </div>
      					  <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class="control-label">Used limit</label>
                            <input type="number"  class="form-control" name="limit_users" placeholder="used limit" required>
                        </div>
                    </div>
      					  </div>
                </div>
  				      
                


                
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group width">
                        <label for="field-1" class="control-label">*works for</label>
                          <select class="form-control" name="type" placeholder="type" required>
                              <option value="">Web , iOS and Android</option> 
                              <option value="web">Web</option> 
                              <option value="ios">iOS</option> 
                              <option value="android">Android</option> 
                          </select>
                        </div>
                    </div>
                  </div>
                

                <div class="row">
                  <div class="col col-lg-12 col-md-12 col-sm-12">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                      <div class="form-group width">
                          <input type="submit" class="btn btn-primary btn-block" value="Add Coupon">
                      </div> 
                    </div>
                  </div>
                </div>

              </form>
	
	<?php
}

?>
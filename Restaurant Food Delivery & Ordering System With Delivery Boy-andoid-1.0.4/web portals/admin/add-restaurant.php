<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	<h2 class="toast-title">Add Restaurant</h2>
   <div class="panel-body">
		<?php 
            if(isset($_GET['insert'])){
                if($_GET['insert']=="ok") {

                    $searchReplaceArray = array(
                      '(' => '', 
                      ')' => '',
                      '-' => '',
					  '_' => '',
                      ' ' => ''
                    );

                    for($i=0; $i<7; $i++) {
                        $opening_time[$i] = $_POST['opening_time'][$i];
                        $closing_time[$i] = $_POST['closing_time'][$i];
                        $day[$i] = $_POST['day'][$i];

                        $restaurant_timings_details[] = array( 'opening_time' => $opening_time[$i], 'closing_time' => $closing_time[$i], 'day' => $day[$i] );
                    }

					
					
					
                    $name = $_POST['name'];
					$currency_id=$_POST['currency_id'];//
					$preparation_time=30;//
					$speciality=$_POST['speciality'];//
					$tax_id=$_POST['tax_id'];//
					$slogan = $_POST['slogan'];
					$about = $_POST['about'];
					$phone = str_replace( array_keys($searchReplaceArray), array_values($searchReplaceArray), $_POST['phone'] );
					$timezone = $_POST['timezone'];
					$state = $_POST['state'];
					$menu_style = $_POST['menu_style'];
					$promoted = $_POST['promoted'];
					$city = $_POST['city'];
					$country = $_POST['country'];
					$zip = $_POST['zip'];
					$lat = $_POST['lat'];
					$long = $_POST['long'];
                    $restaurant_timing = $restaurant_timings_details;
					/*$opening_time = $_POST['opening_time'];
					$closing_time = $_POST['closing_time'];
					$day = $_POST['day'];*/
					$email = $_POST['email'];
					$password = $_POST['password'];
					$first_name = $_POST['first_name'];
					$last_name = $_POST['last_name'];
					//$image = $_POST['image'];

                    $min_order_price = $_POST['min_order_price'];
                    $delivery_free_range = $_POST['delivery_free_range'];
                    $preparation_time = $_POST['preparation_time'];
                    $tax_free = $_POST['tax_free'];
                    $google_analytics = $_POST['google_analytics'];
                    $notes = $_POST['notes'];
					$added_by = $_SESSION['id'];


                    $image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
                    $image = base64_encode($image_base);
					//Cover_upload_image
					$image_base1 = file_get_contents($_FILES['Cover_upload_image']['tmp_name']);
                    $image1 = base64_encode($image_base1);


					   $headers = array(
					    "Accept: application/json",
					    "Content-Type: application/json"
					   );
					   $data = array(
					   	"name" => $name, 
					    "currency_id"=>$currency_id,//
                        "preparation_time"=>$preparation_time,//
                        "speciality" =>$speciality,//

                        "min_order_price" =>$min_order_price,//
                        "delivery_free_range" =>$delivery_free_range,//
                        "preparation_time" =>$preparation_time,//
                        "tax_free" =>$tax_free,//
                        "google_analytics" =>$google_analytics,//
                        "notes" =>$notes,//
						"added_by" =>$added_by,//
						
						
                        "tax_id"=>$tax_id,//
					   	"slogan" => $slogan, 
					   	"about" => $about, 
					   	"phone" => $phone, 
					   	"timezone" => $timezone, 
					   	"state" => $state, 
					   	"menu_style" => $menu_style, 
					   	"promoted" => $promoted, 
					   	"city" => $city, 
					   	"country" => $country, 
					   	"zip" => $zip, 
					   	"lat" => $lat, 
					   	"long" => $long, 
                        "restaurant_timing" => $restaurant_timing,
					   	/*"opening_time" => $opening_time, 
					   	"closing_time" => $closing_time, 
					   	"day" => $day, */
					   	"email" => $email, 
					   	"password" => $password,
					   	"first_name" => $first_name, 
					   	"last_name" => $last_name, 
					   	//"image" => $image
                        "image" => array("file_data" => $image),
						"cover_image" => array("file_data" => $image1)
						);

						//echo json_encode( $data,true);
						
                      
					   $ch = curl_init( $baseurl.'/addRestaurant' );
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
					   	echo "<script>window.location='restaurants.php';</script>";
					   }

						
                }
            }	
        ?>
        
        <form role="form" method="post" action="add-restaurant.php?insert=ok" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
        
<div class="row">
<div class="col-sm-12">
<div class="col-sm-6">      
	  <div class="form-group">
           
            <div class="col-sm-6">
                
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                        <img src="http://placehold.it/200x150" alt="...">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                    <div>
                        <span class="btn btn-white btn-file">
                            <span class="fileinput-new">Select Logo</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="upload_image" accept="image/*" reqired>
                        </span>
                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                
            </div>
        </div> </div>
		
	<div class="col-sm-6">
		 <div class="form-group">
            
            <div class="col-sm-6">
                
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                        <img src="http://placehold.it/200x150" alt="...">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                    <div>
                        <span class="btn btn-white btn-file">
                            <span class="fileinput-new">Select Cover image</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="Cover_upload_image" accept="image/*" reqired>
                        </span>
                        <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                
            </div>
        </div> </div>
		
		</div> </div>
		
		
		
		
		
		
<div class="row">
    <div class="col col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-4 col-md-4 col-sm-4">

            <div class="form-group width">
                <label for="field-1" class="control-label">Name</label>
                 <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">	
        
            <div class="form-group width">
                <label for="field-1" class=" control-label">Slogan</label>
              <input type="text" class="form-control" name="slogan" placeholder="Slogan" required>
		    </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">	
            <div class="form-group width">
                <label for="field-1" class="control-label">About</label>
                    <textarea class="form-control" name="about" rows="3" placeholder="About" required></textarea>
                </div>
            </div> 
        </div> 
    </div> 

<div class="row">
<div class="col col-lg-12 col-md-12 col-sm-12">
<div class="col-lg-4 col-md-4 col-sm-4">	
  <div class="form-group width">
            <label for="field-1" class="control-label"> Speciality </label>
                
				 <input type="text" class="form-control" name="speciality" placeholder="speciality" required>
            </div>
        
</div>     
      
      
        
        <?php /*<div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Delivery Fee</label>
            
            <div class="col-sm-6">
                <input type="text" class="form-control" data-mask="fdecimal" name="delivery_fee" placeholder="Delivery Fee" data-dec="," data-rad="." maxlength="10">
            </div>
        </div>*/ ?>
		
<div class="col-lg-4 col-md-4 col-sm-4">
        
        <div class="form-group width">
            <label for="field-1" class=" control-label">Phone</label>
                <input type="text" class="form-control" name="phone" placeholder="Phone" data-mask="phone" required>
        </div>
		</div>
		
	<div class="col-lg-4 col-md-4 col-sm-4">
		<div class="form-group width">
            <label for="field-1" class="control-label">Timezone</label>
            
          
                <select name="timezone" class="form-control" required>
                    <option value="">Select timezone</option>
                    <option value="+01:00">Netherlands (GMT+01:00)</option>
               </select>
				</div>
				</div>
				</div>
				</div>
           
        
<div class="row">
<div class="col col-lg-12 col-md-12 col-sm-12">
<div class="col-lg-4 col-md-4 col-sm-4">
 <div class="form-group width">
            <label for="field-1" class=" control-label">State</label>
                <?php /*<input type="text" class="form-control" name="state" placeholder="State">*/ ?>
                <select name="state" class="form-control" required>
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
                        ?><option value="<?php echo $cntry['Tax']['state']; ?>"><?php echo $cntry['Tax']['state']; ?></option><?php
                    }
                    curl_close($ch);
                    ?>
                </select>
             </div>
        </div>
		<div class="col-lg-4 col-md-4 col-sm-4">
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
		
        <div class="col-lg-4 col-md-4 col-sm-4">
        <div class="form-group width">
            <label for="field-1" class="control-label">Promoted</label>
                <select name="promoted" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
		  </div>
        </div>
     
<div class="row">
<div class="col col-lg-12 col-md-12 col-sm-12">
<div class="col-lg-4 col-md-4 col-sm-4">
 <div class="form-group width">
            <label for="field-1" class="control-label">City</label>
                <?php /*<input type="text" class="form-control" name="city" placeholder="City">*/ ?>
                <select name="city" class="form-control" required>
                    <option value="">Select city</option> 
                    <?php 
                    $json_data = json_decode($result, true);
                    //var_dump($json_data);
                    foreach($json_data['cities'] as $cntry) {
                        ?><option value="<?php echo $cntry['Tax']['city']; ?>"><?php echo $cntry['Tax']['city']; ?></option><?php
                    }
                    curl_close($ch);
                    ?>
                </select>
            </div>
        </div>
        
<div class="col-lg-4 col-md-4 col-sm-4">      
	  <div class="form-group width">
            <label for="field-1" class="control-label">Country</label>
                <select name="country" class="form-control" required>
                    <option value="">Select country</option> 
                    <?php 
                    
                    $json_data = json_decode($result, true);
                    //var_dump($json_data);
                    foreach($json_data['countries'] as $cntry) {
                        ?><option value="<?php echo $cntry['Tax']['country']; ?>"><?php echo $cntry['Tax']['country']; ?></option><?php
                    }
                    curl_close($ch);
                    ?>
                </select>
            </div>
        </div>
		
		
		<div class="col-lg-4 col-md-4 col-sm-4"> 
		 <div class="form-group width">
            <label for="field-1" class="control-label">Currency</label>
                <select name="currency_id" class="form-control" required>
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
                        ?><option value="<?php echo $cntry['Currency']['id']; ?>"><?php echo $cntry['Currency']['code']; ?></option><?php
                    }
                    curl_close($ch);
                    ?>
                </select>
            </div>
        </div>
	</div>
   </div>
		
<div class="row">
    
    <div class="col col-lg-12 col-md-12 col-sm-12">
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label">Tax</label>
                    <select name="tax_id" class="form-control" required>
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
                                foreach($json_data['msg'] as $str => $data) 
                                {
                                    //var_dump($data);
                                    if(!empty($data['Tax']['id'])) 
                                    {
                                        ?>
                                            <option value="<?php echo $data['Tax']['id']; ?>">
                                                    <?php echo $data['Tax']['city']; ?> (<?php echo $data['Tax']['tax']; ?>%)
                                            </option>
                                        <?php
                                    }
        					   }
                               curl_close($ch);
                            ?>
                    </select>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class=" control-label" required>ZIP</label>
                    <input type="text" class="form-control" name="zip" placeholder="ZIP"  required>
                </div>
            </div>
    		
    		<div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label" required>Lattitude</label>
                    <input type="text" class="form-control" name="lat" placeholder="Lattitude" required>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label" required>Longitude</label>
                    <input type="text" class="form-control" name="long" placeholder="Longitude" required>
                </div>
            </div>
    </div>


    <div class="col col-lg-12 col-md-12 col-sm-12">
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label">min order price</label>
                    <input type="text" class="form-control" name="min_order_price" placeholder="min order price" required>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class=" control-label">delivery free range</label>
                    <input type="text" class="form-control" name="delivery_free_range" placeholder="delivery free range" required>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label">preparation time</label>
                    <input type="text" class="form-control" name="preparation_time" placeholder="preparation time" required>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label">Tax implementation</label>
                    <select name="tax_free" class="form-control" required>
                        <option value="1">No Tax will implement</option>    
                        <option value="0">Tax will implement</option>    
                    </select>
                </div>
            </div>
    </div>

    <div class="col col-lg-12 col-md-12 col-sm-12">
            
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="form-group width">
                    <label for="field-1" class="control-label">Google tracking code</label>
                    <input type="text" class="form-control" name="google_analytics" placeholder="Analytic tracking code">
                </div>
            </div>

             <div class="col-lg-3 col-md-3 col-sm-3">
               <div class="form-group width">
                    <label for="field-1" class="control-label">Note</label>
                    <textarea type="text" class="form-control" name="notes" placeholder="Note (write  what ever you want to write)"></textarea>
                </div>
            </div>
            
           
    </div>


</div>

 <div id="clonediv" class="1" style="padding: 20px; border:1px solid #eee;"">    
<div class="row">
<div class="col col-lg-12 col-md-12 col-sm-12"><!--Monday start-->
 <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group width">
                <label for="field-1" class="control-label">Day</label>
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Monday" readonly>
                </div>
            </div>
<div class="col-lg-4 col-md-4 col-sm-4">
 <div class="form-group width">
                <label for="field-1" class=" control-label">Opening Time</label>
              <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
 </div>
  </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group width">
                <label for="field-1" class="control-label">Closing Time</label>
                
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
           </div><!--Monday end-->
		   
<div class="col col-lg-12 col-md-12 col-sm-12"><!--Tuesday start-->
 <div class="col-lg-4 col-md-4 col-sm-4">   
		  <div class="form-group width">
               
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Tuesday" readonly>
                </div>
            </div>  
           <div class="col-lg-4 col-md-4 col-sm-4">   
		  <div class="form-group width">
               
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>
            
           <div class="col-lg-4 col-md-4 col-sm-4">   
		  <div class="form-group width">
                
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
            </div><!--Tuesday end-->
		<div class="col col-lg-12 col-md-12 col-sm-12"><!--Wednesday start-->
		<div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
             <input type="text" class="form-control" name="day[]" placeholder="Day" value="Wednesday" readonly>
                </div>
            </div>
 <div class="col-lg-4 col-md-4 col-sm-4">  	
<div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>
            
           <div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
                </div><!--Wednesday end-->
				
        

       	<div class="col col-lg-12 col-md-12 col-sm-12"><!--Thursday start-->
		 <div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Thursday" readonly>
                </div>
            </div>
		<div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>
            
           <div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
        </div><!--Thursday end-->

     	<div class="col col-lg-12 col-md-12 col-sm-12"><!--Friday start-->
		 <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Friday" readonly>
                </div>
            </div>
		 <div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>
            
          
		   <div class="col-lg-4 col-md-4 col-sm-4">  	
            <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>  
        </div><!--Friday end-->

       <div class="col col-lg-12 col-md-12 col-sm-12"><!--Saturday start-->
	    <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Saturday" readonly>
                </div>
            </div>
		 <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>
            
          <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
        </div><!--Saturday end-->
		
 <div class="col col-lg-12 col-md-12 col-sm-12"><!--Sunday start-->
 <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control" name="day[]" placeholder="Day" value="Sunday" readonly>
                </div>
            </div>
	    <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="opening_time[]" placeholder="Opening Time" />
                </div>
            </div>

	         <div class="col-lg-4 col-md-4 col-sm-4">  	
                <div class="form-group width">
                    <input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" name="closing_time[]" placeholder="Closing Time" />
                </div>
            </div>
           </div> <!--Sunday end-->    
        </div><!--end row-->	
 </div>
      <div class="row">
	 <div class="col col-lg-12 col-md-12 col-sm-12"><!--Sunday start-->
     <div class="col-lg-3 col-md-3 col-sm-3">  	
             <div class="form-group width">  
            <label for="field-1" class=" control-label">Email</label>
            <input type="text" class="form-control" name="email" placeholder="Email" required>
            </div>
        </div>
        
         <div class="col-lg-3 col-md-3 col-sm-3">  	
             <div class="form-group width">  
            <label for="field-1" class=" control-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-3">  	
             <div class="form-group width">  
            <label for="field-1" class="control-label">First Name</label>
                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
            </div>
        </div>
        
       <div class="col-lg-3 col-md-3 col-sm-3">  	
        <div class="form-group width"> 
            <label for="field-1" class="control-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
            </div>
        </div>
		</div>
		</div>
        
       
        <div class="col col-lg-12 col-sm-12 col-md-12">
                    <div class="form-group width">
                <input type="submit" class="btn btn-primary" value="Add Restaurant">
            </div>
        </div>
		
        
        </form>
    </div>
    

<!-- Footer -->
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>


<style>
.form-group.width {
    width: 100%;
}
</style>

	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/jquery.inputmask.bundle.min.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
    <script src="assets/js/fileinput.js"></script>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
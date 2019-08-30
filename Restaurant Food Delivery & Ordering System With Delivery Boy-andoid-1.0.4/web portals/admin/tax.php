<?php 

require_once("Header.php"); 

if($_SESSION['role']=="1")
{
    @header('Location: index.php');
}
?>
<?php if(isset($_SESSION['id'])) { ?>
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View All Tax</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class='btn btn-default'>Add Tax</a></div>
        <div class="clearfix"></div>
        <br>
          <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Tax</th>
                    <th>Delivery fee (per Km)</th>
                    <th>Country Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
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
                $countTax=$json_data['msg'];
                $i=0;
                foreach($json_data['msg'] as $str => $data) {
                    //var_dump($data);
                    if(!empty($data['Tax']['id'])) {
                        echo "<tr>
                            <td>".$data['Tax']['id']."</td>
                            <td>".$data['Tax']['city']."</td>
                            <td>".$data['Tax']['state']."</td>
                            <td>".$data['Tax']['country']."</td>
                            <td>".$data['Tax']['tax']."</td>
                            <td>".$data['Tax']['delivery_fee_per_km']."</td>
                            <td>".$data['Tax']['country_code']."</td>
                            <td>
                                <a style='cursor:pointer;' data-id='".$data['Tax']['id']."' data-city='".$data['Tax']['city']."' data-state='".$data['Tax']['state']."' data-country='".$data['Tax']['country']."' data-tax='".$data['Tax']['tax']."' data-fee='".$data['Tax']['delivery_fee_per_km']."' data-code='".$data['Tax']['country_code']."' class='edittax btn btn-default btn-sm'>Edit Tax</a>
                            </td>
                        </tr>";
                    }
                    $i++;
                }

                curl_close($ch);
                ?>
            </tbody>
        </table>
        
        <script type="text/javascript">
         $('#table-1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
        </script>
         
            
        </div>
    </div>    


<script type="text/javascript">
function addtax() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}

jQuery(document).ready(function(){
    jQuery(".edittax").on("click", function(){
        var id = jQuery(this).attr('data-id');
        var city = jQuery(this).attr('data-city');
        var state = jQuery(this).attr('data-state');
        var country = jQuery(this).attr('data-country');
        var tax = jQuery(this).attr('data-tax');
        var fee = jQuery(this).attr('data-fee');
        var code = jQuery(this).attr('data-code');

        jQuery('#idd').val(id);
        jQuery('#city').val(city);
        jQuery('#state').val(state);
        jQuery('#country').val(country);
        jQuery('#tax').val(tax);
        jQuery('#deliveryfee').val(fee);
        jQuery('#countrycode').val(code);

        jQuery('#modal-8').modal('show', {backdrop: 'static'});
    });
});
</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Tax</h4>
            </div>
            
            <div class="modal-body">
                <?php 
                    if(isset($_GET['insert'])){
                        if($_GET['insert']=="ok") {

                            $city = $_POST['city'];
                            $state = $_POST['state'];
                            $country = $_POST['country'];
                            $tax = $_POST['tax'];
                            $delivery_fee_per_km = $_POST['delivery_fee_per_km'];
                            $country_code = $_POST['country_code'];
                            $delivery_time = $_POST['delivery_time'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "city" => $city, 
                                "state" => $state, 
                                "country" => $country, 
                                "tax" => $tax,
                                "delivery_fee_per_km" => $delivery_fee_per_km,
                                "country_code" => $country_code,
                                "delivery_time" => $delivery_time
                                );

                               $ch = curl_init( $baseurl.'/addTax' );
                                
                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                               curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                               $return = curl_exec($ch);

                               $curl_error = curl_error($ch);
                               $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                               curl_close($ch);
                               //echo "123";
                               //print_r(json_encode($data));
                               // do some checking to make sure it sent
                               //var_dump($http_code);
                               //die;

                               if($http_code !== 200){
                                //  echo "<div class='alert alert-danger'>".$curl_error."</div>";
                                echo "<script>window.location='tax.php?status=error';</script>";
                               
                               }else{
                                 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                 echo "<script>window.location='tax.php?status=success';</script>";
                               }

                                
                        }
                    }   
                ?>

                <form role="form" method="post" action="tax.php?insert=ok" class="form-horizontal form-groups-bordered">

                    <div class="row">
		<div class="col col-lg-12 col-md-12 col-sm-12">	
           <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class="control-label">City</label>
                            <input type="text" class="form-control" name="city" placeholder="City" required>
                        </div>
                    </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class=" control-label">State</label>
                            <input type="text" class="form-control" name="state" placeholder="State" required>
                        </div>
                    </div>
					</div>
                    </div>

                <div class="row">
		            <div class="col col-lg-12 col-md-12 col-sm-12">	
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group width">
                                <label for="field-1" class=" control-label">Country</label>
                                <!-- <input type="text" class="form-control" name="country" placeholder="Country">-->
    							<select class="form-control" name="country" placeholder="Country" required> 
        							<option value='' selected>Select Country</option>
        							<?php
        							    echo count($countTax);
        							    if(count($countTax)==0)
        							    {
        							        include('countries.php');
                                        	//echo  $json_data = json_encode($countryArray, true);
                                        	foreach($countryArray as  $country){
                                        		
                                        		 $countryName = ucwords(strtolower($country["name"]));
                                        	?>
                                        		<option value='<?php echo $countryName;?>'><?php echo $countryName;?></option>
                                        		<?php 
                                        	} 
        							    }
        							    else
        							    {
        							        $url = $baseurl."/showCountries";
                                            $params = "";
                                            $ch = curl_init($url);
                                            curl_setopt($ch, CURLOPT_POST, 1);
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            $result = curl_exec($ch);
                                            $json_data = json_decode($result, true);
                                            $i=0;
                                            foreach($json_data['countries'] as  $data) 
                                            {
                                				?>
                					                <option value='<?php  echo $data['Tax']['country']; ?>'> <?php  echo $data['Tax']['country']; ?></option>
                							    <?php
            							    }
            							    curl_close($ch);
        							    }
                                        
                                    
                                    ?>
        							
    				            </select>
                            </div>
                        </div>
    
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group width">
                                <label for="field-1" class=" control-label">Tax</label>
                                <input type="text" class="form-control" name="tax" placeholder="Tax eg. 6" required>
                            </div>
                        </div>
                    
					</div>
                </div>

				<div class="row">
		            <div class="col col-lg-12 col-md-12 col-sm-12">	
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group width">
                                <label for="field-1" class=" control-label">Delivery Fee Per Km</label>
                                <input type="text" class="form-control" name="delivery_fee_per_km" placeholder="Delivery Fee Per Km" required>
                            </div>
                        </div>
    
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group width">
                                <label for="field-1" class=" control-label">Country Code</label>
                                <input type="text" class="form-control" name="country_code" placeholder="Country Code eg. +1" required>
                            </div>
                        </div>
                    
					</div>
                </div>	
				
				
				<div class="row">
		            <div class="col col-lg-12 col-md-12 col-sm-12">	
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group width">
                                <label for="field-1" class=" control-label">Delivery Est time</label>
                                <input type="text" class="form-control" name="delivery_time" placeholder="Delivery Est time like (30)" required>
                            </div>
                        </div>
    
                        
					</div>
                </div>		

                   <div class="row">
                 <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
                   
                  
                            <input type="submit" class="btn btn-primary btn-block" value="Add Tax">
                        </div>
                    </div>
                </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>



<!-- Modal 8-->
<div class="modal fade custom-width in" id="modal-8">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Edit Tax</center></h4>
            </div>
            
            <div class="modal-body">
                <?php 
                    if(isset($_GET['edit'])){
                        if($_GET['edit']=="ok") {
    
                            $id = $_POST['id'];
                            $city = $_POST['city'];
                            $state = $_POST['state'];
                            $country = $_POST['country'];
                            $tax = $_POST['tax'];
                            $delivery_fee_per_km = $_POST['deliveryfee'];
                            $country_code = $_POST['countrycode'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "id" => $id,
                                "city" => $city, 
                                "state" => $state, 
                                "country" => $country, 
                                "tax" => $tax,
                                "delivery_fee_per_km" => $delivery_fee_per_km,
                                "country_code" => $country_code
                                );

                               $ch = curl_init( $baseurl.'/addTax' );

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
                                echo "<script>window.location='tax.php';</script>";
                               }

                                
                        }
                    }   
                ?>

                <form role="form" method="post" action="tax.php?edit=ok" class="form-horizontal form-groups-bordered">
                    <input type="hidden" class="form-control" name="id" id="idd">
		<div class="row">
		<div class="col col-lg-12 col-md-12 col-sm-12">	
           <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class="control-label">City</label>
                         <input type="text" class="form-control" name="city" id="city" placeholder="City" required>
                        </div>
                    </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class=" control-label">State</label>
                            <input type="text" class="form-control" name="state" id="state" placeholder="State" required>
                        </div>
                    </div>
					   </div>
                    </div>
					
					
					
					<div class="row">
		<div class="col col-lg-12 col-md-12 col-sm-12">	
           <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class=" control-label">Country</label>
                          <!--  <input type="text" class="form-control" name="country" id="country" placeholder="Country">-->
							<select class="form-control" name="country" id="country" placeholder="Country" required>
							<option selected>Select Country</option>
							 <?php 
                				$url = $baseurl."/showCountries";
                                $params = "";
                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $result = curl_exec($ch);
                                $json_data = json_decode($result, true);
                                $i=0;
                                foreach($json_data['countries'] as  $data) 
                                {
                    				?>
    					                <option value='<?php  echo $data['Tax']['country']; ?>'> <?php  echo $data['Tax']['country']; ?></option>
    							    <?php
							    }
							    curl_close($ch);
							?>
				</select>
                        </div>
                    </div>

                   <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class=" control-label">Tax</label>         
                            <input type="text" class="form-control" name="tax" id="tax" placeholder="Tax" required>
                        </div>
                    </div>
					  </div>
                    </div>

					<div class="row">
		<div class="col col-lg-12 col-md-12 col-sm-12">	
           <div class="col-lg-6 col-md-6 col-sm-6">
               <div class="form-group width">
                        <label for="field-1" class=" control-label">Delivery Fee (per mile)</label>
                            <input type="text" class="form-control" name="deliveryfee" id="deliveryfee" placeholder="Delivery fee (per mile)" required>
                        </div>
                    </div>

                   <div class="col-lg-6 col-md-6 col-sm-6">
                     <div class="form-group width">
                        <label for="field-1" class="control-label">Country Code</label>
                            <input type="text" class="form-control" name="countrycode" id="countrycode" placeholder="Country code" required>
                        </div>
                    </div>
					   </div>
                    </div>
					<div class="row">
                 <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
                            <input type="submit" class="btn btn-primary btn-block" value="Update Tax">
                        
                    </div> </div>
                    </div> </div>
                
                </form>

            </div>

        </div>
    </div>
</div>
<style>
.form-group.width {
    width: 100%;
}
</style>


	<?php require_once('footer_bottom.php');?>


</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
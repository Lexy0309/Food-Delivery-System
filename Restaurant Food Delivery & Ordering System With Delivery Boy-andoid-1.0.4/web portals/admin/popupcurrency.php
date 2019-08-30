<?php
require_once("config.php");

 
                    if(isset($_GET['edit'])){
                        if($_GET['edit']=="ok") {
    
                            $id = $_POST['id'];
							$Currency = $_POST['currency'];
                            $Country = $_POST['country'];
                            $Code = $_POST['code'];
                            $Symbol = $_POST['symbol'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "id" => $id,
                                "currency" => $Currency, 
                                "country" => $Country, 
                                "code" => $Code, 
                                "symbol" => $Symbol
                               
                                );

								// var_dump($data);
								// die();
                               $ch = curl_init( $baseurl.'/addCurrency' );

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
                                // echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                echo "<script>window.location='currency.php';</script>";
                               }

                                
                        }
                    }   
                ?>
				<?php

	if(isset($_GET['userid'])){

    $resid = $_GET['userid'];
	
	
	 $url = $baseurl."/showCurrencies";
                $params = "";

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
                //echo var_dump($json_data);
                //$i=0;
                foreach($json_data['msg'] as $data) {
                    //var_dump($data);
                    if(!empty($data['Currency']['id'])) {
						if($data['Currency']['id']==$resid){
                        
						
						
                          $Currency_id = $data['Currency']['id'];
                          $Currency_currency = $data['Currency']['currency'];
                          $Currency_country = $data['Currency']['country'];
                          $Currency_code =  $data['Currency']['code'];
                         $Currency_symbol =  $data['Currency']['symbol'];
						 
						 }
	
	}
					
	}?>
						 
						

						<form role="form" method="post" action="popupcurrency.php?edit=ok" class="form-horizontal form-groups-bordered">
                    <input type="hidden" class="form-control" name="id" value="<?php echo  $Currency_id;?>">
					<div class="col col-lg-12 col-md-12 col-sm-12">	
                   <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                    
                        <label for="field-1" class="control-label">Currency</label>

                            <input type="text" class="form-control" name="currency" value="<?php echo  $Currency_currency;?>" required>
                        </div>
                    </div>
					
					

                      <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class=" control-label">Country</label>
                        
                       
                            
							<select class="form-control " name="country" required>
							<option value='<?php echo  $Currency_currency;?>' selected><?php echo  $Currency_country;?></option>
							 <?php 
				 
             
				 $url = $baseurl."/showCountries";
                $params = "";

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
                foreach($json_data['countries'] as  $data) {
				   echo $data['Tax']['country'];
					
				?>
					<option value='<?php  echo $data['Tax']['country']; ?>'> <?php  echo $data['Tax']['country']; ?></option>
							
							<?php
							
							
                 }

                curl_close($ch);
				
                ?>
				</select>
                        </div>
                    </div>
					</div>

                  

                  <div class="col col-lg-12 col-md-12 col-sm-12">	
                   <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class=" control-label">Code</label>
                        
                       
                            <input type="text" class="form-control" name="code" value="<?php echo  $Currency_code;?>" required>
                        </div>
                    </div>

                   

                    <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class="col-sm-2 control-label">Symbol</label>
                        
                       
                            <input type="text" class="form-control" name="symbol" value="<?php echo  $Currency_symbol;?>" required>
                        </div>
                    </div>
					</div>

              <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
                   
                  
                        
                            <input type="submit" class="btn btn-primary btn-block" value="Update Currency" required>
                       
                    </div>   </div>  
                 </div>
                </form>
						 
	
	<?php
	
	}
	?>
	
         

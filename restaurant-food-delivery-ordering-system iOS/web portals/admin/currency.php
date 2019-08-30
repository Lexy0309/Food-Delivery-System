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
        
		<!--<CENTER>
			
			<a href="#" onClick="pop('popDiv')">open</a>
		</CENTER>-->
		
		
		
        <div class="pull-left"><h2 class="toast-title">View All Currencies</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class='btn btn-default'>Add Currency</a></div>
        <div class="clearfix"></div>
        <br>
         <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>		
                    <th>ID</th>
                    <th>currency</th>
                    <th>country</th>
                    <th>code</th>
                    <th>symbol</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
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
                        echo "<tr>
						
						
                            <td>".$data['Currency']['id']."</td>
                            <td>".$data['Currency']['currency']."</td>
                            <td>".$data['Currency']['country']."</td>
                            <td>".$data['Currency']['code']."</td>
                            <td>".$data['Currency']['symbol']."</td>
                           
                            <td>
                                <a style='cursor:pointer;' data-id='".$data['Currency']['id']."' data-currency='".$data['Currency']['currency']."' data-country='".$data['Currency']['country']."' data-code='".$data['Currency']['code']."' data-symbol='".$data['Currency']['symbol']."'   class='editcurrency btn btn-default btn-sm'>Edit Currency</a>
                            </td>
                        </tr>";
                    }
                    //$i++;
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
       
var table = $('#table-1').DataTable();
    // column order
     table
	  .column( '0:visible' )
      .order( 'desc' )
	  .draw();
	   </script>
        
         
            
        </div>
    </div>    


<script type="text/javascript">
function addtax() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}




jQuery(document).ready(function(){
    jQuery(".editcurrency").on("click", function(){
        var id = jQuery(this).attr('data-id');
        var currency = jQuery(this).attr('data-currency');
        var country = jQuery(this).attr('data-country');
        var code = jQuery(this).attr('data-code');
        var symbol = jQuery(this).attr('data-symbol');


        jQuery('#id').val(id);
        jQuery('#currency').val(currency);
        jQuery('#country').val(country);
        jQuery('#code').val(code);
        jQuery('#symbol').val(symbol);

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
                <h4 class="modal-title">Add Currency</h4>
            </div>
            
            <div class="modal-body">
                <?php 
                    if(isset($_GET['insert'])){
                        if($_GET['insert']=="ok") {
							
							

                            $Currency = $_POST['currency'];
                            $Country = $_POST['country'];
                            $Code = $_POST['code'];
                            $Symbol = $_POST['symbol'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "currency" => $Currency, 
                                "country" => $Country, 
                                "code" => $Code, 
                                "symbol" => $Symbol
                               
                                );

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
                                 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                echo "<script>window.location='currency.php';</script>";
                               }

                                
                        }
                    }   
                ?>


                <form role="form" method="post" action="currency.php?insert=ok" class="form-horizontal form-groups-bordered">
<div class="col col-lg-12 col-md-12 col-sm-12">	
<div class="col-lg-6 col-md-6 col-sm-6">		
	        <div class="form-group width">
                    
                        <label for="field-1" class="control-label">Currency Name</label>
                        
                       
                            <input type="text" class="form-control" name="currency" placeholder="Eg. United States Doller" required>
                        </div>
                    </div>

                   <div class="col-lg-6 col-md-6 col-sm-6">		
	        <div class="form-group width">
                        <label for="field-1" class="control-label">Country</label>
                        
                        
                            <select class="form-control" name="country" placeholder="Country" required>
							<option value='' selected>Select Country</option>
							
							 <?php 
				 
                               // $url ="http://api.foodomia.pk/superAdmin/showCountries";
                				   $url = $baseurl."/showCountries";
                                $params = "";
                
                                $ch = curl_init($url);
                
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $result = curl_exec($ch);
                                $json_data = json_decode($result, true);
                				//echo '<pre></pre>';
                               // print_r($json_data);
                                $i=0;
                                foreach($json_data['countries'] as  $data) {
                                  // echo var_dump($data);
                				   
                				   // echo $data['cities']['Tax']['city']  
                				   
                				   echo $data['Tax']['country'];
                					
                				?>
                					<option value='<?php  echo $data['Tax']['country']; ?>'> <?php  echo $data['Tax']['country']; ?></option>
                							
                							<?php
                							
                							 $i++;
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
                        <label for="field-1" class="control-label">Code</label>
                            <input type="text" class="form-control" name="code" placeholder="Code" required>
                        </div>
                    </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class="control-label">Symbol</label>
                            <input type="text" class="form-control" name="symbol" placeholder="symbol" required>
                        </div>
                    </div>
					</div>
					
					

                   
<div class="col col-lg-12 col-md-12 col-sm-12">	
                   <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
                       
                            <input type="submit" class="btn btn-primary btn-block" value="Add Currency">
                      
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
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Currency</h4>
            </div>
            
            <div class="modal-body">
                <?php 
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
                                 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
                                echo "<script>window.location='currency.php';</script>";
                               }

                                
                        }
                    }   
                ?>

                <form role="form" method="post" action="currency.php?edit=ok" class="form-horizontal form-groups-bordered">
                    <input type="hidden" class="form-control" name="id" id="id">
					<div class="col col-lg-12 col-md-12 col-sm-12">	
                   <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                    
                        <label for="field-1" class="control-label">Currency</label>

                            <input type="text" class="form-control" name="currency" id="currency" placeholder="currency" required>
                        </div>
                    </div>
					
					

                      <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class=" control-label">Country</label>
                        
                       
                            <!--<input type="text" class="form-control" name="country" id="country" placeholder="country" required>-->
							<select class="form-control " name="country" id="country" placeholder="country" required>
							<option value='' selected>Select Country</option>
							 <?php 
				 
               // $url ="http://api.foodomia.pk/superAdmin/showCountries";
				   $url = $baseurl."/showCountries";
                $params = "";

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
				//echo '<pre></pre>';
               // print_r($json_data);
                $i=0;
                foreach($json_data['countries'] as  $data) {
                  // echo var_dump($data);
				   
				   // echo $data['cities']['Tax']['city']  
				   
				   echo $data['Tax']['country'];
					
				?>
					<option value='<?php  echo $data['Tax']['country']; ?>'> <?php  echo $data['Tax']['country']; ?></option>
							
							<?php
							
							 $i++;
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
                        
                       
                            <input type="text" class="form-control" name="code" id="code" placeholder="code" required>
                        </div>
                    </div>

                   

                    <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class="col-sm-2 control-label">Symbol</label>
                        
                       
                            <input type="text" class="form-control" name="symbol" id="symbol" placeholder="symbol" required>
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

            </div>

        </div>
    </div>
</div>
         
			
			
			
			
			
			 <div id="popDiv" class="ontop"> 
		     <div border="1" id="popup">
				<div style="float:right;"> <img src="https://png.icons8.com/color/260/cancel.png" style="width: 30px;cursor: pointer;" onClick="hide('popDiv')"></div>
				<div class="clearfix"></div>
				<center> <h4 class="modal-title">Edit Currency</h4></center>
				<hr>
				 <div class="clearfix"></div>
				 
				<div id="popup-body">
				
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
<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) 
{ 


if(isset($_GET['insert'])){
    
    if($_GET['insert']=="ok") {

    
        $coupon_code = $_POST['coupon_code'];
        $restaurant_id = $_POST['restaurant_id'];
        $discount = $_POST['discount'];
        $expire_date = $_POST['expire_date'];
        $limit_users = $_POST['limit_users'];
        $type = $_POST['type'];


         $headers = array(
          "Accept: application/json",
          "Content-Type: application/json"
         );
       
         $data = array(
          "coupon_code" => $coupon_code, 
          "restaurant_id" => $restaurant_id, 
          "discount" => $discount, 
          "expire_date" => $expire_date,
          "limit_users"=>$limit_users,
          "type"=>$type
        );

       // var_dump($data);
        // echo "<div class='alert alert-success'>Successfully submitted..</div>";
       
      	///die();


           $ch = curl_init( $baseurl.'/addRestaurantCoupon' );

           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
           curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
           curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
           $return = curl_exec($ch);

           $curl_error = curl_error($ch);
           $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

           curl_close($ch);

           // do some checking to make sure it sent
           // var_dump($http_code);
           // die;

           if($http_code !== 200){
            echo "<script>window.location='Foodomia_Coupons.php?status=error';</script>";
           
           }else{
            // echo "<div class='alert alert-success'>Successfully submitted..</div>";
            echo "<script>window.location='Foodomia_Coupons.php?status=ok';</script>";
           
           }

            
    }
} 




?>


<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">Foodomia Coupons</h2></div>
     <div class="pull-right">
	 
	 <a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class=' addfooomiacoupns btn btn-default'>Add Foodomia Coupons</a></div>
	 
        <div class="clearfix"></div>
        <br>
        <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
			              <th>ID</th>
                  		  <th>Coupon code</th>
                 		   <th>Discount</th>
          					<th>Expire date</th>
          					<th>Use limit</th>
                            <th>Used</th>
                    		<th>Type</th>
          					<th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				
		$headers = array(
  		"Accept: application/json",
  		"Content-Type: application/json"
  	);

  	$data = array(
  		"restaurant_id" =>"0"
  	);
  	//$data;
  	$ch = curl_init( $baseurl.'/showRestaurantCouponWhoseRestaurantIDisZero' );			
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  	$return = curl_exec($ch);
  	$json_data = json_decode($return, true);
  	$curl_error = curl_error($ch);
  	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				 // var_dump($json_data);
				// die();
			foreach($json_data['msg'] as $str => $data) { 
			
			if(!empty($data['RestaurantCoupon']['id'])) {
                   
                        echo "<tr>
						
						
                           <td>".$data['RestaurantCoupon']['id']."</td>
                           <td>".$data['RestaurantCoupon']['coupon_code']."</td>
							 <td>".$data['RestaurantCoupon']['discount']."%"."</td>
							 <td>".$data['RestaurantCoupon']['expire_date']."</td>
							 <td>".$data['RestaurantCoupon']['limit_users']."</td>
							 <td>".count($data['CouponUsed'])."</td>
                           <td>".$data['RestaurantCoupon']['type']."</td>
                           
                          <td>
            						    <a style='cursor:pointer;' data-id='".$data['RestaurantCoupon']['id']."' data-coupon_code='".$data['RestaurantCoupon']['coupon_code']."' data-discount='".$data['RestaurantCoupon']['discount']."' data-expire_date='".$data['RestaurantCoupon']['expire_date']."' data-limit_users='".$data['RestaurantCoupon']['limit_users']."' data-type='".$data['RestaurantCoupon']['type']."'   class='editcurrency btn btn-default btn-sm'>Update Coupon</a>
            						  </td>
                           
                              
                        </tr>";
                    }
                   
                }

                curl_close($ch);
                ?>
            </tbody>
        </table>
        
        <script type="text/javascript">
        <?php

              if($_SESSION['role']=="0")
              {
                 ?>
                      $('#table-1').DataTable({
                          dom: 'Bfrtip',
                          buttons: [
                              'copy', 'csv', 'excel', 'pdf', 'print'
                          ]
                      });
                 <?php 
              }
          ?>
        </script>
         
            
        </div>
    </div>    


<script type="text/javascript">
function addtax() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}




jQuery(document).ready(function(){ 
	 $('.addfooomiacoupns').on('click',function(){
        var dataURL = $(this).attr('data-href');
        $('.modal-body').load(dataURL,function(){
            $('#modal-8').modal({show:true});
        });
    });     
});






jQuery(document).ready(function(){
    jQuery(".editcurrency").on("click", function(){
        var id = jQuery(this).attr('data-id');
		 var coupon_code = jQuery(this).attr('data-coupon_code');
        var discount = jQuery(this).attr('data-discount');
        var expire_date = jQuery(this).attr('data-expire_date');
        var limit_users = jQuery(this).attr('data-limit_users');



        jQuery('#id').val(id);
		 jQuery('#coupon_code').val(coupon_code);
        jQuery('#discount').val(discount);
        jQuery('#expire_date').val(expire_date);
        jQuery('#limit_users').val(limit_users);

        jQuery('#modal-8').modal('show', {backdrop: 'static'});
    });
});



 





</script>


<?php require_once('footer.php'); ?>
</div>
	
		
	</div>

 <?php 
                      
                ?>

	<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Add Foodomia Couppons</center></h4>
            </div>
            
            <div class="modal-body">
              <form role="form" method="post" action="Foodomia_Coupons.php?insert=ok" class="form-horizontal form-groups-bordered">
               <div class="row">
			           <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-6 col-md-6 col-sm-6">				
	                   <div class="form-group width">
                        <label for="field-1" class="control-label">Coupon Code</label>
                        <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" required>
  					            <input type="hidden" class="form-control" name="restaurant_id" value="0" required>
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
            </div>

        </div>
    </div>
</div>


	
	
	
	
	
	
	
	
 <?php 
        
        if(isset($_GET['update'])){
            if($_GET['update']=="ok") {
	
                $id = $_POST['id'];
	              $coupon_code = $_POST['coupon_code'];
                $discount = $_POST['discount'];
                $expire_date = $_POST['expire_date'];
	              $limit_users = $_POST['limit_users'];
                $type = $_POST['type'];
               
                $headers = array(
                    "Accept: application/json",
                    "Content-Type: application/json"
                );
	   
               $data = array(
                  "id" => $id, 
                  "coupon_code"=>$coupon_code,
                  "discount" => $discount, 
                  "expire_date" => $expire_date,
	                "limit_users"=>$limit_users,
                  "type"=>$type
                );

							 // var_dump($data);
							  // echo "<div class='alert alert-success'>Successfully submitted..</div>";
							  // echo "<script> window.location.href='Foodomia_Coupons.php';</script>";
							// die();
		
		
                   $ch = curl_init( $baseurl.'/addRestaurantCoupon' );

                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                   $return = curl_exec($ch);

                   $curl_error = curl_error($ch);
                   $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                   curl_close($ch);

                   // do some checking to make sure it sent
                   // var_dump($http_code);
                   // die;

                   if($http_code !== 200){
                     echo "<div class='alert alert-danger'>".$curl_error."</div>";
                   
                   }else{
                    // echo "<div class='alert alert-success'>Successfully submitted..</div>";
                    echo "<script> window.location.href='Foodomia_Coupons.php';</script>";
		
                   }

                    
            }
        }   
?>	
	
	

<!-- Modal 8-->
<div class="modal fade custom-width in" id="modal-8">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Update Restaurant Coupons</center></h4>
            </div>
            
            <div class="modal-body">
              
                <form role="form" method="post" action="Foodomia_Coupons.php?update=ok" class="form-horizontal form-groups-bordered">
               <div class="row">
			   <div class="col col-lg-12 col-md-12 col-sm-12">	
               <div class="col-lg-6 col-md-6 col-sm-6">				
	               <div class="form-group width">
                        <label for="field-1" class="control-label">Coupon_code</label>
                            <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="coupon_code" required readonly>
							<input type="hidden" class="form-control" name="id" id="id"  required>
							
                        </div>
                    </div>

                     <div class="col-lg-6 col-md-6 col-sm-6">
	                <div class="form-group width">
                        <label for="field-1" class="control-label">Discount</label>
                       
						<select class="form-control" name="discount" placeholder="Discount" id="discount" required>
						
						
						<?php 
						$varDiscount=0;
						$val=0;
						$percentage=0;
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
                        <label for="field-1" class="control-label">Expire_date</label>
                         <input type="date"  class="form-control" name="expire_date" placeholder="expire_date" id="expire_date" required>
                        </div>
                    </div>
					
					<div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group width">
                        <label for="field-1" class="control-label">limit_users</label>
                            <input type="number"  class="form-control" name="limit_users" placeholder="limit_users" id="limit_users" required>
                        </div>
                    </div>
					</div>
                    </div>


                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group width">
                            <label for="field-1" class="control-label">*works for</label>
                              <select class="form-control" name="type" id="type" placeholder="type" required>
                                  <option value="">Web , iOS and Android</option> 
                                  <option value="web">Web</option> 
                                  <option value="iOS">iOS</option> 
                                  <option value="Android">Android</option> 
                              </select>
                            </div>
                        </div>
                      </div>


				<div class="row">
                  <div class="col col-lg-12 col-md-12 col-sm-12">
				  <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
                        <input type="submit" class="btn btn-primary btn-block" value="update Coupon">
                      
                    </div> 
					</div>
                  </div>
                  </div>
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
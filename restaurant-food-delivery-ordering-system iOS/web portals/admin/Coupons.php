<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) 
{ 


	if(isset($_GET['addCoupan'])){
    
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
	
		   //var_dump($data);
			// echo "<div class='alert alert-success'>Successfully submitted..</div>";
		   
			//die();
	
	
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
				echo "<script>window.location='Coupons.php?status=error';</script>";
			   
			   }else{
				// echo "<div class='alert alert-success'>Successfully submitted..</div>";
				echo "<script>window.location='Coupons.php?status=ok';</script>";
			   
			   }
	
	} 
?>


<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View All Restaurants Coupons</h2></div>
       <!-- <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class='btn btn-default'>Add Currency</a></div>-->
        <div class="clearfix"></div>
        <br>
         <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Restaurant Name</th>
					<th>Owner Name</th>
					<th>Phone No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				 
                $url = $baseurl."/showRestaurants";
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
                     if(!empty($data['Restaurant']['id'])) {
                        echo "<tr>
						

                            <td>".$data['Restaurant']['id']."</td>
                            <td>".$data['Restaurant']['name']."</td>
							 <td>".$data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']."</td>
							 <td>".$data['UserInfo']['phone']."</td>
							 
                           
                           <td>
						   <a href='javascript:void(0);' data-href='restaurantscoupons.php?userid=".$data['UserInfo']['user_id']."' class='viewrirestcoupons btn btn-default btn-sm'>View all Coupons</a>
						 	
							<a href='javascript:;' onClick='addCoupan(".$data['Restaurant']['id'].")' class='viewrirestcoupons btn btn-default btn-sm'>Add Coupons</a>
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
function addCoupan(data) {
   
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
    document.getElementById("addCoupan").innerHTML="loading...";
    var xmlhttp;
    if(window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      
      xmlhttp.onreadystatechange=function()
      {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
           // alert(xmlhttp.responseText);
            document.getElementById("addCoupan").innerHTML=xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","ajex-events.php?addCoupan=ok&resID="+data);
    xmlhttp.send();
	
}




jQuery(document).ready(function(){ 
	 $('.viewrirestcoupons').on('click',function(){
        var dataURL = $(this).attr('data-href');
        $('.modal-body').load(dataURL,function(){
            $('#modal-8').modal({show:true});
        });
    });     
});
</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>



<!-- Modal 8-->
<div class="modal fade custom-width in" id="modal-8">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>View All Restaurant Coupons</center></h4>
            </div>
            
            <div class="modal-body">
              
                 </div>
               

           

        </div>
    </div>
</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Add Foodomia Couppons</center></h4>
                
            </div>
            
            <div class="modal-body" id="addCoupan">
              
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
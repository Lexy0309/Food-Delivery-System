<?php require_once("Header.php"); ?>
<?php 

if(isset($_SESSION['id'])) 
{ 

		if(@$_GET['blockUser']=="ok" && @$_GET['id']!="") 
        {

               $headers = array(
                "Accept: application/json",
                "Content-Type: application/json"
               );
               $data = array(
                "user_id" => $_GET['id'], 
                "block" => $_GET['block'], 
                );
				
				
				// var_dump($data); 								
				 // die();
				

               $ch = curl_init( $baseurl.'/userBlockStatus');

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
                 //echo "<div class='alert alert-danger'>".$curl_error."</div>";
               	echo "<script>window.location='riders.php?status=error';</script>";
               
               }
			   else{
                // echo "<div class='alert alert-success'>Successfully Edit User..</div>";
                echo "<script>window.location='riders.php?status=ok';</script>"; 
               }
        }
		
		
?>
<script>
   $(document).ready(function() { $("#riders").select2(); });
     $(document).ready(function() { $("#countries").select2(); });
  </script>

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View all Riders</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addrider()' class='btn btn-default'>Add Rider</a></div>
        <div class="clearfix"></div>
        <br>
         <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Device Token</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $url = $baseurl."/showRiders";
                $params = "";

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
                //var_dump($json_data);
                $i=0;
                foreach($json_data['msg']['OnlineRiders'] as $str => $data) {
                    //var_dump($data);
                    if(!empty($data['UserInfo']['user_id'])) {
                        ?>
                            <td><?php echo $data['UserInfo']['user_id']; ?></td>
                            <td><?php echo $data['UserInfo']['first_name']." ". $data['UserInfo']['last_name']; ?></td>
                            <td><?php echo $data['UserInfo']['phone']; ?></td>
                            <td>
                                <?php  
                                    if( !empty($data['UserInfo']['device_token']) ) 
                                    {
                                        echo "Available";
                                    } 
                                    else 
                                    {
                                        echo "Not available";
                                    }
                                ?>
                            </td>
                            <td><?php echo $data['UserInfo']['note']; ?></td>
                            <td>
                                <?php 
                                    if( $data['UserInfo']['online'] == '1' )  
                                    {
                                      ?>
                                        <div class='label label-success'>Online</div> 
                                        <a href="riders.php?user_id=<?php echo $data['UserInfo']['user_id'];?>" class='label label-danger'> Logout</a>
                                      <?php
                                    }
                                    else
                                    {
                                      ?>
                                        <div class='label label-secondary'>Offline</div>
                                      <?php
                                    } 
                                ?>
                            </td>

                            <td>
                                <a href='verifieddocs.php?user=<?php echo $data['UserInfo']['user_id']; ?>' target="_blank" class='btn btn-default btn-sm'>Verified Documents</a>
                                
                                <?php
                                
									if($data['User']['block']=="0")
                                    {
                                         ?>
                                            <a onClick="return confirm('Are you sure?')" href='riders.php?blockUser=ok&id=<?php echo $data['User']['id']; ?>&block=1' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;'>Block </a>
                                        <?php
                                    }
                                    else
                                    if($data['User']['block']=="1")
                                    {
                                        ?>
                                            <a onClick="return confirm('Are you sure?')" href='riders.php?blockUser=ok&id=<?php echo $data['User']['id']; ?>&block=0' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px; background:red; color: white; ' >Unblock </a>
                                        <?php
                                    }
									
								?>
                            </td>

                        <?php
                        
                          
                            echo "
                        </tr>";
                    }
                    $i++;




                }


                foreach($json_data['msg']['OfflineRiders'] as $str => $data) {
                    //var_dump($data);
                    if(!empty($data['UserInfo']['user_id'])) {
                        ?>
                            <td><?php echo $data['UserInfo']['user_id']; ?></td>
                            <td><?php echo $data['UserInfo']['first_name']." ". $data['UserInfo']['last_name']; ?></td>
                            <td><?php echo $data['UserInfo']['phone']; ?></td>
                            <td>
                                <?php  
                                    if( !empty($data['UserInfo']['device_token']) ) 
                                    {
                                        echo "Available";
                                    } 
                                    else 
                                    {
                                        echo "Not available";
                                    }
                                ?>
                            </td>
                            <td><?php echo $data['UserInfo']['note']; ?></td>
                            <td>
                                <?php 
                                    if( $data['UserInfo']['online'] == '1' )  
                                    {
                                      ?>
                                        <div class='label label-success'>Online</div> 
                                        <a href="riders.php?user_id=<?php echo $data['UserInfo']['user_id'];?>" class='label label-danger'> Logout</a>
                                      <?php
                                    }
                                    else
                                    {
                                      ?>
                                        <div class='label label-secondary'>Offline</div>
                                      <?php
                                    } 
                                ?>
                            </td>
                            <td>
                                <a href='verifieddocs.php?user=<?php echo $data['UserInfo']['user_id']; ?>' target="_blank" class='btn btn-default btn-sm'>Verified Documents</a>
                                
                                <?php
                                if($_SESSION['role']=="0")
								{
									if($data['User']['block']=="0")
                                    {
                                         ?>
                                            <a onClick="return confirm('Are you sure?')" href='riders.php?blockUser=ok&id=<?php echo $data['User']['id']; ?>&block=1' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;'>Block </a>
                                        <?php
                                    }
                                    else
                                    if($data['User']['block']=="1")
                                    {
                                        ?>
                                            <a onClick="return confirm('Are you sure?')" href='riders.php?blockUser=ok&id=<?php echo $data['User']['id']; ?>&block=0' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px; background:red; color: white; ' >Unblock </a>
                                        <?php
                                    }
								}
								?>
                                
                            </td>
                        <?php
                        
                          
                            echo "
                        </tr>";
                    }
                    $i++;



                    
                }
				
				// foreach($json_data['msg']['OfflineRiders'] as $str => $data) {
    //                 //var_dump($data);
    //                 if(!empty($data['UserInfo']['user_id'])) {
    //                     echo "<tr>
    //                         <td>".$data['UserInfo']['user_id']."</td>
    //                         <td>".$data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']."</td>
    //                         <td>".$data['UserInfo']['phone']."</td>
    //                         <td>";
    //                         if( !empty($data['UserInfo']['device_token']) ) {
    //                             echo "Available";
    //                         } else {
    //                             echo "Not available";
    //                         }
    //                         echo "</td>
    //                         <td>";
    //                         if( $data['UserInfo']['online'] == '1' )  {
    //                             echo "<div class='label label-success'>Online</div>";
				// 				//echo "<div class='label label-danger'>Logout</div>";
								
				// 				?>
								
				// 			   <!-- <a href="riders.php?user_id=<?php echo $data['UserInfo']['user_id'];?>" class='label label-danger'> Logout</a> -->
                              
				// 			   <?php 
    //                         } else {
    //                             echo "<div class='label label-secondary'>Offline</div>";
    //                         }
    //                         echo "</td>
    //                     </tr>";
    //                 }
    //                 $i++;
    //             }

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


        var table = $('#table-1').DataTable();
            // column order
             table
              .column( '0:visible' )
              .order( 'asc' )
              .draw();

        </script>
         
            
        </div>
    </div>    

<script type="text/javascript">
function addrider() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}

<?php
  if($_GET['approveRider']=="ok")
  {
    ?>
      $( document ).ready(function() {
        jQuery('#modal-7').modal('show', {backdrop: 'static'});
        $("#modal-7").css("display", "block");
      });
    <?php
  }
?>



</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>
	
	
	
	
	
	
	
	<?php 
                    if(isset($_GET['user_id'])){
                               $id = $_GET['user_id'];
                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "user_id" => $id
                                );

                               $ch = curl_init( $baseurl.'/updateRiderOnlineStatus' );

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
                                echo "<script>window.location='riders.php';</script>";
                               }
                    }   
                ?>
	
	
	
	
	
	

<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Add Rider</center></h4>
            </div>
            
            <div class="modal-body">
                
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

                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            $first_name = $_POST['first_name'];
                            $last_name = $_POST['last_name'];
							$phone = str_replace( array_keys($searchReplaceArray), array_values($searchReplaceArray), $_POST['phone'] );
                            $device_token = "";
                            $role = "rider";
                            $city = $_POST['city'];
                            $country = $_POST['country'];
                            $address_to_start_shift = $_POST['address_to_start_shift'];
                            $note=$_POST['note'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "email" => $email, 
                                "password" => $password, 
                                "first_name" => $first_name, 
                                "last_name" => $last_name,
                                "phone" => $phone,
                                "device_token" => $device_token,
                                "role" => $role,
                                "city" => $city,
                                "country" => $country,
                                "address_to_start_shift" => $address_to_start_shift,
                                "note" => $note
                                );

                               $ch = curl_init( $baseurl.'/registerRider' );

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
                                echo "<script>window.location='riders.php';</script>";
                               }

                                
                        }
                    }   
                ?>
                
                <form role="form" method="post" action="riders.php?insert=ok" class="form-horizontal form-groups-bordered">

                  <div class="row">
                    <div class="col col-sm-12">
                    <div class="col-sm-6">
                    	        <div class="form-group width">
                    	            <label for="field-1" class="control-label">Email</label>
                    	                <input type="text" class="form-control" name="email" value="<?php echo @$_GET['email']; ?>" placeholder="Email">
                    	            </div>
                    	        </div>
                    <div class="col-sm-6">
                    	        <div class="form-group width">
                    	            <label for="field-1" class="control-label">Password</label>
                    	                <input type="password" class="form-control" name="password" placeholder="password">
                    	            </div>
                    	        </div>
                    		 </div>
                    	    </div>
                    <div class="row">
                    <div class="col col-sm-12">
                    <div class="col-sm-6">
                    	        <div class="form-group width">
                    	            <label for="field-1" class=" control-label">First Name</label>
                    	                <input type="text" class="form-control" name="first_name" value="<?php echo @$_GET['firstName']; ?>" placeholder="First Name">
                    	            </div>
                    	        </div>
                    		<div class="col-sm-6">
                          <div class="form-group width">
                    	            <label for="field-1" class=" control-label">Last Name</label>
                                      <input type="text" class="form-control" name="last_name" value="<?php echo @$_GET['lastName']; ?>" placeholder="Last Name">
                    	            </div>
                    	        </div>
                    		 </div>
                    	    </div>

              <div class="row">
              <div class="col col-sm-12">
              <div class="col-sm-6">		
              	        <div class="form-group width">
              	            <label for="field-1" class=" control-label">Phone</label>
                               <input type="text" class="form-control" name="phone" placeholder="Phone"  value="<?php echo @$_GET['phone']; ?>"data-mask="phone">
              	            </div>
              	        </div>
              			<div class="col-sm-6">
                            <div class="form-group width">
              	            <label for="field-1" class="control-label">City</label>
              	            <!-- <input type="text" class="form-control" name="city" placeholder="City">-->
              				 <select name="city" class="form-control" id="riders">
                                  <option value="">Select city</option> 
                                  <?php 
              				$url = $baseurl."/showCountries";
                              $params = "";
                              $ch = curl_init($url);
                              curl_setopt($ch, CURLOPT_POST, 1);
                              curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                              $result = curl_exec($ch);
                              $json_data = json_decode($result, true);
              				
              				echo $json_data;
              					
                              $i=0;
              			
                              foreach($json_data['cities'] as  $data) {
              				?>
              					<option value='<?php  echo $data['Tax']['city']; ?>' <?php if($_GET['city']==$data['Tax']['city']){echo "selected";} ?> > <?php  echo $data['Tax']['city']; ?></option>
              							<?php
              							}curl_close($ch);
              							?>
                              </select>
              	           </div>
              	        </div>
              			 </div>
	        </div>
			
<div class="row">
<div class="col col-sm-12">
<div class="col-sm-6">	
	        <div class="form-group width">
		
	            <label for="field-1" class="control-label">Country</label>
                
				   <select name="country" class="form-control" id="countries">
                    <option value="">Select Country</option> 
                    <?php 
				$url = $baseurl."/showCountries";
                $params = "";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
				
				echo $json_data;
					
                $i=0;
			
                foreach($json_data['countries'] as  $data) {
				?>
					<option value='<?php  echo $data['Tax']['country']; ?>' <?php if($_GET['country']==$data['Tax']['country']){echo "selected";} ?>> <?php  echo $data['Tax']['country']; ?></option>
							<?php
							}curl_close($ch);
							?>
                </select>
	            </div>
	        </div>
			
<div class="col-sm-6">
	        <div class="form-group width">
	            <label for="field-1" class="control-label">Address to start shift</label>
	            
	            
	                <input type="text" class="form-control" name="address_to_start_shift" placeholder="Address to start shift" value="<?php echo $_GET['startFrom']; ?>" >
	            </div>
	        </div>
			</div>
	        </div>
<div class="col col-lg-12 col-sm-12 col-md-12">
  <textarea placeholder="note (optional)" class="form-control" name="note"></textarea>
</div>
<div class="col col-lg-12 col-sm-12 col-md-12">
                    <div class="form-group width">
                       
                            <input type="submit" class="btn btn-primary btn-block" value="Add Rider">
                       
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





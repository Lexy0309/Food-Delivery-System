<?php 	

	require_once("Header.php"); 

	if($_SESSION['role']=="1")
	{
	    @header('Location: index.php');
	}

	
	    if($_GET['addAdminUser']=="ok") 
        {

            
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email= $_POST['email'];
            $phone=$_POST['phone'];
			$password=$_POST['password'];
			$role=$_POST['role'];
			
			if($_POST['role']==0)
			{
				$role_name='Super admin';
			}
			else if($_POST['role']==1){
				$role_name='Admin';
			}
			else if($_POST['role']==2){
				$role_name='Restaurant Manager';
			}
			else if($_POST['role']==3){
				$role_name='Rider Manager';
			}
			else if($_POST['role']==4){
				$role_name='Orders Manager';
			}
			else if($_POST['role']==5){
				$role_name='Customer Care Manager';
			}
			else if($_POST['role']==6){
				$role_name='Customer care representative';
			}
			else if($_POST['role']==7){
				$role_name='Call center Representative';
			}
			else if($_POST['role']==8){
				$role_name='Restaurant Management Representative';
			}
			else if($_POST['role']==9){
				$role_name='Rider Management Representative';
			}
			else if($_POST['role']==10){
				$role_name='Accounts Manager';
			}
			else if($_POST['role']==11){
				$role_name='Accountant';
			}
			else if($_POST['role']==12){
				$role_name='Dispatcher';
			}
			else if($_POST['role']==13){
				$role_name='Schedule Manager';
			}
			else if($_POST['role']==14){
				$role_name='Schedule Controller';
			}
			else if($_POST['role']==15){
				$role_name='Human resource manager';
			}

               $headers = array(
                "Accept: application/json",
                "Content-Type: application/json"
               );
               $data = array(
                "first_name" => $first_name, 
                "last_name" => $last_name, 
                "email" => $email,
				"phone" =>$phone,
				"password"=> $password,
                "role_name" => $role_name,
                "role" => $role
				);
				
				
				// var_dump($data); 								
				 // die();
				

               $ch = curl_init( $baseurl.'/addAdminUser');

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
               	echo "<script>window.location='adminusers.php?status=error';</script>";
               
               }
			   else{
                // echo "<div class='alert alert-success'>Successfully Edit User..</div>";
                echo "<script>window.location='adminusers.php?status=ok';</script>";
               }
        }
     	
     	if($_GET['disalbeUser']=="ok") 
        {

               $headers = array(
                "Accept: application/json",
                "Content-Type: application/json"
               );
               $data = array(
                "user_id" => $_GET['user_id'], 
                "active" => $_GET['active'], 
                );
				
				
				// var_dump($data); 								
				 // die();
				

               $ch = curl_init( $baseurl.'/EnableOrDisableAdminUser');

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
               	echo "<script>window.location='adminusers.php?status=error';</script>";
               
               }
			   else{
                // echo "<div class='alert alert-success'>Successfully Edit User..</div>";
                echo "<script>window.location='adminusers.php?status=ok';</script>";
               }
        }

?>
<?php 

if(isset($_SESSION['id'])) 
{ 

	if(@$_GET['updatePasswrod']=="ok")
		{
				$id = $_POST['idd'];
				$password = $_POST['password'];
			   $headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			   );
			   $data = array(
				"user_id" => $id,
				"password" => $password
				);
		
			   $ch = curl_init( $baseurl.'/editAdminUserPassword' );
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
		
			   if($http_code !== 200)
			   {
			   
				 echo "<script>window.location='adminusers.php?status=error';</script>";
			   
			   }
			   else
			   {
				 //echo "<div class='alert alert-success'>Successfully submitted..</div>";
				 echo "<script>window.location='adminusers.php?status=ok';</script>";
			   }
		}

?>
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View All Admin Users</h2></div>
		<div class="pull-right">  <a style='position: relative; top: 10px;' href='javascript:;' onClick='assignrole()' class='assignrole btn btn-default'>addAdminUser</a></div>
        <div class="clearfix"></div>
        <br>
       <table id="table-1" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
				

                    <th>ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Role Name</th>
                    <th>Role</th>
					<th>Created</th>
					<th>Action</th>
                </tr>
            </thead>
            <tbody>
<?PHP
                $url = $baseurl."/showAdminUsers";
                $params = "";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
				
				//var_dump($json_data);
				
				  foreach($json_data['msg'] as $str => $data) 
				  {
						?>
							<tr>
								<td><?php echo $data['UserAdmin']['id']; ?></td>
								<td><?php echo $data['UserAdmin']['email'] ?></td>
								<td><?php echo $data['UserAdmin']['first_name'] ?> &nbsp; <?php echo $data['UserAdmin']['last_name'] ?></td>
								<td><?php echo $data['UserAdmin']['phone'] ?></td>
								<td><?php echo $data['UserAdmin']['role_name'] ?></td>
								<td><?php echo $data['UserAdmin']['role'] ?></td>
								<td><?php echo $data['UserAdmin']['created'] ?></td>
								<td>
									<?php

										if($data['UserAdmin']['active']=="1")
										{
											?>
												<a href="?disalbeUser=ok&user_id=<?php echo $data['UserAdmin']['id']; ?>&active=0"  onclick="return confirm('Are you sure?')" class="btn btn-default btn-sm">
			                                        Block
			                                    </a>
											<?php
										}
										else
										if($data['UserAdmin']['active']=="0")
										{
											?>
												<a href="?disalbeUser=ok&user_id=<?php echo $data['UserAdmin']['id']; ?>&active=1"  onclick="return confirm('Are you sure?')" class="btn btn-default btn-sm">
			                                        UnBlock
			                                    </a>
											<?php
										}

									?>
                                    <a href='javascript:;' onClick='chnagePassword("<?php echo $data['UserAdmin']['id']; ?>")' class='btn btn-default btn-sm' style='cursor:pointer;'>Change Password</a>
								</td>
							</tr>
						<?php
				  }
            
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

function assignrole() {
   jQuery('#modal-9').modal('show', {backdrop: 'static'});
}
jQuery(document).ready(function(){
    jQuery(".assignrole").on("click", function(){
    
        jQuery('#modal-9').modal('show', {backdrop: 'static'});
    });
});

function chnagePassword(data)
{
	 var data = data;
   //console.log(data);

   jQuery('#modal-7').modal('show', {backdrop: 'static'});

   var htmltext = "";

   htmltext += '<div class="row">';
       htmltext += '<div class="col-md-12"><h3 style="margin:0 0 20px;">Change Password</h3><form role="form" method="post" action="adminusers.php?updatePasswrod=ok" class="form-horizontal form-groups-bordered"><div class="row"> <div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><label for="field-1" class=" control-label">Password</label><input type="hidden" name="idd" value='+data+'><input type="text" class="form-control" name="password" id="password"></div></div></div></div><div class="row"> <div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><input type="submit" class="btn btn-primary btn-block" value="Update Password"></div></div></div></div></form>';

        htmltext += '</div>';
    htmltext += '</div>';

    $('#modal-7 .modal-body').html(htmltext);
}


</script>

<?php require_once('footer.php'); ?>
</div>
	
		
	</div>
	
    
    <!-- Modal 7 (Ajax Modal)-->
        <div class="modal fade custom-width in" id="modal-7"  style=" background:#0000004d;">
            <div class="modal-dialog" style="width:50%; margin-top:100px;">
                <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
                    
                    <div class="modal-header">
            
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">&nbsp;</h4>
                    </div>
                    
                    <div class="modal-body">
                        content here.... 
                    </div>
        
                </div>
            </div>
        </div>
	<!-- Modal 9-->
	
	

<div class="modal fade custom-width in" id="modal-9" style="margin-top:180px">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><center>Add Admin User</center></h4>
            </div>
            <div class="modal-body">

			
			  <form role="form" method="post" action="adminusers.php?addAdminUser=ok" class="form-horizontal form-groups-bordered">
			 <div class="row">
			<div class="col col-lg-12 col-md-12 col-sm-12">
			<div class="col-lg-6 col-md-6 col-sm-6">
			 <div class="form-group width">
					
			            <label for="field-1" class=" control-label">First Name</label>
			                <input type="text" class="form-control" name="first_name" placeholder="First name" required>
			        </div>
					 </div>

			  			<div class="col-lg-6 col-md-6 col-sm-6">
			           <div class="form-group width">
			            <label for="field-1" class=" control-label">Last Name</label>
			                <input type="text" class="form-control" name="last_name"  placeholder="Last name" required>
			            </div>
			        </div>
					</div>
			        </div>
					     <div class="row">
						<div class="col col-lg-12 col-md-12 col-sm-12">
			<div class="col-lg-6 col-md-6 col-sm-6">
			 <div class="form-group width">
					 
			            <label for="field-1" class=" control-label">Email</label>
			                 <input type="email" class="form-control" name="email" placeholder="Email" required>
			        </div>
					 </div>

			  			<div class="col-lg-6 col-md-6 col-sm-6">
			           <div class="form-group width">
			            <label for="field-1" class=" control-label">Phone</label>
			                <input type="text" class="form-control" name="phone"  placeholder="Phone No" required>
			            </div>
			        </div>
					</div>
			        </div>
					
					
					<div class="row">
						<div class="col col-lg-12 col-md-12 col-sm-12">
			        <div class="col-lg-6 col-md-6 col-sm-6">
			         <div class="form-group width">
					 <input  type="hidden" class="form-control" name="user_id" id="user_id">
			            <label for="field-1" class=" control-label">Password</label>
			                 <input type="password" class="form-control" name="password" placeholder="Password" required>
			        </div>
					 </div>

			  			<div class="col-lg-6 col-md-6 col-sm-6">
			           <div class="form-group width " >
			            <label for="field-1" class=" control-label">Role</label>
						
						<select name="role" class="form-control">
							<option value="0">Super admin</option>
							<option value="1">Admin</option>
							<option value="2">Restaurant Manager</option>
							<option value="3">Rider Manager</option>
							<option value="4">Orders Manager</option>
							<option value="5">Customer Care Manager</option>
							<option value="6">Customer care representative</option>
							<option value="7">Call center Representative</option>
							<option value="8">Restaurant Management Representative</option>
							<option value="9">Rider Management Representative</option>
							<option value="10">Accounts Manager</option>
							<option value="11">Accountant</option>
							<option value="12">Dispatcher</option>
							<option value="13">Schedule Manager</option>
							<option value="14">Schedule Controller</option>
							<option value="15">Human resource manager</option>
						</select>
			              
			            </div>
			        </div>
					</div>
			        </div>
		
			
			<div class="row">			
            <div class="col col-lg-12 col-md-12 col-sm-12">	
                    <div class="col-lg-12 col-md-12 col-sm-6">
                    <div class="form-group width">
			              <input type="submit" class="btn btn-primary btn-block" value="Add Admin User">
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
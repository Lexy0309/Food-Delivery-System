<?php 

require_once("Header.php"); 

if($_SESSION['role']=="1")
{
    @header('Location: index.php');
}

?>
<?php if(isset($_SESSION['id'])) { 


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

       $ch = curl_init( $baseurl.'/editUserPassword' );
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
       
         echo "<script>window.location='users.php?status=error';</script>";
       
       }
       else
       {
         //echo "<div class='alert alert-success'>Successfully submitted..</div>";
         echo "<script>window.location='users.php?status=ok';</script>";
       }
}

 if(isset($_GET['edit'])){
        if($_GET['edit']=="ok") {

            $user_id = $_POST['user_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
          

               $headers = array(
                "Accept: application/json",
                "Content-Type: application/json"
               );
               $data = array(
                "user_id" => $user_id,
                "first_name" => $first_name, 
                "last_name" => $last_name, 
                "email" => $email 
              );

               $ch = curl_init( $baseurl.'/editUserProfile' );

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
               
               }
 else{
                 //echo "<div class='alert alert-success'>Successfully Edit User..</div>";
                 echo "<script>window.location='users.php?status=ok';</script>";
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
        
        <div class="pull-left"><h2 class="toast-title">All Users</h2></div>
        
        <div class="clearfix"></div>
        <br>

	<table id="table-1" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
												<th>ID</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Role</th>
												<th>Device Token</th>
												<th>Reg Date</th>
												<th>Action</th>
											</tr>
                                        </thead>
                                        <tfoot>
                                           <tr>
												<th>ID</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Role</th>
												<th>Device Token</th>
												<th>Reg Date</th>
												<th>Action</th>
											</tr>
                                        </tfoot>
                                        <tbody>
                                           <?php 
												$url = $baseurl."/showAllUsers";
												$params = "";

												$ch = curl_init($url);

												curl_setopt($ch, CURLOPT_POST, 1);
												curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
												curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
												$result = curl_exec($ch);
												$json_data = json_decode($result, true);
												//var_dump($json_data);
												$i=0;
												foreach($json_data['msg'] as $str => $data) {
													//var_dump($data);
													if(!empty($data['UserInfo']['user_id'])) {
														echo "<tr>
															<td>".$data['UserInfo']['user_id']."</td>
															<td>".$data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']."</td>
															<td>".$data['User']['email']."</td>
															<td>".$data['UserInfo']['phone']."</td>
															<td>".$data['User']['role']."</td>
															<td>";
															if( !empty($data['UserInfo']['device_token']) ) {
																echo "Available";
															} else {
																echo "NULL";
															}
															echo "</td>
															<td>".convertintotime($data['User']['created'])."</td>";
														
														?>
                                                            
                                                            <td>
                                                                <a class='btn btn-default btn-sm' 
        onClick="editUserProfile('<?php echo $data['User']['id']; ?>','<?php echo $data['User']['email']; ?>','<?php echo $data['UserInfo']['first_name']; ?>','<?php echo $data['UserInfo']['last_name']; ?>')" style='cursor:pointer;' >Edit User</a>
                                                                 
                                                                <a href='javascript:;' onClick='getuser_details(<?php echo htmlspecialchars(json_encode($data), ENT_QUOTES); ?>)' class='btn btn-default btn-sm' style='cursor:pointer;'>View Details</a>
                                                                <a href='javascript:;' 
                                                                onClick='chnagePassword(<?php echo $data['User']['id']; ?>)' class='btn btn-default btn-sm' style='cursor:pointer;'>Change Password</a>
                                                            
                                                            </td>
                                                        </tr>
                                                        
														<?php
													}
													$i++;
												}

												curl_close($ch);
												?>
                                        </tbody>
                                    </table>
                               

    <script>
// jQuery( document ).ready( function( $ ) {
// var $table1 = jQuery( '#example23' );
// // Initialize DataTable
// $table1.DataTable( {
// "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
// "bStateSave": true
// });
// // Initalize Select Dropdown after DataTables is created
// $table1.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {
// minimumResultsForSearch: -1
// });
// } );

	
	
    $('#table-1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>

<style>
	.modal-backdrop
	{ z-index:0 !important;}
</style>

<script type="text/javascript">

function chnagePassword(data)
{
	 var data = data;
   //console.log(data);

   jQuery('#modal-7').modal('show', {backdrop: 'static'});

   var htmltext = "";

   htmltext += '<div class="row">';
       htmltext += '<div class="col-md-12"><h3 style="margin:0 0 20px;">Change Password</h3><form role="form" method="post" action="users.php?updatePasswrod=ok" class="form-horizontal form-groups-bordered"><div class="row"> <div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><label for="field-1" class=" control-label">Password</label><input type="hidden" name="idd" value='+data+'><input type="text" class="form-control" name="password" id="password"></div></div></div></div><div class="row"> <div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><input type="submit" class="btn btn-primary btn-block" value="Update Password"></div></div></div></div></form>';

        htmltext += '</div>';
    htmltext += '</div>';

    $('#modal-7 .modal-body').html(htmltext);
}


function getuser_details(data) {

   var data = data;
   //console.log(data);

   jQuery('#modal-7').modal('show', {backdrop: 'static'});

   var htmltext = "";

   htmltext += '<div class="row">';
       htmltext += '<div class="col-md-12"><h3 style="margin:0 0 20px;">User Details</h3>';

           for ( var key in data ) {

                if( key == "UserInfo" ) {
                    htmltext += '<p> ID: ' + data["User"].id + '</p>';
                    htmltext += '<p> Name: ' + data["UserInfo"].first_name + ' ' + data["UserInfo"].last_name + '</p>';
                    htmltext += '<p> Email: ' + data["User"].email + '</p>'; 
                    htmltext += '<p> Phone: ' + data["UserInfo"].phone + '</p>'; 
                    htmltext += '<p> Role: ' + data["User"].role + '</p>'; 
                    htmltext += '<p> Created: ' + data["User"].created + '</p>'; 
                    htmltext += '<p> Active: ' + data["User"].active + '</p>'; 
                }
                 
            }

        htmltext += '</div>';
    htmltext += '</div>';

    $('#modal-7 .modal-body').html(htmltext);

}
</script>

<!--edit user-->
<script type="text/javascript">
function addtax() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}


function editUserProfile(id,email,fname,lname) {
  
   var id = id;
   var email = email;
   var fname = fname;
   var lname = lname;
   
   //alert(id);
  //console.log(data);

   jQuery('#modal-7').modal('show', {backdrop: 'static'});

   var htmltext = "";

   htmltext += '<div class="row">';
       htmltext += '<div class="col-md-12"><h3 style="margin:0 0 20px;">Edit User</h3><form role="form" method="post" action="users.php?edit=ok" class="form-horizontal form-groups-bordered"><div class="row"><div class="col col-lg-12 col-md-12 col-sm-12">	<div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group width"><input  type="hidden" class="form-control" value="'+id+'" name="user_id" id="user_id"><label for="field-1" class=" control-label">First Name</label><input type="text" class="form-control" name="first_name" id="first_name" value="'+fname+'"></div></div><div class="col-lg-6 col-md-6 col-sm-6"><div class="form-group width"><label for="field-1" class=" control-label">Last Name</label><input type="text" class="form-control" name="last_name" value="'+lname+'"  id="last_name"></div></div></div></div><div class="row"><div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><label for="field-1" class=" control-label">Email</label><input type="email" class="form-control" name="email" id="email" value="'+email+'"></div></div></div></div><div class="row"><div class="col col-lg-12 col-md-12 col-sm-12"><div class="col-lg-12 col-md-12 col-sm-6"><div class="form-group width"><input type="submit" class="btn btn-primary btn-block" value="Update User"></div></div></div></div></form>';

        htmltext += '</div>';
    htmltext += '</div>';

    $('#modal-7 .modal-body').html(htmltext);
   
}


</script>
<!--end edit user-->
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>
<!-- Modal 8-->

<div class="modal fade custom-width in" id="modal-8"  style=" background:#0000004d;">
    <div class="modal-dialog" style="width:40%; margin-top:100px;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <div class="modal-body">
		
			  <form role="form" method="post" action="users.php?edit=ok" class="form-horizontal form-groups-bordered">
			 	<div class="row">
					<div class="col col-lg-12 col-md-12 col-sm-12">
						<div class="col-lg-6 col-md-6 col-sm-6">
			 				<div class="form-group width">
					 			<input  type="hidden" class="form-control" name="user_id" id="user_id">
			            		<label for="field-1" class=" control-label">First Name</label>
			                	<input type="text" class="form-control" name="first_name" id="first_name">
			        		</div>
					 	</div>

			  			<div class="col-lg-6 col-md-6 col-sm-6">
			           		<div class="form-group width">
                                <label for="field-1" class=" control-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name"  id="last_name">
			            	</div>
			        	</div>
					</div>
			 	</div>
                
				<div class="row">			
            		<div class="col col-lg-12 col-md-12 col-sm-12">	
                    	<div class="col-lg-12 col-md-12 col-sm-6">
                    		<div class="form-group width">
                                <label for="field-1" class=" control-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email">
			            	</div>
			        	</div>
					</div>
				</div>
			
				<div class="row">			
            		<div class="col col-lg-12 col-md-12 col-sm-12">	
                    	<div class="col-lg-12 col-md-12 col-sm-6">
                    		<div class="form-group width">
			              		<input type="submit" class="btn btn-primary btn-block" value="Update User">
			            	</div>
			        	</div>
					</div>
				</div>
			</form>	
			</div>
		</div>
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


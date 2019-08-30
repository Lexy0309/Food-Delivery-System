<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>

<body class="page-body" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View All Documents</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px; display:none;" href='#' class='btn btn-default'>Add Document</a></div>
        <div class="clearfix"></div>
        <br>
        <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>File</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if( isset($_GET['user']) ) {
                    $user_id = $_GET['user'];
                } else {
                    $user_id = "0";
                }
                
                $headers = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                );

                $data = array(
                    "user_id" => $user_id
                );

                $ch = curl_init( $baseurl.'/showAllUserVerificationDocuments' );

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $return = curl_exec($ch);

                $json_data = json_decode($return, true);
                //var_dump($json_data);

                $curl_error = curl_error($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if($json_data['code'] !== 200){
                    //echo "<div class='alert alert-danger'>".$json_data['msg']."</div>";

                } else {
                    //echo "<div class='alert alert-success'>".$json_data['msg']."</div>";
                    $i=0;
                    foreach($json_data['msg'] as $str => $data) {
                        //var_dump($data);
                        if(!empty($data['VerificationDocument']['id'])) {
                            echo "<tr>
                                <td>".$data['VerificationDocument']['id']."</td>
                                <td><a href='".$image_baseurl."/".$data['VerificationDocument']['file']."' target='_blank'> <img src='".$image_baseurl."/".$data['VerificationDocument']['file']."' width='120px'></a></td>
                                <td>".$data['VerificationDocument']['description']."</td>
                                <td>"; 
                                    if( $data['VerificationDocument']['status'] == 0 ) {
                                        echo "<label class='label label-default label-warning'>Pending</label>";
                                    } else if( $data['VerificationDocument']['status'] == 1 ) {
                                        echo "<label style='color:#fff;' class='label label-default label-success'>Approved</label>";
                                    }  else if( $data['VerificationDocument']['status'] == 2 ) {
                                        echo "<label style='color:#fff;' class='label label-default label-danger'>Rejected</label>";
                                    } else {
                                        
                                    }
                                echo "</td>
                                <td>
									<a href='javascript:;' onClick='updated_status(".$data['VerificationDocument']['id'].")' class='btn btn-default btn-sm'>Update Status</a>
								</td>
                            </tr>";
                        }
                        $i++;
                    }
                    
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
/*function getuser_details(data) {

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
                    htmltext += '<p> Address: ' + data["UserInfo"].lat + ', ' + data["UserInfo"].long + '</p>'; 
                    htmltext += '<p> Device Token: ' + data["UserInfo"].id + '</p>';
                    htmltext += '<p> Role: ' + data["User"].role + '</p>'; 
                    htmltext += '<p> Created: ' + data["User"].created + '</p>'; 
                    htmltext += '<p> Active: ' + data["User"].active + '</p>'; 
                }

            }

        htmltext += '</div>';
    htmltext += '</div>';

    $('#modal-7 .modal-body').html(htmltext);

}*/

function updated_status(doc_id) {
    jQuery("input[id='docidd']").val(doc_id);
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}
</script>
<?php require_once('footer.php'); ?>
</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Status</h4>
            </div>
            
            <div class="modal-body">

                <?php 
                if(isset($_GET['upd'])){
                    if($_GET['upd']=="ok") {

                        $id = $_POST['docidd'];
                        $status = $_POST['stt'];

                           $headers = array(
                            "Accept: application/json",
                            "Content-Type: application/json"
                           );
                           $data = array(
                            "id" => $id, 
                            "status" => $status
                            );

                           $ch = curl_init( $baseurl.'/updateVerificationDocumentStatus' );

                           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                           curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                           curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                           $return = curl_exec($ch);

                           $curl_error = curl_error($ch);
                           $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                           curl_close($ch);

                           if($http_code !== 200){
                                echo "<script>window.location='verifieddocs.php?user=".$_GET['user']."';</script>";
                           }else{
                                echo "<script>window.location='verifieddocs.php?user=".$_GET['user']."';</script>";
                           }

                    }
                }
                ?>
                <form role="form" method="post" action="verifieddocs.php?user=<?php echo $_GET['user']; ?>&upd=ok" class="form-horizontal form-groups-bordered">
                    <input type="hidden" class="form-control" id="docidd" name="docidd">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label">Status</label>
                        
                        <div class="col-sm-6">
                            <select class="form-control" name="stt">
								<option value="">Choose Status</option>
								<option value="0">Pending</option>
								<option value="1">Approved</option>
								<option value="2">Rejected</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <input type="submit" class="btn btn-primary" value="Update Status">
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
	
		
	</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width: 60%;">
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

	<?php require_once('footer_bottom.php');?>

</body>
</html>
<?php } else {
	@header('Location: login.php');
} ?>
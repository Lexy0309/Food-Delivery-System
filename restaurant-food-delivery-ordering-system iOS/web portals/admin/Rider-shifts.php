<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>

<?php

  if(isset($_GET['confirmTiming']) && isset($_GET['id']))
  {
       
       $id = $_GET['id'];
       $headers = array(
        "Accept: application/json",
        "Content-Type: application/json"
       );
       $data = array(
        "id" => $id,
        "admin_confirm" => "1"
        );

       $ch = curl_init( $baseurl.'/confirmRiderTiming' );
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
       
         echo "<script>window.location='Rider-shifts.php?status=error';</script>";
       
       }
       else
       {
         //echo "<div class='alert alert-success'>Successfully submitted..</div>";
         echo "<script>window.location='Rider-shifts.php?status=ok';</script>";
       }

  }   

  


?>






<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">All Rider Shifts</h2></div>
      <!--  <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class='btn btn-default'>Add Open Shift</a></div>-->
        <div class="clearfix"></div>
        <br>
          <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
                <tr>
				            <th>ID</th>
                    <th>Name</th>
					          <th>Phone </th>
					          <th>Date</th>
                    <th>Start - End</th>
                    <th>Rider Confirmation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $url = $baseurl."/showRiderTimings";
                $params = "";

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $json_data = json_decode($result, true);
                //echo var_dump($json_data);
                //$i=0;
				 //foreach($json_data['msg'] as $str => $data) {
					   
					   
					  //  foreach($data['RiderTiming'] as $d=>$rd){
							
							//var_dump($rd);
							
							// if($rd['user_id']==22){
							// echo $var1=$rd['starting_time'].'<br>';
							//}	
						//}
				// }
				
				       
                foreach($json_data['msg'] as $data) 
                {
                    //var_dump($data);
					           
                     foreach($data['RiderTiming'] as $data1) 
                     {
                        ?>
                          <tr>
                            <td><?php echo $data1['id']; ?></td>
                            <td><?php echo $data['UserInfo']['first_name'] ."&nbsp;". $data['UserInfo']['last_name']; ?></td>
                            <td><?php echo $data['UserInfo']['phone']; ?></td>
                            <td><?php echo $data1['date']; ?></td>
                            <td><?php echo $data1['starting_time'] ." - ". $data1['ending_time'] ; ?></td>
                            <td>
                                <?php 
                                  if($data1['confirm']=="0")
                                  {
                                      ?>
                                        <span style="padding: 2px 6px; border-radius: 3px; background: #f0f0f1;">Pending</span>
                                      <?php
                                  }
                                  else
                                  {
                                      ?>
                                        <span style="padding: 2px 6px; border-radius: 3px; color: white; background:#be2c2c;">Confirmed</span>
                                      <?php
                                  }
                                ?>
                            </td>
                            <td>

                                <?php 
                                  if($data1['admin_confirm']=="0")
                                  {
                                      ?>
                                        <a href='?confirmTiming=ok&id=<?php echo $data1['id']; ?>' onclick="return confirm('Are you sure?')">
                                            <span style="padding: 4px 6px; border-radius: 3px; background: #f0f0f1;">Confirm Shift</span>
                                        </a>
                                      <?php
                                  }
                                  else
                                  {
                                      ?>
                                        <span style="padding: 2px 6px; border-radius: 3px; color: white; background:#be2c2c;">Confirmed</span>
                                      <?php
                                  }
                                ?>

                                
                            </td>
                          </tr>     
                        <?php
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
jQuery(document).ready(function(){
	
	 $('.viewridertiming').on('click',function(){
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
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Rider Timings</h4>
            </div>
            
            <div class="modal-body">
            
               

            </div>

        </div>
    </div>
</div>

<style>
.form-group.width {
    width: 100%;
}
</style>







<?php 

require_once('footer_bottom.php');

} else {
	@header('Location: login.php');
} ?>
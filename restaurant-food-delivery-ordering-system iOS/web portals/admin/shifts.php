<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { ?>
<?php

  if(isset($_GET['id']))
  {

     $id = $_GET['id'];
     $headers = array(
      "Accept: application/json",
      "Content-Type: application/json"
     );
     $data = array(
      "id" => $id
      );

     $ch = curl_init( $baseurl.'/deleteOpenShift' );
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
      echo "<script>window.location='shifts.php';</script>";
     }

  } 

?>
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    
    <?php require_once('left-sidebar.php'); ?>
    <div class="main-content">
    <?php require_once('topbar.php'); ?>
    <div class="row">
        <div class="col-md-12">
        <div class="pull-left">
            <h2 class="toast-title">All Open Shifts</h2>
          </div>
        <div class="pull-right"><a style="position: relative; top: 10px;" href='javascript:;' onClick='addtax()' class='btn btn-default'>Add Open Shift</a></div>
        <div class="clearfix"></div>
        <br>
        <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
            <thead>
            <tr>
                <th>ID</th>
                <th>date</th>
                <th>starting_time</th>
                <th>ending_time</th>
                <th>rider_user_id</th>
                <th>shift</th>
                <th>created</th>
                <th>Action</th>
              </tr>
          </thead>
            <tbody>
            <?php 
                $url = $baseurl."/showOpenShifts";
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
                    if(!empty($data['OpenShift']['id'])) {
                        echo "<tr>
            
            
                            <td>".$data['OpenShift']['id']."</td>
                            <td>".$data['OpenShift']['date']."</td>
                            <td>".$data['OpenShift']['starting_time']."</td>
                            <td>".$data['OpenShift']['ending_time']."</td>
                            <td>".$data['OpenShift']['rider_user_id']."</td>
               <td>".$data['OpenShift']['shift']."</td>
                <td>".$data['OpenShift']['created']."</td>




                           
                            <td>
                                <a style='cursor:pointer;' data-id='".$data['OpenShift']['id']."' data-date='".$data['OpenShift']['date']."' data-starting_time='".$data['OpenShift']['starting_time']."' data-ending_time='".$data['OpenShift']['ending_time']."'   class='editcurrency btn btn-default btn-sm'>Edit Open Shift</a> 
                ";?>
          <a href="shifts.php?id=<?php echo $data['OpenShift']['id'];?>" class='btn btn-danger btn-sm '> Delete</a>
            <?php
                            "</td>
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
function addtax() {
   jQuery('#modal-7').modal('show', {backdrop: 'static'});
}




jQuery(document).ready(function(){
    jQuery(".editcurrency").on("click", function(){
        var id = jQuery(this).attr('data-id');
        var date = jQuery(this).attr('data-date');
        var starting_time = jQuery(this).attr('data-starting_time');
        var ending_time = jQuery(this).attr('data-ending_time');
        


        jQuery('#id').val(id);
        jQuery('#date').val(date);
        jQuery('#starting_time').val(starting_time);
        jQuery('#ending_time').val(ending_time);

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
        <h4 class="modal-title">Add Shift</h4>
      </div>
        <div class="modal-body">
        <?php 
                    if(isset($_GET['insert'])){
                        if($_GET['insert']=="ok") {
              
              

                            $date = $_POST['date'];
                            $starting_time = $_POST['starting_time'];
                            $ending_time = $_POST['ending_time'];
                        
                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "date" => $date, 
                                "starting_time" => $starting_time, 
                                "ending_time" => $ending_time
                                );
                               $ch = curl_init( $baseurl.'/addOpenShift' );
                              
                              // {"date":"2018-02-20",
                              // "starting_time":"10:17:27",
                              // "ending_time":"10:30:27"

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
                                echo "<script>window.location='shifts.php';</script>";
                               }

                                
                        }
                    }   
                ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
        <script>
        jQuery(document).ready(function($) {
        $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        } );
  
  </script>
        <form role="form" method="post" action="shifts.php?insert=ok" class="form-horizontal form-groups-bordered">
            <div class="row">
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label">Date</label>
                <input id="datepicker" type="text" class="form-control" name="date" placeholder="date" required>
              </div>
              </div>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label">Start Time</label>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript" ></script> 
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
                <div class='input-group date' id='datetimepicker3'>
                    <input type='text' class="form-control"  name="starting_time" placeholder="starting time" required/>
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div>
                <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker3').datetimepicker({
                                        format: 'HH:mm:ss'
                                    });
                   $('#datetimepicker2').datetimepicker({
                                        format: 'HH:mm:ss'
                                    });
                                });
               </script> 
              </div>
              </div>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label">End Time</label>
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="ending_time" placeholder="ending_time" required/>
                    <span class="input-group-addon"> <span class="glyphicon glyphicon-time"></span> </span> </div>
              </div>
              </div>
            <br>
            <br>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <input type="submit" class="btn btn-primary btn-block" value="Add Shift">
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
        <h4 class="modal-title">Edit Shift</h4>
      </div>
        <div class="modal-body">
        <?php 
                    if(isset($_GET['edit'])){
                        if($_GET['edit']=="ok") {
    
                            $id = $_POST['id'];
              $date = $_POST['date'];
                            $starting_time = $_POST['starting_time'];
                            $ending_time = $_POST['ending_time'];

                               $headers = array(
                                "Accept: application/json",
                                "Content-Type: application/json"
                               );
                               $data = array(
                                "id" => $id,
                                "date" => $date, 
                                "starting_time" => $starting_time, 
                                "ending_time" => $ending_time
                               
                                );

                               $ch = curl_init( $baseurl.'/addOpenShift' );

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
                                echo "<script>window.location='shifts.php';</script>";
                               }

                                
                        }
                    }   
                ?>
        <form role="form" method="post" action="shifts.php?edit=ok" class="form-horizontal form-groups-bordered">
            <input type="hidden" class="form-control" name="id" id="id">
            <div class="row">
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label ">Date</label>
                <input type="date" class="form-control" name="date" id="date" placeholder="date" required>
              </div>
              </div>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label">Start Time</label>
                <input type="text" class="form-control" name="starting_time" id="starting_time" placeholder="starting_time" required>
              </div>
              </div>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <label for="field-1" class="control-label">End Time</label>
                <input type="text" class="form-control" name="ending_time" id="ending_time" placeholder="ending_time" required>
              </div>
              </div>
            <div class="col col-lg-12 col-md-12 col-sm-12">
                <div class="form-group width">
                <input type="submit" class="btn btn-primary btn-block" value="Update Shift">
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
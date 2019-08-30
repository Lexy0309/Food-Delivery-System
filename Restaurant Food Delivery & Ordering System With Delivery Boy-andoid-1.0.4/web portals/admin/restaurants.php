<?php require_once("Header.php"); ?>
<?php if(isset($_SESSION['id'])) { 


if(isset($_GET['blockstatus']))
{

    if($_GET['blockstatus']=="ok") 
    {

        $id = $_GET['id'];
        $block = $_GET['block'];


        $headers = array(
        "Accept: application/json",
        "Content-Type: application/json"
        );
        $data = array(
        "id" => $id,
        "block" => $block
        );

        $ch = curl_init( $baseurl.'/blockRestaurant' );

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
            echo "<script>window.location='restaurants.php?status=error';</script>";
        }
         else
         {
             //echo "<div class='alert alert-success'>Successfully Edit User..</div>";
             echo "<script>window.location='restaurants.php?status=ok';</script>";
        }

                
    }
     
}
else
if($_GET['editRestaurant']=="ok") 
{
	//print_r($_POST);
	$searchReplaceArray = array(
	  '(' => '', 
	  ')' => '',
	  '-' => '',
	  '_' => '',
	  ' ' => ''
	);

	for($i=0; $i<7; $i++) {
		$opening_time[$i] = $_POST['opening_time'][$i];
		$closing_time[$i] = $_POST['closing_time'][$i];
		$day[$i] = $_POST['day'][$i];

		$restaurant_timings_details[] = array( 'opening_time' => $opening_time[$i], 'closing_time' => $closing_time[$i], 'day' => $day[$i] );
	}

	
	
	
	$restaurant_id = $_GET['restaurant_id'];
	$name = $_POST['name'];
	$currency_id=$_POST['currency_id'];//
	$preparation_time=30;//
	$speciality=$_POST['speciality'];//
	$tax_id=$_POST['tax_id'];//
	$slogan = $_POST['slogan'];
	$about = $_POST['about'];
	$phone = str_replace( array_keys($searchReplaceArray), array_values($searchReplaceArray), $_POST['phone'] );
	$timezone = $_POST['timezone'];
	$state = $_POST['state'];
	$menu_style = $_POST['menu_style'];
	$promoted = $_POST['promoted'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$zip = $_POST['zip'];
	$lat = $_POST['lat'];
	$long = $_POST['long'];
	$restaurant_timing = $restaurant_timings_details;
	/*$opening_time = $_POST['opening_time'];
	$closing_time = $_POST['closing_time'];
	$day = $_POST['day'];*/
	$email = $_POST['email'];
	$password = $_POST['password'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	//$image = $_POST['image'];

	$min_order_price = $_POST['min_order_price'];
	$delivery_free_range = $_POST['delivery_free_range'];
	$preparation_time = $_POST['preparation_time'];
	$tax_free = $_POST['tax_free'];
	$google_analytics = $_POST['google_analytics'];
  $notes = $_POST['notes'];


   // $image_base = file_get_contents($_FILES['upload_image']['tmp_name']);
	//$image = base64_encode($image_base);
	//Cover_upload_image
	//$image_base1 = file_get_contents($_FILES['Cover_upload_image']['tmp_name']);
	//$image1 = base64_encode($image_base1);


	   $headers = array(
		"Accept: application/json",
		"Content-Type: application/json"
	   );
	   $data = array(
		"restaurant_id" => $restaurant_id, 
		"name" => $name, 
		"currency_id"=>$currency_id,//
		"preparation_time"=>$preparation_time,//
		"speciality" =>$speciality,//

		"min_order_price" =>$min_order_price,//
		"delivery_free_range" =>$delivery_free_range,//
		"preparation_time" =>$preparation_time,//
		"tax_free" =>$tax_free,//
		"google_analytics" =>$google_analytics,//
    "notes" =>$notes,//


		"tax_id"=>$tax_id,//
		"slogan" => $slogan, 
		"about" => $about, 
		"phone" => $phone, 
		"timezone" => $timezone, 
		"state" => $state, 
		"menu_style" => $menu_style, 
		"promoted" => $promoted, 
		"city" => $city, 
		"country" => $country, 
		"zip" => $zip, 
		"lat" => $lat, 
		"long" => $long, 
		"restaurant_timing" => $restaurant_timing
		/*"opening_time" => $opening_time, 
		"closing_time" => $closing_time, 
		"day" => $day, */
		/*"email" => $email, 
		"password" => $password,
		"first_name" => $first_name, 
		"last_name" => $last_name, */
		//"image" => $image
		//"image" => array("file_data" => $image),
		//"cover_image" => array("file_data" => $image1)
		);
		
		
		$ch = curl_init( $baseurl.'/editRestaurant' );

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$return = curl_exec($ch);
		
		$json_data2 = json_decode($return, true);
		//var_dump($json_data2);

		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		
		if($http_code !== 200){
         //echo "<div class='alert alert-danger'>".$curl_error."</div>";
            echo "<script>window.location='restaurants.php?status=error';</script>";
        }
         else
         {
             //echo "<div class='alert alert-success'>Successfully Edit User..</div>";
             echo "<script>window.location='restaurants.php?status=ok';</script>";
        }
		//echo json_encode( $data,true);
}

?>


<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
	<div class="row">
        <div class="col-md-12">
        
        <div class="pull-left"><h2 class="toast-title">View all Restaurants</h2></div>
        <div class="pull-right"><a style="position: relative; top: 10px;" href='add-restaurant.php' class='btn btn-default'>Add Restaurant</a></div>
        <div class="clearfix"></div>
        <br>
       <table id="table-1" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Owner Name</th>
                    <th>Owner Phone</th>
                    <th>Phone</th>
                    <th>Note</th>
                    <th>Prep Time</th>
                    <th>Promoted</th>
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
                //var_dump($json_data);
                $i=0;
                foreach($json_data['msg'] as $str => $data) {
                    //var_dump($data);
                    if(!empty($data['Restaurant']['id'])) {
                        echo "<tr>
                            <td>".$data['Restaurant']['id']."</td>
                            <td>".$data['Restaurant']['name']."</td>
                            <td title=".$data['User']['id'].">".$data['User']['email']."</td>
                            <td>".$data['UserInfo']['first_name']." ".$data['UserInfo']['last_name']."</td>
                            <td>".$data['UserInfo']['phone']."</td>
                            <td>".$data['Restaurant']['phone']."</td>
                            <td>".$data['Restaurant']['notes']."</td>
                            <td>".$data['Restaurant']['preparation_time']." min</td>
                            <td>".$data['Restaurant']['promoted']."</td>
                            <td>
							
							 <a href='manage_menu.php?resid=".$data['Restaurant']['id']."&userid=".$data['UserInfo']['user_id']."' class='btn btn-default btn-sm' style='margin-bottom:5px;' target='_blank'>Manage menu</a>
							
                                <a href='javascript:;' onClick='getresto_details(".$data['Restaurant']['id'].")' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;'>View Details</a>


                                

                                <a href='hotel_deals.php?resid=".$data['Restaurant']['id']."&userid=".$data['UserInfo']['user_id']."' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;' target='_blank'>Deals</a>

                                ";

                                //if($_SESSION['role']=='0')
                                //{
                                    ?>
                                        <a href='javascript:;' onClick='editresto_details(<?php echo $data['Restaurant']['id']; ?>)' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;'>Edit Details</a>
                                    <?php
                                //}

                                if($_SESSION['role']=="0")
                                {
                                    if($data['Restaurant']['block']=="0")
                                    {
                                         ?>
                                            <a onClick="return confirm('Are you sure?')" href='restaurants.php?blockstatus=ok&id=<?php echo $data['Restaurant']['id']; ?>&block=1' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px;'>Block </a>
                                        <?php
                                    }
                                    else
                                    if($data['Restaurant']['block']=="1")
                                    {
                                        ?>
                                            <a onClick="return confirm('Are you sure?')" href='restaurants.php?blockstatus=ok&id=<?php echo $data['Restaurant']['id']; ?>&block=0' class='btn btn-default btn-sm' style='cursor:pointer;margin-bottom:5px; background:red; color: white; ' >Unblock </a>
                                        <?php
                                    } 
                                }
                             ?>
                                    
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
function getresto_details($id) 
{

    jQuery('#modal-7').modal('show', {backdrop: 'static'});
    document.getElementById("hotel_details_popup").innerHTML="loading...";
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
            document.getElementById("hotel_details_popup").innerHTML=xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","ajex-events.php?getHotelDetails=ok&resID="+$id);
    xmlhttp.send();

   // var data = data;

   // //console.log(data);

   // jQuery('#modal-7').modal('show', {backdrop: 'static'});

   // var htmltext = "";

   // htmltext += '<div class="row">';

   //  htmltext += '</div>';

   //  $('#modal-7 .modal-body').html(htmltext);

}

function editresto_details($id) 
{
    
    jQuery('#modal-7').modal('show', {backdrop: 'static'});
    document.getElementById("hotel_details_popup").innerHTML="loading...";
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
            document.getElementById("hotel_details_popup").innerHTML=xmlhttp.responseText;
        }
      }
    xmlhttp.open("GET","ajex-events.php?editHotelDetails=ok&resID="+$id);
    xmlhttp.send();

}    
</script>
<?php require_once('footer.php'); ?>
</div>
	
		
	</div>

<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content" style="max-height: 500px; overflow-x: hidden;">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            
            <div class="modal-body" id="hotel_details_popup" style="margin: 20px; overflow: scroll;">
                Loading...
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
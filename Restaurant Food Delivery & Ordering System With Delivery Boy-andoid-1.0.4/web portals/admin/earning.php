<?php 
require_once("Header.php"); 

require_once("config.php");
if(isset($_SESSION['id'])) 
{ 

    $headers = array(
	"Accept: application/json",
	"Content-Type: application/json"
   );
   $data = array();
	
	
	// var_dump($data); 								
	 // die();
	

   $ch = curl_init( $baseurl.'/showWeeklyAndMonthlyOrders');

   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   $return = curl_exec($ch);
	
   $json_data = json_decode($return, true);
				
   $curl_error = curl_error($ch);
   $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

   curl_close($ch);
   
   $totalEarning = 0;
   foreach($json_data['msg'] as $str => $data) 
   {
	  if($data['Order']['status']=="2")
	  {
	  	$totalEarning+= $data['Order']['price']; 
	  }
   }
   
   
   $totalResturentEarning = 0;
   foreach($json_data['msg'] as $str => $data) 
   {
	  if($data['Order']['status']=="2")
	  {
	  	$totalResturentEarning+= $data['Order']['sub_total']+$data['Order']['tax']; 
	  }
   }


   $totalRiderEarning = 0;
   foreach($json_data['msg'] as $str => $data) 
   {
	  if($data['Order']['status']=="2")
	  {
	  	$totalRiderEarning+= $data['Order']['delivery']+$data['Order']['rider_tip']; 
	  }
   } 
   
   
   $totalRiderEarning = 0;
   foreach($json_data['msg'] as $str => $data) 
   {
	  if($data['Order']['status']=="2")
	  {
	  	$totalRiderEarning+= $data['Order']['delivery_fee']+$data['Order']['rider_tip']; 
	  }
   }   
	
  
?>

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php require_once('left-sidebar.php'); ?>			
	<div class="main-content">
	<?php require_once('topbar.php'); ?>		
	
    <div class="row">
        <div class="col-sm-4">
            <div class="tile-stats tile-red">
                <div class="icon"><i class="entypo-users"></i></div>
                <!--<div class="num" data-start="0" data-end="20" data-postfix="" data-duration="1500" data-delay="0">0</div>-->
                
                <div class="num"><?php echo number_format($totalEarning); ?></div>
                
                <h3>Total Earning</h3>
                
            </div>
            
        </div>
        <div class="col-sm-4">
        	<a href="?restaurentEarning=ok">
                <div class="tile-stats tile-green" style="padding-bottom: 10px;  padding-top: 12px;">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <div class="num"><?php echo number_format($totalResturentEarning); ?></div>
                    
                    <h3>Restaurant Earning</h3>
                    <span style="font-size:10px; color:white;">Click To View Details</span>
                </div>
            </a>
        </div>
        
        <div class="col-sm-4">
        
            <div class="tile-stats tile-aqua">
                <div class="icon"><i class="entypo-mail"></i></div>
                <div class="num"><?php echo number_format($totalRiderEarning); ?></div>
                
                <h3>Riders Earning</h3>
                
            </div>
            
        </div>
       
    </div>
    
    <?php
    	
		if(@$_GET['restaurentEarning']=="ok")
		{
	
	?>
			<div class="row">
                <div class="col-md-12">
                
                <div class="pull-left"><h2 class="toast-title">Earning Restaurant Based</h2></div>
                <div class="pull-right" style="margin:20px;"></div>
                <div class="clearfix"></div>
                <br>
                <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Restaurant Name</th>
                            <th>Earned Amount</th>
                            <th>Cancelled/Rejected Amount</th>
                            <th>Active Order Amount</th>
                            <th>Rider Earning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $url = $baseurl."/showRestaurantOrders";
                        
                        
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($ch);
                        $json_data = json_decode($result, true);
                        //print_r(json_encode($params));
                       /* echo"<pre>";
                        var_dump($json_data);
                        echo"</pre>";*/
                        foreach($json_data['msg'] as $str => $data) {
                        			
									
                               ?>
                                <tr>
                                    <td><?php echo $data['Restaurant']['id']; ?></td>
                                    <td><?php echo $data['Restaurant']['name']; ?></td>
                                    <td>
										<?php 
											$earnedAmount=0;
											foreach($data['Order'] as $str => $data1) 
											{
												//echo $data1['id'];
												if($data1['status']=="2")
												{
													$earnedAmount+=$data1['sub_total']+$data1['tax'];	
												}
											}
											echo 'RS '.$earnedAmount;
										?>
                                 	</td>
                                    <td>
										<?php 
											$earnedAmount=0;
											foreach($data['Order'] as $str => $data1) 
											{
												//echo $data1['id'];
												if($data1['status']=="4")
												{
													$earnedAmount+=$data1['sub_total']+$data1['tax'];	
												}
											}
											echo 'RS '.$earnedAmount;
										?>
                                 	</td>
                                    <td>
										<?php 
											$earnedAmount=0;
											foreach($data['Order'] as $str => $data1) 
											{
												//echo $data1['id'];
												if($data1['status']=="1")
												{
													$earnedAmount+=$data1['sub_total']+$data1['tax'];	
												}
											}
											echo 'RS '.$earnedAmount;
										?>
                                 	</td>
                                    <td>
										<?php 
											$earnedAmount=0;
											foreach($data['Order'] as $str => $data1) 
											{
												//echo $data1['id'];
												if($data1['status']=="1")
												{
													$earnedAmount+=$data1['delivery_fee']+$data1['rider_tip'];	
												}
											}
											echo 'RS '.$earnedAmount;
										?>
                                 	</td>
                                </tr>
                              <?php
                           
                            
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
            
        
                function assginedOrders()
                {
                    $(document).ready(function(){
                        $( "#table-1_filter input" ).val("assigned ");
                        $('#table-1_filter input').keyup();
                    });
        
                    
                }
        
                </script>
                 
                    
                </div>
            </div>    
	
    
    <?php
		}
	?>
<style type="text/css">
    .getorderdtals .col-md-6 {
        min-height: 450px;
    }
</style>

<script type="text/javascript">


function getorder_details($id) 
{
    //alert('ad');
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
    xmlhttp.open("GET","ajex-events.php?getOrderDetails=ok&orderID="+$id);
    xmlhttp.send();

}  

/*function getorder_details(data) {

   var data = data;
   //console.log(data);

   jQuery('#modal-6').modal('show', {backdrop: 'static'});

   var htmltext = "";

   htmltext += '<div class="row getorderdtals">'; 

       for ( var key in data ) {

            if( key == "Order" ) {
                 htmltext += '<div class="col-md-12"><h3>Order Details</h3>';
                    htmltext += '<table class="table table-bordered">';
                    htmltext += '<tr> <td>ID:</td> <td>' + data[key].id + '</td> </tr>';
                    htmltext += '<tr> <td>Created:</td> <td>' + data[key].created + '</td> </tr>';
                    htmltext += '<tr> <td>Price:</td> <td> ' + data[key].price + '</td> </tr>';
                    htmltext += '<tr> <td>Status:</td> <td> ' + data[key].status + '</td> </tr>';
                    htmltext += '<tr> <td>User ID:</td> <td> ' + data[key].user_id + '</td> </tr>';
                    htmltext += '<tr> <td>Address ID:</td> <td> ' + data[key].address_id + '</td> </tr>';
                    htmltext += '<tr> <td>Payment Method ID:</td> <td> ' + data[key].payment_method_id + '</td> </tr>';
                    htmltext += '<tr> <td>Quantity:</td> <td> ' + data[key].quantity + '</td> </tr>';
                    htmltext += '<tr> <td>Restaurant ID:</td> <td> ' + data[key].restaurant_id + '</td> </tr>';
                    htmltext += '<tr> <td>Instructions:</td> <td> ' + data[key].instructions + '</td> </tr>';
                    htmltext += '</table>';
                htmltext += '</div>';
            }

            if( key == "Address" ) {
                 htmltext += '<div class="col-md-12"><h3>Address Details</h3>';
                    htmltext += '<table class="table table-bordered">';
                    htmltext += '<tr> <td> ID:</td> <td> ' + data[key].id + '</td> </tr>';
                    htmltext += '<tr> <td> Street:</td> <td> ' + data[key].street + '</td> </tr>';
                    //htmltext += '<tr> <td> Apartment:</td> <td> ' + data[key].apartment + '</td> </tr>';
                    htmltext += '<tr> <td> City:</td> <td> ' + data[key].city + '</td> </tr>';
                    htmltext += '<tr> <td> State:</td> <td> ' + data[key].state + '</td> </tr>';
                    htmltext += '<tr> <td> Lat long:</td> <td> ' + data[key].lat + ','+ data[key].long +'</td> </tr>';
                    //htmltext += '<tr> <td> Country:</td> <td> ' + data[key].country + '</td> </tr>';
                    htmltext += '<tr> <td> Instruction:</td> <td> ' + data[key].instruction + '</td> </tr>';
                    htmltext += '<tr> <td> Default:</td> <td> ' + data[key].default + '</td> </tr>';
                    htmltext += '<tr> <td> Created:</td> <td> ' + data[key].created + '</td> </tr>';
                    htmltext += '<tr> <td> User ID:</td> <td> ' + data[key].user_id + '</td> </tr>';
                    htmltext += '</table>';
                htmltext += '</div>';
            }

            if( key == "PaymentMethod" ) {
                 htmltext += '<div class="col-md-12"><h3>Payment Method</h3>';
                    htmltext += '<table class="table table-bordered">';
                    htmltext += '<tr> <td>ID:</td> <td> ' + data[key].id + '</td> </tr>';
                    htmltext += '<tr> <td>Stripe:</td> <td> ' + data[key].stripe + '</td> </tr>';
                    htmltext += '<tr> <td>Paypal:</td> <td> ' + data[key].paypal + '</td> </tr>';
                    htmltext += '<tr> <td>Created:</td> <td> ' + data[key].created + '</td> </tr>';
                    htmltext += '<tr> <td>User ID:</td> <td> ' + data[key].user_id + '</td> </tr>';
                    htmltext += '<tr> <td>Default:</td> <td> ' + data[key].default + '</td> </tr>';
                    htmltext += '</table>';
                htmltext += '</div>';
            }

            if( key == "UserInfo" ) {
                 htmltext += '<div class="col-md-12"><h3>User Information</h3>';
                    htmltext += '<table class="table table-bordered">';
                    htmltext += '<tr> <td> User ID:</td> <td> ' + data[key].user_id + '</td> </tr>';
                    htmltext += '<tr> <td> Name:</td> <td> ' + data[key].first_name + ', ' + data[key].last_name + '</td> </tr>';
                    htmltext += '<tr> <td> Phone:</td> <td> ' + data[key].phone + '</td> </tr>';
                    htmltext += '<tr> <td> Device Token:</td> <td> ' + data[key].device_token + '</td> </tr>';
                    htmltext += '<tr> <td> Address:</td> <td> ' + data[key].lat + ' ' + data[key].long + '</td> </tr>';
                    htmltext += '</table>';
                htmltext += '</div>';
            }

            

            if( key == "Restaurant" ) {
                 htmltext += '<div class="col-md-12"><h3>Restaurant Details</h3>';
                    htmltext += '<table class="table table-bordered">';
                    htmltext += '<tr> <td> ID:</td> <td> ' + data[key].id + '</td> </tr>';
                    htmltext += '<tr> <td> Name:</td> <td> ' + data[key].name + '</td> </tr>';
                    htmltext += '<tr> <td> Slogan:</td> <td> ' + data[key].slogan + '</td> </tr>';
                    htmltext += '<tr> <td> About:</td> <td> ' + data[key].about + '</td> </tr>';
                    htmltext += '<tr> <td> Delivery Fee:</td> <td> ' + data[key].delivery_fee + '</td> </tr>';
                    htmltext += '<tr> <td> Phone:</td> <td> ' + data[key].phone + '</td> </tr>';
                    htmltext += '<tr> <td> Timezone:</td> <td> ' + data[key].timezone + '</td> </tr>';
                    htmltext += '<tr> <td> Menu Style:</td> <td> ' + data[key].menu_style + '</td> </tr>';
                    htmltext += '<tr> <td> Promoted:</td> <td> ' + data[key].promoted + '</td> </tr>';
                    htmltext += '<tr> <td> User ID:</td> <td> ' + data[key].user_id + '</td> </tr>';
                    htmltext += '<tr> <td> Created:</td> <td> ' + data[key].created + '</td> </tr>';
                    htmltext += '</table>';
                htmltext += '</div>';
            }

            if( key == "OrderMenuItem" ) {
                 htmltext += '<div class="col-md-12"><h3>Order Menu Items</h3>';
                    datakeyys = data[key];
                    
                    for ( var key1 in datakeyys ) { 
                        htmltext += '<table class="table table-bordered">';
                        htmltext += '<tr><td> ID:</td> <td> ' + datakeyys[key1].id + '</td> </tr>';
                        htmltext += '<tr><td> Order ID:</td> <td> ' + datakeyys[key1].order_id + '</td> </tr>';
                        htmltext += '<tr><td> Name:</td> <td> ' + datakeyys[key1].name + '</td> </tr>';
                        htmltext += '<tr><td> Quantity:</td> <td> ' + datakeyys[key1].quantity + '</td> </tr>';
                        htmltext += '<tr><td> Price:</td> <td> ' + datakeyys[key1].price + '</td> </tr>';
                        htmltext += '<tr><td colspan="2">';
                            htmltext += '<h4 style="margin:10px 0; text-align:center; font-weight:bold;">Order Menu Extra Item</h4>';
                            dataextraitems = datakeyys[key1].OrderMenuExtraItem;
                            for ( var key2 in dataextraitems ) { 
                                htmltext += '<tr><td style="width:50%;"> ID:</td> <td style="width:50%;"> ' + dataextraitems[key2].id + '</td> </tr>';
                                htmltext += '<tr><td> Order Menu Item ID:</td> <td> ' + dataextraitems[key2].order_menu_item_id + '</td> </tr>';
                                htmltext += '<tr><td> Name:</td> <td> ' + dataextraitems[key2].name + '</td> </tr>';
                                htmltext += '<tr><td> Quantity:</td> <td> ' + dataextraitems[key2].quantity + '</td> </tr>';
                                htmltext += '<tr><td> Price:</td> <td> ' + dataextraitems[key2].price + '</td> </tr>';
                                htmltext += '<tr><td colspan="2" style="text-align:center;"> -- </td> </tr>';
                            }
                        htmltext += '</tr></td>';
                        htmltext += '</table>';
                    }

                htmltext += '</div>';
            }

        }

    htmltext += '</div>';

    $('#modal-6 .modal-body').html(htmltext);

}*/

/////

function assignRider(order_id){

    var order_id = order_id;
    var rider_user_id = '0';
    var assigner_user_id = <?php echo $_SESSION['id']; ?>;

    //alert(userid);

    jQuery('#modal-6').modal('show', {backdrop: 'static'});

    $.ajax({
        type: "GET",
        url: "assignRiders.php?getriderlist=ok",
        data: {
            'order_id' : order_id,
            'rider_user_id' : rider_user_id,
            'assigner_user_id' : assigner_user_id
        },
        success: function(response)
        {
            jQuery('#modal-6').html(response);
        }
    });
}

function submitAssignRider(order_id, rider_user_id) {

    var order_id = order_id;
    var rider_user_id = rider_user_id;
    var assigner_user_id = '0';

   $.ajax({
        type: "GET",
        url: "assignRiders.php",
        data: {
            'order_id' : order_id,
            'rider_user_id' : rider_user_id,
            'assigner_user_id' : <?php echo $_SESSION['id'];?>
        },
        success: function(response)
        {
            jQuery('#modal-7 .modal-body').html(response);
        }
    });

}
</script>
<?php //require_once('footer.php'); ?>
</div>
	
		
	</div>

<!-- Modal 6 (Ajax Modal)-->




<div class="modal fade custom-width in" id="modal-6">
    <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content" style="height: 500px; overflow-x: hidden;">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                
                <div class="modal-body">
                </div>
            </div>
    </div>                
</div>


<!-- Modal 7 (Ajax Modal)-->
<div class="modal fade custom-width in" id="modal-7">
    <div class="modal-dialog" style="width: 70%;">
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
<?php } 
else {
	@header('Location: login.php');
} ?>
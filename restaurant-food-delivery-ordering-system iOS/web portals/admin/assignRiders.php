<?php
require_once("config.php");
if(isset($_SESSION['id'])) 
{

	if(@$_GET['AssignToRider']=="ok")
	{
		$rider_user_id = $_GET['rider_user_id'];
		$assigner_user_id = $_SESSION['id'];
		$order_id = $_GET['order_id'];

		$headers = array(
		    "Accept: application/json",
		    "Content-Type: application/json"
		   );
		   $data = array("rider_user_id" => $rider_user_id, "assigner_user_id" => $assigner_user_id, "order_id" => $order_id);
		   
		   //var_dump( $data );
		   //die();
		   
		   $ch = curl_init( $baseurl.'/assignOrderToRider' );
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
		   //die();
		   if($http_code == 200)
		   {
		    	@header('Location: orders.php?status=ok');
		   }
		   else
		   {
		    	@header('Location: orders.php?status=error');
		   }

	}

?>
<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<!--<link rel="stylesheet" href="assets/css/neon-theme.css">-->
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" href="assets/css/custom-datatables.css">
	<script src="assets/js/jquery-1.11.0.min.js"></script>
	
  
	<script src="jquery.min.js"></script>
   
    <!-- This is data table -->
    <script src="jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<?php


if($_GET['getriderlist']=="ok")
{

	$url = $baseurl."/showRiders";
    $params = "";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $json_data = json_decode($result, true);
    //var_dump($json_data);
	
	


	?>	

		<script src="https://maps.google.com/maps/api/js?key=AIzaSyAEDq8M6WsXVmo_08lPapjlqYCFVRBt6ro"></script>
		<script src="../js/locationpicker.jquery.js"></script>
 		<div id="map" style="height: 100%; width: 70%;"></div>

		<script>
                        
                        
                            // window.onload = function () {
                            //   initialize();
                            //   generateMarkers(
                            //     ['malik pura', 31.45623, 73.12974],
                            //     ['Nishat ababd', 31.461088, 73.112302],
                            //     ['ameen town',31.4442286, 73.1175795]
                                
                            //   );
                            // };

                            // var map;

                            // Cretes the map
                         
                             function initialize() 
                             {
    
        
							         <?php  
							         
					                    $url = $baseurl."/showRiders"; $params = ""; $ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, 1);
					                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
					                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					                    $result = curl_exec($ch);
					                    $json_data = json_decode($result, true);
					                    foreach($json_data['msg']['OnlineRiders'] as $str => $data) 
					                    {
					                        foreach($data['RiderLocation'] as $d=>$rd)
					                        {
					                            $var ='var '.'a'.$rd['RiderLocation']['id'].' = {
					                           	b'. $rd['RiderLocation']['user_id'].
					                           ': '.
					                           '"'.'<srtong><b>'.$data['UserInfo']['first_name'].' '.$data['UserInfo']['last_name']."<p  style='margin:0px;padding:0px;display:inherit;color:#0c930b'>".'(Online)'."</p></b>".'<br/>'. $rider_full_name= $rd['RiderLocation']['city']." , ".$rd['RiderLocation']['country'].'<br/>'.'<b>Start Shift Place:</b> '. $rd['RiderLocation']['address_to_start_shift'].'<br/>'.'<b>Phone:</b>  +92'.$data['UserInfo']['phone'].'<br/>'.'<b>Order Count:</b> '.$data['UserInfo']['total_orders']."<br/><a href='https://www.google.com/maps/place/".$rd['RiderLocation']['lat'].','.$rd['RiderLocation']['long']."'  style='margin-top:5px; color:#be2c2c; text-decoration:underline;'>View On Map</a><br><br><a class='btn btn-danger btn-block btn-sm' href='?AssignToRider=ok&rider_user_id=".$data['UserInfo']['user_id']."&assigner_user_id=".@$_SESSION['id']."&order_id=".$_GET['orderID']."' style='background:#be2c2c; margin-top:5px; color:white; text-decoration: none; width:100%; padding:5px 20px; ' >Assign Order</a><br><br>".'</srtong>'.'",'.$rider_full_name='lat:'. $rd['RiderLocation']['lat'].",long:".$rd['RiderLocation']['long'].",countt:".$data['UserInfo']['total_orders']; 
					                                 echo $var.''.' }; ';
					                             
					                                        
					                         }
					                    }
							                       
							        ?>
		          

							    
							    var locations = [
							    
								    <?php  
								     
								        $url = $baseurl."/showRiders"; $params = ""; $ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, 1);
								        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
								        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								        $result = curl_exec($ch);
								        $json_data = json_decode($result, true);
								        foreach($json_data['msg']['OnlineRiders'] as $str => $data) 
								        {
								            foreach($data['RiderLocation'] as $d=>$rd)
								            {
								                $var ='[a'.$rd['RiderLocation']['id'].'.b'.$rd['RiderLocation']['user_id'].', a'.$rd['RiderLocation']['id'].'.lat'.', a'.$rd['RiderLocation']['id'].'.long'.', a'.$rd['RiderLocation']['id'].'.countt'.',0],';
								                echo $var;
								            }
								        }
								     
								    
								                       
								    ?>
							    
							    ];
    
    

		        				var map = new google.maps.Map(document.getElementById('map'), {
		        				zoom: 14,
		        
						            <?php  
						                    //  $url = $baseurl."/showRiders"; $params = ""; $ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, 1);
						                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
						                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						                    // $result = curl_exec($ch);
						                    // $json_data = json_decode($result, true);
						                    // foreach($json_data['msg']['OnlineRiders'] as $str => $data) 
						                    // {
						                    //     foreach($data['RiderLocation'] as $d=>$rd)
						                    //     {
						                    //         //$var ='center: new google.maps.LatLng'.'('. $rider_full_name= $rd['RiderLocation']['lat']." , ".$rd['RiderLocation']['long'].')'; 

						                    //          $var ='center: new google.maps.LatLng'.'('. $_GET['orderLocation'] .')'; 
						                    //          echo $var.','.'';
						                    //          //echo "icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'";
						                                        
						                    //      }
						                    // }
						    
						                       
						            ?>
		       						center: new google.maps.LatLng(<?php echo $_GET['hotelLocation']; ?>),
		       						mapTypeId: google.maps.MapTypeId.ROADMAP
		    					});


		        				

			                    var infowindow = new google.maps.InfoWindow({});

			                    var marker, i;
			                    
			                    
			                    for (i = 0; i < locations.length; i++) 
			                    {

			                    	
			                    	if(locations[i][3]!="0")
			                        {
			                        	marker = new google.maps.Marker({
			                            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			                            map: map ,
			                            title:'Rider',
			                            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
				                        });

				                        google.maps.event.addListener(marker, 'click', (function (marker, i) {
				                            return function () {
				                                infowindow.setContent(locations[i][0]);
				                                infowindow.open(map, marker);
				                            }
				                        })(marker, i));
			                        }
			                        else
			                        {
			                        	marker = new google.maps.Marker({
			                            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			                            map: map ,
			                            title:'Rider',
			                            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
				                        });

				                        google.maps.event.addListener(marker, 'click', (function (marker, i) {
				                            return function () {
				                                infowindow.setContent(locations[i][0]);
				                                infowindow.open(map, marker);
				                            }
				                        })(marker, i));
			                        }	

			                        


			                    }



			                    marker = new google.maps.Marker({
		                            //position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		                            position: new google.maps.LatLng(<?php echo $_GET['orderLocation']; ?>),
		                            map: map ,
		                            title:'Customer Location',
		                            icon: 'assets/house.png'
		                        });
		                        marker = new google.maps.Marker({
		                            //position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		                            position: new google.maps.LatLng(<?php echo $_GET['hotelLocation']; ?>),
		                            map: map ,
		                            title:'Hotel Location',
		                            icon: 'assets/hotel.png'
		                        });

						}



                        </script>

        
        <style>
        	#table-1_wrapper div { background: #eee; margin:0px;}
        	#table-1_paginate a{padding: 5px}
        	#table-1_paginate{margin-top:3px !important; }
        	#table-1_length.dataTables_length{padding: 25px 12px;}
        </style>            
        <div class="col-md-4" style="background-color: #eee; padding: 10px; padding: 20px; height: 400px; overflow-x: hidden; position: fixed; top: 0px; z-index: 2; right: 0;height: 100%;"> 
			<h3>Online Riders</h3>
            <br>
	        <table class="display nowrap table table-hover table-striped table-bordered" id="table-1">
	            <thead style="font-size: 12px;">
	                <tr>
					    <th>ID</th>
	                    <th>Name</th>
	                    <th>Starting Place</th>
	                    <th>Action</th>
	                </tr>
	            </thead>
	            <tbody style="font-size: 12px;">
	                <?php 
	                
	                foreach($json_data['msg']['OnlineRiders'] as $str => $data) 
					{	
						//print_r($json_data);
	                       ?>
	                        <tr>
							    <td><?php echo $data['UserInfo']['user_id']; ?></td>
	                            <td>	
	                            	<?php echo $data['UserInfo']['first_name']." ".$data['UserInfo']['last_name'] ?>
	                            </td>
	                            <td>	
	                            	<?php echo $data['RiderLocation'][0]['RiderLocation']['address_to_start_shift']; ?>
	                            </td>
	                            <td><a href="?AssignToRider=ok&rider_user_id=<?php echo $data['UserInfo']['user_id']; ?>&assigner_user_id=<?php echo @$_SESSION['id']; ?>&order_id=<?php echo $_GET['orderID']; ?>" style='background:#be2c2c; margin-top:5px; color:white; text-decoration: none; width:100%; padding:5px 20px; ' >Assign Order</a></td>
	                        </tr>
	                      <?php
	                   
	                    
	                }

	                curl_close($ch);
	                ?>
	            </tbody>
	        </table>
       </div> 

		<div class="modal-dialog" style="width: 100%; display: none;">
	        <div class="modal-content" style="height: 700px; overflow-x: hidden;">
	            
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title">Assign Order</h4>
	            </div>
	            
	            <div class="modal-body">
                	<div class="col-md-9" style="padding-left: 0;">
	                    
								
								

					</div>
					
					
				   
				
                        <script>
						
						
                            window.onload = function () {
                              initialize();
                              generateMarkers(
                                ['malik pura', 31.45623, 73.12974],
                                ['Nishat ababd', 31.461088, 73.112302],
                                ['ameen town',31.4442286, 73.1175795]
                                
                              );
                            };

                            var map;
                        </script>    

	            </div>

	        </div>
	    </div>



	   

        <script type="text/javascript">
        
			var table = $('#table-1').DataTable();
		    // column order
		     table
			  .column( '0:visible' )
		      .order( 'desc' )
			  .draw();
	
        </script>
         
            
        </div>
    </div> 
	<?php


}


// $rider_user_id = $_GET['rider_user_id'];
// $assigner_user_id = $_GET['assigner_user_id'];
// $order_id = $_GET['order_id'];






// if(empty($rider_user_id)) {
// 	$rider_user_id = "0";
// }

// if(empty($assigner_user_id)) {
// 	$assigner_user_id = "0";
// }

// if(empty($order_id)) {
// 	$order_id = "0";
// }

// //$baseurl./assignOrderToRider


// if( isset($_GET['a']) && $_GET['a'] == '1') {

// 	echo $order_id;
	
// }




}
else
{
	@header('Location: login.php');
}	
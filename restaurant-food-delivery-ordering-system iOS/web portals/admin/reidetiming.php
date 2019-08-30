<?php
require_once("config.php");
$riderid = $_GET['uid'];


?>

 <table class="table table-bordered datatable" id="table-2">
            <thead>
                <tr>
				 <th>ID</th>
					 <th>date</th>
                    <th>starting_time</th>
                    <th>ending_time</th>
                    <th>confirm</th>
					<th>action</th></tr>
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
				 foreach($json_data['msg'] as $str => $data) {
					   
					   
					   foreach($data['RiderTiming'] as $d=>$rd){
							
							//var_dump($rd);
							
							if($rd['user_id']==$riderid){
							
							?>
							 <tr>
				<td><?php echo $rd['id'];?></td>
		
				<td><?php echo $rd['date'];?></td>
				<td><?php echo $rd['starting_time'];?></td>
				<td><?php echo $rd['ending_time'];?></td>
				<td><?php echo $rd['confirm'];?></td>
				<td><button class="btn btn-default btn-sm">confirm</button></td>
				
				 </tr>
                   
					<?php
					}	
						}
				}
                     ?>   
               
            </body>
			</table>
			
			<?php
			?>
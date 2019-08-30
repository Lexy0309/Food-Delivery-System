<?php 
require_once("config.php");
if( isset($_SESSION['id']) && $_SESSION['user_type'] == "hotel" )
{ 
?>


<div class="form" style="background:#F9F9F9; padding:40px 0px;  margin-bottom:100px;">
		
        <?php
        	$user_id = $_SESSION['id'];
	
			$headers = array(
				"Accept: application/json",
				"Content-Type: application/json"
			);
	
			$data = array(
				"user_id" => $user_id
			);
			
			$ch = curl_init( $baseurl.'/showEarnings' );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$return = curl_exec($ch);
	
			$json_data = json_decode($return, true);
	
			//var_dump($json_data);
			$curl_error = curl_error($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//echo $json_data['code'];
			//die;
	
			if($json_data['code'] !== 200){
	
				echo "Error in fetching content";
	
			} 
			else 
			{
				
				$currency=$json_data['msg']['Currency']['symbol'];
				$joinDate=$json_data['msg']['Restaurant']['created'];
				$total_earning=number_format($json_data['msg']['TotalEarning']['total_earning']);
				$weekly_earning=$json_data['msg']['WeeklyEarning'][0]['total_earning'];
				$weekly_earning_week_start=$json_data['msg']['WeeklyEarning'][0]['week_start'];
				$weekly_earning_week_end=$json_data['msg']['WeeklyEarning'][0]['week_end'];
				
			}
		?>
        
		<div align="center">
			<h2 class="title">
				<span>Total Earning</span>
			</h2>
			<h2 style="font-size:50px; color:#BE2C2C; margin:20px ;"><?php echo $currency; echo $total_earning; ?></h2>
            <span style=" background:#BE2C2C; color:white; padding:5px 8px; border-radius: 10px;"><?php echo $joinDate; ?></span>
		</div>
		
		<div id="revenue-chart" style="padding:50px 0; height:280px; overflow:hidden;"></div>
		
        <br>
        <div style="padding:20px 0px; border-top:20px solid #f2f2f2">
        	<div style="padding:0px 20px;">
                <div class="left">
                    <h2 class="title">Statements</h2>
                </div>
                <div class="right">
                    <a href="javascript:;" class="filtericon"></a>
                </div>
                <div class="clear"></div>
            </div>
            
            
             <style>
			 	.order_table td {
					padding: 18px 25px !important;
					font-weight: 300;
					border-bottom: 3px solid #f2f2f2;
				}
			 </style>
        	 <table class="order_table" id="myTable" cellspacing="0" cellpadding="0" border="0">
                <thead>
                    <tr>
                        <td style="font-weight: bold;"><strong>Invoice Date</strong></td>
                        <td style="font-weight: bold;" align="right"><strong>Earning</strong></td>
                        <td>&nbsp;</td>
                    </tr>
                </thead>
                    <tbody id="myTable_row">
                        <?php
                        	foreach ($json_data['msg']['WeeklyEarning'] as $key => $value) 
							{

							  //var_dump($value);
								
								?>
									<tr style="display: table-row;">
                                        <td><?php echo $value['week_start']." - "; echo $value['week_end']; ?></td>
                                        <td align="right">
                                        	<span class="blok" style="margin:0; color:green; font-weight:500;">
                                        	  <?php echo $currency; echo $value['total_earning']; ?>
                                            </span>
                                        </td>
                                        <td align="center"><a href="#" style=" color:#be2c2c; text-decoration:none; font-weight:400;">view</a></td>
                                        
                                    </tr>
								<?php
							
							}
						?>
                        
                        
                        
                        
                   </tbody>
            </table>
        </div>
        
        
		<script src="asset-js/jquery-2.js" type="text/javascript"></script>
		
		<script type="text/javascript" src="asset-js/jquery_002.js"></script>
		<script type="text/javascript" src="asset-js/canvasjs-charts.js"></script>
		<script type="text/javascript" src="asset-js/performance-earnings.js"></script>
		<script type="text/javascript" src="asset-js/mustache.js"></script>
		
		<script type="text/javascript">
			
					var revenueChartParams = {
						chartContainerSelector: "#revenue",
						chartCallOutSelector: "#revenue-chart-call-out"
					};
	
					window.foodomia.PerformanceEarnings.renderRevenueChart(	
						revenueChartParams, 
						[	
							{"y": <?php echo $weekly_earning; ?>, "label": "<?php echo $weekly_earning_week_start; ?>"}
							
						]);
	
					
		</script>
</div>









<?php } else {
	
	@header("Location: index.php");
    echo "<script>window.location='index.php'</script>";
    die;
    
} ?>
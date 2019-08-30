<?php
require_once("config.php");
$restaurant_menu_item_id = $_GET['menu_item_id'];

?>

<table class="table table-bordered datatable" id="table-1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Created</th>
            <th>Menu Item ID</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json"
           );
           $data = array(
            	"restaurant_menu_item_id" => $restaurant_menu_item_id
            );

           $ch = curl_init( $baseurl.'/showMenuExtraItems' );

           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
           curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
           curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
           $result = curl_exec($ch);

           $curl_error = curl_error($ch);
           $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

           $json_data = json_decode($result, true);
            //var_dump($json_data);
            $i=0;
            foreach($json_data['msg'] as $str => $data) {
                //var_dump($data);
                if(!empty($data['RestaurantMenuExtraItem']['id'])) {
                    echo "<tr>
                        <td>".$data['RestaurantMenuExtraItem']['name']."</td>
                        <td>".$data['RestaurantMenuExtraItem']['price']."</td>
                        <td>".$data['RestaurantMenuExtraItem']['created']."</td>
                        <td>".$data['RestaurantMenuExtraItem']['restaurant_menu_item_id']."</td>
                    </tr>";
                }
                $i++;
            }

           curl_close($ch);
        ?>
    </tbody>
</table>
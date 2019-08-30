<?php

App::uses('Lib', 'Utility');
App::uses('Firebase', 'Lib');
App::uses('Postmark', 'Utility');
App::uses('Message', 'Utility');
App::uses('Variables', 'Utility');
App::uses('CustomEmail', 'Utility');
App::uses('Security', 'Utility');
App::uses('PushNotification', 'Utility');




class ApiController extends AppController
{

    //public $components = array('Email');

    public $autoRender = false;
    public $layout = false;





   public function index(){


    echo "Congratulations!. You have configured your mobile api correctly";
  
  
 }
    

    public function test(){

      echo "Congratulations!. It worked";
    }


    public function registerUser()
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');
        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email        = $data['email'];
            $password     = $data['password'];
            $first_name   = $data['first_name'];
            $last_name    = $data['last_name'];
            $phone        = $data['phone'];
            $device_token = $data['device_token'];
            $role         = $data['role'];







            if ($email != null && $password != null) {




                $user['email']    = strtolower($email);
                $user['password'] = $password;

                $user['active']  = 1;
                $user['role']    = $role;
                $user['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);






                $count = $this->User->isEmailAlreadyExist($email);

             
                if ($count && $count > 0) {
                    echo Message::DATAALREADYEXIST();
                    die();

                } else {





                    if (!$this->User->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $user_id              = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;

                    $user_info['device_token'] = $device_token;
                    $user_info['first_name']   = $first_name;
                    $user_info['last_name']    = $last_name;
                    $user_info['phone']        = $phone;



                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }




                    $output      = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);
                    $key     = Security::hash(CakeText::uuid(), 'sha512', true);
                    CustomEmail::welcomeEmail($email,$key);
                    $output['code'] = 200;
                    $output['msg']  = $userDetails;
                    echo json_encode($output);




                }
            } else {
                echo Message::ERROR();
            }
        }
    }



      public function verifyPhoneNo()
    {

        $this->loadModel('PhoneNoVerification');
        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);

            $phone_no = $data['phone_no'];
            $verify   = $data['verify'];
            $code     = Lib::randomNumber(4);
             //$code = 1234;

            $created                  = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
            $phone_verify['phone_no'] = $phone_no;
            $phone_verify['code']     = $code;
            $phone_verify['created']  = $created;
            if ($verify == 0) {


                $response = Lib::sendSmsVerificationCurl($phone_no, VERIFICATION_PHONENO_MESSAGE . ' ' . $code);
                //$response = true;

              
               
                if (array_key_exists('code', $response)){
                if($response['code'] == 21608){

                     $output['code'] = 200; 
                    $output['msg']  = $response['message'];
                    echo json_encode($output);
                    die();

                }else{


                     if($response['code'] == 201){

                     $output['code'] = 200; 
                    $output['msg']  = $response['message'];
                    echo json_encode($output);
                    die();

                }
                }
}
                
                //$response = true;

                if ($response) {
                    $this->PhoneNoVerification->save($phone_verify);
                    $output['code'] = 200;
                    $output['msg']  = "code has been generated and sent to user's phone number";
                    echo json_encode($output);
                    die();
                } else {

                    $output['code'] = 201; 
                    $output['msg']  = "invalid number";
                    echo json_encode($output);
                    die();
                }




            } else {
                $code_user = $data['code'];
                if ($this->PhoneNoVerification->verifyCode($phone_no, $code_user) > 0) {
                    $output['code'] = 200;
                    $output['msg']  = "successfully code matched";
                    /*$this->PhoneNoVerification->deleteAll(array(
                        'phone_no' => $phone_no
                    ), false);*/

                    echo json_encode($output);
                    die();

                } else {

                    $output['code'] = 201; 
                    $output['msg']  = "invalid code";

                    echo json_encode($output);
                    die();

                }

            }
        }


    }
    // login
    public function login() //changes done by irfan
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');
        $this->loadModel('UserAdmin');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            // $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email        = $data['email'];
            $password     = $data['password'];
            $device_token = $data['device_token'];
            $lat          = $data['lat'];
            $long         = $data['long'];
            $email        = strtolower($email);

            $count = $this->User->isEmailAlreadyExist($email);


            if ( $count < 1) {
                $output['code'] = 201;
                $output['msg']  = "No account exist with this email. You have to signup first";

                echo json_encode($output);
                die();

            }
            if ($email != null && $password != null) {
                $adminDetails = $this->UserAdmin->getAdminDetails();

                $userData     = $this->User->loginRestaurantAndRiderAndUser($email, $password);
               if($userData!=203) {
                   if ($userData[0]['User']['role'] == "rider" && $lat == "") {

                       $output['code'] = 201;
                       $output['msg'] = "Allow your location from settings > General > Location";
                       echo json_encode($output);
                       die();
                   }
               }

               if (($userData) && $userData !== "203") {
                    $user_id = $userData[0]['User']['id'];

                    $this->UserInfo->id = $user_id;
                    $savedField         = $this->UserInfo->saveField('device_token', $device_token);

                    $output               = array();
                    $userDetails          = $this->UserInfo->getUserDetailsFromID($user_id);

                    $userDetails['Admin'] = $adminDetails['UserAdmin'];



                    $output['code'] = 200;
                    $output['msg']  = $userDetails;

                    echo json_encode($output);



                } else if ($userData == "203") {

                    $output['code'] = 203;
                    $output['msg']  = "Not allowed";
                    echo json_encode($output);
                    die();

                } else {
                    echo Message::INVALIDDETAILS();
                    die();

                }





            } else {
                echo Message::ERROR();
                die();
            }
        }
    }

    public function addRiderLocation()
    {

        $this->loadModel("RiderLocation");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $lat     = $data['lat'];
            $long    = $data['long'];



            $rider_location = array();

            $rider_location['user_id'] = $user_id;
            $rider_location['lat']     = $lat;
            $rider_location['long']    = $long;


            $result = $this->RiderLocation->getRiderLocation($user_id);
            if (count($result) > 0) {


                $id                      = $result[0]['RiderLocation']['id'];

                $this->RiderLocation->id = $id;
                if (!$this->RiderLocation->save($rider_location)) {
                    echo Message::DATASAVEERROR();
                    die();

                } else {
                    echo Message::DATASUCCESSFULLYSAVED();

                    die();

                }
            } else {

                if (!$this->RiderLocation->save($rider_location)) {
                    echo Message::DATASAVEERROR();
                    die();

                } else {
                    echo Message::DATASUCCESSFULLYSAVED();

                    die();
                }

            }



        }
    }

    public function showRiderLocationAgainstOrder()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];
            $user_id = $data['user_id'];
            $map_change = $data['map_change'];



            $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($order_id);
            $pickup_time = $this->RiderTrackOrder->isEmptyPickUpTime($order_id);
            $on_my_way_to_user_time = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($order_id);
            $delivery_time = $this->RiderTrackOrder->isEmptyDeliveryTime($order_id);
            $order_detail = $this->Order->getOrderDetailBasedOnID($order_id);


            $status_0 = "";
            $status_1 = "";
            $status_2 = "";
            $status_3 = "";
            $status_4 = "";
            $status_5 = "";
            $status_6 = "";
            $status_7 = "";

            if ($order_detail[0]['Order']['hotel_accepted'] == 0) {

                $status_0 = "Order is in processing";
                $status_pusher[0]['order_status'] = $status_0;
                 if($map_change == 1){
                     $status_pusher[0]['map_change'] = $map_change;
                    }else {
                     $status_pusher[0]['map_change'] = "0";
                 }
            }else {

                $status_0 = "Order is in processing";
                $status_pusher[0]['order_status'] = $status_0;
                if($map_change == 1){
                    $status_pusher[0]['map_change'] = $map_change;
                }else {
                    $status_pusher[0]['map_change'] = "0";
                }

            }

            if ($order_detail[0]['Order']['hotel_accepted'] == 1) {

                $status_1 = $order_detail[0]['Restaurant']['name'] . ' ' . "has accepted your order and processing it";
                $status_pusher[1]['order_status'] = $status_1;
                if($map_change == 1){
                    $status_pusher[1]['map_change'] = $map_change;
                }else {
                    $status_pusher[1]['map_change'] = "0";
                }

            }

            if ($order_detail[0]['RiderOrder']['id'] > 0) {
                if (Lib::multi_array_key_exists('RiderOrder', $order_detail)) {


                    $status_2 = "Order has been assigned to " . $order_detail[0]['RiderOrder']['Rider']['first_name'];
                    //$status_pusher[0]['order_status'] =  $status_0;
                    $status_pusher[2]['order_status'] = $status_2;
                    if($map_change == 1){
                        $status_pusher[2]['map_change'] = $map_change;
                    }else {
                        $status_pusher[2]['map_change'] = "1";
                    }
                }


            if ($on_my_way_to_hotel_time == 1) {


                $status_3 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "is on the way to restaurant to pickup your order";
                //$status_pusher[0]['order_status'] =  $status_0;
                $status_pusher[3]['order_status'] = $status_3;
                if($map_change == 1){
                    $status_pusher[3]['map_change'] = $map_change;
                }else {
                    $status_pusher[3]['map_change'] = "0";
                }

                //  $status = "order is in processing";
                //$status_pusher[0]['order_status'] = $status;

            }

            if ($pickup_time == 1) {


                $status_4 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "has picked up your food";

                $status_pusher[4]['order_status'] = $status_4;
                if($map_change == 1){
                    $status_pusher[4]['map_change'] = $map_change;
                }else {
                    $status_pusher[4]['map_change'] = "1";
                }

            }
            if ($on_my_way_to_user_time == 1) {


                $status_5 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "is on the way to you";

                $status_pusher[5]['order_status'] = $status_5;
                if($map_change == 1){
                    $status_pusher[5]['map_change'] = $map_change;
                }else {
                    $status_pusher[5]['map_change'] = "0";
                }


            }

            if ($delivery_time == 1) {


                $status_6 = $order_detail[0]['RiderOrder']['Rider']['first_name'] . ' ' . "just delivered the food";

                $status_pusher[6]['order_status'] = $status_6;
                if($map_change == 1){
                    $status_pusher[6]['map_change'] = $map_change;
                }else {
                    $status_pusher[6]['map_change'] = "0";
                }

            }

        }
    $reverse_status_pusher = array_reverse($status_pusher);

    $rider = $this->RiderOrder->getRiderDetailsAgainstOrderID($order_id);

    //  $rider_location = $this->RiderLocation->getRiderLocation($rider[0]['RiderOrder']['rider_user_id']);
    if (count($rider) > 0 && $pickup_time > 0) {


        //order has been assigned and picked up
        $result[0]['RiderOrder'] = $rider[0]['RiderOrder'];

        $result[0]['Rider'] = $rider[0]['Rider'];

        if(!Lib::multi_array_key_exists('RiderLocation',$rider[0]['RiderOrder'])){


            $result[0]['RiderOrder']['RiderLocation']['lat'] = "";
            $result[0]['RiderOrder']['RiderLocation']['long'] = "";


        }
        $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
        $result[0]['UserLocation'] = $rider[0]['Order']['Address'];
        $result[0]['RestaurantLocation']['lat'] = "";
        $result[0]['RestaurantLocation']['long'] = "";

        $output['code'] = 200;

        $output['msg'] = $result;
        echo json_encode($output);


    } else if (count($rider) > 0 && $pickup_time == 0) {

        //order has been assigned but not picked up yet

        $result[0]['RiderOrder'] = $rider[0]['RiderOrder'];
        $result[0]['Rider'] = $rider[0]['Rider'];

        if(!Lib::multi_array_key_exists('RiderLocation',$rider[0]['RiderOrder'])){


            $result[0]['RiderOrder']['RiderLocation']['lat'] = "";
            $result[0]['RiderOrder']['RiderLocation']['long'] = "";


        }
        $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
        $result[0]['UserLocation']['lat'] = "";
        $result[0]['UserLocation']['long'] = "";
        $result[0]['RestaurantLocation'] = $rider[0]['Order']['Restaurant']['RestaurantLocation'];

        $output['code'] = 200;

        $output['msg'] = $result;
        echo json_encode($output);


    } else {

        //no order has been assigned to rider...: send only restaurant location

        $restaurant_location = $this->Order->getOrderDetailBasedOnID($order_id);

        $result[0]['RiderOrder']['RiderLocation']['lat'] = "";
        $result[0]['RiderOrder']['RiderLocation']['long'] = "";
        $result[0]['Rider']['first_name'] = "";
        $result[0]['Rider']['last_name'] = "";
        $result[0]['Rider']['phone'] = "";


        $result[0]['RiderOrder']['RiderLocation']['status'] = $reverse_status_pusher;
        $result[0]['UserLocation']['lat'] = "";
        $result[0]['UserLocation']['long'] = "";
        $result[0]['RestaurantLocation'] = $restaurant_location[0]['Restaurant']['RestaurantLocation'];

        $output['code'] = 200;

        $output['msg'] = $result;
        echo json_encode($output);

    }






        }
    }









public function enableOrderTracking(){

    $this->loadModel("Order");

    if ($this->request->isPost()) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, TRUE);

        $order_id = $data['order_id'];
        $status = $data['status'];

        $this->Order->id = $order_id;

        if($this->Order->saveField('tracking',$status)){

            echo Message::DATASUCCESSFULLYSAVED();


            die();

        }else{


            echo Message::ERROR();
            die();
        }
    }

}


    public function checkIn()
    {

        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $online  = $data['online'];








            if($data['online'] == 0) {
                if (isset($data['current_datetime'])) {
                    $current_datetime = $data['current_datetime'];

                    $date = Lib::getOnlyDateFromDatetime($current_datetime);
                    $time = Lib::getTimeFromDatetime($current_datetime);

                    $rider_timing = $this->RiderTiming->IsExistRiderShift($user_id, $date, $time);
                    $rider_order = $this->RiderOrder->countRiderAssignOrders($user_id);

                    if ($rider_timing == 0 && $rider_order == 0) {

                      $online = 0;


                    }
                }
            }


            $this->UserInfo->id = $user_id;
            if ($this->UserInfo->saveField("online",$online)) {
                $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                $output['code'] = 200;

                $output['msg'] = $userDetails;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();

            }

        }
    }


    public function showUserOnlineStatus()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("RiderTiming");
        $this->loadModel("RiderOrder");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            if(isset($data['current_datetime'])) {
                $current_datetime = $data['current_datetime'];

                $date = Lib::getOnlyDateFromDatetime($current_datetime);
                $time = Lib::getTimeFromDatetime($current_datetime);

                $rider_timing = $this->RiderTiming->IsExistRiderShift($user_id, $date, $time);
                $rider_order = $this->RiderOrder->countRiderAssignOrders($user_id);

                if ($rider_timing == 0 && $rider_order == 0) {

                    $this->UserInfo->id = $user_id;
                    $this->UserInfo->saveField("online", 0);
                }
            }
            $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


            $output['code'] = 200;

            $output['msg'] = $userDetails['UserInfo']['online'];
            echo json_encode($output);


            die();


        }
    }




    public function editUserProfile()
    {

        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id    = $data['user_id'];
            $first_name = $data['first_name'];
            $last_name  = $data['last_name'];
            $email      = $data['email'];




            $user_info['first_name'] = $first_name;
            $user_info['last_name']  = $last_name;
            $user_info['email']      = $email;





            $this->UserInfo->id = $user_id;
            if ($this->UserInfo->save($user_info)) {
                $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                $output['code'] = 200;

                $output['msg'] = $userDetails;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();

            }

        }
    }

    public function addDeliveryAddress()
    {


        $this->loadModel("Address");
        $this->loadModel("UserInfo");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $user_id     = $data['user_id'];
            $street      = $data['street'];
            $lat         = $data['lat'];
            $long        = $data['long'];
            $apartment   = $data['apartment'];
            $city        = $data['city'];
            $state       = $data['state'];
            $zip         = $data['zip'];
            $country     = $data['country'];
            $instruction = $data['instruction'];






            $address['user_id']      = $user_id;
            $address['lat']          = $lat;
            $address['long']         = $long;
            $address['street']       = $street;
            $address['apartment']    = $apartment;
            $address['city']         = $city;
            $address['state']        = $state;
            $address['zip']          = $zip;
            $address['country']      = $country;
            $address['instructions'] = $instruction;

            //update
            if (isset($data['id'])) {

                $id                = $data['id'];
                $this->Address->id = $id;
                $this->Address->save($address);

                $userDetails    = $this->UserInfo->getUserDetailsFromID($user_id);
                $output['code'] = 200;
                $output['msg']  = $userDetails;
                echo json_encode($output);


                die();
            } else if ($this->Address->isDuplicateRecord($user_id, $street, $city, $apartment, $state, $country) == 0) {
                if ($this->Address->save($address)) {


                    //$gigpost_category['cat_id'] = $cat_id;


                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
                    $output['code'] = 200;

                    $output['msg'] = $userDetails;
                    echo json_encode($output);

                    die();

                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

        }


    }

    public function showDeliveryAddresses()
    {

        $this->loadModel('Address');

        $this->loadModel('RestaurantLocation');
        $this->loadModel('Restaurant');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id   = $data['user_id'];
            $addresses = $this->Address->getUserDeliveryAddresses($user_id);

            if (isset($data['restaurant_id']) && isset($data['sub_total'])) {
                $restaurant_id = $data['restaurant_id'];
                $sub_total     = $data['sub_total'];

                // $total_amount = $sub_total;
                $delivery_fee = 0;
                $restaurant_will_pay = 0;


                $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant_id);

                $i = 0;
                foreach ($addresses as $address) {

                    $distance_difference_btw_user_and_restaurant = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $address['Address']['lat'], $address['Address']['long']);

                   //convert distance in Kms from miles
                    $distance =  $distance_difference_btw_user_and_restaurant['rows'][0]['elements'][0]['distance']['text'] * 1.6;



                    $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);

                    $min_order_price = $restaurant_detail[0]['Restaurant']['min_order_price'];
                    $delivery_free_range = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                    if ($sub_total >= $min_order_price && $distance > $delivery_free_range) { //case 1

                        $distance_difference = $distance - $delivery_free_range;
                        $delivery_fee        =          $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance_difference;
                        $restaurant_will_pay        =   $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $delivery_free_range;
                        // $total_amount = $delivery_fee + $sub_total;

                    } else if ($sub_total < $min_order_price && $distance > $delivery_free_range) {



                        $delivery_fee = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;


                    } else if ($sub_total > $min_order_price && $distance <= $delivery_free_range) {

                        // $total_amount = $sub_total;
                        $delivery_fee = "0";
                        $restaurant_will_pay  =  $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;

                    } else if ($sub_total < $min_order_price && $distance <= $delivery_free_range) {
                        // $distance_difference = 5 - $distance;
                        $delivery_fee = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                        //$total_amount = $delivery_fee + $sub_total;

                    }



                    $delivery_fee_add_zero_in_the_end = strlen(substr(strrchr($delivery_fee, "."), 1));
                    if($delivery_fee_add_zero_in_the_end == 1){


                        $delivery_fee = $delivery_fee."0";
                    }

                    $addresses[$i]['Address']['total_amount'] = (string) $sub_total;
                    $addresses[$i]['Address']['delivery_fee'] = (string) $delivery_fee;
                    $addresses[$i]['Address']['restaurant_will_pay'] = (string) $restaurant_will_pay;
                    $addresses[$i]['Address']['distance'] = (string) $distance;

                    $i++;
                }
            }
            $output['code'] = 200;
            $output['msg']  = $addresses;

            echo json_encode($output);
            die();


        }

    }


    public function deleteDeliveryAddress()
    {

        $this->loadModel('Address');
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id      = $data['id'];
            $user_id = $data['user_id'];
            $this->Address->query('SET FOREIGN_KEY_CHECKS=0');
            if ($this->Address->delete($id)) {


                $addresses = $this->Address->getUserDeliveryAddresses($user_id);


                $output['code'] = 200;
                $output['msg']  = $addresses;
                echo json_encode($output);
                die();
                //$this->RiderTiming->deleteAll(array('upvote_question_id' => $upvote_question_id), false);
            } else {

                Message::ALREADYDELETED();
                die();


            }
        }
    }


    public function addPaymentMethod()
    {

        $this->loadModel('StripeCustomer');
        $this->loadModel('PaymentMethod');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id = $data['user_id'];
            $default = $data['default'];




            //$email = $data['email'];
            //$first_name = $data['first_name'];
            //$last_name = $data['last_name'];
            $name      = $data['name'];
            $card      = $data['card'];
            $cvc       = $data['cvc'];
            $exp_month = $data['exp_month'];
            $exp_year  = $data['exp_year'];
            // $address_line_1 = $data['street'];
            //$address_line_2 = $data['city'];
            // $address_zip = $data['zip'];
            //$address_state = $data['state'];
            //$address_country = $data['country'];

            if ($card != null && $cvc != null) {

                $a      = array(

                    // 'email' => $email,
                    'card' => array(
                        //'name' => $first_name . " " . $last_name,
                        'number' => $card,
                        'cvc' => $cvc,
                        'exp_month' => $exp_month,
                        'exp_year' => $exp_year,
                        'name' => $name

                        // 'address_line_1' => $address_line_1,
                        //'address_line_2' => $address_line_2,
                        //'address_zip' => $address_zip,
                        //'address_state' => $address_state,
                        //'address_country' => $address_country
                    )
                );
                $stripe = $this->StripeCustomer->save($a);


                if ($stripe) {





                    $payment['stripe']  = $stripe['StripeCustomer']['id'];
                    $payment['user_id'] = $user_id;
                    $payment['default'] = $default;
                    $result             = $this->PaymentMethod->save($payment);
                    $count              = $this->PaymentMethod->isUserStripeCustIDExist($user_id);
                    if ($count > 0) {

                        $cards = $this->PaymentMethod->getUserCards($user_id);


                        foreach ($cards as $card) {

                            $response[] = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);

                        }



                        $i = 0;
                        foreach ($response as $re) {

                            $stripeCustomer                        = $re[0]['StripeCustomer']['sources']['data'][0];
                            $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                            $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                            $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
                            $stripData[$i]['CardDetails']['name']  = $stripeCustomer['name'];

                            $i++;
                        }


                        $output['code'] = 200;
                        $output['msg']  = $stripData;
                        echo json_encode($output);
                        die();
                    } else {
                        Message::EmptyDATA();
                        die();
                    }




                } else {
                    $error['code'] = 400;
                    $error['msg']  = $this->StripeCustomer->getStripeError();
                    echo json_encode($error);
                }
            } else {
                echo Message::ERROR();



            }

        }

    }


    public function getPaymentDetails()
    {



        $this->loadModel('StripeCustomer');
        $this->loadModel('PaymentMethod');


        if ($this->request->isPost()) {
            //$json = file_get_contents('php://input');
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);
            $user_id = $data['user_id'];
            if ($user_id != null) {

                $count = $this->PaymentMethod->isUserStripeCustIDExist($user_id);

                if ($count > 0) {

                    $cards = $this->PaymentMethod->getUserCards($user_id);

                    $j = 0;
                    foreach ($cards as $card) {

                        $response[$j]['Stripe']              = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);
                        $response[$j]['PaymentMethod']['id'] = $card['PaymentMethod']['id'];
                        $j++;
                    }


                    $i = 0;
                    foreach ($response as $re) {

                        $stripeCustomer                       = $re['Stripe'][0]['StripeCustomer']['sources']['data'][0];
                        /* $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
                        $stripData[$i]['CardDetails']['name'] = $stripeCustomer['name'];*/
                        $stripData[$i]['brand']               = $stripeCustomer['brand'];
                        $stripData[$i]['brand']               = $stripeCustomer['brand'];
                        $stripData[$i]['last4']               = $stripeCustomer['last4'];
                        $stripData[$i]['name']                = $stripeCustomer['name'];
                        $stripData[$i]['exp_month']           = $stripeCustomer['exp_month'];
                        $stripData[$i]['exp_year']            = $stripeCustomer['exp_year'];
                        $stripData[$i]['PaymentMethod']['id'] = $re['PaymentMethod']['id'];

                        $i++;
                    }


                    $output['code'] = 200;
                    $output['msg']  = $stripData;
                    echo json_encode($output);
                    die();
                } else {
                    Message::EmptyDATA();
                    die();
                }

            } else {
                echo Message::ERROR();
            }
        }
    }


    /*$this->loadModel('StripeCustomer');
    $this->loadModel('PaymentMethod');


    if ($this->request->isPost()) {
    //$json = file_get_contents('php://input');
    $json = file_get_contents('php://input');
    $data = json_decode($json, TRUE);
    $user_id = $data['user_id'];
    if ($user_id != null) {

    $count = $this->PaymentMethod->isUserStripeCustIDExist($user_id);

    if ($count > 0) {

    $cards = $this->PaymentMethod->getUserCards($user_id);


    foreach ($cards as $card){

    $response[] = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);

    }



    $i= 0;
    foreach ($response as $re){

    $stripeCustomer = $re[0]['StripeCustomer']['sources']['data'][0];
    /* $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
    $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
    $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
    $stripData[$i]['CardDetails']['name'] = $stripeCustomer['name'];*/
    /* $stripData[$i]['brand'] = $stripeCustomer['brand'];
    $stripData[$i]['brand'] = $stripeCustomer['brand'];
    $stripData[$i]['last4'] = $stripeCustomer['last4'];
    $stripData[$i]['name'] = $stripeCustomer['name'];

    $i++;
    }


    $output['code'] = 200;
    $output['msg'] = $stripData;
    echo json_encode($output);
    die();
    } else {
    Message::EmptyDATA();
    die();
    }

    } else {
    echo Message::ERROR();
    }
    }
    */




    public function deletePaymentMethod()
    {

        $this->loadModel("PaymentMethod");
        $this->loadModel("StripeCustomer");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id      = $data['id'];
            $user_id = $data['user_id'];
            $this->PaymentMethod->query('SET FOREIGN_KEY_CHECKS=0');
            if ($this->PaymentMethod->delete($id)) {



                $count = $this->PaymentMethod->isUserStripeCustIDExist($user_id);

                if ($count > 0) {

                    $cards = $this->PaymentMethod->getUserCards($user_id);


                    foreach ($cards as $card) {

                        $response[] = $this->StripeCustomer->getCardDetails($card['PaymentMethod']['stripe']);

                    }



                    $i = 0;
                    foreach ($response as $re) {

                        $stripeCustomer         = $re[0]['StripeCustomer']['sources']['data'][0];
                        /* $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['CardDetails']['last4'] = $stripeCustomer['last4'];
                        $stripData[$i]['CardDetails']['name'] = $stripeCustomer['name'];*/
                        $stripData[$i]['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['brand'] = $stripeCustomer['brand'];
                        $stripData[$i]['last4'] = $stripeCustomer['last4'];
                        $stripData[$i]['name']  = $stripeCustomer['name'];

                        $i++;
                    }


                    $output['code'] = 200;
                    $output['msg']  = $stripData;
                    echo json_encode($output);
                    die();
                } else {
                    Message::EmptyDATA();
                    die();
                }
            } else {

                Message::ALREADYDELETED();
                die();

            }

            //$this->RiderTiming->deleteAll(array('upvote_question_id' => $upvote_question_id), false);

        }
    }
    public function registerRestaurantUser($email, $password, $first_name, $last_name, $phone)
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');


        //$json = file_get_contents('php://input');








        //file_put_contents(Variables::$UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

        if ($email != null && $password != null) {




            $user['email']    = $email;
            $user['password'] = $password;

            $user['active']  = 1;
            $user['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);






            $count = $this->User->isEmailAlreadyExist($email);


            if ($count && $count > 0) {
                echo Message::DATAALREADYEXIST();
                die();

            } else {

                $lib = new Lib;
                $key = Security::hash(CakeText::uuid(), 'sha512', true);



                if (!$this->User->save($user)) {
                    echo Message::DATASAVEERROR();
                    die();
                }


                $user_id              = $this->User->getInsertID();
                $user_info['user_id'] = $user_id;


                $user_info['first_name'] = $first_name;
                $user_info['last_name']  = $last_name;
                $user_info['phone']      = $phone;



                if (!$this->UserInfo->save($user_info)) {
                    echo Message::DATASAVEERROR();
                    die();
                }




                return $user_id;




            }
        } else {
            echo Message::ERROR();
        }

    }




    public function showRestaurants()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RestaurantRating");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $lat  = $data['lat'];
            $long = $data['long'];






            $user_id = null;
            if (isset($data['user_id'])) {

                $user_id          = $data['user_id'];
                $completed_orders = $this->Order->getCompletedOrdersWhoseNotificationHasNotBeenSent($user_id);

                if (count($completed_orders) > 0) {



                    $restaurant_name = $completed_orders[0]['Restaurant']['name'];
                    $restaurant_id   = $completed_orders[0]['Order']['restaurant_id'];

                    $restaurant_image = $completed_orders[0]['Restaurant']['image'];

                    $device_token         = $completed_orders[0]['UserInfo']['device_token'];


                    /************notification*************/


                    $notification['to'] = $device_token;
                    $notification['notification']['title'] = "";
                    $notification['notification']['body'] = "";
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "order_review";
                    $notification['notification']['restaurant_name'] = $restaurant_name;
                    $notification['notification']['restaurant_id'] = $restaurant_id;
                    $notification['notification']['img'] = $restaurant_image ;



                    $notification['data']['restaurant_name']= $restaurant_name;
                    $notification['data']['restaurant_id']= $restaurant_id;
                    $notification['data']['type'] = "order_review";
                    $notification['data']['img'] = $restaurant_image ;
                    $notification['data']['icon'] = "";
                    $notification['data']['badge'] = "1";
                    $notification['data']['sound'] = "default";

                    PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));



                    /********end notification***************/


                    $this->Order->id = $completed_orders[0]['Order']['id'];
                    $this->Order->saveField('notification', 1);
                }
                /*----------------------------------*/

                $user_order_details = $this->Order->getCompletedOrdersAgainstUserID($user_id);

                if (count($user_order_details) > 0) {

                    foreach($user_order_details as $user_orders) {
                        $rider_details = $this->RiderOrder->getRiderDetailsAgainstOrderID($user_orders['Order']['id']);


                        $riderOrderDelivered = $this->RiderTrackOrder->getRiderDeliveredOrderWhoseNotificationHasNotBeenSent($user_orders['Order']['id']);

                        if (count($riderOrderDelivered) > 0) {


                            $rider_name = $rider_details[0]['Rider']['first_name'] . " " . $rider_details[0]['Rider']['last_name'];
                            $rider_user_id = $rider_details[0]['Rider']['user_id'];
                            $order_id = $user_orders['Order']['id'];


                            $device_token_user = $user_orders['UserInfo']['device_token'];


                            /************notification*************/


                            $notification['to'] = $device_token_user;
                            $notification['notification']['title'] = "";
                            $notification['notification']['body'] = "";
                            $notification['notification']['badge'] = "1";
                            $notification['notification']['sound'] = "default";
                            $notification['notification']['icon'] = "";
                            $notification['notification']['type'] = "rider_review";
                            $notification['notification']['rider_user_id'] = $rider_user_id;
                            $notification['notification']['order_id'] = $order_id;
                            $notification['notification']['rider_name'] = $rider_name;
                            // $notification['notification']['data'] = "";

                            $notification['data']['rider_user_id'] = $rider_user_id;
                            $notification['data']['order_id'] = $order_id;
                            $notification['data']['type'] = "rider_review";
                            $notification['data']['rider_name'] = $rider_name;
                            $notification['data']['icon'] = "";
                            $notification['data']['badge'] = "1";
                            $notification['data']['sound'] = "default";
                            PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                            //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                            /********end notification***************/

                            $this->RiderTrackOrder->id = $riderOrderDelivered[0]['RiderTrackOrder']['id'];
                            $this->RiderTrackOrder->saveField('notification', 1);

                           break;
                        }

                    }
                    /*-------------------------*/

                }
            }

           /******if you want to only show current city restaurants then use below method******/
            /*$results = Lib::getCountryCityProvinceFromLatLong($lat,$long);

            if(strlen($results['city']) > 2) {

                $restaurants1 = $this->Restaurant->getCurrentCityRestaurants($lat, $long, $user_id,$results['city']);
                $restaurants[0] = $this->Restaurant->getCurrentCityRestaurantsBasedOnPromoted($lat, $long, $user_id,$results['city']);
                $restaurants[1] = $this->Restaurant->getCurrentCityRestaurantsBasedOnDistance($lat, $long, $user_id,$results['city']);


                //  array_push($restaurants[0], $restaurants[1]);

                array_splice( $restaurants[0], count($restaurants[0]), 0,  $restaurants[1] );
            }else{

                $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);

            }*/

            /*************************end**********************/

            $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);
            $output['code'] = 200;

            $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($restaurants);
            echo json_encode($output);


            die();
        }
    }




    public function showRestaurantDetail()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");
        $this->loadModel("RestaurantTiming");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];


            $restaurants = $this->Restaurant->getRestaurantDetail($restaurant_id);

            $i = 0;
            foreach ($restaurants as $rest) {



                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"]          = $ratings[0]['average'];
                    $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }
                $i++;

            }
            $output['code'] = 200;

            $output['msg'] = $restaurants;
            echo json_encode($output);


            die();
        }
    }

    public function showRestaurantsAgainstSpeciality()
    {

        $this->loadModel('Restaurant');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $speciality  = $data['speciality'];
            $lat         = $data['lat'];
            $long        = $data['long'];
            $user_id     = $data['user_id'];
            $restaurants = $this->Restaurant->getRestaurantsAgainstSpeciality($speciality, $lat, $long, $user_id);


            $output['code'] = 200;
            $output['msg']  = $restaurants;
            echo json_encode($output);
            die();


        }

    }

    public function showRestaurantsSpecialities()
    {

        $this->loadModel('Restaurant');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $specialities = $this->Restaurant->getRestaurantSpecialities();


            $output['code'] = 200;
            $output['msg']  = $specialities;
            echo json_encode($output);
            die();


        }

    }


    public function showRestaurantsMenu()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantTiming");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];
            $current_time  = $data['current_time'];


            $menus = $this->Restaurant->getRestaurantMenusForMobiletest($restaurant_id);

            $i = 0;
            foreach($menus[0]['RestaurantMenu'] as $menu){

                 $menu_items = $this->RestaurantMenuItem->getMenuItemsMobile($menu['id']);

                    $j = 0;
                foreach($menu_items as $menu_item){


                     $menus[0]['RestaurantMenu'][$i]['RestaurantMenuItem'][$j] = $menu_item['RestaurantMenuItem'];

                     $menu_extra_section = $this->RestaurantMenuExtraSection->getSectionsAgainstRestaurantMenuItemMobile($menu_item['RestaurantMenuItem']['id']);
                        if(count($menu_extra_section) > 0){
                     $k = 0;

                    foreach ($menu_extra_section as $section) {

                         $menus[0]['RestaurantMenu'][$i]['RestaurantMenuItem'][$j]['RestaurantMenuExtraSection'][$k] = $section['RestaurantMenuExtraSection'];


                         $extra_items = $this->RestaurantMenuExtraItem->getExtraItemsMobile($section['RestaurantMenuExtraSection']['id']);
                         if(count($extra_items) > 0) {
                             $l = 0;

                             foreach ($extra_items as $extra_item) {

                                 $menus[0]['RestaurantMenu'][$i]['RestaurantMenuItem'][$j]['RestaurantMenuExtraSection'][$k]['RestaurantMenuExtraItem'][$l] = $extra_item['RestaurantMenuExtraItem'];
                                 $l++;
                             }
                         }else{

                             $menus[0]['RestaurantMenu'][$i]['RestaurantMenuItem'][$j]['RestaurantMenuExtraSection'][$k]['RestaurantMenuExtraItem'] = array();
                         }

                         $k++;
                     }
                        }else{

                            $menus[0]['RestaurantMenu'][$i]['RestaurantMenuItem'][$j]['RestaurantMenuExtraSection'] = array();
                        }
                     $j++;
                 }
                $i++;
            }






            $day   = Lib::getDayOfTheWeek($current_time);


            $time  = date('H:i:s', strtotime($current_time));

            //$restaurant_timing = $this->RestaurantTiming->isRestaurantOpen($day, $time, $restaurant_id);

            $menus[0]['Restaurant']['open'] = "1";


            /* if($count > 0) {
                 $menus[0]['Restaurant']['open'] = "1"; //(string)$count;
             }else{

                 $menus[0]['Restaurant']['open'] = "1";
             }*/


           // pr($menus);



            $menus = Lib::convert_from_latin1_to_utf8_recursively($menus);

            $output['code'] = 200;

            $output['msg'] = $menus;
           echo json_encode($output);



            die();
        }
    }


    public function showRestaurantRatings()
    {

        $this->loadModel("RestaurantRating");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];





            $ratings  = $this->RestaurantRating->getAvgRatings($restaurant_id);
            $comments = $this->RestaurantRating->getComments($restaurant_id);

            if (count($ratings) > 0) {
                // $restaurants[0]['TotalRatings']["avg"] = $ratings[0]['average'];
                //$restaurants[0]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                $restaurants[0]['comments'] = $comments;
                // $restaurants[0]['comments']['UserInfo'] = $comments[0]['UserInfo'];

                $output['code'] = 200;

                $output['msg'] = $restaurants;

                echo json_encode($output);
                die();
            } else {
                $output['code'] = 201;
                $output['msg']  = $ratings;
                echo json_encode($output);
                die();

            }






        }
    }

    public function addRestaurantRating()
    {

        $this->loadModel("RestaurantRating");
        $this->loadModel("Restaurant");
        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json          = file_get_contents('php://input');
            $data          = json_decode($json, TRUE);
            $user_id       = $data['user_id'];
            $restaurant_id = $data['restaurant_id'];
            $star          = $data['star'];
            $comment       = $data['comment'];
            $created       = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $rating['user_id']       = $user_id;
            $rating['restaurant_id'] = $restaurant_id;
            $rating['star']          = $star;
            $rating['comment']       = $comment;

            $rating['created'] = $created;
            if ($this->RestaurantRating->save($rating)) {

                $id             = $this->RestaurantRating->getLastInsertId();
                $result         = $this->RestaurantRating->getLastInsertedRow($id);

                        /* push notification */

                $this->Restaurant->id = $restaurant_id;
                $restaurant_user_id = $this->Restaurant->field('user_id');
                $this->UserInfo->id = $restaurant_user_id;
                $restaurant_device_token = $this->UserInfo->field('device_token');
                $this->UserInfo->id = $user_id;
                $name = $this->UserInfo->field('first_name');


                $notification['to'] = $restaurant_device_token;
                $notification['notification']['title'] = $name.':'.$comment;
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "ratings";


                $notification['data']['title']=$name.':'.$comment;
                $notification['data']['type']= "ratings";
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";


                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));



                echo Message::DATASUCCESSFULLYSAVED();


                die();

                        /***************/
                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();
            }


        }
    }


    public function giveRatingsToRider()
    {

        $this->loadModel("RiderRating");
        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id  = $data['user_id'];
            $rider_id = $data['rider_user_id'];
            $star     = $data['star'];
            $comment  = $data['comment'];
            $order_id = $data['order_id'];
            $created  = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $rating['user_id']       = $user_id;
            $rating['rider_user_id'] = $rider_id;
            $rating['star']          = $star;
            $rating['comment']       = $comment;
            $rating['order_id']      = $order_id;

            $rating['created'] = $created;
            if ($this->RiderRating->save($rating)) {

                $id             = $this->RiderRating->getLastInsertId();
                $result         = $this->RiderRating->getLastInsertedRow($id);

                $this->UserInfo->id = $rider_id;
                $rider_device_token = $this->UserInfo->field('device_token');

                $this->UserInfo->id = $user_id;
                $name = $this->UserInfo->field('first_name');
                $notification['to'] = $rider_device_token;
                $notification['notification']['title'] = $name.':'.$comment;
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "ratings";


                $notification['data']['title']=$name.':'.$comment;
                $notification['data']['type']= "ratings";
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";


                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));


                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();
            }


        }
    }


    public function placeOrder()
    {


        $this->loadModel("Order");
        $this->loadModel("User");
        $this->loadModel("UserInfo");
        $this->loadModel("Address");
        $this->loadModel("OrderMenuItem");
        $this->loadModel("OrderMenuExtraItem");
        $this->loadModel("CouponUsed");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("Restaurant");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $user_id       = $data['user_id'];

            $quantity      = $data['quantity'];
            $payment_id    = $data['payment_id'];
            $address_id    = $data['address_id'];
            $restaurant_id = $data['restaurant_id'];
            $cod           = $data['cod'];
            $tax           = $data['tax'];
            $sub_total     = $data['sub_total'];

            $instructions  = $data['instructions'];
            $coupon_id     = $data['coupon_id'];
            $status        = 1;
            $device        = @$data['device'];
            $version     =   @$data['version'];
            $delivery_fee  = $data['delivery_fee'];
            $delivery      = $data['delivery'];
            $rider_tip     = $data['rider_tip'];

            $created   = $data['order_time'];
            $menu_item = $data['menu_item'];


            if (count($menu_item) < 1) {

                echo Message::ERROR();
                die();
            }

            if($this->User->iSUserExist($user_id) == 0){

                echo Message::ERROR();
                die();
            }

            if($sub_total < 1){
                echo Message::ERROR();
                die();
            }


            $price = $delivery_fee + $rider_tip + $tax + $sub_total;






            $order['user_id']           = $user_id;
            $order['price']             = $price;
            $order['status']            = $status;
            $order['created']           = $created;
            $order['quantity']          = $quantity;
            $order['payment_method_id'] = $payment_id;
            $order['cod']               = $cod;
            $order['version']        = $version;

            $order['address_id']        = $address_id;
            $order['sub_total']         = $sub_total;
            $order['tax']               = $tax;
            $order['device']            = $device;
            $order['delivery']          = $delivery;
            $order['rider_tip']         = $rider_tip;
            $order['restaurant_id']     = $restaurant_id;
            $order['instructions']      = $instructions;
            $order['delivery_fee']      = $delivery_fee;
            $this->Order->query('SET FOREIGN_KEY_CHECKS=0');


            $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant_id);
            $address_detail = $this->Address->getAddressDetail($address_id);
            /*if(count($address_detail) > 0) {




                if ($address_detail[0]['Address']['city'] != $restaurant_location[0]['RestaurantLocation']['city']) {

                    $output['code'] = 202;
                   $output['msg'] = "Address is different from the restaurant location. Please select 'Pizza Hut' Restaurant and address should be Newyork USA ";
                    echo json_encode($output);
                    die();
                }
    }*/

            $if_order_exist = $this->Order->isOrderExist($order);


            if(count($if_order_exist) > 0){

            $time_diff = Lib::time_difference($if_order_exist['Order']['created'],$created);


         if(count($if_order_exist) > 0 && $time_diff <=60 ){

             $output['code'] = 200;
             $output['msg'] = "Your order has already been placed.";
             echo json_encode($output);
             die();

    }
            }


            if($payment_id > 0) {
                $stripe_charge = $this->deductPayment($payment_id,$price);
                $order['stripe_charge'] = $stripe_charge;
            }
            if($this->Order->save($order)) {
                $order_id = $this->Order->getLastInsertId();
                $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);


                $restaurant_user_id = $restaurant_detail[0]['Restaurant']['user_id'];
                $restaurant_user_details = $this->UserInfo->getUserDetailsFromID($restaurant_user_id);
                $device_token = $restaurant_user_details['UserInfo']['device_token'];



                Firebase::placeOrder($order_id,$restaurant_user_id,$delivery);



          if($coupon_id > 0) {
              $coupon['coupon_id'] = $coupon_id;
              $coupon['order_id'] = $order_id;
              $coupon['created'] = $created;
              $this->CouponUsed->save($coupon);
          }

            for ($i = 0; $i < count($menu_item); $i++) {

                $order_menu_item[$i]['name']     = $menu_item[$i]['menu_item_name'];
                $order_menu_item[$i]['quantity'] = $menu_item[$i]['menu_item_quantity'];
                $order_menu_item[$i]['price']    = $menu_item[$i]['menu_item_price'];

                $order_menu_item[$i]['order_id'] = $order_id;
                $this->OrderMenuItem->saveAll($order_menu_item[$i]);
                $order_menu_item_id = $this->OrderMenuItem->getLastInsertId();
                if(array_key_exists('menu_extra_item',$menu_item[$i])) {

                    if (count($menu_item[$i]['menu_extra_item']) > 0 && $menu_item[$i]['menu_extra_item'] != "") {
                        for ($j = 0; $j < count($menu_item[$i]['menu_extra_item']); $j++) {


                            $order_menu_extra_item[$j]['name'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_name'];
                            $order_menu_extra_item[$j]['quantity'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_quantity'];
                            $order_menu_extra_item[$j]['price'] = $menu_item[$i]['menu_extra_item'][$j]['menu_extra_item_price'];
                            $order_menu_extra_item[$j]['order_menu_item_id'] = $order_menu_item_id;
                            $this->OrderMenuExtraItem->saveAll($order_menu_extra_item[$j]);
                        }
                    }
                }
            }
            $order_detail   = $this->Order->getOrderDetailBasedOnID($order_id);



                /************notification*************/


                $notification['to'] = $device_token;
                $notification['notification']['title'] = "You have received a new order";
                $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['data']['title']= "You have received a new order";
                $notification['data']['body']=  'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";
                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));



                /********end notification***************/





            }



           if($delivery == 1) {
               $restaurant_will_pay = 0;



               $distance_difference_btw_user_and_restaurant = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $address_detail[0]['Address']['lat'], $address_detail[0]['Address']['long']);

               //convert distance in Kms from miles
               $distance = $distance_difference_btw_user_and_restaurant['rows'][0]['elements'][0]['distance']['text'] * 1.6;




               $min_order_price = $restaurant_detail[0]['Restaurant']['min_order_price'];
               $delivery_free_range = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

               if ($sub_total >= $min_order_price && $distance > $delivery_free_range) { //case 1

                   $distance_difference = $distance - $delivery_free_range;
                   $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance_difference;
                   $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $delivery_free_range;
                   // $total_amount = $delivery_fee + $sub_total;

               } else if ($sub_total < $min_order_price && $distance > $delivery_free_range) {


                   $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                   //$total_amount = $delivery_fee + $sub_total;


               } else if ($sub_total > $min_order_price && $distance <= $delivery_free_range) {

                   // $total_amount = $sub_total;
                   $delivery_fee_new = "0";
                   $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;

               } else if ($sub_total < $min_order_price && $distance <= $delivery_free_range) {
                   // $distance_difference = 5 - $distance;
                   $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                   //$total_amount = $delivery_fee + $sub_total;

               }


               $delivery_fee_add_zero_in_the_end = strlen(substr(strrchr($delivery_fee, "."), 1));
               if ($delivery_fee_add_zero_in_the_end == 1) {


                   $delivery_fee = $delivery_fee . "0";
               }


               $order_update['restaurant_delivery_fee'] = $restaurant_will_pay;
               $order_update['total_distance_between_user_and_restaurant'] = $distance;
               $order_update['delivery_fee_per_km'] = $restaurant_detail[0]['Tax']['delivery_fee_per_km'];
               $order_update['delivery_free_range'] = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

               /*********/

               $this->Order->id = $order_id;

               if ($this->Order->save($order_update)) {


                   //$this->UserInfo->id = $user_id;

                   /*send an email*/

                   $user_details = $this->UserInfo->getUserDetailsFromID($user_id);

                   //$email_data['User'] = $user_details['User'];
                   $order_detail[0]['User'] = $user_details['User'];
                   $email_data['OrderDetail'] = $order_detail[0];

                   CustomEmail::sendEmailPlaceOrderToUser($email_data);
                   /**********/


               }


           }


            $output['code'] = 200;

            $output['msg'] = $order_detail;
            echo json_encode($output);
            die();


        }
    }


   function deductPayment($payment_id,$total)
    {
        $this->loadModel('Order');
        $this->loadModel('PaymentMethod');
        $this->loadModel('StripeCharge');




        // $expense =  $order_gig_post[0]['OrderGigPost']['extra_expense_seller'];
            $this->PaymentMethod->id = $payment_id;
        $stripe_cust_id  = $this->PaymentMethod->field('stripe');


        if (strlen($stripe_cust_id) > 1) {



            $a = array(
                'customer' => $stripe_cust_id,
                'currency' => STRIPE_CURRENCY,

                'amount' => $total * 100
            );



            $result = $this->StripeCharge->save($a);
            if (!$result) {

                $error          = $this->StripeCharge->getStripeError();
                $output['code'] = 201;

                $output['msg'] = $error;
                return $output;
                die();
            } else {
               return $result['StripeCharge']['id'];
            }


        } else {
            $output['code'] = 201;

            $output['msg'] = "Please add a card first";
            return $output;
            die();


        }

    }




    public function orderDeal()
    {


        $this->loadModel("Order");
        $this->loadModel("OrderMenuItem");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("Restaurant");
        $this->loadModel("Address");
        $this->loadModel("UserInfo");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $name         = $data['name'];
            $restaurant_id  = $data['restaurant_id'];
            $deal_id  = $data['deal_id'];
            $description  = $data['description'];
            $price        = $data['price'];
            $quantity     = $data['quantity'];
            $delivery     = $data['delivery'];
            $tax          = $data['tax'];
            $sub_total    = $data['sub_total'];
            $delivery_fee = $data['delivery_fee'];
            $payment_id   = $data['payment_id'];
            $cod          = $data['cod'];
            $status       = 1;
            $address_id   = $data['address_id'];
            $user_id      = $data['user_id'];
            $created      = $data['order_time'];
            $device        = @$data['device'];
            $version     =   @$data['version'];







           // $order_deal['deal_name']              = $name;
            $order_deal['restaurant_id']     = $restaurant_id;

            $order_deal['deal_id']       = $deal_id;
            $order_deal['price']             = $price;
            $order_deal['quantity']          = $quantity;
            $order_deal['tax']               = $tax;
            $order_deal['sub_total']         = $sub_total;
            $order_deal['device']         = $device;
            $order_deal['version']         = $version;
            $order_deal['delivery_fee']      = $delivery_fee;
            $order_deal['delivery']      = $delivery;
            $order_deal['payment_method_id'] = $payment_id;
            $order_deal['cod']               = $cod;
            $order_deal['status']             = $status;
            $order_deal['address_id']        = $address_id;
            $order_deal['user_id']           = $user_id;
            $order_deal['created']           = $created;



            if($price == "" || $price < 1 ){

                echo Message::ERROR();
                die();
            }


            $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);
            $restaurant_location = $this->RestaurantLocation->getRestaurantLatLong($restaurant_id);
            $address_detail = $this->Address->getAddressDetail($address_id);
            if(count($address_detail) > 0) {




                if ($address_detail[0]['Address']['city'] != $restaurant_location[0]['RestaurantLocation']['city']) {

                    $output['code'] = 202;
                    $output['msg'] = "Address is different from the restaurant location";
                    echo json_encode($output);
                    die();
                }
            }



                    if($delivery == 1) {
                        $restaurant_will_pay = 0;



                        $distance_difference_btw_user_and_restaurant = Lib::getDurationTimeBetweenTwoDistances($restaurant_location[0]['RestaurantLocation']['lat'], $restaurant_location[0]['RestaurantLocation']['long'], $address_detail[0]['Address']['lat'], $address_detail[0]['Address']['long']);

                        //convert distance in Kms from miles
                        $distance = $distance_difference_btw_user_and_restaurant['rows'][0]['elements'][0]['distance']['text'] * 1.6;




                        $min_order_price = $restaurant_detail[0]['Restaurant']['min_order_price'];
                        $delivery_free_range = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                        if ($sub_total >= $min_order_price && $distance > $delivery_free_range) { //case 1

                            $distance_difference = $distance - $delivery_free_range;
                            $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance_difference;
                            $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $delivery_free_range;
                            // $total_amount = $delivery_fee + $sub_total;

                        } else if ($sub_total < $min_order_price && $distance > $delivery_free_range) {


                            $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                            //$total_amount = $delivery_fee + $sub_total;


                        } else if ($sub_total > $min_order_price && $distance <= $delivery_free_range) {

                            // $total_amount = $sub_total;
                            $delivery_fee_new = "0";
                            $restaurant_will_pay = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;

                        } else if ($sub_total < $min_order_price && $distance <= $delivery_free_range) {
                            // $distance_difference = 5 - $distance;
                            $delivery_fee_new = $restaurant_detail[0]['Tax']['delivery_fee_per_km'] * $distance;
                            //$total_amount = $delivery_fee + $sub_total;

                        }


                        $delivery_fee_add_zero_in_the_end = strlen(substr(strrchr($delivery_fee, "."), 1));
                        if ($delivery_fee_add_zero_in_the_end == 1) {


                            $delivery_fee = $delivery_fee . "0";
                        }


                        $order_deal['restaurant_delivery_fee'] = $restaurant_will_pay;
                        $order_deal['total_distance_between_user_and_restaurant'] = $distance;
                        $order_deal['delivery_fee_per_km'] = $restaurant_detail[0]['Tax']['delivery_fee_per_km'];
                        $order_deal['delivery_free_range'] = $restaurant_detail[0]['Restaurant']['delivery_free_range'];

                        /*********/











                    }

                 if($payment_id > 0) {
                     $stripe_charge = $this->deductPayment($payment_id,$sub_total);
                     $order_deal['stripe_charge'] = $stripe_charge;
                         }
                    if($this->Order->save($order_deal)) {


                        $restaurant_user_id = $restaurant_detail[0]['Restaurant']['user_id'];

                        $order_id = $this->Order->getLastInsertId();

                        Firebase::OrderDeal($order_id,$restaurant_user_id,$delivery);





                        $order_menu_item['name'] = $name;
                        $order_menu_item['deal_description'] = $description;
                        $order_menu_item['price'] = $price;
                        $order_menu_item['quantity'] = $quantity;
                        $order_menu_item['order_id'] = $order_id;

                        $this->OrderMenuItem->save($order_menu_item);

                        //$this->UserInfo->id = $user_id;

                        /*send an email*/

                        $user_details = $this->UserInfo->getUserDetailsFromID($user_id);

                        //$email_data['User'] = $user_details['User'];
                        $order_detail[0]['User'] = $user_details['User'];
                        $email_data['OrderDetail'] = $order_detail[0];

                        //CustomEmail::sendEmailPlaceOrderToUser($email_data);
                        /**********/

                    }



            $order_detail = $this->Order->getOrderDetailBasedOnID($order_id);


            /************notification*************/
            $restaurant_user_id = $restaurant_detail[0]['Restaurant']['user_id'];
            $restaurant_user_details = $this->UserInfo->getUserDetailsFromID($restaurant_user_id);
            $device_token = $restaurant_user_details['UserInfo']['device_token'];

            $notification['to'] = $device_token;
            $notification['notification']['title'] = "You have received a new order";
            $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
            $notification['notification']['badge'] = "1";
            $notification['notification']['sound'] = "default";
            $notification['notification']['icon'] = "";
            $notification['notification']['type'] = "";
            $notification['data']['title'] = "You have received a new order";
            $notification['data']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
            $notification['data']['icon'] = "";
            $notification['data']['type'] = "";
            $notification['data']['badge'] = "1";
            $notification['data']['sound'] = "default";
            PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
            //PushNotification::sendPushNotificationToTablet(json_encode($notification));


            /********end notification***************/


                     $output['code'] = 200;

                    $output['msg'] = $order_detail;
                    echo json_encode($output);
                    die();







        }
    }

    public function showOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("OrderDeal");
        $this->loadModel("RiderOrder");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $status = $data['status'];

            if (isset($data['deal'])){

                $order_deal_details = $this->Order->getUserDeals($user_id,$status);







                   if (count($order_deal_details) > 0) {

                       $i = 0;
                       foreach ($order_deal_details as $order_deal) {
                           // $active_orders[$i]['PaymentMethod'] = $this->getCardDetail($active['PaymentMethod']['stripe']);
                    /* pure bullshit is going on here. it is done only for the mobile app*/
                           $order_deal_details[$i]['Order'] = $order_deal['Order'];
                           $order_deal_details[$i]['Order']['name'] = $order_deal['Restaurant']['name'];
                           $order_deal_details[$i]['Order']['Currency'] = $order_deal['Restaurant']['Currency'];
                           $order_deal_details[$i]['Order']['Tax'] = $order_deal['Restaurant']['Tax'];
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["id"] = "0";
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["order_id"] = $order_deal['Order']['id'];
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["name"] = $order_deal['OrderMenuItem'][0]["name"];
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["quantity"] = $order_deal['Order']['quantity'];
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["price"] = $order_deal['Order']['price'];
                           $order_deal_details[$i]['Order']['OrderMenuItem'][0]["OrderMenuExtraItem"] = array();


                           //unset($order_deal_details[$i]['OrderMenuItem']);
                           unset($order_deal_details[$i]['Order']);
                           unset($order_deal_details[$i]['Restaurant']);
                           unset($order_deal_details[$i]['Restaurant']['Currency']);
                           unset($order_deal_details[$i]['Restaurant']['Tax']);


                          $riderOrderDetail = $this->RiderOrder->getRiderDetailsAgainstOrderID($order_deal['Order']['id']);
                           if (count($riderOrderDetail) > 0) {

                               if (Lib::multi_array_key_exists('RiderLocation', $riderOrderDetail[0])) {
                                   $order_deal_details[$i]['Order']['Rider']['lat'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['lat'];
                                   $order_deal_details[$i]['Order']['Rider']['long'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['long'];
                               }else{

                                   $order_deal_details[$i]['Order']['Rider']['lat'] = "";
                                   $order_deal_details[$i]['Order']['Rider']['long'] = "";
                               }
                               $order_deal_details[$i]['Order']['Rider']['first_name'] = $riderOrderDetail[0]['Rider']['first_name'];
                               $order_deal_details[$i]['Order']['Rider']['last_name'] = $riderOrderDetail[0]['Rider']['last_name'];
                               $order_deal_details[$i]['Order']['Rider']['phone'] = $riderOrderDetail[0]['Rider']['phone'];
                           } else {


                               $order_deal_details[$i]['Order']['Rider']['lat'] = "";
                               $order_deal_details[$i]['Order']['Rider']['long'] = "";
                               $order_deal_details[$i]['Order']['Rider']['first_name'] = "";
                               $order_deal_details[$i]['Order']['Rider']['last_name'] = "";
                               $order_deal_details[$i]['Order']['Rider']['phone'] = "";

                           }
                           $i++;
                       }
                   }else{


                       $order_deal_details = array();
                   }






                $output['code'] = 200;

                $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($order_deal_details);
                // $output['CompletedOrders'] = $completed_orders;

                echo json_encode($output);

                die();

            }else {

      $orders = $this->Order->getOrders($user_id,$status);

               if (count($orders) > 0) {
                    $i = 0;
                    foreach ($orders as $active) {
                        // $active_orders[$i]['PaymentMethod'] = $this->getCardDetail($active['PaymentMethod']['stripe']);
                        $orders[$i]['Order']['name'] = $orders[$i]['Restaurant']['name'];
                        $orders[$i]['Order']['Currency'] = $orders[$i]['Restaurant']['Currency'];
                        $orders[$i]['Order']['Tax'] = $orders[$i]['Restaurant']['Tax'];
                        $orders[$i]['Order']['OrderMenuItem'] = $orders[$i]['OrderMenuItem'];


                        unset($orders[$i]['OrderMenuItem']);
                        unset($orders[$i]['Restaurant']);
                        unset($orders[$i]['Restaurant']['Currency']);
                        unset($orders[$i]['Restaurant']['Tax']);


                        $riderOrderDetail = $this->RiderOrder->getRiderDetailsAgainstOrderID($orders[$i]['Order']['id']);
                        if (count($riderOrderDetail) > 0) {

                            if (Lib::multi_array_key_exists('RiderLocation', $riderOrderDetail[0])) {
                                $orders[$i]['Order']['Rider']['lat'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['lat'];
                                $orders[$i]['Order']['Rider']['long'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['long'];
                            }else{

                                $orders[$i]['Order']['Rider']['lat'] = "";
                                $orders[$i]['Order']['Rider']['long'] = "";
                            }
                            $orders[$i]['Order']['Rider']['first_name'] = $riderOrderDetail[0]['Rider']['first_name'];
                            $orders[$i]['Order']['Rider']['last_name'] = $riderOrderDetail[0]['Rider']['last_name'];
                            $orders[$i]['Order']['Rider']['phone'] = $riderOrderDetail[0]['Rider']['phone'];
                        } else {


                            $orders[$i]['Order']['Rider']['lat'] = "";
                            $orders[$i]['Order']['Rider']['long'] = "";
                            $orders[$i]['Order']['Rider']['first_name'] = "";
                            $orders[$i]['Order']['Rider']['last_name'] = "";
                            $orders[$i]['Order']['Rider']['phone'] = "";

                        }
                        $i++;
                    }
                }

            }

            $output['code'] = 200;

            $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($orders);
            // $output['CompletedOrders'] = $completed_orders;

            echo json_encode($output);


            die();





        }
    }


    public function showOrderDetail()
    {

        $this->loadModel("Order");
      //  $this->loadModel("OrderDeal");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            if(isset($data['order_id'])) {
                $order_id = $data['order_id'];
                $orders = $this->Order->getOrderDetailBasedOnID($order_id);


            }else  if(isset($data['order_deal_id'])) {

                $order_deal_id = $data['order_deal_id'];

                $orders = $this->Order->getOrderDetailBasedOnID($order_deal_id);

                $orders[0]['Order'] = $orders[0]['Order'];

                $orders[0]['OrderMenuItem'][0]["id"] = "0";
                $orders[0]['OrderMenuItem'][0]["order_id"] = $orders[0]['Order']['id'];
                $orders[0]['OrderMenuItem'][0]["name"] = $orders[0]['OrderMenuItem'][0]["name"];
                $orders[0]['OrderMenuItem'][0]["quantity"] = $orders[0]['Order']['quantity'];
                $orders[0]['OrderMenuItem'][0]["price"] = $orders[0]['Order']['price'];
                $orders[0]['OrderMenuItem'][0]["OrderMenuExtraItem"] = array();
                unset($orders[0]['Order']);

            }

            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();

    }


    }

    public function showRestaurantCompletedOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $user_id = $data['user_id'];
            $date = $data['datetime'];
            $id = $this->Restaurant->getRestaurantID($user_id);

            if (count($id) > 0) {

                $restaurant_id = $id[0]['Restaurant']['id'];
                $status = 2;

                /*the new date has been created because we need to make sure it will get the data of this date
                          from db but because we are saying time also in the db and if we compare date with datetime it
                          doesn't fetch results.*/


                $date_new = date('Y-m-d H:i:s', strtotime($date . ' +1 day'));

                $orders = $this->Order->getActiveAndCompletedOrdersOfRestaurant($restaurant_id, $status);

                if (count($orders) > 0) {


                    $i = 0;
                    foreach ($orders as $active) {
                        // $active_orders[$i]['PaymentMethod'] = $this->getCardDetail($active['PaymentMethod']['stripe']);
                        $orders[$i]['Order']['name'] = $orders[$i]['Restaurant']['name'];
                        $orders[$i]['Order']['Currency'] = $orders[$i]['Restaurant']['Currency'];
                        $orders[$i]['Order']['Tax'] = $orders[$i]['Restaurant']['Tax'];
                        $orders[$i]['Order']['OrderMenuItem'] = $orders[$i]['OrderMenuItem'];


                        unset($orders[$i]['OrderMenuItem']);
                        unset($orders[$i]['Restaurant']);
                        unset($orders[$i]['Restaurant']['Currency']);
                        unset($orders[$i]['Restaurant']['Tax']);


                        $riderOrderDetail = $this->RiderOrder->getRiderDetailsAgainstOrderID($orders[$i]['Order']['id']);

                        if (count($riderOrderDetail) > 0) {


                            $orders[$i]['Order']['Rider']['lat'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['lat'];
                            $orders[$i]['Order']['Rider']['long'] = $riderOrderDetail[0]['RiderOrder']['RiderLocation']['long'];
                            $orders[$i]['Order']['Rider']['first_name'] = $riderOrderDetail[0]['Rider']['first_name'];
                            $orders[$i]['Order']['Rider']['last_name'] = $riderOrderDetail[0]['Rider']['last_name'];
                            $orders[$i]['Order']['Rider']['phone'] = $riderOrderDetail[0]['Rider']['phone'];
                        } else {


                            $orders[$i]['Order']['Rider']['lat'] = "";
                            $orders[$i]['Order']['Rider']['long'] = "";
                            $orders[$i]['Order']['Rider']['first_name'] = "";
                            $orders[$i]['Order']['Rider']['last_name'] = "";
                            $orders[$i]['Order']['Rider']['phone'] = "";

                        }
                        $i++;
                    }
                }  else {

                Message::EmptyDATA();
                die();

            }



                $output['code'] = 200;

                $output['msg'] = $orders;

                echo json_encode($output);
                die();


        } else {

            Message::ERROR();
            die();


        }








        }
    }

    public function showOrdersBasedOnRestaurant()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];


            $id = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $pending_orders  = $this->Order->getRestaurantAcceptedAndPendingOrders($restaurant_id, 0); //0 means pending
                $accepted_orders = $this->Order->getRestaurantAcceptedAndPendingOrders($restaurant_id, 1);

                if (count($pending_orders) > 0) {
                    $i = 0;
                    foreach ($pending_orders as $active) {
                        // $active_orders[$i]['PaymentMethod'] = $this->getCardDetail($active['PaymentMethod']['stripe']);
                        $pending_orders[$i]['Order']['name']          = $pending_orders[$i]['Restaurant']['name'];
                        $pending_orders[$i]['Order']['Currency']      = $pending_orders[$i]['Restaurant']['Currency'];
                        $pending_orders[$i]['Order']['Tax']           = $pending_orders[$i]['Restaurant']['Tax'];
                        $pending_orders[$i]['Order']['OrderMenuItem'] = $pending_orders[$i]['OrderMenuItem'];


                        unset($pending_orders[$i]['OrderMenuItem']);
                        unset($pending_orders[$i]['Restaurant']);
                        unset($pending_orders[$i]['Restaurant']['Currency']);
                        unset($pending_orders[$i]['Restaurant']['Tax']);

                        $i++;
                    }
                }

                if (count($accepted_orders) > 0) {
                    $i = 0;
                    foreach ($accepted_orders as $active) {
                        // $active_orders[$i]['PaymentMethod'] = $this->getCardDetail($active['PaymentMethod']['stripe']);
                        $accepted_orders[$i]['Order']['name']          = $accepted_orders[$i]['Restaurant']['name'];
                        $accepted_orders[$i]['Order']['Currency']      = $accepted_orders[$i]['Restaurant']['Currency'];
                        $accepted_orders[$i]['Order']['Tax']           = $accepted_orders[$i]['Restaurant']['Tax'];
                        $accepted_orders[$i]['Order']['OrderMenuItem'] = $accepted_orders[$i]['OrderMenuItem'];


                        unset($accepted_orders[$i]['OrderMenuItem']);
                        unset($accepted_orders[$i]['Restaurant']);
                        unset($accepted_orders[$i]['Restaurant']['Currency']);
                        unset($accepted_orders[$i]['Restaurant']['Tax']);

                        $i++;
                    }
                }


                $output['code'] = 200;

                $output['PendingOrders']  = $pending_orders;
                $output['AcceptedOrders'] = $accepted_orders;
                $output['msg']            = "success";

                // $output['CompletedOrders'] = $completed_orders;




            } else {

                $output['code'] = 201;
                $output['msg']  = "No restaurant exist for this user id";


            }

            echo json_encode($output);

            die();


        }
    }


    public function updateRestaurantOrderStatus()
    {

        $this->loadModel("Order");
        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json            = file_get_contents('php://input');
            $data            = json_decode($json, TRUE);
            $order_id        = $data['order_id'];
            $status          = $data['status'];


            $accepted_reason          = $data['accepted_reason'];
            $rejected_reason          = $data['rejected_reason'];



            $order['accepted_reason'] = $accepted_reason;
            $order['rejected_reason '] = $rejected_reason;
            $order['hotel_accepted'] = $status;



                $this->Order->id = $order_id;
                $user_id = $this->Order->field('user_id');
                $this->UserInfo->id = $user_id;
                $device_token = $this->UserInfo->field('device_token');




                if($status == 1){


                    $order['status'] = 3;
                    $this->Order->id = $order_id;
                    $this->Order->save($order);
                    $result = $this->Order->getOrderDetailBasedOnID($order_id);

                    $restaurant_name =  $this->Order->getRestaurantName($order_id);
                   Firebase::updateRestaurantOrderStatus($order_id,$restaurant_name[0]['Restaurant']['name']);

                    /************notification*************/


                    $notification['to'] = $device_token;
                    $notification['notification']['title'] = "Order has been accepted by the restaurant";
                    $notification['notification']['body'] = $result[0]['OrderMenuItem'][0]['name'].' has been accepted by '.$restaurant_name[0]['Restaurant']['name'];
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "";
                    $notification['notification']['data']= "";

                    $notification['data']['title'] = "Order has been accepted by the restaurant";
                    $notification['data']['body'] = $result[0]['OrderMenuItem'][0]['name'].' has been accepted by '.$restaurant_name[0]['Restaurant']['name'];
                    $notification['data']['icon'] = "";
                    $notification['data']['badge'] = "1";
                    $notification['data']['sound'] = "default";
                    $notification['data']['type'] = "";
                    PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));



                    /********end notification***************/




                }else if($status == 2){

                    $order['status'] = 4;
                    $this->Order->id = $order_id;
                    $this->Order->save($order);
                    $result = $this->Order->getOrderDetailBasedOnID($order_id);
                    /************notification*************/


                    $notification['to'] = $device_token;
                    $notification['notification']['title'] = "Order has been rejected by the restaurant";
                    $notification['notification']['body'] = $rejected_reason;
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "";
                    $notification['notification']['data']= "";
                    $notification['data']['title'] =  "Order has been rejected by the restaurant";
                    $notification['data']['body'] = $rejected_reason;
                    $notification['data']['icon'] = "";
                    $notification['data']['badge'] = "1";
                    $notification['data']['sound'] = "default";
                    $notification['data']['type'] = "";
                   PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                   //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                    /********end notification***************/

                }






                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


                die();



        }
    }
    public function showAppSliderImages()
    {

        $this->loadModel("AppSlider");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $images = $this->AppSlider->getImages();


            $output['code'] = 200;

            $output['msg'] = $images;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderOrdersBasedOnDate()
    {

        $this->loadModel("RiderOrder");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $date    = $data['date'];
            $user_id = $data['user_id'];

            /*the new date has been created because we need to make sure it will get the data of this date
            from db but because we are saying time also in the db and if we compare date with datetime it
            doesn't fetch results.*/


            $date_new = date('Y-m-d H:i:s', strtotime($date . ' +1 day'));


            $orders = $this->RiderOrder->getOrdersBasedOnDate($date, $date_new, $user_id);


            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderOrders()
    {

        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTiming");
        $this->loadModel("UserInfo");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $date = $data['datetime'];

            $this->UserInfo->id = $user_id;
            $online             = $this->UserInfo->field('online');

            if ($online == 1) {

                $active_orders = $this->RiderOrder->getActiveOrders($user_id);

                $pending_orders = $this->RiderOrder->getPendingOrders($user_id);


                $output['code'] = 200;

                $output['PendingOrders'] = $pending_orders;
                $output['ActiveOrders']  = $active_orders;

               //  $output['RiderTiming'] = $rider_timing;
                // $output['CompletedOrders'] = $completed_orders;
                // $output['RejectedOrders'] = $rejected_orders;

                echo json_encode($output);

                die();

            } else {
                $output['code'] = 201;

                $output['msg'] = "rider is not online";
                echo json_encode($output);
                die();

            }










        }
    }
    public function showRiderRatings()
    {

        $this->loadModel("RiderRating");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];





            $ratings  = $this->RiderRating->getAvgRatings($user_id);
            $comments = $this->RiderRating->getComments($user_id);

            if (count($ratings) > 0) {
                // $restaurants[0]['TotalRatings']["avg"] = $ratings[0]['average'];
                //$restaurants[0]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                $ratings_rider[0]['comments'] = $comments;
                // $restaurants[0]['comments']['UserInfo'] = $comments[0]['UserInfo'];

                $output['code'] = 200;

                $output['msg'] = $ratings_rider;

                echo json_encode($output);
                die();
            } else {
                $output['code'] = 201;
                $output['msg']  = $ratings;
                echo json_encode($output);
                die();

            }






        }
    }


    public function showUpComingRiderShifts()
    {

        $this->loadModel('RiderTiming');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $datetime = $data['datetime'];
            $user_id  = $data['user_id'];



            $date = Lib::getOnlyDateFromDatetime($datetime);
            $time = Lib::getTimeFromDatetime($datetime);

            $rider_shifts = $this->RiderTiming->getRiderUpComingShifts($user_id, $date, $time);

            if (count($rider_shifts) > 0) {
                $output['code'] = 200;

                $output['msg'] = $rider_shifts;
                echo json_encode($output);
                die();
            } else {

                Message::EmptyDATA();
                die();

            }
        }

    }

    public function updateRiderOrderStatus()
    {

        $this->loadModel("RiderOrder");

        if ($this->request->isPost()) {
            $json     = file_get_contents('php://input');
            $data     = json_decode($json, TRUE);
            $order_id = $data['order_id'];
            $status   = $data['status'];


            $rider_order['accept_reject_status'] = $status;





            if ($this->RiderOrder->updateRiderResponse($status, $order_id)) {

                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else {

                echo Message::DATASAVEERROR();
                die();
            }


        }
    }


    public function updateRiderShiftStatus()
    {

        $this->loadModel("RiderTiming");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id      = $data['id'];
            $confirm = $data['confirm'];

            $this->RiderTiming->id = $id;
            if ($this->RiderTiming->saveField('confirm', $confirm)) {

                $rider_timing_detail = $this->RiderTiming->getRiderTimingAgainstID($id);

                $output['code'] = 200;
                $output['msg']  = $rider_timing_detail;
                echo json_encode($output);

            } else {

                echo Message::DATASAVEERROR();
                die();


            }



        }

    }


    public function trackRiderStatus()
    {

        $this->loadModel("RiderTrackOrder");
        $this->loadModel("RiderOrder");
        $this->loadModel("Order");
        $this->loadModel("Restaurant");
        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];
            $time     = $data['time'];

            $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($order_id);
            $pickup_time             = $this->RiderTrackOrder->isEmptyPickUpTime($order_id);
            $on_my_way_to_user_time  = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($order_id);
            $delivery_time           = $this->RiderTrackOrder->isEmptyDeliveryTime($order_id);

            $rider_details_curl =  $this->RiderOrder->getRiderUserID($order_id);
            $rider_name = $rider_details_curl[0]['Rider']['first_name'];


            $this->Order->id = $order_id;
            $user_id = $this->Order->field('user_id');
            $this->UserInfo->id = $user_id;
            $user_device_token = $this->UserInfo->field('device_token');

            $restaurant_details = $this->Order->getRestaurantName($order_id);

            $restaurant_user_id = $restaurant_details[0]['Restaurant']['user_id'];
            $this->UserInfo->id = $restaurant_user_id;
            $device_token_restaurant = $this->UserInfo->field('device_token');


            $order_detail = $this->Order->getOnlyOrderDetailBasedOnID($order_id);
            if ($on_my_way_to_hotel_time == 0) {


                $track_rider['on_my_way_to_hotel_time'] = $time;
                $msg                                    = "order collected";

                /************notification*************/


                $notification['to'] = $user_device_token;
                $notification['notification']['title'] = "Rider is on the way of Restaurant";
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['data']['title'] = "Rider is on the way of Restaurant";
                $notification['data']['body'] = '';
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";
                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /************end notification*************/
                Firebase::showRiderStatus($order_id,"0",$rider_name." is on the way to restaurant to pickup your order");
            } else if ($pickup_time == 0) {


                $track_rider['pickup_time'] = $time;
                $msg                        = "on my way to customer";


                /************notification*************/


                $notification['to'] = $user_device_token;
                $notification['notification']['title'] = "Rider has picked up your order";
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['data']['title']= "Rider has picked up your order";
                $notification['data']['body']= "";
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";
                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /************end notification*************/



                Firebase::showRiderStatus($order_id,"1",$rider_name. " has picked up your food");


            } else if ($on_my_way_to_user_time == 0) {


                $track_rider['on_my_way_to_user_time'] = $time;
                $msg                                   = "delivered";

                /************notification*************/


                $notification['to'] = $user_device_token;
                $notification['notification']['title'] = "Rider is on the way to you";
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['data']['title']= "Rider is on the way to you";
                $notification['data']['body']= "";
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";
                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /************end notification*************/


                Firebase::showRiderStatus($order_id,"0",$rider_name. " is on the way to you");

            } else if ($delivery_time == 0) {


                $track_rider['delivery_time'] = $time;
                $msg                          = "order completed";

                $this->RiderOrder->updateRiderResponse(3, $order_id);
                $this->Order->id = $order_id;
                $this->Order->saveField('status', 2);




                /************notification*************/



                $notification['to'] = $device_token_restaurant;
                $notification['notification']['title'] = "Order has been delivered";
                $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' has been delivered to customer';
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";
                $notification['data']['title']= "Order has been delivered";
                $notification['data']['body']= 'Order #'.$order_detail[0]['Order']['id'] .' has been delivered to customer';
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";


                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));



                /********end notification***************/

                /************notification to you*************/


                $notification['to'] = $user_device_token;
                $notification['notification']['title'] = "Order has been delivered to you";
                $notification['notification']['body'] = "";
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";
                $notification['data']['title']= "Order has been delivered to you";
                $notification['data']['body']= '';
                $notification['data']['icon'] = "";
                $notification['data']['badge'] = "1";
                $notification['data']['sound'] = "default";
                $notification['data']['type'] = "";
                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                /************end notification*************/



                Firebase::showRiderStatus($order_id,"0","Your order has been delivered");
                Firebase::deleteOrder($order_id);







            } else {


                $msg = "order already completed";
            }


            $track_rider['order_id'] = $order_id;


            $result = $this->RiderTrackOrder->getRiderTrackOrder($order_id);


            if (count($result) > 0) {
                $id                        = $result[0]['RiderTrackOrder']['id'];
                $this->RiderTrackOrder->id = $id;
                if ($this->RiderTrackOrder->save($track_rider)) {
                    // $result = $this->RiderTrackOrder->getRiderTrackOrder($order_id);
                    $output['code'] = 200;
                    $output['msg']  = $msg;
                    echo json_encode($output);

                    die();
                } else {

                    echo Message::DATASAVEERROR();
                    die();

                }


            } else {


                if ($this->RiderTrackOrder->save($track_rider)) {
                    //$result = $this->RiderTrackOrder->getRiderTrackOrder($order_id);
                    $output['code'] = 200;
                    $output['msg']  = $msg;
                    echo json_encode($output);

                    die();

                } else {

                    echo Message::DATASAVEERROR();
                    die();

                }
            }




        }
    }


    public function addRiderTiming()
    {



        $this->loadModel("RiderTiming");
        $this->loadModel("OpenShift");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);







            $starting_time = $data['starting_time'];
            $ending_time   = $data['ending_time'];
            $date          = $data['date'];
            $user_id       = $data['user_id'];


            if(isset($data['open_shift_id'])){

                $this->OpenShift->updateOpenShift($user_id,$data['open_shift_id']);

            }


            if (isset($data['id'])) {

                $timing['starting_time'] = $starting_time;
                $timing['ending_time']   = $ending_time;
                $timing['user_id']       = $user_id;

                $timing['date'] = $date;

                $this->RiderTiming->id = $data['id'];
                $this->RiderTiming->save($timing);
                $result         = $this->RiderTiming->getRiderTiming($user_id);
                $output['code'] = 200;
                $output['msg']  = $result;
                echo json_encode($output);
                die();

            } else {

                //$result = $this->RiderTiming->checkDuplicate($user_id,$date);
                $dates = Lib::comma_separated_to_array($date);

                if ($dates != null) {
                    for ($i = 0; $i < count($dates); $i++) {

                        $timing[$i]['date']          = $dates[$i];
                        $timing[$i]['starting_time'] = $starting_time;
                        $timing[$i]['ending_time']   = $ending_time;
                        $timing[$i]['user_id']       = $user_id;
                    }
                }



                if ($this->RiderTiming->saveAll($timing)) {

                    $result         = $this->RiderTiming->getRiderTiming($user_id);
                    $output['code'] = 200;
                    $output['msg']  = $result;
                    echo json_encode($output);
                    die();

                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }



            }





        }

        /* $result = $this->RiderTiming->checkDuplicate($user_id,$date);

        if($result > 0){
        $result = $this->RiderTiming->getRiderTiming($user_id);
        $id = $result[0]['RiderTiming']['id'];
        $this->RiderTiming->id = $id;
        $this->RiderTiming->save($timing);
        $result = $this->RiderTiming->getRiderTiming($user_id);
        $output['code'] = 200;
        $output['msg'] = $result;
        echo json_encode($output);
        die();
        }else{*/
    }

    public function showRiderTracking()
    {

        $this->loadModel("RiderTrackOrder");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];

            // $result = $this->RiderTrackOrder->getRiderTrackOrder($order_id);


            $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($order_id);
            $pickup_time             = $this->RiderTrackOrder->isEmptyPickUpTime($order_id);
            $on_my_way_to_user_time  = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($order_id);
            $delivery_time           = $this->RiderTrackOrder->isEmptyDeliveryTime($order_id);

            if ($on_my_way_to_hotel_time == 1 && $pickup_time == 0 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {



                $msg = "order collected";

            } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 0 && $delivery_time == 0) {




                $msg = "on my way to customer";

            } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 0) {




                $msg = "delivered";

            } else if ($on_my_way_to_hotel_time == 1 && $pickup_time == 1 && $on_my_way_to_user_time == 1 && $delivery_time == 1) {




                $msg = "order completed";

            } else {


                $msg = "on my way to hotel";
            }





            $output['code'] = 200;


            $output['msg'] = $msg;

            echo json_encode($output);

            die();





        }
    }

    public function showRiderTiming()
    {

        $this->loadModel("RiderTiming");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $result         = $this->RiderTiming->getRiderTiming($user_id);
            $output['code'] = 200;
            $output['msg']  = $result;
            echo json_encode($output);
            die();




        }
    }

    public function showRiderTimingBasedOnDate()
    {

        $this->loadModel("RiderTiming");
        $this->loadModel("OpenShift");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $date    = $data['date'];

            $date =  Lib::getOnlyDateFromDatetime($date);

            $result = $this->RiderTiming->getRiderTimingBasedOnDate($user_id, $date);
            $open_shifts = $this->OpenShift->getOpenShifts();
            if(count($open_shifts) > 0){

                $k   = 0;
                foreach($open_shifts as $key => $val){

                    $rider_timing['OpenShift'][0]['date'] = $val['OpenShift']['date'];

                    $rider_timing['OpenShift'][0]['timing'][$k] = $val['OpenShift'];

$k++;
                }


            }else{

                $rider_timing['OpenShift'] = array();

            }

            $i = 0;

            if (count($result) > 0) {



                foreach ($result as $r) {




                    $result2 = $this->RiderTiming->getRiderTimingsAgainstDate($user_id, $r['RiderTiming']['date']);


                    $rider_timing['RiderTiming'][$i]['date'] = $r['RiderTiming']['date'];
                    $j   = 0;
                    foreach ($result2 as $r2) {

                        $rider_timing['RiderTiming'][$i]['timing'][$j] = $r2['RiderTiming'];

                        $j++;

                    }




                    $i++;
                }



            }else{


                $rider_timing['RiderTiming'] = array();
            }

            $output['code'] = 200;
            $output['msg']  = $rider_timing;
            echo json_encode($output);
            die();


        }
    }

    public function deleteRiderTiming()
    {

        $this->loadModel("RiderTiming");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $date    = $data['date'];
            $id      = $data['id'];
            $this->RiderTiming->id = $id;
            if($this->RiderTiming->delete($id)){


            /*($this->RiderTiming->deleteAll(array(
                'RiderTiming.user_id' => $user_id,
                'RiderTiming.date' => $date,
                'RiderTiming.id' => $id
            ), true))*/

                Message::DELETEDSUCCESSFULLY();
                die();
            } else {

                Message::ERROR();
                die();

            }

            //$this->RiderTiming->deleteAll(array('upvote_question_id' => $upvote_question_id), false);

        }
    }
    public function addFavouriteRestaurant()
    {

        $this->loadModel("RestaurantFavourite");

        if ($this->request->isPost()) {
            $json          = file_get_contents('php://input');
            $data          = json_decode($json, TRUE);
            $user_id       = $data['user_id'];
            $restaurant_id = $data['restaurant_id'];
            $favourite     = $data['favourite'];

            $favourite_rest['user_id']       = $user_id;
            $favourite_rest['restaurant_id'] = $restaurant_id;
            $favourite_rest['favourite']     = $favourite;

            $fav_rest = $this->RestaurantFavourite->getFavouriteRestaurant($user_id, $restaurant_id);


            if (count($fav_rest) > 0) {

                $id = $fav_rest[0]['RestaurantFavourite']['id'];


                if ($this->RestaurantFavourite->delete($id)) {
                    Message::DELETEDSUCCESSFULLY();
                    die();

                } else {

                    Message::ERROR();
                    die();

                }


            } else {

                $this->RestaurantFavourite->save($favourite_rest);
                $id     = $this->RestaurantFavourite->getLastInsertId();
                $result = $this->RestaurantFavourite->getFavouriteRestaurantDetail($id);

                $output['code'] = 200;

                $output['msg'] = $result;
                echo json_encode($output);


                die();
            }


        }
    }
    public function showFavouriteRestaurants()
    {

        $this->loadModel("RestaurantFavourite");

        if ($this->request->isPost()) {
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);
            $user_id = $data['user_id'];

            $favourite = $this->RestaurantFavourite->getFavouritesRestaurant($user_id);

            $output['code'] = 200;

            $output['msg'] = $favourite;
            echo json_encode($output);


            die();
        }
    }

    public function dd()
    {
        $this->loadModel("RestaurantMenuExtraSection");
        $count = $this->RestaurantMenuExtraSection->countRequiredOne(5, 5);
        echo $count;
    }
    public function showMenuExtraItems()
    {

        $this->loadModel("RestaurantMenuExtraItem");
        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_menu_item_id = $data['restaurant_menu_item_id'];
            $restaurant_id           = $data['restaurant_id'];



            // $menu_extra_items = $this->RestaurantMenuExtraItem->getMenuExtraItems($restaurant_menu_item_id);
            $menu_extra_items = $this->RestaurantMenuExtraSection->getSectionsWithItems($restaurant_id, $restaurant_menu_item_id);
            $count            = $this->RestaurantMenuExtraSection->countRequiredOne($restaurant_id, $restaurant_menu_item_id);
            //$menu_extra_items[0]['count'] = $count;
            if (count($menu_extra_items) > 0) {
                for ($i = 0; $i < count($menu_extra_items); $i++) {
                    // //this array was repeating so we remove this at one place
                    //$new_menu_extra_items[$i]['RestaurantMenuExtraSection'] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
                    $menu_extra_items[$i]['RestaurantMenuExtraSection']['RestaurantMenuExtraItem'] = $menu_extra_items[$i]['RestaurantMenuExtraItem'];
                    unset($menu_extra_items[$i]['RestaurantMenuExtraItem']);


                }

            }


            $output['code']  = 200;
            $output['count'] = $count;

            $output['msg'] = $menu_extra_items;

            echo json_encode($output);


            die();
        }
    }




    public function verifyCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("CouponUsed");
        // $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $coupon_code   = $data['coupon_code'];
            $restaurant_id = $data['restaurant_id'];

            $coupon_exist  = $this->RestaurantCoupon->isCouponCodeExistAgainstRestaurantAndDeviceIsMobileApp($coupon_code, $restaurant_id);

             if(count($coupon_exist) > 0) {

              $coupon_id = $coupon_exist[0]['RestaurantCoupon']['id'];
              $user_limit = $coupon_exist[0]['RestaurantCoupon']['limit_users'];
              $count_coupon_used = $this->CouponUsed->countCouponUsed($coupon_id);

              $coupon_user_used = $this->RestaurantCoupon->ifCouponUsedAgainstRestaurant($user_id, $coupon_code, $restaurant_id);


              if (count($coupon_exist) == 1 && $coupon_user_used == 1) {

                  $output['code'] = 201;


                  $output['msg'] = "invalid coupon code";

                  echo json_encode($output);

                  die();

              } else if (count($coupon_exist) == 1 && $coupon_user_used == 0 && $count_coupon_used < $user_limit) {

                  $coupon = $this->RestaurantCoupon->getCouponDetails($restaurant_id, $coupon_code);


                  $output['code'] = 200;


                  $output['msg'] = $coupon;

                  echo json_encode($output);

                  die();


              }else{



                  $output['code'] = 201;


                  $output['msg'] = "invalid coupon code";

                  echo json_encode($output);

                  die();
              }


          }else{


              $output['code'] = 201;


              $output['msg'] = "invalid coupon code";

              echo json_encode($output);

              die();

          }








        }
    }

    public function showRestaurantDeals()
    {

        $this->loadModel("Deal");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $deals = $this->Deal->getRestaurantDeals($restaurant_id);


            $output['code'] = 200;

            $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($deals);
            echo json_encode($output);


            die();
        }
    }


    public function showDeals()
    {

        $this->loadModel("Deal");


        if ($this->request->isPost()) {



            $json         = file_get_contents('php://input');
            $data         = json_decode($json, TRUE);
            $lat          = $data['lat'];
            $long         = $data['long'];
            $current_time = $data['current_time'];


            $results = Lib::getCountryCityProvinceFromLatLong($lat,$long);

            if(strlen($results['city']) > 2) {

                $deals = $this->Deal->getDealsAgainstCity($lat,$long,$results['city']);
            }else{

                $deals = $this->Deal->getDeals($lat, $long);

            }



            //debug($this->Deal->lastQuery());
            $output['code'] = 200;

            $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($deals);
            echo json_encode($output);


            die();
        }
    }

    public function showDealBasedOnID()
    {

        $this->loadModel("Deal");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $deals = $this->Deal->getDeal($id);


            $output['code'] = 200;

            $output['msg'] = $deals;
            echo json_encode($output);


            die();
        }
    }
    public function chat()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $chat = array();
            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $sender_id   = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $message     = $data['message'];
            $datetime    = $data['created'];

            $chat['sender_id']   = $sender_id;
            $chat['receiver_id'] = $receiver_id;
            $chat['message']     = $message;
            $chat['created']     = $datetime;
            $isChatExist         = $this->Chat->getUserChat($sender_id, $receiver_id);

            if (count($isChatExist) > 0) {

                $id = $isChatExist[0]['Chat']['conversation_id'];

                $chat['conversation_id'] = $id;
                if ($this->Chat->save($chat)) {
                    Message::DATASUCCESSFULLYSAVED();

                }

            } else {

                if ($this->Chat->save($chat)) {
                    $id                              = $this->Chat->getInsertID();
                    $this->Chat->id                  = $id;
                    $conversation['conversation_id'] = $id;
                    if ($this->Chat->save($conversation)) {
                        Message::DATASUCCESSFULLYSAVED();

                    }
                }

            }
            /* if($this->Chat->save($chat)){

            Message::DATASUCCESSFULLYSAVED();

            }else{

            echo Message::DATASAVEERROR();

            }*/
        }
    }

    public function getConversation()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);

            $sender_id   = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $user_id     = $data['user_id'];
            $userMessage = $this->Chat->getUserChat($sender_id, $receiver_id);


            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id']              = $userMessage[$i]['Chat']['id'];
                $message[$i]['Chat']['sender_id']       = $userMessage[$i]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id']     = $userMessage[$i]['Chat']['receiver_id'];
                $message[$i]['Chat']['message']         = $userMessage[$i]['Chat']['message'];
                $message[$i]['Chat']['created']         = $userMessage[$i]['Chat']['created'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i]['Chat']['conversation_id'];


                if ($userMessage[$i]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i]['sender_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i]['sender_info']['profile_img'];



                } else if ($userMessage[$i]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i]['receiver_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }
            $output         = array();
            $output['code'] = 200;
            $output['msg']  = $message;
            echo json_encode($output);


        }

    }

    public function getNewConversation()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);

            $sender_id   = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $user_id     = $data['user_id'];
            $id          = $data['id'];
            $userMessage = $this->Chat->getLastChat($sender_id, $receiver_id, $id);


            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id']              = $userMessage[$i]['Chat']['id'];
                $message[$i]['Chat']['sender_id']       = $userMessage[$i]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id']     = $userMessage[$i]['Chat']['receiver_id'];
                $message[$i]['Chat']['message']         = $userMessage[$i]['Chat']['message'];
                $message[$i]['Chat']['created']         = $userMessage[$i]['Chat']['created'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i]['Chat']['conversation_id'];


                if ($userMessage[$i]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i]['sender_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i]['sender_info']['profile_img'];



                } else if ($userMessage[$i]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i]['receiver_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }
            $output         = array();
            $output['code'] = 200;
            $output['msg']  = $message;
            echo json_encode($output);


        }

    }

    public function chatInbox()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json    = file_get_contents('php://input');
            $data    = json_decode($json, TRUE);

            $user_id = $data['user_id'];


            $userMessage = $this->Chat->showUserInbox($user_id);
            //print_r($userMessage);
            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id']              = $userMessage[$i][0]['Chat']['id'];
                $message[$i]['Chat']['sender_id']       = $userMessage[$i][0]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id']     = $userMessage[$i][0]['Chat']['receiver_id'];
                $message[$i]['Chat']['message']         = $userMessage[$i][0]['Chat']['message'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i][0]['Chat']['conversation_id'];
                $message[$i]['Chat']['created']         = $userMessage[$i][0]['Chat']['created'];


                if ($userMessage[$i][0]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i][0]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i][0]['sender_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i][0]['sender_info']['profile_img'];



                } else if ($userMessage[$i][0]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id']    = $userMessage[$i][0]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name']  = $userMessage[$i][0]['receiver_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] = $userMessage[$i][0]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }

            // debug($this->User->lastQuery());

            $output         = array();
            $output['code'] = 200;
            $output['msg']  = $message;
            echo json_encode($output);


        }
    }



    function forgotPassword()
    {

        $this->loadModel('User');
        if ($this->request->isPost()) {


            $result = array();
            $json   = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $email     = $data['email'];
            $user_info = $this->User->findByEmail($email);


            if (!empty($user_info)) {

                $key     = Security::hash(CakeText::uuid(), 'sha512', true);
                $user_id = $user_info['User']['id'];
                $email   = $user_info['User']['email'];


                $response = CustomEmail::sendEmailResetPassword($email, $key);


                if ($response) {

                    $this->User->id = $user_id;
                    $savedField     = $this->User->saveField('token', $key);
                    $result['code'] = 200;
                    $result['msg']  = "An email has been sent to " . $email . ". You should receive it shortly.";
                } else {

                    $result['code'] = 201;
                    $result['msg']  = "invalid email";


                }

            } else {

                $result['code'] = 201;
                $result['msg']  = "Email doesn't exist";
            }



            echo json_encode($result);
        }


    }

    public function resetPassword()
    {
        $this->loadModel('User');
        $token     = $this->request->query('token');
        //echo $this->params['url']['token'];
        $user_info = $this->User->findByToken($token);
        if (!empty($user_info)) {
            $this->User->id = $user_info['User']['id'];
            $db_token       = $user_info['User']['token'];
            if ($token == $db_token) {
                $this->autoRender = true;
                $this->layout     = "resetpassword-default";
            } else {
                echo "token has been expired";
            }
        } else {
            echo "invalid url";
        }
    }

    public function saveNewPassword()
    {
        $this->loadModel('User');
        if ($this->request->isPost()) {

            $password1                       = $this->request->data("pw1");
            $pw1                             = trim($password1);
            $password2                       = $this->request->data("pw2");
            $email                           = $this->request->data("email");
            $user_info                       = $this->User->findByEmail($email);
            $this->User->id                  = $user_info['User']['id'];
            $this->request->data['password'] = $pw1;
            $this->request->data['token']    = 0;
            if ($this->User->save($this->request->data)) {


                echo "success";
            }
        }
    }
    public function changePassword()
    {
        $this->loadModel('User');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            //$json = $this->request->data('json');
            $data = json_decode($json, TRUE);


            $user_id        = $data['user_id'];
            $this->User->id = $user_id;
            $email          = $this->User->field('email');
            //$this->request->data['email'] = $data['email'];
            $old_password   = $data['old_password'];
            $new_password   = $data['new_password'];


            if ($this->User->verifyPassword($email, $old_password)) {

                $this->request->data['password'] = $new_password;
                $this->User->id                  = $user_id;


                if ($this->User->save($this->request->data)) {

                    echo Message::DATASUCCESSFULLYSAVED();

                    die();
                } else {


                    echo Message::DATASAVEERROR();
                    die();


                }

            } else {

                echo Message::INCORRECTPASSWORD();
                die();

            }


        }

    }

    function getCardDetail($stripe_id)
    {

        $this->loadModel('StripeCustomer');
        $response = $this->StripeCustomer->getCardDetails($stripe_id);



        $stripeCustomer                       = $response[0]['StripeCustomer']['sources']['data'][0];
        $stripData[0]['CardDetails']['brand'] = $stripeCustomer['brand'];
        $stripData[0]['CardDetails']['brand'] = $stripeCustomer['brand'];
        $stripData[0]['CardDetails']['last4'] = $stripeCustomer['last4'];
        $stripData[0]['CardDetails']['name']  = $stripeCustomer['name'];


        return $stripData[0]['CardDetails'];


    }
    public function addLocation()
    {


        $this->loadModel("Location");
        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $user_id = $data['user_id'];
            $long    = $data['long'];
            $lat     = $data['lat'];
            //$city = $data['city'];
            //$state = $data['state'];
            // $country = $data['country'];

            $location_string = $data['location_string'];






            $array['user_id']         = $user_id;
            $array['long']            = $long;
            $array['lat']             = $lat;
            // $array['city'] = $city;
            //$array['state'] = $state;
            //$array['country'] = $country;
            $array['location_string'] = $location_string;






            $locationData = $this->Location->getUserLocation($user_id);

            if (count($locationData) == 0) {
                $this->Location->save($array);

            } else {

                $this->Location->id = $locationData[0]['Location']['location_id'];
                $this->Location->save($array);

            }


            //$gigpost_category['cat_id'] = $cat_id;





            $userDetails = $this->UserInfo->getUserDetails($user_id);


            $output['code'] = 200;

            $output['msg'] = $userDetails;
            echo json_encode($output);


            die();




        }



    }






    public function showCountries()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {




            $countries = $this->Tax->getCountries();
            $cities    = $this->Tax->getCities();
            $states    = $this->Tax->getStates();





            $output['code'] = 200;

            $output['cities']    = $cities;
            $output['states']    = $states;
            $output['countries'] = $countries;
            echo json_encode($output);


            die();
        }
    }



/*public function testPush(){

    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= '/app/Lib/pusher/src/Pusher.php';
    require_once($path);

    $app_id = '491175';
    $app_key = '92393bdc5c9e9f372cce';
    $app_secret = '0c46d4f5f775c0fa83b7';
    $app_cluster = 'ap2';




    $pusher = new Pusher\Pusher( $app_key, $app_secret, $app_id, array('cluster' => $app_cluster) );

//$pusher = new Pusher\Pusher($app_key, $app_secret, $app_id);

    while (true) {
        $data['message'] = 'hello f';
        $result = $pusher->trigger('my-channel', 'my-event', $data);// Call your function
        sleep(5);
    }

    //$data['message'] = 'hello f';

}*/



    function checkRestuarantIsOpenOrNot($day, $time, $restaurant_id)
    {
        $this->loadModel('RestaurantTiming');

        $count = $this->RestaurantTiming->isRestaurantOpen($day, $time, $restaurant_id);
        // debug($this->RestaurantTiming->lastQuery());
        if ($count == 1) {

            return "open";

        } else {

            return "closed";

        }

    }


    public function temporarySetOnlineStatus()
    {

        $result = Lib::getDurationTimeBetweenTwoDistances(-31.441510, 2073.134561, 31.456465, 2073.130099);

        //pr($result['rows'][0]['elements'][0]['duration']['text']);
        echo $result['rows'][0]['elements'][0]['duration']['value'];
        echo $result['rows'][0]['elements'][0]['duration']['text'];

        $this->loadModel('UserInfo');

        if ($this->UserInfo->temporaryUpdation()) {


            echo "success";
        } else {

            echo "false";
        }

    }

    public function emailsuccess($token = null)
    {
        $this->layout = false;
        $this->loadModel('User');



        $user_info = $this->User->findByToken($token);
        if (!empty($user_info)) {
            $userid = $user_info['User']['id'];
            if ($userid != "") {

                $user['token']  = "";
                $user['active'] = 1;
                $this->User->id = $userid;
                $savedField     = $this->User->save($user);
                if ($savedField) {
                    echo "Your email has been successfully verified";

                } else {

                    $this->set("message", "Sorry, something went wrong.Contact the administrator");
                }
            }
        } else {

            echo "link has been expired. Please register again";
        }
    }

    /*function RiderOrderNotificationsCronjob(){


    $this->loadModel('RiderOrder');
    $this->loadModel('RiderLocation');
    $accepted_orders = $this->RiderOrder->getAllAcceptedOrders();

    $i=0;
    foreach($accepted_orders as $accepted_order){

    $location = $this->RiderLocation->getRiderLocation($accepted_order['RiderOrder']['rider_user_id']);
    $lat = $location[0]['RiderLocation']['lat'];
    $long = $location[0]['RiderLocation']['long'];

    $device_token = $accepted_order['Order']['UserInfo']['device_token'];


    $new_data[$i]['rider_lat'] = $lat;
    $new_data[$i]['rider_long'] = $long;



    //$new_data[$i]['message'] = "";
    $new_data[$i]['type'] = "map_tracking";
    $new_data[$i]['alert'] = "hello";
    $new_data[$i]['badge'] = 1;
    $new_data[$i]['sound'] = 'default';

    PushNotification::sendPushNotificationToApp($new_data[$i],$device_token);
    $i++;

    }



    }*/




}




?>
<?php
App::uses('Lib', 'Utility');
App::uses('Postmark', 'Utility');
App::uses('Message', 'Utility');

App::uses('CustomEmail', 'Utility');
App::uses('Security', 'Utility');
App::uses('PushNotification', 'Utility');

class SuperAdminController extends AppController
{


    public $autoRender = false;
    public $layout = false;
    //101 - something already in the db
    //100 - success
    //102 - invalid


  

   public function index(){


    echo "Congratulations!. You have configured your admin api correctly";

    //show students count on web
}
    public function registerRider()
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');
        $this->loadModel('RiderLocation');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $phone = $data['phone'];
            $note = @$data['note'];
            $device_token = $data['device_token'];
            $role = $data['role'];
            $city = $data['city'];
            $country = $data['country'];
            $address_to_start_shift = $data['address_to_start_shift'];




            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['active'] = 1;
                $user['role'] = $role;
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


                    $user_id = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;

                    $user_info['device_token'] = $device_token;
                    $user_info['first_name'] = $first_name;
                    $user_info['note'] = $note;
                    $user_info['last_name'] = $last_name;
                    $user_info['phone'] = $phone;
                    $rider_location['city'] = $city;
                    $rider_location['country'] = $country;
                    $rider_location['address_to_start_shift'] = $address_to_start_shift;
                    $rider_location['user_id'] = $user_id;


                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }

                    if (!$this->RiderLocation->save($rider_location)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $output = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function blockRestaurant()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $block = $data['block'];
            $this->Restaurant->id = $id;


            if ($this->Restaurant->saveField('block',$block)) {

                $restaurant_details = $this->Restaurant->getRestaurantDetail($id);

                $output['code'] = 200;
                $output['msg'] = $restaurant_details;

                echo json_encode($output);
                die();
            }else{

                Message::ERROR();
                die();
            }
        }
    }


    public function login() //changes done by irfan
    {
        $this->loadModel('UserAdmin');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            // $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = strtolower($data['email']);
            $password = $data['password'];
            //  $device_token = $data['device_token'];
            // $userData['msg'] = ;

            if ($email != null && $password != null) {
                $userData = $this->UserAdmin->loginAllUsers($email, $password);

                if ($userData) {
                    $user_id = $userData[0]['UserAdmin']['id'];

                    // $this->UserInfo->id = $user_id;
                    // $savedField = $this->UserInfo->saveField('device_token', $device_token);

                    $output = array();
                    $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


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



    public function addUser() //admin can add multiple users and assign roles
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $phone = $data['phone'];

            $role = $data['role'];


            //file_put_contents(Variables::$UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['active'] = 1;
                $user['role'] = $role;
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


                    $user_id = $this->User->getInsertID();
                    $user_info['user_id'] = $user_id;


                    $user_info['first_name'] = $first_name;
                    $user_info['last_name'] = $last_name;
                    $user_info['phone'] = $phone;


                    if (!$this->UserInfo->save($user_info)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }


                    $output = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function addAdminUser() //admin can add multiple users and assign roles
    {


        $this->loadModel('UserAdmin');


        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email = $data['email'];
            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];


            $role = $data['role'];

            $role_name = $data['role_name'];
            $phone = $data['phone'];


            //file_put_contents(Variables::$UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

            if ($email != null && $password != null) {


                $user['email'] = $email;
                $user['password'] = $password;

                $user['first_name'] = $first_name;
                $user['last_name'] = $last_name;
                $user['phone'] = $phone;
                $user['active'] = 1;
                $user['role'] = $role;
                $user['role_name'] = $role_name;
                $user['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


                $count = $this->UserAdmin->isEmailAlreadyExist($email);


                if ($count && $count > 0) {
                    echo Message::DATAALREADYEXIST();
                    die();

                } else {



                    if (!$this->UserAdmin->save($user)) {
                        echo Message::DATASAVEERROR();
                        die();
                    }




                    $id = $this->UserAdmin->getLastInsertId();
                    $output = array();
                    $userDetails = $this->UserAdmin->getUserDetailsFromID($id);


                    $output['code'] = 200;
                    $output['msg'] = $userDetails;
                    echo json_encode($output);


                }
            } else {
                echo Message::ERROR();
            }
        }
    }

    public function showAdminUsers()
    {

        $this->loadModel("UserAdmin");


        if ($this->request->isPost()) {


            $users = $this->UserAdmin->getAllUsers();


            $output['code'] = 200;

            $output['msg'] = $users;
            echo json_encode($output);


            die();
        }
    }

    public function showRestaurantOrders()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {


            $restaurant_orders = $this->Restaurant->getRestaurantOrders();


            $output['code'] = 200;

            $output['msg'] = $restaurant_orders;
            echo json_encode($output);


            die();
        }
    }

    public function editUserProfile()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("User");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $email = $data['email'];


            $user_info['first_name'] = $first_name;
            $user_info['last_name'] = $last_name;
            $user['email'] = $email;


            $this->UserInfo->id = $user_id;
            $this->User->id = $user_id;
            if ($this->UserInfo->save($user_info) && $this->User->save($user)) {
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

    public function editUserPassword()
    {

        $this->loadModel("UserInfo");
        $this->loadModel("User");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $password = $data['password'];

            $info['password'] = $password;



            $this->User->id = $user_id;
            if ($this->User->save($info)){
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

    public function editAdminUserPassword()
    {


        $this->loadModel("UserAdmin");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $password = $data['password'];

            $info['password'] = $password;



            $this->UserAdmin->id = $user_id;
            if ($this->UserAdmin->save($info)){
                $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);


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
    public function EnableOrDisableAdminUser()
    {


        $this->loadModel("UserAdmin");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $active = $data['active'];
            $user_id = $data['user_id'];


            $this->UserAdmin->id = $user_id;

            if ($this->UserAdmin->saveField("active",$active)) {
                $userDetails = $this->UserAdmin->getUserDetailsFromID($user_id);


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


    public function showAllUsers()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $users = $this->User->getAllUsers();


            $output['code'] = 200;

            $output['msg'] = $users;
            echo json_encode($output);


            die();
        }
    }

    public function showUserDetail()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $userDetail = $this->UserInfo->getUserDetailsFromID($user_id);


            $output['code'] = 200;

            $output['msg'] = $userDetail;
            echo json_encode($output);


            die();
        }
    }

    public function showUsersBasedOnSearchKeyword()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $keyword = $data['keyword'];
            $users = $this->UserInfo->searchUser($keyword);


            $output['code'] = 200;

            $output['msg'] = $users;
            echo json_encode($output);


            die();
        }
    }


    public function showRestaurants()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {


            $restaurants = $this->Restaurant->getAllRestaurants();

            $i = 0;
            foreach ($restaurants as $rest) {
                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }
                $i++;

            }
            $output['code'] = 200;

            $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($restaurants);
            echo json_encode($output);


            die();
        }
    }

    public function showRestaurantsBasedOnSearchKeyword()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $keyword = $data['keyword'];


            $restaurants = $this->Restaurant->searchRestaurant($keyword);

            $i = 0;
            foreach ($restaurants as $rest) {
                $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $restaurants[$i]['TotalRatings']["avg"] = $ratings[0]['average'];
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

    public function editRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("Tax");
        $this->loadModel("Currency");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name = $data['name'];
            $slogan = $data['slogan'];
            $about = $data['about'];
            $notes = $data['notes'];

            $min_order_price = $data['min_order_price'];
            $delivery_free_range = $data['delivery_free_range'];
            $preparation_time = $data['preparation_time'];
            $tax_free = $data['tax_free'];
            $google_analytics = $data['google_analytics'];
            $phone = $data['phone'];
            $time_zone = $data['timezone'];
            $menu_style = $data['menu_style'];
            $promoted = $data['promoted'];
            $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

            $city = $data['city'];
            $restaurant_id = $data['restaurant_id'];
            $state = $data['state'];

            $country = $data['country'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];
            $currency_id = $data['currency_id'];
            $tax_id = $data['tax_id'];
            $speciality = $data['speciality'];



            $restaurant_timing = $data['restaurant_timing'];








            $restaurant['name'] = $name;
            $restaurant['slogan'] = $slogan;
            $restaurant['about'] = $about;


            //$restaurant['delivery_fee'] = $delivery_fee;
            $restaurant['phone'] = $phone;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['timezone'] = $time_zone;
            $restaurant['menu_style'] = $menu_style;
            $restaurant['promoted'] = $promoted;
            $restaurant['speciality'] = $speciality;
            $restaurant['notes'] = $notes;
            $restaurant['min_order_price'] = $min_order_price;
            $restaurant['delivery_free_range'] = $delivery_free_range;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['tax_free'] = $tax_free;
            $restaurant['google_analytics'] = $google_analytics;

            $restaurant['currency_id'] = $currency_id;
            $restaurant['tax_id'] = $tax_id;


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;

            $restaurant_details = $this->Restaurant->getRestaurantDetail($restaurant_id);

            if (isset($data['image']) && $data['image'] != " ") {
                $image_db = $restaurant_details[0]['Restaurant']['image'];


                @unlink($image_db);


                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $image, $folder_url);
                $restaurant['image'] = $filePath;


            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {
                $cover_image_db = $restaurant_details[0]['Restaurant']['cover_image'];
                @unlink($cover_image_db);
                $cover_image = $data['cover_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                $restaurant['cover_image'] = $filePath;

            }


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;


            $this->RestaurantTiming->deleteAll(array(
                'restaurant_id' => $restaurant_id
            ), false);

            foreach ($restaurant_timing as $k => $v) {


                $timing[$k]['day'] = $v['day'];
                $timing[$k]['opening_time'] = $v['opening_time'];
                $timing[$k]['closing_time'] = $v['closing_time'];
                $timing[$k]['restaurant_id'] = $restaurant_id;

            }

            $this->RestaurantTiming->saveAll($timing);
            $this->RestaurantLocation->id = $restaurant_id;
            $this->RestaurantLocation->save($restaurant_location);
            $this->Restaurant->id = $restaurant_id;


            if($this->Restaurant->save($restaurant)) {


                $restaurant_details = $this->Restaurant->getRestaurantDetail($restaurant_id);


                $output['code'] = 200;
                $output['msg'] = $restaurant_details;
                echo json_encode($output);

                die();


            }else{


                echo Message::DATASAVEERROR();
                die();

            }



        }


    }

    public function editRestaurantold()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $restaurant_id = $data['restaurant_id'];

            $slogan = strtolower($data['slogan']);
            $about = strtolower($data['about']);

            $slogan = ucwords($slogan);
            $about = ucwords($about);


            $delivery_fee = $data['delivery_fee'];
            $phone = $data['phone'];
            $time_zone = $data['timezone'];
            $menu_style = $data['menu_style'];
            //$promoted = $data['promoted'];
            $updated = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
            $google_analytics = $data['google_analytics'];

            $city = $data['city'];
            //$currency = $data['currency'];
            $state = $data['state'];
            $country = $data['country'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];

            $restaurant_timing = $data['restaurant_timing'];


            //delete images-------------------

            $restaurant = $this->Restaurant->getRestaurantDetail($restaurant_id);


            $image = $restaurant[0]['Restaurant']['image'];
            $cover_image = $restaurant[0]['Restaurant']['cover_image'];

            @unlink($image);
            @unlink($cover_image);


            //   -----------------------------------------------
            if (isset($data['image']) && $data['image'] != " ") {

                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::compressImage($restaurant_id, $image, $folder_url);
                $restaurant['image'] = $filePath;
            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                $cover_image = $data['cover_image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::compressImage($restaurant_id, $cover_image, $folder_url);
                $restaurant['cover_image'] = $filePath;
            }


            $restaurant['user_id'] = $user_id;
            //  $restaurant['name'] = $name;
            $restaurant['slogan'] = $slogan;
            $restaurant['about'] = $about;
            //$restaurant['currency'] = $currency;
            $restaurant['updated'] = $updated;
            $restaurant['google_analytics'] = $google_analytics;
            $restaurant['delivery_fee'] = $delivery_fee;
            $restaurant['phone'] = $phone;
            $restaurant['timezone'] = $time_zone;
            $restaurant['menu_style'] = $menu_style;
            //   $restaurant['promoted'] = $promoted;

            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;


            $this->RestaurantTiming->deleteAll(array(
                'restaurant_id' => $restaurant_id
            ), false);

            foreach ($restaurant_timing as $k => $v) {


                $timing[$k]['day'] = $v['day'];
                $timing[$k]['opening_time'] = $v['opening_time'];
                $timing[$k]['closing_time'] = $v['closing_time'];
                $timing[$k]['restaurant_id'] = $restaurant_id;

            }

            $this->RestaurantTiming->saveAll($timing);
            $this->RestaurantLocation->id = $restaurant_id;
            $this->RestaurantLocation->save($restaurant_location);
            $this->Restaurant->id = $restaurant_id;
            $this->Restaurant->save($restaurant);

            $rest_details = $this->Restaurant->getRestaurantDetail($restaurant_id);
            $output['code'] = 200;
            $output['msg'] = $rest_details;
            echo json_encode($output);


        }


    }




    public function showAllOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderTrackOrder");

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $status = $data['status'];

            foreach ($status as $key => $val) {

                $status[$key] = $val['status'];

            }
            $orders = $this->Order->getAllOrdersSuperAdmin($status);
            if($status==0){

                //$orders = $this->Order->getAllOrdersSuperAdmin();
            }else if($status==3){
                //$orders = $this->Order->getRejectedOrAcceptedOrdersSuperAdmin(2);
            }else {
                //$orders = $this->Order->getAllOrdersAccordingToStatusSuperAdmin($status);
            }
            foreach ($orders as $key => $val) {

               $rider_details =  $this->RiderOrder->getRiderDetailsAgainstOrderID($val['Order']['id']);



             /*  if($val['Order']['payment_method_id'] == 0){
                   $orders[$key]['PaymentMethod']['stripe'] = "";
                   $orders[$key]['PaymentMethod']['paypal'] = "";
                   $orders[$key]['PaymentMethod']['created'] = "";
                   $orders[$key]['PaymentMethod']['user_id'] = "";
                   $orders[$key]['PaymentMethod']['id'] = "";
                   $orders[$key]['PaymentMethod']['default'] = "";
               }
*/

                if(count($rider_details) > 0){




                    $on_my_way_to_hotel_time = $this->RiderTrackOrder->isEmptyOnMyWayToHotelTime($val['Order']['id']);
                    $pickup_time             = $this->RiderTrackOrder->isEmptyPickUpTime($val['Order']['id']);
                    $on_my_way_to_user_time  = $this->RiderTrackOrder->isEmptyOnMyWayToUserTime($val['Order']['id']);
                    $delivery_time           = $this->RiderTrackOrder->isEmptyDeliveryTime($val['Order']['id']);

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


                    $orders[$key]['Order']['RiderOrder']= $rider_details[0]['RiderOrder'];
                    $orders[$key]['Order']['RiderOrder']['order_status'] = $msg;
                    $orders[$key]['Order']['RiderOrder']['Rider']= $rider_details[0]['Rider'];
                    $orders[$key]['Order']['RiderOrder']['Assigner']= $rider_details[0]['Assigner'];


                }else{

                    $orders[$key]['Order']['RiderOrder']= array();

                }
            }


            $output['code'] = 200;

            $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($orders);
            echo json_encode($output);


            die();
        }
    }

    public function showAllOrdersAutoLoad()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {



            $orders = $this->Order->getOnlyOrdersAccordingToStatusSuperAdmin();


            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }


    /*public function showOrderDetail()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];
            $orders = $this->Order->getOrderDetailBasedOnID($order_id);


               $orders[0]['Currency'] = $orders[0]['Restaurant']['Currency'];
               $orders[0]['Tax'] = $orders[0]['Restaurant']['Tax'];
               $orders[0]['RestaurantLocation'] = $orders[0]['Restaurant']['RestaurantLocation'];

            if(array_key_exists("Rider",$orders[0]['RiderOrder'])) {
                $orders[0]['Rider'] = $orders[0]['RiderOrder']['Rider'];
                unset($orders[0]['RiderOrder']['Rider']);
            }
            if(array_key_exists("RiderLocation",$orders[0]['RiderOrder'])) {
                $orders[0]['RiderLocation'] = $orders[0]['RiderOrder']['RiderLocation'];
                unset($orders[0]['RiderOrder']['RiderLocation']);
            }
                    $i=0;
           foreach($orders[0]['OrderMenuItem'] as $menu_item){

               $orders[0]['OrderMenuItem'][$i]['id'] = $menu_item['id'];
               $orders[0]['OrderMenuItem'][$i]['order_id'] = $menu_item['order_id'];
               $orders[0]['OrderMenuItem'][$i]['name'] = $menu_item['name'];
               $orders[0]['OrderMenuItem'][$i]['quantity'] = $menu_item['quantity'];
               $orders[0]['OrderMenuItem'][$i]['price'] = $menu_item['price'];
               $orders[0]['OrderMenuItem'][$i]['deal_description'] = $menu_item['deal_description'];

               if(count( $menu_item['OrderMenuExtraItem']) > 0){
                   $j = 0;
                   foreach( $menu_item['OrderMenuExtraItem'] as $extra_menu_item){

                       $orders[0]['OrderMenuExtraItem'][$j]['id'] = $extra_menu_item['id'];
                       $orders[0]['OrderMenuExtraItem'][$j]['order_menu_item_id'] = $extra_menu_item['order_menu_item_id'];
                       $orders[0]['OrderMenuExtraItem'][$j]['name'] = $extra_menu_item['name'];
                       $orders[0]['OrderMenuExtraItem'][$j]['quantity'] = $extra_menu_item['quantity'];
                       $orders[0]['OrderMenuExtraItem'][$j]['price'] = $extra_menu_item['price'];
                       unset($orders[0]['OrderMenuItem'][$i]['OrderMenuExtraItem'][$j]);
                       $j++;
                   }

               }
              // unset($orders[0]['OrderMenuItem'][$i]);
              $i++;
           }

               unset($orders[0]['Restaurant']['Currency']);
               unset($orders[0]['Restaurant']['Tax']);
               unset($orders[0]['Restaurant']['RestaurantLocation']);




            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }*/

    public function showOrderDetail()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];

            //$user_id = $data['user_id'];






                $orders = $this->Order->getOrderDetailBasedOnOrderIDSuperAdmin($order_id);


            if (count($orders) > 0) {
                $output['code'] = 200;

                $output['msg'] = $orders;
                echo json_encode($output);
                die();

            } else {

                Message::EmptyDATA();
                die();

            }


        }
    }

    public function showWeeklyAndMonthlyOrders()
    {

        $this->loadModel("Order");



        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            if(isset($data['restaurant_id']) && !isset($data['start_date'])){

               $orders =  $this->Order->getOnlyRestaurantOrders($data['restaurant_id']);
            }else if(isset($data['restaurant_id']) && isset($data['start_date'])){

                $orders =  $this->Order->getOnlyRestaurantOrdersBetweenTwoDates($data['restaurant_id'],$data['start_date'],$data['end_date']);
            }else if(!isset($data['restaurant_id']) && isset($data['start_date'])){

                $orders =  $this->Order-> getOnlyOrdersBetweenTwoDates($data['start_date'],$data['end_date']);
            }else if(!isset($data['restaurant_id']) && !isset($data['start_date'])){

                $orders =  $this->Order-> getAllOnlyOrders();
            }

           /* $add_day_in_start_date = new DateTime($start_date);
            $add_day_in_start_date->modify('+1 day');
            $start_date_next_day =  $add_day_in_start_date->format('Y-m-d');

            $add_day_in_end_date = new DateTime($start_date);
            $add_day_in_end_date->modify('+1 day');
            $end_date_next_day =  $add_day_in_end_date->format('Y-m-d');
*/








            if (count($orders) > 0) {
                $output['code'] = 200;

                $output['msg'] = $orders;
                echo json_encode($output);
                die();

            } else {

                Message::EmptyDATA();
                die();

            }


        }
    }
    public function assignOrderToRider()
    {

        $this->loadModel("RiderOrder");
        $this->loadModel("Order");
        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_user_id = $data['rider_user_id'];
            $assigner_user_id = $data['assigner_user_id'];
            $order_id = $data['order_id'];
            $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

            $this->Order->id = $order_id;
            $delivery = $this->Order->field('delivery');
            if($delivery == 0){


                $output['code'] = 201;

                $output['msg'] = "You can't assign this order to any rider because user will himself pickup the food from the restaurant ";
                echo json_encode($output);


                die();
            }

            if(isset($data['id'])){
               // $this->RiderOrder->id = $data['id'];
                $this->RiderOrder->delete($data['id']);

            }

            $rider_order['rider_user_id'] = $rider_user_id;
            $rider_order['assigner_user_id'] = $assigner_user_id;
            $rider_order['order_id'] = $order_id;
            $rider_order['assign_date_time'] = $created;
            $this->UserInfo->id = $rider_user_id;

            //$device_token = $this->UserInfo->field('device_token');
            $rider_name = $this->UserInfo->field('first_name');


            if ($this->RiderOrder->isDuplicateRecord($rider_user_id, $assigner_user_id, $order_id) <= 0) {

                if ($this->RiderOrder->save($rider_order)) {

                    /*firebase*/
                    $rider_order_id = $this->RiderOrder->getLastInsertId();


                    $curl_date[$order_id] =
                        array (



                            'order_status' => 'Order has been assigned to '.$rider_name,
                            'map_change' => "1",




                        );

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => FIREBASE_URL."tracking_status.json",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PATCH",
                        CURLOPT_POSTFIELDS => json_encode($curl_date),
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
                        ),
                    ));

                    $response_curl = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        // echo "cURL Error #:" . $err;
                    } else {
                        //echo $response_curl;
                    }

                    /****/

                 /*   PushNotification::sendPushNotification($device_token, "you have been assigned an order");

                    $this->Order->id = $order_id;
                    $user_id_order = $this->Order->field('user_id');
                    $this->UserInfo->id = $user_id_order;
                    $user_device_token = $this->UserInfo->field('device_token');
                    $this->UserInfo->id = $rider_user_id;
                    $rider_device_token = $this->UserInfo->field('device_token');
                    $notifcation_user[0]['type'] = "track_order";
                    $notifcation_user[0]['alert'] = "Order has been assigned to the rider";
                    $notifcation_user[0]['badge'] = 1;
                    $notifcation_user[0]['sound'] = 'default';

                    $notifcation_rider[0]['type'] = "";
                    $notifcation_rider[0]['alert'] = "You have been assigned an order";
                    $notifcation_rider[0]['badge'] = 1;
                    $notifcation_rider[0]['sound'] = 'default';

                    $result = PushNotification::sendPushNotificationToApp($notifcation_user[0], $user_device_token);
                    $result = PushNotification::sendPushNotificationToApp($notifcation_rider[0], $rider_device_token);*/


                    /************notification to RIDER*************/
                    $this->Order->id = $order_id;
                    $user_id_order = $this->Order->field('user_id');
                    $this->UserInfo->id = $user_id_order;
                    $user_device_token = $this->UserInfo->field('device_token');
                    $this->UserInfo->id = $rider_user_id;
                    $rider_device_token = $this->UserInfo->field('device_token');


                    $order_detail   = $this->Order->getOrderDetailBasedOnID($order_id);

                    $notification['to'] = $rider_device_token;
                    $notification['notification']['title'] = "Order has been assigned to the rider";
                    $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "";
                    $notification['notification']['data']= "";

                    PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                    //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                    /********end notification***************/


                    /************notification to USER*************/





                    $notification['to'] = $user_device_token;
                    $notification['notification']['title'] = "Order has been assigned to the rider";
                    $notification['notification']['body'] = 'Order #'.$order_detail[0]['Order']['id'] .' '.$order_detail[0]['OrderMenuItem'][0]['name'];
                    $notification['notification']['badge'] = "1";
                    $notification['notification']['sound'] = "default";
                    $notification['notification']['icon'] = "";
                    $notification['notification']['type'] = "";
                    $notification['notification']['data']= "";

                    PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                    //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                    /********end notification***************/

                    /*firebase*/



                    $curl_data2['order_id'] = $order_id;
                    $curl_data2['status'] = "0";
                    $curl_data2['symbol'] = $order_detail[0]['Restaurant']['Currency']['symbol'];
                    $curl_data2['price'] = $order_detail[0]['Order']['price'];
                    $curl_data2['restaurants'] = $order_detail[0]['Restaurant']['name'];

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => FIREBASE_URL."RiderOrdersList/".$rider_user_id."/CurrentOrders/.json",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($curl_data2),
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "postman-token: 6b83e517-1eaf-2013-dab4-29b19c86e09e"
                        ),
                    ));

                    $response_curl = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        // echo "cURL Error #:" . $err;
                    } else {
                         $snap = json_decode($response_curl,true);
                         $name = $snap['name'];
                         $this->RiderOrder->id = $rider_order_id;
                         $this->RiderOrder->saveField('snap',$name);
                    }


                    echo Message::DATASUCCESSFULLYSAVED();

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

    public function showRiderOrders()
    {

        $this->loadModel("RiderOrder");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_user_id = $data['rider_user_id'];
            $orders = $this->RiderOrder->getAllRiderOrders($rider_user_id);


            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderTimings()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $riderTimings = $this->User->getAllRidersTimings();

            if(count($riderTimings) > 0){

                foreach ($riderTimings  as $key => $val) {

                    if(count($val['RiderTiming']) > 0) {
                        $timings[$key] = $val;

                    }
                }
            }
            $output['code'] = 200;

            $output['msg'] = $timings;
            echo json_encode($output);


            die();
        }
    }

    public function confirmRiderTiming()
    {



        $this->loadModel("RiderTiming");
        $this->loadModel("UserInfo");




        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




               $id = $data['id'];
            $admin_confirm = $data['admin_confirm'];






                $this->RiderTiming->id = $id;
                $this->RiderTiming->saveField('admin_confirm',$admin_confirm);


                $result         = $this->RiderTiming->getRiderTimingAgainstID($id);

               $user_id = $result[0]['RiderTiming']['user_id'];
               $this->UserInfo->id = $user_id;
               $device_token = $this->UserInfo->field('device_token');





            if (strlen($device_token) > 10) {


                /************************notification*********************************/
                $notification['to'] = $device_token;
                $notification['notification']['title'] = "Your shift has been approved";
                $notification['notification']['body'] = 'Please confirm your schedule!';
                $notification['notification']['badge'] = "1";
                $notification['notification']['sound'] = "default";
                $notification['notification']['icon'] = "";
                $notification['notification']['type'] = "";
                $notification['notification']['data']= "";

                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));

                /************************end notification*********************************/
            }
                $output['code'] = 200;
                $output['msg']  = $result;
                echo json_encode($output);
                die();







        }

    }

    public function editRiderLocation()
    {

        $this->loadModel("RiderLocation");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $rider_location['city'] = $data['city'];
            $rider_location['country'] = $data['country'];
            $rider_location['address_to_start_shift'] = $data['address_to_start_shift'];
           

            $this->RiderLocation->id = $id;
            if($this->RiderLocation->save($rider_location)){

               $rider_location =  $this->RiderLocation->getRiderLocationAgainstID($id);

                $output['code'] = 200;

                $output['msg'] = $rider_location;
                echo json_encode($output);


                die();

            }

        }
    }
    public function addDeal()
    {

        $this->loadModel("Deal");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name        = $data['name'];
            $price       = $data['price'];
            $description = $data['description'];
            $starting_time = $data['starting_datetime'];
            $ending_time = $data['ending_datetime'];




            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];

            $deal['name']          = $name;
            $deal['price']         = $price;
            $deal['description']   = $description;
            $deal['starting_time']   = $starting_time;
            $deal['ending_time']   = $ending_time;
            $deal['restaurant_id'] = $restaurant_id;

            if (isset($data['image']) && $data['image'] != " ") {


                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath      = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $deal['image'] = $filePath;
            }

            if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                $cover_image = $data['cover_image'];
                $folder_url  = UPLOADS_FOLDER_URI;

                $filePath            = Lib::uploadFileintoFolder($user_id, $cover_image, $folder_url);
                $deal['cover_image'] = $filePath;
            }
//--------------------------------  editing-----------------------------------------
            if (isset($data['id'])) {
                $id = $data['id'];
                $deal_detail = $this->Deal->getDeal($id);
                if (isset($data['image'])) {
                    @unlink($deal_detail[0]['Deal']['image']);

                }

                if (isset($data['cover_image'])) {

                    @unlink($deal_detail[0]['Deal']['cover_image']);
                }

                $this->Deal->id = $id;
                $this->Deal->save($deal);

                $deal_detail = $this->Deal->getDeal($id);


                $output['code'] = 200;

                $output['msg'] = $deal_detail;
                echo json_encode($output);


                die();
            }
            //--------------------------------  end editing-----------------------------------------
            else if ($this->Deal->isDuplicateRecord($restaurant_id, $name, $price, $description) == 0) {


                if ($this->Deal->save($deal)) {
                    $id   = $this->Deal->getInsertID();
                    $deal = $this->Deal->getDeal($id);


                    $output['code'] = 200;

                    $output['msg'] = $deal;
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
    public function showRiders()
    {

        $this->loadModel("User");
        $this->loadModel("RiderOrder");
        $this->loadModel("RiderLocation");


        if ($this->request->isPost()) {


           // $riders = $this->User->getAllRiders();
            $online_riders = $this->User->getOnlineOfflineRiders(1);
            $offline_riders = $this->User->getOnlineOfflineRiders(0);

             $riders = $this->User->getAllRiders();


            foreach ($online_riders as $key => $val) {

                $user_id = $val['UserInfo']['user_id'];
                $riderLocation = $this->RiderLocation->getRiderLocation($user_id);
                $online_riders[$key]['RiderLocation'] = $riderLocation;
                $total_rider_orders = $this->RiderOrder->countRiderAssignOrders($user_id);
                $online_riders[$key]['UserInfo']['total_orders'] = $total_rider_orders;

            }

            foreach ($offline_riders as $key => $val) {

                $user_id = $val['UserInfo']['user_id'];
                $riderLocation = $this->RiderLocation->getRiderLocation($user_id);
                $offline_riders[$key]['RiderLocation'] = $riderLocation;
                $total_rider_orders = $this->RiderOrder->countRiderAssignOrders($user_id);
                $offline_riders[$key]['UserInfo']['total_orders'] = $total_rider_orders;
            }


            $result['OnlineRiders'] = $online_riders;
            $result['OfflineRiders'] = $offline_riders;
            $result['Riders'] = $riders;



            $output['code'] = 200;

            $output['msg'] = $result;
            echo json_encode($output);


            die();
        }
    }

    public function showRiderRequests()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {


            $request = $this->RiderRequest->getAllRiderRequests();


            $output['code'] = 200;

            $output['msg'] = $request;
            echo json_encode($output);


            die();
        }
    }

    public function deleteRiderRequest()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];


            if ($this->RiderRequest->delete($id)) {

                Message::DELETEDSUCCESSFULLY();
                die();
            }
        }
    }


    public function editRiderRequest()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $rider_request['first_name'] = $data['first_name'];
            $rider_request['last_name'] = $data['last_name'];
            $rider_request['phone'] = $data['phone'];
            $rider_request['email'] = $data['email'];
            $rider_request['city'] = $data['city'];
            $rider_request['state'] = $data['state'];
            $rider_request['country'] = $data['country'];
            $rider_request['address'] = $data['address'];

            $this->RiderRequest->id = $id;
            $this->RiderRequest->save($rider_request);
            $result = $this->RiderRequest->getLastInsertRow($id);


            $output['msg'] = $result;
            $output['code'] = 200;
            echo json_encode($output);
            die();
        }
    }

    public function showRestaurantRequests()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $request = $this->RestaurantRequest->getAllRestaurantRequests();


            $output['code'] = 200;

            $output['msg'] = $request;
            echo json_encode($output);


            die();
        }
    }

    public function deleteRestaurantRequest()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];


            if ($this->RestaurantRequest->delete($id)) {

                Message::DELETEDSUCCESSFULLY();
                die();
            }
        }
    }


    public function editRestaurantRequest()
    {

        $this->loadModel("RestaurantRequest");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $restaurant_request['restaurant_name'] = $data['restaurant_name'];
            $restaurant_request['contact_name'] = $data['contact_name'];
            $restaurant_request['phone'] = $data['phone'];
            $restaurant_request['email'] = $data['email'];
            $restaurant_request['address'] = $data['address'];
            $restaurant_request['description'] = $data['description'];


            $this->RestaurantRequest->id = $id;
            $this->RestaurantRequest->save($restaurant_request);
            $result = $this->RestaurantRequest->getLastInsertRow($id);


            $output['msg'] = $result;
            $output['code'] = 200;
            echo json_encode($output);
            die();
        }
    }


    public function addRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("Tax");
        $this->loadModel("Currency");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $name = $data['name'];
            $slogan = $data['slogan'];
            $about = $data['about'];
            $added_by = $data['added_by'];

            $min_order_price = $data['min_order_price'];
            $delivery_free_range = $data['delivery_free_range'];
            $preparation_time = $data['preparation_time'];
            $tax_free = $data['tax_free'];
            $google_analytics = $data['google_analytics'];
            $phone = $data['phone'];
            $time_zone = $data['timezone'];
            $menu_style = $data['menu_style'];
            $promoted = $data['promoted'];
            $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

            $city = $data['city'];

            $state = $data['state'];
            $country = $data['country'];
            $notes = $data['notes'];
            $zip = $data['zip'];
            $lat = $data['lat'];
            $long = $data['long'];
            $currency_id = $data['currency_id'];
            $tax_id = $data['tax_id'];
            $speciality = $data['speciality'];
            $preparation_time = $data['preparation_time'];


            $restaurant_timing = $data['restaurant_timing'];


            $email = strtolower($data['email']);

            $password = $data['password'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];


            /* $tax = $this->Tax->getTaxID($state, $country);
             $currency = $this->Currency->getCurrencyID($country);
             if (count($tax) == 1) {

                 $tax_id               = $tax[0]['Tax']['id'];
                 $restaurant['tax_id'] = $tax_id;
             } else {

                 $output['code'] = 205;

                 $output['msg'] = "Please first add Tax Detail";
                 echo json_encode($output);


                 die();

             }

             if (count($currency) == 1) {

                 $currency_id = $currency[0]['Currency']['id'];
                 $restaurant['currency_id'] = $currency_id;
             } else {

                 $output['code'] = 205;

                 $output['msg'] = "Please first add Currency Detail First";
                 echo json_encode($output);


                 die();

             }
 */


            $user_id = $this->registerRestaurantUser($email, $password, $first_name, $last_name, $phone);




            $restaurant['user_id'] = $user_id;
            $restaurant['name'] = $name;
            $restaurant['notes'] = $notes;
            $restaurant['slogan'] = $slogan;
            $restaurant['about'] = $about;
            $restaurant['added_by'] = $added_by;

            //$restaurant['delivery_fee'] = $delivery_fee;
            $restaurant['phone'] = $phone;

            $restaurant['timezone'] = $time_zone;
            $restaurant['menu_style'] = $menu_style;
            $restaurant['promoted'] = $promoted;
            $restaurant['speciality'] = $speciality;

            $restaurant['min_order_price'] = $min_order_price;
            $restaurant['delivery_free_range'] = $delivery_free_range;
            $restaurant['preparation_time'] = $preparation_time;
            $restaurant['tax_free'] = $tax_free;
            $restaurant['google_analytics'] = $google_analytics;

            $restaurant['currency_id'] = $currency_id;
            $restaurant['tax_id'] = $tax_id;


            $restaurant_location['lat'] = $lat;
            $restaurant_location['long'] = $long;
            $restaurant_location['city'] = $city;
            $restaurant_location['state'] = $state;
            $restaurant_location['country'] = $country;
            $restaurant_location['zip'] = $zip;


            //update
            if (isset($data['id'])) {

                $id = $data['id'];
                $this->Restaurant->id = $id;
                $this->Restaurant->save($restaurant);

                if (isset($data['image']) && $data['image'] != " ") {

                    $image = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath = Lib::uploadFileintoFolder($id, $image, $folder_url);
                    $restaurant_image['image'] = $filePath;
                    $this->Restaurant->id = $id;
                    $this->Restaurant->save($restaurant_image);

                }

                if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                    $cover_image = $data['cover_image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath = Lib::uploadFileintoFolder($id, $cover_image, $folder_url);
                    $restaurant_image['cover_image'] = $filePath;
                    $this->Restaurant->id = $id;
                    $this->Restaurant->save($restaurant_image);
                }

                $rest_details = $this->Restaurant->getRestaurantDetail($id);
                $output['code'] = 200;
                $output['msg'] = $rest_details;
                echo json_encode($output);


                die();
            } else
                $restaurant['created'] = $created;
            if ($this->Restaurant->isDuplicateRecord($user_id, $name, $slogan, $phone, $about) == 0) {
                if ($this->Restaurant->save($restaurant)) {


                    //$gigpost_category['cat_id'] = $cat_id;


                    $id = $this->Restaurant->getLastInsertId();
                    $restaurant_location['restaurant_id'] = $id;
                    $this->RestaurantLocation->save($restaurant_location);


                    if (isset($data['image']) && $data['image'] != " ") {

                        $image = $data['image'];
                        $folder_url = UPLOADS_FOLDER_URI;

                        $filePath = Lib::uploadFileintoFolder($id, $image, $folder_url);
                        $restaurant_image['image'] = $filePath;
                        $this->Restaurant->id = $id;
                        $this->Restaurant->save($restaurant_image);

                    }

                    if (isset($data['cover_image']) && $data['cover_image'] != " ") {

                        $cover_image = $data['cover_image'];
                        $folder_url = UPLOADS_FOLDER_URI;

                        $filePath = Lib::uploadFileintoFolder($id, $cover_image, $folder_url);
                        $restaurant_image['cover_image'] = $filePath;
                        $this->Restaurant->id = $id;
                        $this->Restaurant->save($restaurant_image);
                    }




                    foreach ($restaurant_timing as $k => $v) {

                        $timing[$k]['day'] = $v['day'];
                        $timing[$k]['opening_time'] = $v['opening_time'];
                        $timing[$k]['closing_time'] = $v['closing_time'];
                        $timing[$k]['restaurant_id'] = $id;

                    }

                    $this->RestaurantTiming->saveAll($timing);


                    //CustomEmail::welcomeStudentEmail($email);
                    $rest_details = $this->Restaurant->getRestaurantDetail($id);
                    $output['code'] = 200;
                    $output['msg'] = $rest_details;
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

    public function registerRestaurantUser($email, $password, $first_name, $last_name, $phone)
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');


        //$json = file_get_contents('php://input');


        //file_put_contents(UPLOADS_FOLDER_URI . "/regStudentlog.txt", print_r($data, true));

        if ($email != null && $password != null) {


            $user['email'] = $email;
            $user['password'] = $password;
            $user['role'] = "hotel";

            $user['active'] = 1;
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


                $user_id = $this->User->getInsertID();
                $user_info['user_id'] = $user_id;


                $user_info['first_name'] = $first_name;
                $user_info['last_name'] = $last_name;
                $user_info['phone'] = $phone;


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

    public function addMenu()
    {
        $this->loadModel('RestaurantMenu');
        $this->loadModel('Restaurant');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name        = $data['name'];
            $description = $data['description'];


            $user_id = $data['user_id'];

            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];
            $created       = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $restaurant_menu['name']          = $name;
            $restaurant_menu['description']   = $description;
            $restaurant_menu['restaurant_id'] = $restaurant_id;

            $restaurant_menu['created'] = $created;

            $menu = array();

            if (isset($data['image']) && $data['image'] != " ") {

                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath                 = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $restaurant_menu['image'] = $filePath;
            }

            if (isset($data['id'])) {
                $this->RestaurantMenu->id = $data['id'];
                $this->RestaurantMenu->save($restaurant_menu);

                $menu = $this->RestaurantMenu->getMainMenuFromID($data['id']);


            } else if ($this->RestaurantMenu->isDuplicateRecord($name, $description, $restaurant_id) == 0) {

                if ($this->RestaurantMenu->save($restaurant_menu)) {

                    $id   = $this->RestaurantMenu->getLastInsertId();
                    $menu = $this->RestaurantMenu->getMainMenuFromID($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();

        }
    }



    public function addMenuItem()
    {

        $this->loadModel('RestaurantMenu');
        $this->loadModel('RestaurantMenuItem');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name               = $data['name'];
            $description        = $data['description'];
            $restaurant_menu_id = $data['restaurant_menu_id'];
            $price              = $data['price'];
            $out_of_order       = $data['out_of_order'];
            $created            = date('Y-m-d H:i:s', time() - 60 * 60 * 4);



            $restaurant_menu_item['name']               = $name;
            $restaurant_menu_item['description']        = $description;
            $restaurant_menu_item['restaurant_menu_id'] = $restaurant_menu_id;
            $restaurant_menu_item['price']              = $price;
            $restaurant_menu_item['created']            = $created;
            $restaurant_menu_item['out_of_order']            = $out_of_order;




            if (isset($data['id'])) {
                $this->RestaurantMenuItem->id = $data['id'];
                $this->RestaurantMenuItem->save($restaurant_menu_item);
                $menu = $this->RestaurantMenuItem->getMenuItemFromID($data['id']);
            } else if ($this->RestaurantMenuItem->isDuplicateRecord($name, $description, $restaurant_menu_id, $price) == 0) {


                if ($this->RestaurantMenuItem->save($restaurant_menu_item)) {
                    $id   = $this->RestaurantMenuItem->getLastInsertId();
                    $menu = $this->RestaurantMenuItem->getMenuItemFromID($id);
                    $this->RestaurantMenu->id = $restaurant_menu_id;
                    $this->RestaurantMenu->saveField('has_menu_item', 1);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }

            } else {

                echo Message::DUPLICATEDATE();
                die();
            }
            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();

        }
    }

    public function addMenuExtraItem()
    {


        $this->loadModel('RestaurantMenuExtraItem');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name = $data['name'];
            //  $description = $data['description'];

            $price = $data['price'];

            $restaurant_menu_extra_section_id = $data['restaurant_menu_extra_section_id'];
            $created                          = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            $restaurant_menu_extra_item['name'] = $name;
            // $restaurant_menu_extra_item['description'] = $description;

            $restaurant_menu_extra_item['price']   = $price;
            $restaurant_menu_extra_item['created'] = $created;

            $restaurant_menu_extra_item['restaurant_menu_extra_section_id'] = $restaurant_menu_extra_section_id;


            if (isset($data['id'])) {
                $this->RestaurantMenuExtraItem->id = $data['id'];
                $this->RestaurantMenuExtraItem->save($restaurant_menu_extra_item);
                $menu = $this->RestaurantMenuExtraItem->getMenuExtraItemFromID($data['id']);
            } else if ($this->RestaurantMenuExtraItem->isDuplicateRecord($name, $price, $restaurant_menu_extra_section_id) == 0) {


                if ($this->RestaurantMenuExtraItem->save($restaurant_menu_extra_item)) {
                    $id   = $this->RestaurantMenuExtraItem->getLastInsertId();
                    $menu = $this->RestaurantMenuExtraItem->getMenuExtraItemFromID($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $menu;
            echo json_encode($output);
            die();
        }
    }


    public function addMenuExtraSection()
    {

        $this->loadModel('Restaurant');
        $this->loadModel('RestaurantMenuExtraSection');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $name    = $data['name'];
            $user_id = $data['user_id'];
            //  $description = $data['description'];

            $required      = $data['required'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];

            $created = date('Y-m-d H:i:s', time() - 60 * 60 * 4);

            $restaurant_menu_item_id                                  = $data['restaurant_menu_item_id'];
            $restaurant_menu_extra_section['restaurant_menu_item_id'] = $restaurant_menu_item_id;
            $restaurant_menu_extra_section['name']                    = $name;

            $restaurant_menu_extra_section['restaurant_id'] = $restaurant_id;
            $restaurant_menu_extra_section['required']      = $required;



            if (isset($data['id'])) {
                $this->RestaurantMenuExtraSection->id = $data['id'];
                $this->RestaurantMenuExtraSection->save($restaurant_menu_extra_section);
                $section_names = $this->RestaurantMenuExtraSection->getRecentlyAddedSection($data['id']);

            } else if ($this->RestaurantMenuExtraSection->isDuplicateRecord($name, $restaurant_menu_item_id, $restaurant_id) == 0) {

                if ($this->RestaurantMenuExtraSection->save($restaurant_menu_extra_section)) {
                    $id            = $this->RestaurantMenuExtraSection->getLastInsertId();
                    $section_names = $this->RestaurantMenuExtraSection->getRecentlyAddedSection($id);


                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                echo Message::DUPLICATEDATE();
                die();
            }

            $output['code'] = 200;

            $output['msg'] = $section_names;
            echo json_encode($output);
            die();


        }
    }

    public function showRestaurantsMenu()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            $menus = $this->Restaurant->getRestaurantMenusForMobile($restaurant_id);


            $output['code'] = 200;

            $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($menu);
            echo json_encode($output);


            die();

        }
    }
    public function showRestaurantDetail()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['id'];


            $restaurant_detail = $this->Restaurant->getRestaurantDetailInfoSuperAdmin($restaurant_id);

           


            $output['code'] = 200;

            $output['msg'] = $restaurant_detail;
            echo json_encode($output);


            die();

        }
    }

    public function showMainMenus()
    {

        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $id = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];


                // $main_menus = $this->RestaurantMenu->getMainMenu($restaurant_id);
                $menus = $this->Restaurant->getRestaurantMenusForWeb($restaurant_id);

                if (isset($data['time'])) {

                    $result = $this->checkRestuarantIsOpenOrNot(ucfirst($data['day']), $data['time'], $restaurant_id);

                    $menus[0]['Restaurant']['availability'] = $result;
                }


                $output['code'] = 200;

                $output['msg'] = $menus;
                echo json_encode($output);


                die();
            } else {


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showMenuItems()
    {

        $this->loadModel("RestaurantMenuItem");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_menu_id = $data['restaurant_menu_id'];

            $menu_items = $this->RestaurantMenuItem->getMenuItems($restaurant_menu_id);


            $output['code'] = 200;

            $output['msg'] = $menu_items;
            echo json_encode($output);


            die();
        }
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

            if (count($menu_extra_items) > 0) {
                for ($i = 0; $i < count($menu_extra_items); $i++) {
                    // //this array was repeating so we remove this at one place
                    //$new_menu_extra_items[$i]['RestaurantMenuExtraSection'] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
                    $menu_extra_items[$i]['RestaurantMenuExtraSection']['RestaurantMenuExtraItem'] = $menu_extra_items[$i]['RestaurantMenuExtraItem'];
                    unset($menu_extra_items[$i]['RestaurantMenuExtraItem']);
                }

            }

            $output['code'] = 200;

            $output['msg'] = $menu_extra_items;
            echo json_encode($output);


            die();
        }
    }



    public function showMenuExtraItemsWithSections()
    {

        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $menu_extra_items = $this->RestaurantMenuExtraSection->getSectionsWithItems($restaurant_id);
            /*if(count($menu_extra_items) > 0) {
            for($i=0; $i < count($menu_extra_items);$i++){

            $new_menu_extra_items[$i]['RestaurantMenuExtraSection'] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
            $new_menu_extra_items[$i]['RestaurantMenuExtraSection'][''] = $menu_extra_items[$i]['RestaurantMenuExtraSection'];
            }

            }*/
            $output['code']   = 200;

            $output['msg'] = $menu_extra_items;
            echo json_encode($output);


            die();
        }
    }


    public function addAppSliderImage()
    {


        $this->loadModel('AppSlider');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $image = $data['image'];
            $user_id = $data['user_id'];

            if (isset($data['image']) && $data['image'] != " ") {

                $image = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI;

                $filePath = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $image['image'] = $filePath;
            }


            if (isset($data['id'])) {
                $id = $data['id'];
                $app_slider = $this->AppSlider->getImageDetail($id);
                $image_path = $app_slider[0]['AppSlider']['image'];

                @unlink($image_path);

                $this->AppSlider->id = $id;
                $this->AppSlider->save($image);
                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else if ($this->AppSlider->save($image)) {

                echo Message::DATASUCCESSFULLYSAVED();

                die();
            } else {


                echo Message::DATASAVEERROR();
                die();
            }


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

    public function deleteAppSliderImage()
    {

        $this->loadModel("AppSlider");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            $app_slider = $this->AppSlider->getImageDetail($id);
            if (count($app_slider) > 0) {
                $image_path = $app_slider[0]['AppSlider']['image'];

                @unlink($image_path);
                if ($this->AppSlider->deleteAppSlider($id)) {

                    Message::DELETEDSUCCESSFULLY();


                    die();

                } else {

                    Message::ERROR();


                    die();

                }


            } else {

                $output['code'] = 202;

                $output['msg'] = "no image exist";
                echo json_encode($output);
                die();
            }


        }
    }


    public function showCurrencies()
    {

        $this->loadModel("Currency");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');


            $currencies = $this->Currency->getAllCurrency();


            $output['code'] = 200;

            $output['msg'] = $currencies;
            echo json_encode($output);


            die();
        }
    }

    public function editMainMenuIndex()
    {

        $this->loadModel("RestaurantMenu");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $menu = $data['menu'];

            foreach ($menu as $k => $v) {


                 $this->RestaurantMenu->id = $v['menu_id'];
                $this->RestaurantMenu->saveField('index',$v['index']);


            }

            $output['code'] = 200;

            $output['msg'] = "updated";
            echo json_encode($output);


            die();


        }
    }

    public function deleteMainMenu()
    {

        $this->loadModel("RestaurantMenu");

        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $restaurant_id = $data['restaurant_id'];
            $menu_id = $data['menu_id'];
            $active = $data['active'];

            $menu_items_array = array();

            //$deleteMainMenu = $this->RestaurantMenu->deleteMainMenu($menu_id,$restaurant_id);


            $menu_item_ids = $this->RestaurantMenuItem->getMenuItems($menu_id);

            for ($i = 0; $i < count($menu_item_ids); $i++) {

                $menu_items_array[$i] = $menu_item_ids[$i]['RestaurantMenuItem']['id'];

            }


            $section_items = $this->RestaurantMenuExtraSection->getSections($menu_items_array);
            $section_items_array = array();

            for ($i = 0; $i < count($section_items); $i++) {

                $section_items_array[$i] = $section_items[$i]['RestaurantMenuExtraSection']['id'];

            }

            $extra_items_deleted =  $this->RestaurantMenuExtraItem->removeMenuExtraItems( $section_items_array,$active);

            if ($extra_items_deleted) {

                $deleted_sections = $this->RestaurantMenuExtraSection->removeSections($menu_items_array,$active);

                if ($deleted_sections) {

                    $deleted_menu_items = $this->RestaurantMenuItem->removeMenuItem($menu_id,$active);

                    if ($deleted_menu_items) {

                        $this->RestaurantMenu->removeMainMenu($menu_id, $restaurant_id,$active);

                        Message::DELETEDSUCCESSFULLY();


                        die();


                    }
                }

            }


        } else {


            Message::ACCESSRESTRICTED();
            die();
        }

    }

    public function deleteMenuItem()
    {


        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_item_id'];
            $active = $data['active'];


            $section_items = $this->RestaurantMenuExtraSection->getSectionsAgainstRestaurantMenuItem($id);

        if(count($section_items)>0){
            $extra_items_deleted = $this->RestaurantMenuExtraItem->removeMenuExtraItemAgainstSectionID($section_items[0]['RestaurantMenuExtraSection']['id'],$active);

            if ($extra_items_deleted) {

                $deleted_sections = $this->RestaurantMenuExtraSection->removeSectionAgainstMenuItemID($id,$active);

                if ($deleted_sections) {

                    $this->RestaurantMenuItem->removeMenuItemAgainstID($id, $active);

                    Message::DELETEDSUCCESSFULLY();

                    die();

                }
                }

            }else{

            $this->RestaurantMenuItem->removeMenuItemAgainstID($id, $active);

            Message::DELETEDSUCCESSFULLY();

            die();


        }


        }

    }

    public function deleteMenuExtraSection()
    {


        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $menu_extra_section_id = $data['menu_extra_section_id'];
            $active = $data['active'];


            $extra_items_deleted = $this->RestaurantMenuExtraItem->removeMenuExtraItemAgainstSectionID($menu_extra_section_id,$active);

            if ($extra_items_deleted) {

                $this->RestaurantMenuExtraSection->removeSectionAgainstID($menu_extra_section_id,$active);

                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }

    public function deleteMenuExtraItem()
    {


        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_extra_item_id'];
            $active = $data['active'];


            $extra_items_deleted = $this->RestaurantMenuExtraItem->removeMenuExtraItemAgainstID($id,$active);

            if ($extra_items_deleted) {


                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }

    /*public function deleteMainMenu()
    {

        $this->loadModel("RestaurantMenu");

        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $restaurant_id = $data['restaurant_id'];
            $menu_id = $data['menu_id'];

            $menu_items_array = array();

            //$deleteMainMenu = $this->RestaurantMenu->deleteMainMenu($menu_id,$restaurant_id);


            $menu_item_ids = $this->RestaurantMenuItem->getMenuItems($menu_id);

            for ($i = 0; $i < count($menu_item_ids); $i++) {

                $menu_items_array[$i] = $menu_item_ids[$i]['RestaurantMenuItem']['id'];

            }


            $section_items = $this->RestaurantMenuExtraSection->getSections($menu_items_array);
            $section_items_array = array();

            for ($i = 0; $i < count($section_items); $i++) {

                $section_items_array[$i] = $section_items[$i]['RestaurantMenuExtraSection']['id'];

            }

            $extra_items_deleted = $this->RestaurantMenuExtraItem->deleteMenuExtraItems($section_items_array);

            if ($extra_items_deleted) {

                $deleted_sections = $this->RestaurantMenuExtraSection->deleteSections($menu_items_array);

                if ($deleted_sections) {

                    $deleted_menu_items = $this->RestaurantMenuItem->deleteMenuItem($menu_id);

                    if ($deleted_menu_items) {

                        $this->RestaurantMenu->deleteMainMenu($menu_id, $restaurant_id);

                        Message::DELETEDSUCCESSFULLY();


                        die();


                    }
                }

            }


        } else {


            Message::ACCESSRESTRICTED();
            die();
        }

    }


    public function deleteMenuItem()
    {


        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_item_id'];


            $section_items = $this->RestaurantMenuExtraSection->getSectionsAgainstRestaurantMenuItem($id);


            $extra_items_deleted = $this->RestaurantMenuExtraItem->deleteMenuExtraItemAgainstSectionID($section_items[0]['RestaurantMenuExtraSection']['id']);

            if ($extra_items_deleted) {

                $deleted_sections = $this->RestaurantMenuExtraSection->deleteSectionAgainstMenuItemID($id);

                if ($deleted_sections) {

                    $this->RestaurantMenuItem->deleteMenuItemAgainstID($id);

                    Message::DELETEDSUCCESSFULLY();

                    die();


                }

            }


        }

    }


    public function deleteMenuExtraSection()
    {


        $this->loadModel("RestaurantMenuItem");
        $this->loadModel("RestaurantMenuExtraSection");
        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $menu_extra_section_id = $data['menu_extra_section_id'];


            $extra_items_deleted = $this->RestaurantMenuExtraItem->deleteMenuExtraItemAgainstSectionID($menu_extra_section_id);

            if ($extra_items_deleted) {

                $this->RestaurantMenuExtraSection->deleteSectionAgainstID($menu_extra_section_id);

                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }

    public function deleteMenuExtraItem()
    {


        $this->loadModel("RestaurantMenuExtraItem");
        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['menu_extra_item_id'];


            $extra_items_deleted = $this->RestaurantMenuExtraItem->deleteMenuExtraItemAgainstID($id);

            if ($extra_items_deleted) {


                Message::DELETEDSUCCESSFULLY();

                die();


            }


        }
    }
*/


    public function addTax()
    {


        $this->loadModel('Tax');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $tax['city'] = $data['city'];
            $tax['state'] = $data['state'];
            $tax['country'] = $data['country'];
            $tax['tax'] = $data['tax'];
            $tax['country_code'] = $data['country_code'];
            $tax['delivery_time'] = $data['delivery_time'];
            $tax['delivery_fee_per_km'] = $data['delivery_fee_per_km'];


            if (isset($data['id'])) {

                $tax_id = $data['id'];
                $this->Tax->id = $tax_id;

                if ($this->Tax->save($tax)) {

                    $detail = $this->Tax->getTaxDetail($tax_id);

                    $output['code'] = 200;
                    $output['msg'] = $detail;
                    echo json_encode($output);

                    die();
                } else {


                    echo Message::DATASAVEERROR();
                    die();
                }
            } else {

                $count = $this->Tax->isDuplicateRecord($data['city'], $data['state'], $data['country']);
                if ($count == 0) {
                    if ($this->Tax->save($tax)) {
                        $id = $this->Tax->getLastInsertId();
                        $detail = $this->Tax->getTaxDetail($id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
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
    }


    public function addCurrency()
    {


        $this->loadModel('Currency');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $currency['country'] = $data['country'];
            $currency['currency'] = $data['currency'];
            $currency['code'] = $data['code'];
            $currency['symbol'] = $data['symbol'];


            if (isset($data['id'])) {

                $currency_id = $data['id'];
                $isDataExist = $this->Currency->getCurrencyDetail($currency_id);
                if (count($isDataExist) > 0) {
                    $this->Currency->id = $currency_id;

                    if ($this->Currency->save($currency)) {

                        $detail = $this->Currency->getCurrencyDetail($currency_id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
                        echo json_encode($output);

                        die();
                    } else {


                        echo Message::DATASAVEERROR();
                        die();
                    }
                } else {


                    echo Message::EmptyDATA();
                    die();

                }
            } else {

                $count = $this->Currency->isDuplicateRecord($currency);
                if ($count == 0) {
                    if ($this->Currency->save($currency)) {
                        $id = $this->Currency->getLastInsertId();
                        $detail = $this->Currency->getCurrencyDetail($id);

                        $output['code'] = 200;
                        $output['msg'] = $detail;
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
    }

    public function addRestaurantCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        //$this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_code   = $data['coupon_code'];
            $limit_users   = $data['limit_users'];
            $discount      = $data['discount'];
            $expire_date   = $data['expire_date'];
            $restaurant_id = $data['restaurant_id'];
            $type = $data['type'];





            $coupon['coupon_code']   = $coupon_code;
            $coupon['limit_users']   = $limit_users;
            $coupon['discount']      = $discount;
            $coupon['expire_date']   = $expire_date;
            $coupon['type']   = $type;
            $coupon['restaurant_id']   = $restaurant_id;
            //$id        = $this->Restaurant->getRestaurantID($user_id);

            if(isset($data['id'])){

              $this->RestaurantCoupon->id = $data['id'];
                $this->RestaurantCoupon->save($coupon);
                $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupon( $data['id']);


                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();

            }else{


                if ($this->RestaurantCoupon->isDuplicateRecord($restaurant_id, $coupon_code) == 0) {
                    if ($this->RestaurantCoupon->save($coupon)) {
                        $id = $this->RestaurantCoupon->getInsertID();
                        $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupon($id);


                        $output['code'] = 200;

                        $output['msg'] = $coupon_detail;
                        echo json_encode($output);


                        die();
                    } else {

                        echo Message::DATASAVEERROR();
                        die();

                    }
                }else{


                    Message::DUPLICATEDATE();
                    die();
                }




            }

        }
    }


    public function deleteRestaurantCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_id = $data['coupon_id'];
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];


                if ($this->RestaurantCoupon->deleteCoupon($restaurant_id, $coupon_id)) {

                    Message::DELETEDSUCCESSFULLY();
                    die();
                } else {

                    echo Message::DATASAVEERROR();
                    die();

                }
            } else {


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showRestaurantCoupons()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupons($restaurant_id);


                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();
            }else{


                Message::ACCESSRESTRICTED();
                die();
            }
        }
    }

    public function showRestaurantCouponWhoseRestaurantIDisZero()
    {

        $this->loadModel("RestaurantCoupon");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);
            $restaurant_id = $data['restaurant_id'];


            $coupon_detail = $this->RestaurantCoupon->getRestaurantCoupons($restaurant_id);

            if (count($coupon_detail) > 0) {
                $output['code'] = 200;

                $output['msg'] = $coupon_detail;
                echo json_encode($output);


                die();
            } else {


                Message::EMPTYDATA();
                die();
            }
        }
    }
    public function addOpenShift()
    {


        $this->loadModel('OpenShift');
        $this->loadModel('User');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $openshift['date'] = $data['date'];
            $openshift['starting_time'] = $data['starting_time'];
            $openshift['ending_time'] = $data['ending_time'];
            $openshift['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if(isset($data['id'])){

                $id = $data['id'];
                $this->OpenShift->id = $id;
                $this->OpenShift->save($openshift);

            }else if ($this->OpenShift->save($openshift)) {

                $id = $this->OpenShift->getLastInsertId();

                $riders = $this->User->getAllRiders();
                foreach ($riders as $rider) {

                    $device_token = $rider['UserInfo']['device_token'];





                    if (strlen($device_token) > 10) {

                        /************notification*************/


                        $notification['to'] = $device_token;
                        $notification['notification']['title'] = "Open shift available now";
                        $notification['notification']['body'] = 'Tap to Book your shift';
                        $notification['notification']['badge'] = "1";
                        $notification['notification']['sound'] = "default";
                        $notification['notification']['icon'] = "";
                        $notification['notification']['type'] = "";
                        $notification['notification']['data']= "";

                        PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                        //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                        /********end notification***************/




                    }

                }

            }



            $open_Shift_detail = $this->OpenShift->getOpenShiftDetail($id);


            $output['code'] = 200;

            $output['msg'] = $open_Shift_detail;
            echo json_encode($output);


            die();


        }
    }

    public function showOpenShifts()
    {

        $this->loadModel("OpenShift");


        if ($this->request->isPost()) {


            $shifts = $this->OpenShift->getOpenShifts();


            $output['code'] = 200;

            $output['msg'] = $shifts;
            echo json_encode($output);


            die();
        }
    }

    public function deleteOpenShift()
    {


        $this->loadModel('OpenShift');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];

            if($this->OpenShift->delete($id)){

                Message::DELETEDSUCCESSFULLY();
                die();

            }else{

                 Message::ERROR();
                die();
            }







        }
    }


    public function showAlltaxes()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {


            $taxes = $this->Tax->getTaxes();


            $output['code'] = 200;

            $output['msg'] = $taxes;
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

            $sender_id = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $message = $data['message'];
            $datetime = $data['created'];

            $chat['sender_id'] = $sender_id;
            $chat['receiver_id'] = $receiver_id;
            $chat['message'] = $message;
            $chat['created'] = $datetime;
            $isChatExist = $this->Chat->getUserChat($sender_id, $receiver_id);

            if (count($isChatExist) > 0) {

                $id = $isChatExist[0]['Chat']['conversation_id'];

                $chat['conversation_id'] = $id;
                if ($this->Chat->save($chat)) {
                    Message::DATASUCCESSFULLYSAVED();

                }

            } else {

                if ($this->Chat->save($chat)) {
                    $id = $this->Chat->getInsertID();
                    $this->Chat->id = $id;
                    $conversation['conversation_id'] = $id;
                    if ($this->Chat->save($conversation)) {
                        Message::DATASUCCESSFULLYSAVED();

                    }
                }

            }
            /*  if($this->Chat->save($chat)){

            Message::DATASUCCESSFULLYSAVED();

            }else{

            echo Message::DATASAVEERROR();

            }*/
        }
    }


    public function updateVerificationDocumentStatus()
    {


        $this->loadModel('VerificationDocument');
        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $id = $data['id'];
            $doc['status'] = $data['status'];


            $this->VerificationDocument->id = $id;
            if ($this->VerificationDocument->save($doc)) {
                $result = $this->VerificationDocument->getDocumentDetail($id);


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

    public function showAllUserVerificationDocuments()
    {

        $this->loadModel("VerificationDocument");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id = $data['user_id'];

            $docs = $this->VerificationDocument->getDocuments($user_id);


            $output['code'] = 200;

            $output['msg'] = $docs;
            echo json_encode($output);


            die();
        }
    }

    public function getConversation()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $sender_id = $data['sender_id'];
            $receiver_id = $data['receiver_id'];
            $user_id = $data['user_id'];
            $userMessage = $this->Chat->getUserChat($sender_id, $receiver_id);


            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id'] = $userMessage[$i]['Chat']['id'];
                $message[$i]['Chat']['sender_id'] = $userMessage[$i]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id'] = $userMessage[$i]['Chat']['receiver_id'];
                $message[$i]['Chat']['message'] = $userMessage[$i]['Chat']['message'];
                $message[$i]['Chat']['created'] = $userMessage[$i]['Chat']['created'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i]['Chat']['conversation_id'];


                if ($userMessage[$i]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i]['sender_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i]['sender_info']['profile_img'];


                } else if ($userMessage[$i]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i]['receiver_info']['last_name'];
                    //  $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }
            $output = array();
            $output['code'] = 200;
            $output['msg'] = $message;
            echo json_encode($output);


        }

    }

    public function chatInbox()
    {

        $this->loadModel('Chat');
        if ($this->request->isPost()) {
            $message = array();
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];


            $userMessage = $this->Chat->showUserInbox($user_id);
            //print_r($userMessage);
            for ($i = 0; $i < count($userMessage); $i++) {

                $message[$i]['Chat']['id'] = $userMessage[$i][0]['Chat']['id'];
                $message[$i]['Chat']['sender_id'] = $userMessage[$i][0]['Chat']['sender_id'];
                $message[$i]['Chat']['receiver_id'] = $userMessage[$i][0]['Chat']['receiver_id'];
                $message[$i]['Chat']['message'] = $userMessage[$i][0]['Chat']['message'];
                $message[$i]['Chat']['conversation_id'] = $userMessage[$i][0]['Chat']['conversation_id'];
                $message[$i]['Chat']['created'] = $userMessage[$i][0]['Chat']['created'];


                if ($userMessage[$i][0]['sender_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i][0]['sender_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['sender_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i][0]['sender_info']['last_name'];
                    //  $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i][0]['sender_info']['profile_img'];


                } else if ($userMessage[$i][0]['receiver_info']['user_id'] != $user_id) {

                    $message[$i]['UserInfo']['user_id'] = $userMessage[$i][0]['receiver_info']['user_id'];
                    $message[$i]['UserInfo']['first_name'] = $userMessage[$i][0]['receiver_info']['first_name'];
                    $message[$i]['UserInfo']['last_name'] = $userMessage[$i][0]['receiver_info']['last_name'];
                    // $message[$i]['UserInfo']['profile_img'] =  $userMessage[$i][0]['receiver_info']['profile_img'];


                }
                // $message[$i]['Chat']['id'] = $contractsList[$i]['UserInfo']['last_name'];
                // $notification[$i]['UserInfo']['profile_img'] = $contractsList[$i]['UserInfo']['profile_img'];
                //$notification[$i]['Contract']['datetime'] = $contractsList[$i]['Contract']['datetime'];

            }

            // debug($this->User->lastQuery());

            $output = array();
            $output['code'] = 200;
            $output['msg'] = $message;
            echo json_encode($output);


        }
    }

    public function showCountries()
    {

        $this->loadModel("Tax");


        if ($this->request->isPost()) {


            $countries = $this->Tax->getCountries();
            $cities = $this->Tax->getCities();
            $states = $this->Tax->getStates();


            $output['code'] = 200;

            $output['cities'] = $cities;
            $output['states'] = $states;
            $output['countries'] = $countries;
            echo json_encode($output);


            die();
        }
    }


    public function showUsersCount()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $user_count = $this->User->getUsersCount('user');
            $rider_count = $this->User->getUsersCount('rider');
            $hotel_count = $this->User->getUsersCount('hotel');
            $total_users_count = $this->User->getTotalUsersCount();

            $msg['user_count'] = $user_count;
            $msg['rider_count'] = $rider_count;
            $msg['hotel_count'] = $hotel_count;
            $msg['total_users_count'] = $total_users_count;


            $output['code'] = 200;

            $output['msg'] = $msg;
            echo json_encode($output);


            die();
        }
    }

    public function updateRiderOnlineStatus()
    {

        $this->loadModel("UserInfo");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $online = 0;
            $this->UserInfo->id = $user_id;
            if ($this->UserInfo->saveField('online', $online)) {


                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else {

                echo Message::ERROR();

                die();

            }

        }


    }

    public function userBlockStatus()
    {

        $this->loadModel("User");


        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            $block = $data['block'];
            $this->User->id = $user_id;
            if ($this->User->saveField('block', $block)) {


                echo Message::DATASUCCESSFULLYSAVED();

                die();

            } else {

                echo Message::ERROR();

                die();

            }

        }


    }
}
?>
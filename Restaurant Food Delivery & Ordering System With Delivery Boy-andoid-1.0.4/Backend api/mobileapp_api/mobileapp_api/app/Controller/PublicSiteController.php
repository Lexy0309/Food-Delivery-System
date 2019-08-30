<?php

App::uses('Lib', 'Utility');
App::uses('Postmark', 'Utility');
App::uses('Message', 'Utility');

App::uses('CustomEmail', 'Utility');
App::uses('Security', 'Utility');
App::uses('PushNotification', 'Utility');
App::uses('Firebase', 'Lib');



class PublicSiteController extends AppController{

    public $components = array('Email');

    public $autoRender = false;
    public $layout = false;


public function index(){


    echo "Congratulations!. You have configured your website api correctly";

    //show students count on web
}
   

    public function registerUser()
    {


        $this->loadModel('User');
        $this->loadModel('UserInfo');
        if ($this->request->isPost()) {

            //$json = file_get_contents('php://input');
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email        = strtolower($data['email']);
            $password     = @$data['password'];
            $first_name   = @$data['first_name'];
            $last_name    = @$data['last_name'];
            $phone        = @$data['phone'];
            $device_token = @$data['device_token'];
            $role         = @$data['role'];







            if ($email != null && $password != null) {




                $user['email']    = $email;
                $user['password'] = $password;

                $user['active']  = 1;
                $user['role']    = $role;
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



    public function login() //changes done by irfan
    {
        $this->loadModel('User');
        $this->loadModel('UserInfo');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            // $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $email    = strtolower($data['email']);
            $password = @$data['password'];
            $role = @$data['role'];

            //   $device_token = @$data['device_token'];
            // $userData['msg'] = ;

            if ($email != null && $password != null) {
                $userData = $this->User->loginAllUsersExceptAdmin($email, $password,$role);

                if (($userData) && $userData !== "203") {
                    $user_id = $userData[0]['User']['id'];


                    $output      = array();
                    $userDetails = $this->UserInfo->getUserDetailsFromID($user_id);

                    //CustomEmail::welcomeStudentEmail($email);
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
                    

                        $output['code'] = 201;
                        $output['msg']  = $response['message'];
                        echo json_encode($output);
                        die();

                    
                }else{
                    
                    
                    
                     if (array_key_exists('sid', $response)){
                         
                         
                         
                          $this->PhoneNoVerification->save($phone_verify);
                             $output['code'] = 200;
                            $output['msg']  = "code has been generated and sent to user's phone number";
                            echo json_encode($output);
                                die();
                         
                         
                     }
                    
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
    public function addPaymentMethod()
    {

        $this->loadModel('StripeCustomer');
        $this->loadModel('PaymentMethod');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id = @$data['user_id'];
            $default = @$data['default'];




            //$email = @$data['email'];
            //$first_name = @$data['first_name'];
            //$last_name = @$data['last_name'];
            $name      = @$data['name'];
            $card      = @$data['card'];
            $cvc       = @$data['cvc'];
            $exp_month = @$data['exp_month'];
            $exp_year  = @$data['exp_year'];
            // $address_line_1 = @$data['street'];
            //$address_line_2 = @$data['city'];
            // $address_zip = @$data['zip'];
            //$address_state = @$data['state'];
            //$address_country = @$data['country'];

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
            $user_id = @$data['user_id'];
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


    public function addDeliveryAddress()
    {


        $this->loadModel("Address");
        $this->loadModel("UserInfo");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);




            $user_id     = @$data['user_id'];
            $lat     =     @$data['lat'];
            $long =        @$data['long'];
            $street      = @$data['street'];
            $apartment   = @$data['apartment'];
            $city        = @$data['city'];
            $state       = @$data['state'];
            $zip         = @$data['zip'];
            $country     = @$data['country'];
            $instruction = @$data['instructions'];






            $address['user_id']      = $user_id;
            $address['street']       = $street;
            $address['apartment']    = $apartment;
            $address['city']         = $city;
            $address['state']        = $state;
            $address['zip']          = $zip;
            $address['country']      = $country;
            $address['instructions'] = $instruction;
            $address['lat']         = $lat;
            $address['long']         = $long;

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

    public function getDeliveryAddresses()
    {

        $this->loadModel('Address');

        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id   = @$data['user_id'];
            $addresses = $this->Address->getUserDeliveryAddresses($user_id);


            $output['code'] = 200;
            $output['msg']  = $addresses;
            echo json_encode($output);
            die();


        }

    }

    public function showRestaurants(){

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $lat = @$data['lat'];
            $long = @$data['long'];
            $user_id = null;
            if(isset($data['user_id'])){

                $user_id = $data['user_id'];
            }
            /*$results = Lib::getCountryCityProvinceFromLatLong($lat,$long);

            if(strlen($results['city']) > 2) {

                $restaurants[0] = $this->Restaurant->getCurrentCityRestaurantsBasedOnPromoted($lat, $long, $user_id,$results['city']);
                $restaurants[1] = $this->Restaurant->getCurrentCityRestaurantsBasedOnDistance($lat, $long, $user_id,$results['city']);


                //  array_push($restaurants[0], $restaurants[1]);

                array_splice( $restaurants[0], count($restaurants[0]), 0,  $restaurants[1] );
            }else{

                $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);

            }*/

 $restaurants = $this->Restaurant->getNearByRestaurants($lat, $long, $user_id);
            $output['code'] = 200;

            $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($restaurants);
            echo json_encode($output);


            die();



        }
    }


    public function searchRestaurants(){

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $keyword = $data['keyword'];

            $restaurants = $this->Restaurant->searchRestaurant($keyword);

            if(count($restaurants) > 0) {

                $output['code'] = 200;

                $output['msg'] = $restaurants;
                echo json_encode($output);


                die();
            }else{

                Message::EMPTYDATA();

            }


        }
    }

    public function showRestaurantDetail()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantRating");

        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];
            if (count($id) > 0) {
                $restaurant_detail = $this->Restaurant->getRestaurantDetailInfo($restaurant_id);

               /* $i = 0;
                foreach ($restaurant_detail as $rest) {
                    $ratings = $this->RestaurantRating->getAvgRatings($rest['Restaurant']['id']);

                    if (count($ratings) > 0) {
                        $restaurants[$i]['TotalRatings']["avg"]          = $ratings[0]['average'];
                        $restaurants[$i]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                    }
                    $i++;

                }*/
                $output['code'] = 200;

                $output['msg'] = $restaurant_detail;
                echo json_encode($output);
                die();
            } else {

                Message::ACCESSRESTRICTED();
            }
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

            $output['msg'] =  Lib::convert_from_latin1_to_utf8_recursively($menus);
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
            $menu_id       = $data['menu_id'];

            $menu_items_array = array();

            //$deleteMainMenu = $this->RestaurantMenu->deleteMainMenu($menu_id,$restaurant_id);



            $menu_item_ids = $this->RestaurantMenuItem->getMenuItems($menu_id);

            for ($i = 0; $i < count($menu_item_ids); $i++) {

                $menu_items_array[$i] = $menu_item_ids[$i]['RestaurantMenuItem']['id'];

            }


            $section_items       = $this->RestaurantMenuExtraSection->getSections($menu_items_array);
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


        $this->loadModel("RestaurantMenu");
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

                    /*  for setting hasmenuitem value to none     */

                    $this->RestaurantMenuItem->id = $id;
                    $restaurant_menu_id = $this->RestaurantMenuItem->field('restaurant_menu_id');

                    if($restaurant_menu_id < 1){


                        $this->RestaurantMenu->id = $id;
                        $this->RestaurantMenu->saveField('has_menu_item',0);
                    }
                    /****************************/
                    Message::DELETEDSUCCESSFULLY();

                    die();


                }

            }






        }

    }

    public function test(){

        $this->loadModel("RestaurantMenuItem");
        $json = file_get_contents('php://input');
        $data = json_decode($json, TRUE);



        $id = $data['menu_item_id'];
        $this->RestaurantMenuItem->id = $id;
        $restaurant_menu_id = $this->RestaurantMenuItem->field('restaurant_menu_id');
        if($restaurant_menu_id < 1){

            echo "hello";
        }else{


            echo "bellow";
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

    public function showRestaurantOrders()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);



            $user_id = $data['user_id'];

            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];

            $orders = array();
            // $orders = $this->Order->getRestaurantOrders($restaurant_id);

            if (isset($data['status'])) {
                
                $status = $data['status'];
                $orders = $this->Order->getActiveAndCompletedOrdersOfRestaurant($restaurant_id, $status);




            } else if (isset($data['starting_date'])) {

                $starting_date = $data['starting_date'];
                $ending_date   = $data['ending_date'];
                $orders        = $this->Order->getOrdersBetweenTwoDates($restaurant_id, $starting_date, $ending_date);

            }else if (isset($data['hotel_accepted'])) {

                $hotel_accepted = $data['hotel_accepted'];
                $orders     = $this->Order ->getCancelledOrdersOfRestaurant($restaurant_id,$hotel_accepted);


            }



            if (count($orders) > 0) {
                $output['code'] = 200;

                $output['msg'] = $orders;
                
                echo json_encode($output);
            } else {


                Message::EMPTYDATA();
            }

            die();
        }
    }


    public function showOrdersBetweenDates()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $min_date      = $data['min_date'];
            $max_date      = $data['max_date'];
            $restaurant_id = $data['restaurant_id'];


            $orders = $this->Order->getOrdersBetweenTwoDates($restaurant_id, $min_date, $max_date);
            //  debug($this->Order->lastQuery());

            $output['code'] = 200;

            $output['msg'] = $orders;
            echo json_encode($output);


            die();
        }
    }

    public function showUserOrders()
    {

        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];




            $orders = $this->Order->getUserOrders($user_id);
            //  debug($this->Order->lastQuery());

            $output['code'] = 200;

            $output['msg'] = Lib::convert_from_latin1_to_utf8_recursively($orders);
            echo json_encode($output);


            die();
        }
    }

    public function showOrderDetail()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id = $data['order_id'];

            $user_id = $data['user_id'];

            $id = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {

                $restaurant_id = $id[0]['Restaurant']['id'];


                $orders = $this->Order->getOrderDetailBasedOnIDAndRestaurant($order_id, $restaurant_id);

            } else {

                $orders = $this->Order->getOrderDetailBasedOnUserID($order_id, $user_id);


            }
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


    public function restaurantOwnerResponse()
    {

        $this->loadModel("Order");
        $this->loadModel("Restaurant");
        $this->loadModel("UserInfo");
        $this->loadModel('OrderMenuItem');

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $order_id       = $data['order_id'];


            $response = $data['response'];
            $reason = $data['reason'];


            $restaurant_response['hotel_accepted'] = $response;
         if($response == 1){



             $restaurant_response['accepted_reason'] = $reason;
             $restaurant_detail =  $this->Order->getRestaurantName($order_id);
             $curl_date[$order_id] =
                 array (



                     'order_status' => $restaurant_detail[0]['Restaurant']['name'] . 'has accepted your order and processing it',
                     'map_change' => "0",




                 );

             $curl = curl_init();

             curl_setopt_array($curl, array(
                 CURLOPT_URL => "https://foodies-bc2df.firebaseio.com/tracking_status.json",
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

         }  if($response == 2){


                $restaurant_response['rejected_reason'] = $reason;



         }



            $user_id = $data['user_id'];


            $id            = $this->Restaurant->getRestaurantID($user_id);



           // $restaurant_id = $id[0]['Restaurant']['id'];

if(count($id) > 0){

            $hotel_response = $this->Order->checkAcceptedOrRejectedResponse($order_id);

                    $this->Order->id = $order_id;
                    $user_id = $this->Order->field('user_id');
                    $this->UserInfo->id = $user_id;
                    $device_token = $this->UserInfo->field('device_token');
                    $menu_details = $this->OrderMenuItem->getMenuItem($order_id);

            if ($hotel_response[0]['Order']['hotel_accepted'] == 0) {

                if($response == 1) {

                    $this->Order->id = $order_id;
                    if ($this->Order->save($restaurant_response)) {

                         /* send push notification*/





                        if (strlen($device_token) > 10) {

                            /************notification*************/


                            $notification['to'] = $device_token;
                            $notification['notification']['title'] = "Order has been accepted by the restaurant";
                            $notification['notification']['body'] = $menu_details[0]['OrderMenuItem'][0]['name'].' has been accepted by '.$restaurant_detail[0]['Restaurant']['name'];
                            $notification['notification']['badge'] = "1";
                            $notification['notification']['sound'] = "default";
                            $notification['notification']['icon'] = "";
                            $notification['notification']['type'] = "";
                            $notification['notification']['data']= "";

                            PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                            //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                            /********end notification***************/
                        }

                        echo Message::DATASUCCESSFULLYSAVED();


                        die();
                    } else {

                        echo Message::DATASAVEERROR();
                        die();

                    }
                }else{

                    $this->Order->id = $order_id;
                    if ($this->Order->save($restaurant_response)) {


                        /************notification*************/


                        $notification['to'] = $device_token;
                        $notification['notification']['title'] = "Order has been rejected by the restaurant";
                        $notification['notification']['body'] = $reason;
                        $notification['notification']['badge'] = "1";
                        $notification['notification']['sound'] = "default";
                        $notification['notification']['icon'] = "";
                        $notification['notification']['type'] = "";
                        $notification['notification']['data']= "";

                        PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                        //PushNotification::sendPushNotificationToTablet(json_encode($notification));


                        /********end notification***************/



                        echo Message::DATASUCCESSFULLYSAVED();


                        die();
                    } else {

                        echo Message::DATASAVEERROR();
                        die();

                    }

                }
            } else if ($hotel_response[0]['Order']['hotel_accepted'] == 1) {


                $output['code'] = 201;
                $output['msg']  = "Already Accepted";
                echo json_encode($output);
                die();
            } else if ($hotel_response[0]['Order']['hotel_accepted'] == 2) {


                $output['code'] = 201;
                $output['msg']  = "Already Rejected";
                echo json_encode($output);
                die();
            }
        }else{

    $output['code'] = 203;
    $output['msg']  = "restaurant do not exist";
    echo json_encode($output);
    die();

}

        }
    }

    public function editRestaurant()
    {


        $this->loadModel("Restaurant");
        $this->loadModel("RestaurantLocation");
        $this->loadModel("RestaurantTiming");



        if ($this->request->isPost()) {


            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);





            $user_id = @$data['user_id'];


            $about  = strtolower(@$data['about']);
            $about  = ucwords($about);


            $phone            = @$data['phone'];

            $updated          = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
            $google_analytics = @$data['google_analytics'];


            $preparation_time      = @$data['preparation_time'];
            $min_order_price      =  @$data['min_order_price'];
            $delivery_free_range      = @$data['delivery_free_range'];



            /*$currency_id      = $data['currency_id'];
            $tax_id     = $data['tax_id'];
            $speciality     = $data['speciality'];
            $preparation_time      = $data['preparation_time'];*/

            $restaurant_timing = @$data['restaurant_timing'];


            $id            = $this->Restaurant->getRestaurantID($user_id);
            $restaurant_id = $id[0]['Restaurant']['id'];
            if (count($id) > 0) {
                //delete images-------------------

                $restaurant = $this->Restaurant->getRestaurantDetail($restaurant_id);


                $image       = $restaurant[0]['Restaurant']['image'];
                $cover_image = $restaurant[0]['Restaurant']['cover_image'];




                //   -----------------------------------------------
                if (isset($data['image']) && $data['image'] != " ") {

                    if(is_file($image)) {
                        @unlink($image);
                    }

                    $image      = $data['image'];
                    $folder_url = UPLOADS_FOLDER_URI;

                    $filePath            = Lib::uploadFileintoFolder($restaurant_id, $image, $folder_url);
                    $restaurant['image'] = $filePath;
                }

                if (isset($data['cover_image']) && $data['cover_image'] != " ") {
                    if(is_file($cover_image)) {
                        @unlink($cover_image);
                    }

                    $cover_image = $data['cover_image'];
                    $folder_url  = UPLOADS_FOLDER_URI;

                    $filePath                  = Lib::uploadFileintoFolder($restaurant_id, $cover_image, $folder_url);
                    $restaurant['cover_image'] = $filePath;
                }



                $restaurant['about']            = $about;
                $restaurant['updated']          = $updated;
                $restaurant['google_analytics'] = $google_analytics;

                $restaurant['phone']            = $phone;

                $restaurant['preparation_time'] = $preparation_time;
                $restaurant['min_order_price']  =  $min_order_price;
                $restaurant['delivery_free_range'] = $delivery_free_range;




                $this->RestaurantTiming->deleteAll(array(
                    'restaurant_id' => $restaurant_id
                ), false);

                foreach ($restaurant_timing as $k => $v) {


                    $timing[$k]['day']           = @$v['day'];
                    $timing[$k]['opening_time']  = @$v['opening_time'];
                    $timing[$k]['closing_time']  = @$v['closing_time'];
                    $timing[$k]['restaurant_id'] = $restaurant_id;

                }

                $this->RestaurantTiming->saveAll($timing);
                $this->RestaurantLocation->id = $restaurant_id;

                $this->Restaurant->id = $restaurant_id;
                $this->Restaurant->save($restaurant);

                $rest_details   = $this->Restaurant->getRestaurantDetail($restaurant_id);
                $output['code'] = 200;
                $output['msg']  = $rest_details;
                echo json_encode($output);

            } else {

                Message::ACCESSRESTRICTED();

            }
        }


    }



    public function placeOrder()
    {


        $this->loadModel("Order");
        $this->loadModel("UserInfo");
        $this->loadModel("User");
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
            $version     =   @$data['version'];
            $instructions  = $data['instructions'];
            $coupon_id     = $data['coupon_id'];
            $status        = 1;
            $device        = @$data['device'];
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

            $price = $delivery_fee + $rider_tip + $tax + $sub_total;


            $order['user_id']           = $user_id;
            $order['price']             = $price;
            $order['status']            = $status;
            $order['created']           = $created;
            $order['quantity']          = $quantity;
            $order['payment_method_id'] = $payment_id;
            $order['cod']               = $cod;
            $order['version']               = $version;

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
            if(count($address_detail) > 0) {




                if ($address_detail[0]['Address']['city'] != $restaurant_location[0]['RestaurantLocation']['city']) {

                    $output['code'] = 202;
                    $output['msg'] = "Address is different from the restaurant location";
                    echo json_encode($output);
                    die();
                }
            }
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
                $notification['notification']['data']= "";

                PushNotification::sendPushNotificationToMobileDevice(json_encode($notification));
                //PushNotification::sendPushNotificationToTablet(json_encode($notification));


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
            $result[0]['Restaurant'] = $order_detail[0]['Restaurant'];
            $result[0]['Order'] = $order_detail[0]['Order'];

            //  $rider_location = $this->RiderLocation->getRiderLocation($rider[0]['RiderOrder']['rider_user_id']);
            if (count($rider) > 0 && $pickup_time > 0) {


                //order has been assigned and picked up
                $result[0]['RiderOrder'] = $rider[0]['RiderOrder'];

                $result[0]['Rider'] = $rider[0]['Rider'];

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
                'currency' => 'pkr',

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
            $coupon_exist  = $this->RestaurantCoupon->isCouponCodeExistAgainstRestaurant($coupon_code, $restaurant_id);

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

    public function editUserProfile()
    {

        $this->loadModel("UserInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id    = $data['user_id'];
            $first_name = $data['first_name'];
            $last_name  = $data['last_name'];
           // $email      = $data['email'];




            $user_info['first_name'] = $first_name;
            $user_info['last_name']  = $last_name;
            //$user_info['email']      = $email;





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


    public function addBankingInfo()
    {

        $this->loadModel("BankingInfo");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_banking_info['name']       = $data['name'];
            $rider_banking_info['account_no'] = $data['account_no'];
            $rider_banking_info['user_id']    = $data['user_id'];
            $rider_banking_info['bank_no']    = $data['bank_no'];
            $rider_banking_info['transit_no'] = $data['transit_no'];






            if (isset($data['id'])) {

                $id                    = $data['id'];
                $this->BankingInfo->id = $id;
                $this->BankingInfo->save($rider_banking_info);

                $banking_info   = $this->BankingInfo->getBankingInfo($data['user_id']);
                $output['code'] = 200;
                $output['msg']  = $banking_info;
                echo json_encode($output);


                die();
            } else
                //echo $this->RiderBankingInfo->isDuplicateRecord($data['user_id'],$data['name'], $data['transit_no'],$data['bank_no'],$data['account_no']);
                if ($this->BankingInfo->isDuplicateRecord($data['user_id'], $data['name'], $data['transit_no'], $data['bank_no'], $data['account_no']) == 0) {
                    if ($this->BankingInfo->save($rider_banking_info)) {

                        $banking_info   = $this->BankingInfo->getBankingInfo($data['user_id']);
                        $output['code'] = 200;
                        $output['msg']  = $banking_info;
                        echo json_encode($output);

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

    public function showRiderCompletedOrders()
    {



        $this->loadModel("RiderOrder");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];
            if (isset($data['starting_date'])) {

                $starting_date = $data['starting_date'];
                $ending_date   = $data['ending_date'];
                $orders        = $this->RiderOrder->getRiderOrdersBetweenTwoDates($user_id, $starting_date, $ending_date);

            } else {

                $orders = $this->RiderOrder->getRiderCompletedOrders($user_id);
            }


            $output['code'] = 200;
            $output['msg']  = $orders;
            echo json_encode($output);


            die();



        }
    }



    public function showBankingInfo()
    {

        $this->loadModel("BankingInfo");


        if ($this->request->isPost()) {

            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_id = $data['user_id'];

            $banking_info   = $this->BankingInfo->getBankingInfo($rider_id);
            $output['code'] = 200;
            $output['msg']  = $banking_info;
            echo json_encode($output);


            die();



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

    public function addUserDocument()
    {

        $this->loadModel("VerificationDocument");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $user_id     = $data['user_id'];
            $description = $data['description'];

            $doc['user_id']     = $user_id;
            $doc['description'] = $description;
            if (isset($data['image']) && $data['image'] != " ") {



                $image      = $data['image'];
                $folder_url = UPLOADS_FOLDER_URI . "/" . VERIFICATION_DOCUMENTS;

                $filePath    = Lib::uploadFileintoFolder($user_id, $image, $folder_url);
                $doc['file'] = $filePath;
            }



            if ($this->VerificationDocument->save($doc)) {
                $id       = $this->VerificationDocument->getInsertID();
                $document = $this->VerificationDocument->getDocumentDetail($id);


                $output['code'] = 200;

                $output['msg'] = $document;
                echo json_encode($output);


                die();
            } else {

                echo Message::DATASAVEERROR();
                die();

            }

        }
    }

    public function showUserDocuments()
    {

        $this->loadModel("VerificationDocument");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id = $data['user_id'];

            $documents = $this->VerificationDocument->getDocuments($user_id);


            $output['code'] = 200;

            $output['msg'] = $documents;
            echo json_encode($output);


            die();
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


    public function deleteDeal(){

        $this->loadModel("Deal");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $id = $data['id'];

            $user_id       = $data['user_id'];
            $restaurant_detail            = $this->Restaurant->getRestaurantID($user_id);
            if(count($restaurant_detail)) {
                $restaurant_id = $restaurant_detail[0]['Restaurant']['id'];
                $deal = $this->Deal->getDealDetail($id,$restaurant_id);

                if(count($deal) > 0) {
                    $delete = $this->Deal->deleteDeal($id, $restaurant_id);
                    @unlink($deal[0]['Deal']['image']);
                      @unlink($deal[0]['Deal']['cover_image']);
                    if ($delete) {


                        Message::DELETEDSUCCESSFULLY();
                        die();
                    } else {
                        Message::ERROR();
                        die();

                    }
                }else{

                    Message::ACCESSRESTRICTED();
                    die();
                }  }else{


                Message::ACCESSRESTRICTED();
                die();
            }
        }

    }

    public function showRestaurantDeals()
    {

        $this->loadModel("Deal");

        $this->loadModel("Restaurant");
        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $user_id       = $data['user_id'];
            $id            = $this->Restaurant->getRestaurantID($user_id);

            if(count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];

                $deals = $this->Deal->getRestaurantDeals($restaurant_id);


                $output['code'] = 200;

                $output['msg'] = $deals;
                echo json_encode($output);


                die();
            }else{

                Message::ACCESSRESTRICTED();
                die();

            }
        }
    }

    public function showRestaurantExtraMenuSections()
    {

        $this->loadModel("RestaurantMenuExtraSection");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_id = $data['restaurant_id'];

            $section_names = $this->RestaurantMenuExtraSection->getAllRestaurantSectionNames($restaurant_id);


            $output['code'] = 200;

            $output['msg'] = $section_names;
            echo json_encode($output);


            die();
        }
    }




    public function addRestaurantCoupon()
    {

        $this->loadModel("RestaurantCoupon");
        $this->loadModel("Restaurant");

        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);


            $coupon_code   = $data['coupon_code'];
            $limit_users   = $data['limit_users'];
            $discount      = $data['discount'];
            $expire_date   = $data['expire_date'];
            $type   = $data['type'];





            $coupon['coupon_code']   = $coupon_code;
            $coupon['limit_users']   = $limit_users;
            $coupon['discount']      = $discount;
            $coupon['expire_date']   = $expire_date;
            $coupon['type']   = $type;
            $user_id   = $data['user_id'];
            $id        = $this->Restaurant->getRestaurantID($user_id);
            if (count($id) > 0) {
                $restaurant_id = $id[0]['Restaurant']['id'];
                $coupon['restaurant_id']   = $restaurant_id;
                if ($this->RestaurantCoupon->isDuplicateRecord($restaurant_id, $coupon_code) == 0)
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

                Message::ACCESSRESTRICTED();
                die();

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



    public function subscribe()
    {

        $this->loadModel("Subscriber");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $subscribe['email'] = $data['email'];
            $subscribe['city'] = $data['city'];
            $subscribe['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


             if($this->Subscriber->isDuplicateRecord($subscribe) > 0){

               

                 $output['msg'] = "you have already subscribed";
             }else{

                 $this->Subscriber->save($subscribe);

                 $id = $this->Subscriber->getLastInsertId();

                 $result = $this->Subscriber->getLastInsertRow($id);
                 $output['msg'] = $result;
             }


            $output['code'] = 200;


            echo json_encode($output);


            die();
        }
    }

    public function restaurantRequest()
    {

        $this->loadModel("RestaurantRequest");
        $this->loadModel("User");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $restaurant_request['restaurant_name'] = $data['restaurant_name'];
            $restaurant_request['contact_name'] = $data['contact_name'];
            $restaurant_request['phone'] = $data['phone'];
            $restaurant_request['email'] = $data['email'];
            $restaurant_request['address'] = $data['address'];
            $restaurant_request['description'] = $data['description'];

            $restaurant_request['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if($this->RestaurantRequest->isDuplicateRecord($restaurant_request) > 0){



                $output['msg'] = "you have already applied";
            }else{

                $this->RestaurantRequest->save($restaurant_request);

                $emails = $this->User->getAdminEmails();
                foreach($emails as $email){

                    CustomEmail::sendEmailRestaurantRequest($email['User']['email'],$restaurant_request);

                }

                $id = $this->RestaurantRequest->getLastInsertId();

                $result = $this->RestaurantRequest->getLastInsertRow($id);
                $output['msg'] = $result;
            }


            $output['code'] = 200;


            echo json_encode($output);


            die();
        }
    }

    public function riderRequest()
    {

        $this->loadModel("RiderRequest");


        if ($this->request->isPost()) {



            $json = file_get_contents('php://input');
            $data = json_decode($json, TRUE);

            $rider_request['first_name'] = $data['first_name'];
            $rider_request['last_name'] = $data['last_name'];
            $rider_request['phone'] = $data['phone'];
            $rider_request['email'] = $data['email'];
            $rider_request['city'] = $data['city'];
            $rider_request['state'] = $data['state'];
            $rider_request['country'] = $data['country'];
            $rider_request['address'] = $data['address'];
            $rider_request['created'] = date('Y-m-d H:i:s', time() - 60 * 60 * 4);


            if($this->RiderRequest->isDuplicateRecord($rider_request) > 0){



                $output['msg'] = "you have already applied";
            }else{

                $this->RiderRequest->save($rider_request);

                $id = $this->RiderRequest->getLastInsertId();

                $result = $this->RiderRequest->getLastInsertRow($id);
                $output['msg'] = $result;
            }


            $output['code'] = 200;


            echo json_encode($output);


            die();
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
            $role     = $data['role'];
            $user_info = $this->User->findEmail($email,$role);


            if (count($user_info) > 0) {

                $key     = Security::hash(CakeText::uuid(), 'sha512', true);
                $user_id = $user_info[0]['User']['id'];
                $email   = $user_info[0]['User']['email'];


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
            die();
        }


    }

   /* public function resetPassword()
    {
        $this->loadModel('User');
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
*/
    public function resetPassword()
    {
        $this->loadModel('User');

        if ($this->request->isPost()) {

           $json   = file_get_contents('php://input');

             $data = json_decode($json, TRUE);

             $password = $data['password'];
             $email = $data['email'];
             $token = $data['token'];
            if(strlen($token) > 10) {
                $user_details = $this->User->findTokenAgainstEmail($email, $token);


                if (count($user_details) > 0) {


                    $user['password'] = $password;
                    $user['token'] = 0;
                    $this->User->id = $user_details[0]['User']['id'];
                    if ($this->User->save($user)) {

                        $output['code'] = 200;

                        $output['msg'] = "Password has been successfully Changed";
                        echo json_encode($output);
                        die();

                    } else {

                        echo Message::ERROR();
                        die();

                    }


                } else {
                    $output['code'] = 201;

                    $output['msg'] = "Token has been expired";
                    echo json_encode($output);
                    die();
                }

            }else{


                $output['code'] = 201;

                $output['msg'] = "Invalid Url";
                echo json_encode($output);
                die();

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

    /*-------------*/

    public function riderOrderUpdate(){


        $order_id = $this->request->query('order_id');
        $this->loadModel("RiderOrder");
        if($this->RiderOrder->isRiderOrderExistAgainstOrder($order_id) > 0){
        if($this->RiderOrder->saveField('order_id',$order_id)){

echo "successfully added";
die();

        }else{

            echo "something went wrong";

        }
        }else{

            echo "no order exist";
        }


    }


    public function showEarnings()
    {

        $this->loadModel("Restaurant");
        $this->loadModel("Order");


        if ($this->request->isPost()) {
            $json = file_get_contents('php://input');

            $data = json_decode($json, TRUE);


            $user_id        = $data['user_id'];
            $restaurant_details            = $this->Restaurant->getRestaurantID($user_id);

            if(count($restaurant_details) > 0) {


                $restaurant_id = $restaurant_details[0]['Restaurant']['id'];
                $currency = $restaurant_details[0]['Currency'];
                $created = $restaurant_details[0]['Restaurant']['created'];
                $formatted_registration_date = Lib::getFullMonthAndYear($created);




                $earnings = $this->Order->getTotalEarnings($restaurant_id);

                $total_stats['Currency'] = $currency;
                $total_stats['Restaurant']['created'] = "Since ". $formatted_registration_date;
                $total_stats['TotalEarning'] = $earnings[0][0];

                $add_tax_and_fee =  $earnings[0][0]['total_tax'] +  $earnings[0][0]['total_restaurant_delivery_fee'];
                $total_earning =  $earnings[0][0]['total_sub_total'] - $add_tax_and_fee;
                $total_stats['TotalEarning']['total_earning'] = (string)$total_earning;
                $total_stats['TotalEarning']['total_cash_on_delivery_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,1);
                $total_stats['TotalEarning']['total_online_orders'] = (string)$this->Order->getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,0);

               /*weekly earnings*/

                $weekly_earnings = $this->Order->getWeeklyEarnings($restaurant_id);

                foreach ($weekly_earnings as $key => $val) {

                    $total_stats['WeeklyEarning'][$key] = $val[0];
                    $total_stats['WeeklyEarning'][$key]['week_start'] = Lib::shortMonthAndDay($val[0]['week_start']);
                    $total_stats['WeeklyEarning'][$key]['week_end'] =   Lib::shortMonthAndDay($val[0]['week_end']);
                    $add_tax_and_fee =  $val[0]['total_tax'] +  $val[0]['total_restaurant_delivery_fee'];

                    $weekly_total =  $val[0]['total_sub_total'] - $add_tax_and_fee;
                    $total_stats['WeeklyEarning'][$key]['total_earning'] = (string)$weekly_total;

                    //get orders of that week.



                    $starting_date = $val[0]['week_start'];
                    $ending_date = $val[0]['week_end'];

                    $total_stats['WeeklyEarning'][$key]['total_cash_on_delivery_orders'] = (string)$this->Order->getRestaurantCashOnDeliveryOrOnlineCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date,1);
                    $total_stats['WeeklyEarning'][$key]['total_online_orders'] = (string)$this->Order->getRestaurantCashOnDeliveryOrOnlineCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date,0);

                    $orders = $this->Order->getRestaurantCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date);
                    $total_stats['WeeklyEarning'][$key]['Orders'] = $orders;
                }

               /*------*/


                $output['code'] = 200;


                $output['msg'] = $total_stats;
                echo json_encode($output);
               die();
            }else{



                echo Message::ERROR();
                die();
            }




        }
    }



}


?>
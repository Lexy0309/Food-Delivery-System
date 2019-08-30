<?php



class Order extends AppModel
{
    public $useTable = 'order';



    public $belongsTo = array(

        'PaymentMethod' => array(
            'className' => 'PaymentMethod',
            'foreignKey' => 'payment_method_id',


        ),

        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'user_id',


        ),

        'Address' => array(
            'className' => 'Address',
            'foreignKey' => 'address_id',


        ),
        'Restaurant' => array(
            'className' => 'Restaurant',
            'foreignKey' => 'restaurant_id',


        ),

    );
    public $hasMany = array(
        'OrderMenuItem' => array(
            'className' => 'OrderMenuItem',
            'foreignKey' => 'order_id',



        ),
    );

    public $hasOne = array(
        'CouponUsed' => array(
            'className' => 'CouponUsed',
            'foreignKey' => 'order_id',



        ),

        'RiderOrder' => array(
            'className' => 'RiderOrder',
            'foreignKey' => 'order_id',



        ),

        'RiderLocation' => array(
            'className' => 'RiderLocation',
            'foreignKey' => 'user_id',



        )
    );

     var $contain = array('OrderMenuItem','Restaurant.RestaurantLocation','Restaurant.Currency','Restaurant.Tax','OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','CouponUsed.RestaurantCoupon');
    var $contain_rider = array('OrderMenuItem','Restaurant.Currency','Restaurant.RestaurantLocation','Restaurant.Tax','OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider');
    var $contain_order_deal = array('PaymentMethod','Address','UserInfo','Restaurant.Currency','Restaurant.Tax','OrderMenuItem');

    var $orderby_id = array('Order.id' => 'DESC');

    //public $contain = array('OrderMenuItem','Restaurant','OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider');

    public function getOrders($user_id,$status)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



           // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
            'contain'=>array('OrderMenuItem','Restaurant.Currency','Restaurant.Tax','OrderMenuItem.OrderMenuExtraItem','Restaurant' => array(
                'fields' => array(
                   'name'  // <-- Notice this addition
                ))),

            'conditions' => array(

                'Order.user_id'=> $user_id,
                'Order.status'=> $status



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }

        public function getOrderDetailsWithoutMenuSuperAdmin(){
            $this->Behaviors->attach('Containable');

            return $this->find('all', array(



                // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
                'contain'=>array('OrderMenuItem','Restaurant.RestaurantLocation','Restaurant.Currency','Restaurant.Tax','OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo'),


                'order' => $this->orderby_id,

                'recursive' => 0

            ));


}

    public function getActiveAndCompletedOrdersOfRestaurant($restaurant_id,$status)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
            'contain'=>$this->contain,

            'conditions' => array(


                'Order.status'=> $status,
                'Order.restaurant_id'=> $restaurant_id,
                'Order.hotel_accepted'=> array(0, 1)



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }

    public function isOrderExist($data)
    {


        return $this->find('first', array(





            'conditions' => array(



                'Order.price'=> $data['price'],
                'Order.delivery_fee'=> $data['delivery_fee'],
                'Order.user_id'=> $data['user_id'],
                'Order.address_id'=> $data['address_id'],
                'Order.payment_method_id'=> $data['payment_method_id'],
                'Order.quantity'=> $data['quantity'],
                'Order.delivery'=> $data['delivery'],
                'Order.rider_tip'=> $data['rider_tip'],
                'Order.tax'=> $data['tax'],
                'Order.sub_total'=> $data['sub_total'],
                'Order.cod'=> $data['cod'],
                'Order.version'=> $data['version'],
                'Order.device'=> $data['device'],
                'Order.instructions'=> $data['instructions'],
                'Order.restaurant_id'=> $data['restaurant_id'],



            ),
            'order' => $this->orderby_id,

            'recursive' => -1

        ));


    }

    public function getCancelledOrdersOfRestaurant($restaurant_id,$hotel_accepted)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
            'contain'=>$this->contain,

            'conditions' => array(



                'Order.restaurant_id'=> $restaurant_id,
                'Order.hotel_accepted'=> $hotel_accepted



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }
    public function getCompletedOrdersOfRestaurantOfSpecificDate($restaurant_id,$datetime,$status)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
            'contain'=>$this->contain,

            'conditions' => array(


                'Order.status'=> $status,
                'Order.restaurant_id'=> $restaurant_id,
                'Order.created >=' => $datetime,
                'Order.created <=' => $datetime,



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }



    public function getCompletedOrders($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>array('OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','Restaurant.Currency','Restaurant.Tax','Restaurant','UserInfo'),
            'conditions' => array(


                'Order.status'=> 2,
                'Order.user_id'=> $user_id,



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }

    public function getCompletedCashOnDeliveryOrOnlineOrders($restaurant_id,$cod)
    {

        return $this->find('count', array(



            'conditions' => array(


                'Order.status'=> 2,
                'Order.restaurant_id'=> $restaurant_id,
                 'Order.cod'=> $cod,



            ),


        ));


    }




    public function getRestaurantCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date)
    {

        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
           //'contain'=>array('OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','Restaurant.Currency','Restaurant.Tax','Restaurant','UserInfo'),
            'conditions' => array(


                'Order.status'=> 2,
                'Order.restaurant_id'=> $restaurant_id,
                'Order.created >='=> $starting_date,
                'Order.created <='=> $ending_date



            ),
            'order' => $this->orderby_id,

            'recursive' => -1

        ));


    }
    public function getRestaurantCashOnDeliveryOrOnlineCompletedOrdersBetweenDates($restaurant_id,$starting_date,$ending_date,$cod)
    {

        return $this->find('count', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            //'contain'=>array('OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','Restaurant.Currency','Restaurant.Tax','Restaurant','UserInfo'),
            'conditions' => array(


                'Order.status'=> 2,
                'Order.cod'=> $cod,
                'Order.restaurant_id'=> $restaurant_id,
                'Order.created >='=> $starting_date,
                'Order.created <='=> $ending_date



            ),
            'order' => $this->orderby_id,

            'recursive' => -1

        ));


    }


    public function getCompletedOrdersAgainstUserID($user_id)
    { $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'contain'=>array('Restaurant','UserInfo'),

            'conditions' => array(


                'Order.user_id'=> $user_id,
                'Order.status'=> 2


            )
        ));
    }

    public function getCompletedOrdersWhoseNotificationHasNotBeenSent($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>array('OrderMenuItem','OrderMenuItem.OrderMenuExtraItem','Restaurant.Currency','Restaurant.Tax','Restaurant','UserInfo'),
            'conditions' => array(


                'Order.status'=> 2,
                'Order.user_id'=> $user_id,
                'Order.notification'=> 0,



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }

    /*public function getRiderLocationAgainstOrder($order_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>array('RiderOrder.Rider','Address','Restaurant.RestaurantLocation'),
            'conditions' => array(



                'Order.id'=> $order_id,
                'Order.hotel_accepted'=> 1,
                'RiderOrder.order_id'=> $order_id,




            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }*/
    public function getRestaurantAcceptedAndPendingOrders($restaurant_id,$restaurant_accepted)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),
            'contain'=>$this->contain,

            'conditions' => array(


                'Order.hotel_accepted'=> $restaurant_accepted,
                'Order.restaurant_id'=> $restaurant_id



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }

    public function getAllOrders()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'order' => $this->orderby_id,

            'recursive' => 0

        ));


    }
    public function getAllOnlyOrders()
    {

        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),

            'order' => $this->orderby_id,

            'recursive' => -1

        ));


    }

    public function getAllOrdersSuperAdmin($status=array())
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain' => array('Restaurant.RestaurantLocation','Address','UserInfo'),
            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.status' => $status,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => 0

        ));


    }

    public function getAllOrdersAccordingToStatus($status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.status' => $status,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => 0

        ));


    }

    public function getOnlyOrdersAccordingToStatusSuperAdmin()
    {

        return $this->find('all', array(





            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.status' => 1,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => -1

        ));


    }

    public function getAllOrdersAccordingToStatusSuperAdmin($status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain' => array('Restaurant.RestaurantLocation','Address','UserInfo'),
            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.status' => $status,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => 0

        ));


    }

    public function getRejectedOrAcceptedOrders($status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.hotel_accepted' => $status,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => 0

        ));


    }

    public function getRejectedOrAcceptedOrdersSuperAdmin($status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('OrderMenuItem','Restaurant','OrderMenuItem.OrderMenuExtraItem','PaymentMethod','Address','UserInfo','RiderOrder.Rider'),
            'contain' => array('Restaurant.RestaurantLocation','Address','UserInfo'),
            'order' => $this->orderby_id,
            'conditions' => array(



                'Order.hotel_accepted' => $status,
                // 'Order.status'=> array(0, 1)


            ),
            'recursive' => 0

        ));


    }


	
	public function getOrderDetailBasedOnID($order_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


           // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain_rider,
            'conditions' => array(



                'Order.id' => $order_id,
               // 'Order.status'=> array(0, 1)


            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }

    public function getOnlyOrderDetailBasedOnID($order_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


           'contain' => array('RiderOrder.Rider'),

            'conditions' => array(



                'Order.id' => $order_id


            ),
            'order' => $this->orderby_id,

            'recursive' => -1

        ));
    }
    
    public function getOrderDetailBasedOnUserID($order_id,$user_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.id' => $order_id,
                'Order.user_id' => $user_id


            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }
	
    public function getOrderDetailBasedOnIDAndRestaurant($order_id,$restaurant_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


           // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.id' => $order_id,
                'Order.restaurant_id' => $restaurant_id


            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }

    public function getOrderDetailBasedOnOrderIDSuperAdmin($order_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.id' => $order_id,



            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }

    public function getUserOrders($user_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


           // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.user_id' => $user_id,


            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }

    public function getOrdersBetweenTwoDates($restaurant_id,$min_date,$max_date)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.created >='=> $min_date,
                'Order.created <='=> $max_date,
                'Order.restaurant_id'=> $restaurant_id,
                'Order.hotel_accepted'=> array(0, 1)

            ),
            'order' => $this->orderby_id,

            'recursive' => 0

        ));
    }

    public function getOnlyRestaurantOrdersBetweenTwoDates($restaurant_id,$start_date,$end_date){


        return $this->find('all', array(

            'conditions' => array(



                'Order.restaurant_id'=> $restaurant_id,
                'Order.created >='=> $start_date,
                'Order.created <='=> $end_date,



            ),

            // 'contain'=>array('OrderMenuItem.RestaurantMenuItem','OrderMenuItem.RestaurantMenuItem.RestaurantMenu','OrderMenuItem.OrderMenuExtraItem.RestaurantMenuExtraItem','PaymentMethod','Address'),




            'recursive' => -1

        ));


    }

    public function getOnlyOrdersBetweenTwoDates($min_date,$max_date)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.created >='=> $min_date,
                'Order.created <='=> $max_date,


            ),
            'order' => $this->orderby_id,

            'recursive' => -1

        ));
    }



    public function getRestaurantOrders($restaurant_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'Order.restaurant_id' => $restaurant_id,


            ),
            'order' => $this->orderby_id,
            'recursive' => 0

        ));
    }

    public function getOnlyRestaurantOrders($restaurant_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'conditions' => array(



                'Order.restaurant_id' => $restaurant_id,


            ),
            'order' => $this->orderby_id,
            'recursive' => -1

        ));
    }




    public function getOrderIDSWhoseTrackingisEnabled()
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'Order.tracking' => 1,


            ),
            'fields'=>array('id'),
            'order' => $this->orderby_id,
            'recursive' => -1

        ));
    }

    public function getRestaurantName($order_id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'contain' => array( 'Restaurant'),

            'conditions' => array(



                'Order.id' => $order_id,


            ),

          
            'recursive' => -1

        ));
    }

    public function getUserDeals($user_id,$status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'contain'=>$this->contain_order_deal,
            'conditions' => array(

                'Order.user_id' => $user_id,
                'Order.status' => $status

            )
        ));
    }

    public function getTotalEarnings($restaurant_id)
    {


        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'Order.restaurant_id' => $restaurant_id,
                'Order.status' => 2


            ),
            'fields' => array(
                'sum(Order.sub_total)   AS total_sub_total',
                'sum(Order.tax)   AS total_tax',
                 'sum(Order.restaurant_delivery_fee)   AS total_restaurant_delivery_fee'),

            'recursive' => 0

        ));
    }

    public function getWeeklyEarnings($restaurant_id)
    {


        return $this->find('all', array(


            //'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'Order.restaurant_id' => $restaurant_id,
                'Order.status' => 2,
                'Order.created >    DATE_SUB(NOW(), INTERVAL 4 WEEK)'



            ),
            'fields' => array(


                'WEEK(Order.created) AS WEEK',
                'DATE_ADD(Order.created, INTERVAL(1-DAYOFWEEK(Order.created)) DAY) AS week_start',
                'DATE_ADD(Order.created, INTERVAL(7-DAYOFWEEK(Order.created)) DAY) AS week_end',

                'sum(Order.sub_total)   AS total_sub_total',
                'sum(Order.tax)   AS total_tax',
                'sum(Order.restaurant_delivery_fee)   AS total_restaurant_delivery_fee',

            ),
            'group' => array('WEEK(Order.created)'),

            'recursive' => 0

        ));
    }



    public function restaurantAcceptedResponse($restaurant_id,$order_id,$hotel_accepted,$accepted_reason)
    {

        return $this->updateAll(array(

            'Order.hotel_accepted'=>$hotel_accepted,
            'Order.accepted_reason'=>$accepted_reason

        ),
            array('Order.id'=>$order_id,
                 'Order.restaurant_id' => $restaurant_id));

    }

    public function restaurantRejectedResponse($restaurant_id,$order_id,$hotel_accepted,$rejected_reason)
    {

        return $this->updateAll(array(

            'Order.hotel_accepted'=>$hotel_accepted,
            'Order.rejected_reason'=>$rejected_reason

        ),
            array('Order.id'=>$order_id,
                'Order.restaurant_id' => $restaurant_id));

    }

    public function checkAcceptedOrRejectedResponse($order_id)
    {


        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'Order.id' => $order_id


            ),
            'fields'=>array('hotel_accepted'),


            'recursive' => 0

        ));
    }


    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['instruction'])
        ) {

            $instruction = strtolower($this->data[$this->alias]['instruction']);

             $this->data['Order']['instruction'] = ucwords($instruction);


        }
        return true;
    }


    public function afterFind($results, $primary = false) {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {




           // $rider_location = array();
            $keys = array_keys($results, "delivery");
            if(count($keys) > 0){
            foreach ($results as $key => $val) {

                if ($val['Order']['delivery'] == 0) {


                    $results[$key]['Address']['id'] = "0";
                    unset($results[$key]['Address']);
                    return $results;
                }
            }

                if(Lib::multi_array_key_exists('RiderOrder',$results)){

                if ($val['RiderOrder']['rider_user_id'] === NULL) {

                    $rider_location = ClassRegistry::init('RiderLocation')->getRiderLocation($val['RiderOrder']['rider_user_id']);
                    unset($results[0]['RiderOrder']);


                } else {
                    if (Lib::multi_array_key_exists('RestaurantLocation', $results)) {
                        $rider_location = ClassRegistry::init('RiderLocation')->getRiderLocation($val['RiderOrder']['rider_user_id']);

                        $results[0]['RiderOrder']['RiderLocation'] = $rider_location[$key]['RiderLocation'];


                        $collection_time = Lib::addMinutesInDateTime($val['RiderOrder']['assign_date_time'], $val['Restaurant']['preparation_time']);


                        //$delivery_time = Lib::addSecondsInDateTime($val['RiderOrder']['assign_date_time'], $val['Restaurant']['preparation_time']);

                        $results[$key]['RiderOrder']['EstimateReachingTime']['estimate_collection_time'] = $collection_time;


                        $lat1 = $val['Address']['lat'];
                        $long1 = $val['Address']['long'];
                        $lat2 = $val['Restaurant']['RestaurantLocation']['lat'];
                        $long2 = $val['Restaurant']['RestaurantLocation']['long'];


                        $duration_time = Lib::getDurationTimeBetweenTwoDistances($lat1, $long1, $lat2, $long2);


                        if ($duration_time) {


                            $seconds = $duration_time['rows'][0]['elements'][0]['duration']['value'];
                            $delivery_time = Lib::addSecondsInDateTime($collection_time, $seconds);

                            // echo $result['rows'][0]['elements'][0]['duration']['value'];
                        } else {

                            $delivery_time = "";
                        }


                        $results[$key]['RiderOrder']['EstimateReachingTime']['estimate_delivery_time'] = $delivery_time;

                    }

                }




            }
        }




        return $results;
    }



}
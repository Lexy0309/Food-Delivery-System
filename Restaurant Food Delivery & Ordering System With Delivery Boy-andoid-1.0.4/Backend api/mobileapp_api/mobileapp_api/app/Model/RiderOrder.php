<?php

App::uses('Lib', 'Utility');

class RiderOrder extends AppModel
{

    public $useTable = 'rider_order';

    public $belongsTo =
        array(
            'Rider' => array('className' => 'UserInfo',
                'foreignKey' => 'rider_user_id'),


          



            'Assigner' => array('className' => 'UserAdmin', 'foreignKey' => 'assigner_user_id'),
            'Order' => array('className' => 'Order', 'foreignKey' => 'order_id'));

    /* public $hasOne = array(
    'RiderTrackOrder' => array(
    'className' => 'RiderTrackOrder',
    'foreignKey' => 'order_id'



    )
    );*/

    var $contain = array('Order.Restaurant', 'Order.Restaurant.RestaurantLocation', 'Order.Address', 'Order.Restaurant.Currency', 'Order.UserInfo', 'Rider', 'Assigner');

    public function isDuplicateRecord($rider_user_id, $assigner_user_id, $order_id)
    {
        return $this->find('count', array(
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.assigner_user_id' => $assigner_user_id,
                'RiderOrder.order_id' => $order_id


            )
        ));
    }

    public function getAllRiderOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'contain' => $this->contain,
            //'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }

    public function countRiderAssignOrders($rider_user_id)
    {

        return $this->find('count', array(



            //'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.accept_reject_status' => 1



            ),




        ));


    }
    public function getOrdersBasedOnDate($date, $new_date, $user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'conditions' => array(



                'RiderOrder.assign_date_time >=' => $date,
                'RiderOrder.assign_date_time <=' => $new_date,
                'RiderOrder.rider_user_id' => $user_id,
                'RiderOrder.accept_reject_status' => 3



            ),

            //'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.Restaurant.Currency','Order.UserInfo','Rider','Assigner'),
            'contain' => $this->contain,
            'order' => array(
                'RiderOrder.id DESC'
            ),


            'recursive' => 0

        ));


    }

    public function getPendingOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.UserInfo','Rider','Assigner','Order.Restaurant.Currency'),
            'contain' => $this->contain,
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.accept_reject_status' => 0,
                'Order.status'=> array(0, 1,3,4)



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }

    public function getActiveOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.Currency','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),
            'contain' => $this->contain,
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.accept_reject_status' => 1,
                'Order.status'=> array(0, 1,3,4)



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }



    public function getRejectedOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.Restaurant.Currency','Order.UserInfo','Rider','Assigner'),
            'contain' => $this->contain,
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.accept_reject_status' => 2



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }

    public function getCompletedOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            //'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.Restaurant.Currency','Order.UserInfo','Rider','Assigner'),
            'contain' => $this->contain,
            'conditions' => array(

                'RiderOrder.rider_user_id' => $rider_user_id,
                'RiderOrder.accept_reject_status' => 3



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }

    public function getAllAcceptedOrders()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            // 'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.Currency','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),
            'contain' => $this->contain,
            'conditions' => array(


                'RiderOrder.accept_reject_status' => 1



            ),
            'RiderOrder' => array(
                'RiderOrder.id DESC'
            ),

            'recursive' => 0

        ));


    }



    public function getRiderOrdersBetweenTwoDates($user_id, $min_date, $max_date)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'joins' => array(
                array(
                    'table' => 'rider_track_order',
                    'alias' => 'RiderTrackOrder',
                    'conditions' => 'RiderTrackOrder.order_id = RiderOrder.order_id',

                    'type' => 'LEFT'

                ),


                array(
                    'table' => 'order',
                    'conditions' => 'order.id = RiderOrder.order_id',

                    'type' => 'LEFT'

                ),
                array(
                    'table' => 'rider_rating',
                    'alias' => 'RiderRating',
                    'conditions' => 'RiderRating.order_id = RiderOrder.order_id',

                    'type' => 'LEFT'

                )

            ),
            // 'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),

            'contain' => array(
        'Order.Restaurant',
        'Order.Restaurant.Currency',
        'Order.Restaurant.RestaurantLocation',
        'Order.Address',

    ),
            'fields' => array(
        'RiderOrder.*',
        'RiderTrackOrder.*',
        'Order.*',
        'RiderRating.*'
    ),


            'conditions' => array(



                'RiderOrder.created >=' => $min_date,
                'RiderOrder.created <=' => $max_date,
                'RiderOrder.rider_user_id' => $user_id,
                array('not' => array(
                    'RiderTrackOrder.delivery_time'=> "0000-00-00 00:00:00"

                ))

            ),

            'order'=> array('RiderOrder.order_id' => 'DESC'),


            'recursive' => 0

        ));
    }





    public function updateRiderResponse($status, $order_id)
    {

        return $this->updateAll(array(
            'RiderOrder.accept_reject_status' => $status
        ), array(
            'RiderOrder.order_id' => $order_id
        ));

    }

    public function isRiderOrderExistAgainstOrder($order_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderOrder.order_id' => $order_id


            )
        ));
    }

    public function getRiderDetailsAgainstOrderID($order_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => $this->contain,
            'conditions' => array(


                'RiderOrder.order_id' => $order_id


            )
        ));
    }

    public function getRiderLatLong($order_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => ('Rider'),
            'conditions' => array(


                'RiderOrder.order_id' => $order_id


            )
        ));
    }

    public function getRiderOrderAgainstID($id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderOrder.id' => $id


            )
        ));
    }

    public function getRiderUserID($order_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderOrder.order_id' => $order_id


            )
        ));
    }


    public function getRiderCompletedOrders($user_id)
    {



        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'joins' => array(
                array(
                    'table' => 'rider_track_order',
                    'alias' => 'RiderTrackOrder',
                    'conditions' => 'RiderTrackOrder.order_id = RiderOrder.order_id',

                    'type' => 'LEFT'

                ),


                array(
                    'table' => 'order',
                    'conditions' => 'order.id = RiderOrder.order_id',

                    'type' => 'LEFT'

                ),

                array(
                    'table' => 'rider_rating',
                    'alias' => 'RiderRating',
                    'conditions' => 'RiderRating.order_id = RiderOrder.order_id',

                    'type' => 'LEFT'

                )

            ),

            'conditions' => array(
                'RiderOrder.rider_user_id' => $user_id,
                array('not' => array(
                    'RiderTrackOrder.delivery_time'=> "0000-00-00 00:00:00"

                ))
                //'not' => array('RiderTrackOrder.delivery_time' => null)






            ),
            'contain' => array(
                'Order.Restaurant',
                'Order.Restaurant.Currency',
                'Order.Restaurant.RestaurantLocation',
                'Order.Address'
            ),
            'fields' => array(
                'RiderOrder.*',
                'RiderTrackOrder.*',
                'RiderRating.*',
                'Order.*'
            ),

            'order'=> array('RiderOrder.order_id' => 'DESC'),





        ));
    }

    public function afterFind($results, $primary = false)
    {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {


        if (Lib::multi_array_key_exists('RiderOrder', $results)) {
            // $rider_location = array();


            foreach ($results as $key => $val) {

                if ($val['RiderOrder']['rider_user_id'] !== null) {

                    $rider_location = ClassRegistry::init('RiderLocation')->getRiderLocation($val['RiderOrder']['rider_user_id']);

                    if (count($rider_location) > 0) {

                        $results[$key]['RiderOrder']['RiderLocation'] = $rider_location[0]['RiderLocation'];

                    }

                    if (Lib::multi_array_key_exists('Order', $results) && Lib::multi_array_key_exists('Restaurant', $results)) {
                        $collection_time = Lib::addMinutesInDateTime($val['RiderOrder']['assign_date_time'], $val['Order']['Restaurant']['preparation_time']);

                        $results[$key]['RiderOrder']['EstimateReachingTime']['estimate_collection_time'] = $collection_time;


                        if (array_key_exists("lat", $val['Order']["Address"])) {
                            $lat1 = $val['Order']['Address']['lat'];
                            $long1 = $val['Order']['Address']['long'];
                            $lat2 = $val['Order']['Restaurant']['RestaurantLocation']['lat'];
                            $long2 = $val['Order']['Restaurant']['RestaurantLocation']['long'];
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
                    //getDurationTimeBetweenTwoDistances($lat1,$long1,$lat2,$long2)

                } else {
                    $rider_location = ClassRegistry::init('RiderLocation')->getRiderLocation($val['RiderOrder']['rider_user_id']);
                    // unset($results[$key]['RiderOrder']);
                    // $results['RiderOrder']["id"] = 0;
                }






            }
        }




        return $results;
    }



}


?>
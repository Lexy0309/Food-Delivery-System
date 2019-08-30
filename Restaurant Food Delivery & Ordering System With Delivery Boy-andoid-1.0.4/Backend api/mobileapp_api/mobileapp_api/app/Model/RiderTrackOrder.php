<?php



class RiderTrackOrder extends AppModel
{

    public $useTable = 'rider_track_order';







   public function getRiderTrackOrder($order_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id


            )
        ));
    }


    public function getRiderDeliveredOrderWhoseNotificationHasNotBeenSent($order_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id,
                'RiderTrackOrder.notification'=> 0,
                array('not' => array(
                    'RiderTrackOrder.delivery_time'=> "0000-00-00 00:00:00"

                ))
            )
        ));
    }
    public function isEmptyOnMyWayToHotelTime($order_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id,
                array('not' => array(
                'RiderTrackOrder.on_my_way_to_hotel_time'=> "0000-00-00 00:00:00"

                ))
            )
        ));
    }
    public function isEmptyPickUpTime($order_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id,
                array('not' => array(
                'RiderTrackOrder.pickup_time'=> "0000-00-00 00:00:00"

                ))
            )
        ));
    }

    public function isEmptyOnMyWayToUserTime($order_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id,
                array('not' => array(
                'RiderTrackOrder.on_my_way_to_user_time'=> "0000-00-00 00:00:00"

                ))
            )
        ));
    }
    public function isEmptyDeliveryTime($order_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTrackOrder.order_id'=> $order_id,
                array('not' => array(
                'RiderTrackOrder.delivery_time'=> "0000-00-00 00:00:00"

                ))
            )
        ));
    }

   /* public function getAllRiderOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id'=> $rider_user_id,



            ),
            'RiderOrder' => array('RiderOrder.id DESC'),

            'recursive' => 0

        ));


    }

    public function getPendingOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('Order.Restaurant','Order.Restaurant.RestaurantLocation','Order.Address','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id'=> $rider_user_id,
                'RiderOrder.accept_reject_status'=> 0



            ),
            'RiderOrder' => array('RiderOrder.id DESC'),

            'recursive' => 0

        ));


    }

    public function getActiveOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id'=> $rider_user_id,
                'RiderOrder.accept_reject_status'=> 1



            ),
            'RiderOrder' => array('RiderOrder.id DESC'),

            'recursive' => 0

        ));


    }

    public function getRejectedOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id'=> $rider_user_id,
                'RiderOrder.accept_reject_status'=> 2



            ),
            'RiderOrder' => array('RiderOrder.id DESC'),

            'recursive' => 0

        ));


    }

    public function getCompletedOrders($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('Order.Restaurant','Order.Address','Order.Restaurant.RestaurantLocation','Order.UserInfo','Rider','Assigner'),
            'conditions' => array(

                'RiderOrder.rider_user_id'=> $rider_user_id,
                'RiderOrder.accept_reject_status'=> 3



            ),
            'RiderOrder' => array('RiderOrder.id DESC'),

            'recursive' => 0

        ));


    }

    public function updateRiderResponse($status,$order_id){

        return  $this->updateAll(
            array('RiderOrder.accept_reject_status' => $status),
            array('RiderOrder.order_id' => $order_id)
        );

    }

*/

}


?>
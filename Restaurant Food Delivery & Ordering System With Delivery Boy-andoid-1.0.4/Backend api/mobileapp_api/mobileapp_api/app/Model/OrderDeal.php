<?php


class OrderDeal extends AppModel
{
    public $useTable = 'order_deal';


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

    var $contain = array('PaymentMethod','Address','UserInfo','Restaurant.Currency','Restaurant.Tax');
    var $contain_rider = array('Restaurant.RestaurantLocation','Restaurant.Currency','Restaurant.RestaurantLocation','Restaurant.Tax','PaymentMethod','Address','UserInfo');


    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'OrderDeal.name' => $data['name'],
                'OrderDeal.description'=> $data['description'],
                'OrderDeal.price'=> $data['price'],
                'OrderDeal.payment_method_id' => $data['payment_method_id'],
                'OrderDeal.cod'=> $data['cod'],
                'OrderDeal.address_id'=> $data['address_id'],
                'OrderDeal.user_id'=> $data['user_id'],



            )
        ));
    }

    public function getUserDeals($user_id,$status)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'contain'=>$this->contain,
            'conditions' => array(

                'OrderDeal.user_id' => $user_id,
                'OrderDeal.status' => $status

            )
        ));
    }

    public function getDealOrderDetailBasedOnID($id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain_rider,
            'conditions' => array(



                'OrderDeal.id' => $id


            ),


            'recursive' => 0

        ));
    }

    public function getOrderDetailBasedOnID($id)
    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),
            'contain'=>$this->contain,
            'conditions' => array(



                'OrderDeal.id' => $id


            ),
           

            'recursive' => 0

        ));
    }
    
    

}
?>
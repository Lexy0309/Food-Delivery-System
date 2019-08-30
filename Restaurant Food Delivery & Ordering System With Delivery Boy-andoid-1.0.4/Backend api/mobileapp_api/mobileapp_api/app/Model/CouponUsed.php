<?php


class CouponUsed extends AppModel
{

    public $belongsTo = array(
        'RestaurantCoupon' => array(
            'className' => 'RestaurantCoupon',
            'foreignKey' => 'coupon_id',



        ),

        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id',



        ),
    );
    public $useTable = 'coupon_used';

  public function countCouponUsed($coupon_id)
    {
        return $this->find('count', array(
            'conditions' => array(

                'CouponUsed.coupon_id' => $coupon_id,



            )
        ));
    }
    /*
       public function getRestaurantCoupon($id)
       {
           return $this->find('all', array(
               'conditions' => array(

                   'RestaurantCoupon.id' => $id






               )
           ));
       }*/

}
?>
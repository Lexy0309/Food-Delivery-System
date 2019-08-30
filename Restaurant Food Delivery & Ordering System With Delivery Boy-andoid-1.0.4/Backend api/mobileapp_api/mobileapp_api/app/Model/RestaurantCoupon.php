<?php


class RestaurantCoupon extends AppModel
{

    public $useTable = 'restaurant_coupon';

    public $hasMany = array(
        'CouponUsed' => array(
            'className' => 'CouponUsed',
            'foreignKey' => 'coupon_id',



        ),
    );
    public function isDuplicateRecord($rest_id,$coupon_code)
    {
        return $this->find('count', array(
            'conditions' => array(

                'RestaurantCoupon.restaurant_id' => $rest_id,

                'RestaurantCoupon.coupon_code'=> $coupon_code,




            )
        ));
    }

    public function getRestaurantCoupons($restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCoupon.restaurant_id' => $restaurant_id




                /*SELECT * FROM restaurant_coupon rc
                left  join coupon_used cu on cu.coupon_id=rc.id
                left join `order` o on o.id=cu.order_id
                where rc.coupon_code='123cvg' and o.user_id = 2 and rc.restaurant_id = 1;*/

            )
        ));
    }


    public function getRestaurantCoupon($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCoupon.id' => $id




/*SELECT * FROM restaurant_coupon rc
left  join coupon_used cu on cu.coupon_id=rc.id
left join `order` o on o.id=cu.order_id
where rc.coupon_code='123cvg' and o.user_id = 2 and rc.restaurant_id = 1;*/

            )
        ));
    }

    public function getCouponDetails($restaurant_id,$coupon_code)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCoupon.restaurant_id' => array(0, $restaurant_id),
                'RestaurantCoupon.coupon_code' => $coupon_code




                /*SELECT * FROM restaurant_coupon rc
                left  join coupon_used cu on cu.coupon_id=rc.id
                left join `order` o on o.id=cu.order_id
                where rc.coupon_code='123cvg' and o.user_id = 2 and rc.restaurant_id = 1;*/

            )
        ));
    }
    public function isCouponCodeExistAgainstRestaurant($coupon_code,$restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCoupon.coupon_code' => $coupon_code,
                'RestaurantCoupon.type' => "web",
                'RestaurantCoupon.restaurant_id' => array(0, $restaurant_id),






                /*SELECT * FROM restaurant_coupon rc
                left  join coupon_used cu on cu.coupon_id=rc.id
                left join `order` o on o.id=cu.order_id
                where rc.coupon_code='123cvg' and o.user_id = 2 and rc.restaurant_id = 1;*/

            )
        ));
    }


    public function isCouponCodeExistAgainstRestaurantAndDeviceIsMobileApp($coupon_code,$restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantCoupon.coupon_code' => $coupon_code,
                'RestaurantCoupon.restaurant_id' => array(0, $restaurant_id),
                'RestaurantCoupon.type' => array("ios", "android"),






                /*SELECT * FROM restaurant_coupon rc
                left  join coupon_used cu on cu.coupon_id=rc.id
                left join `order` o on o.id=cu.order_id
                where rc.coupon_code='123cvg' and o.user_id = 2 and rc.restaurant_id = 1;*/

            )
        ));
    }
public function deleteCoupon($restaurant_id,$coupon_id){


    return $this->deleteAll
    (['RestaurantCoupon.restaurant_id'=>$restaurant_id,
            'RestaurantCoupon.id'=>$coupon_id]);

}

    public function ifCouponUsedAgainstRestaurant($user_id,$coupon_code,$restaurant_id){



        /*return $this->query('SELECT * FROM connection
       JOIN user_info ON user_info.user_id = connection.connected_user_id
       LEFT JOIN direct_question ON direct_question.questioner_user_id = user_info.user_id
       LEFT JOIN direct_answer ON direct_answer.answerer_user_id=user_info.user_id
       WHERE connection.user_id='.$user_id);*/

        //$this->Behaviors->attach('Containable');

        return $this->find('count', array(


                'joins' => array(
                    array(
                        'table' => 'coupon_used',
                        'conditions' => 'coupon_used.coupon_id = RestaurantCoupon.id',

                        'type' => 'LEFT'

                    ),


                    array(
                        'table' => 'order',
                        'conditions' => 'order.id = coupon_used.order_id',

                        'type' => 'LEFT'

                    ),
                    /* array(
                    'table' => 'upvote_direct_question',
                    'conditions' => 'upvote_direct_question.direct_question_id = direct_question.direct_question_id',
                    //'fields'

                    'type' => 'LEFT'

                    ),*/
                ),
                //'contain'=>array('UserInfo.DirectAnswer'),
                'conditions' => array(
                    'order.user_id' => $user_id,
                    'RestaurantCoupon.coupon_code' => $coupon_code,
                    'RestaurantCoupon.restaurant_id' => array(0, $restaurant_id)





                ),
                'fields' => array('order.*','coupon_used.*'),


                // 'group'=> array('direct_question_id')


            )
        );
    }

    /*public function checkCouponIfexistAndNotUsed($user_id,$coupon_code)
    {
        return $this->find('all', array(

            'conditions' => array('not exists '.
                '(select id from coupon_used '.
                'where answers.question_id = '.
                'Question.id)'
            )
        ));
    }*/
}
?>


<?php



class RiderTiming extends AppModel
{

    public $useTable = 'rider_timing';

    public $belongsTo = array(



        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'user_id',


        ));


    public function checkDuplicate($user_id,$date)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id,
                 'RiderTiming.date'=> $date


            )
        ));
    }
    public function getRiderTiming($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id



            )
        ));
    }

    public function getAllRidersTimings()
    {
        return $this->find('all', array(
            'order' => 'RiderTiming.id DESC'
        ));
    }

    public function getRiderUpComingShifts($user_id,$date,$time)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id,
                'RiderTiming.confirm'=> 1,
                'RiderTiming.date >='=> $date,
                'RiderTiming.starting_time >='=> $time



            )
        ));
    }

    public function IsExistRiderShift($user_id,$date,$time)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id,
                'RiderTiming.confirm'=> 1,
                'RiderTiming.date '=> $date,
                'RiderTiming.starting_time >='=> $time,
                'RiderTiming.ending_time <='=> $time



            )
        ));
    }

    public function getRiderTimingsAgainstDate($user_id,$date)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id,
                'RiderTiming.date'=> $date



            ),

        ));
    }


    public function getRiderTimingAgainstID($id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTiming.id'=> $id


            )
        ));
    }

    public function getRiderTimingBasedOnDate($user_id,$date)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderTiming.user_id'=> $user_id,
                'RiderTiming.date >='=> $date



            ),
            'order'=>'date DESC',
            'group' => array('RiderTiming.date')
        ));
    }

  /*public function afterFind($results, $primary = false) {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {

        if(Lib::multi_array_key_exists('RiderTiming',$results)){

            foreach ($results as $key => $val) {

                if ($val['RiderTiming']['id'] !== null) {

                    

                }


                $ratings = ClassRegistry::init('RestaurantRating')->getAvgRatings($val['Restaurant']['id']);
                $delivery = ClassRegistry::init('Tax')->getDeliveryFee($val['RestaurantLocation']['country']);

                if (count($ratings) > 0) {
                    $results[$key]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $results[$key]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }

                if(count($delivery) > 0){
                    $distance = intval($val[0]['distance']);
                    $delivery_fee = $delivery[0]['Tax']['delivery_fee_per_mile'] * $distance;
                    $results[$key]['Restaurant']['delivery_fee'] = "$delivery_fee";


                }


            }
        }




        return $results;
    }
*/


}
?>
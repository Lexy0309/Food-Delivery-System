<?php



class OpenShift extends AppModel
{
    public $useTable = 'open_shift';


    public function getOpenShifts()
    {
        return $this->find('all', array(
            'conditions' => array(

                'OpenShift.shift' => 0,



            )
        ));
    }

    public function getOpenShiftDetail($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'OpenShift.id' => $id




            )
        ));
    }

    public function updateOpenShift($user_id,$id)
    {
        return $this->updateAll(
            array( 'OpenShift.rider_user_id' => $user_id,
                'OpenShift.shift' => 1
                ),

            array('OpenShift.id' => $id)
        );
    }

    public function afterFind($results, $primary = false)
    {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {

        if (Lib::multi_array_key_exists('OpenShift', $results)) {

            foreach ($results as $key => $val) {

                       $date = $val['OpenShift']['date'];


                   $results[$key]['OpenShift']['date']  = Lib::getOnlyDateFromDatetime($date);
                  // $results[$key]['ending_datetime'] = Lib::convertDateTimetoFullMonthAndDayNameWithYear($ending_datetime);


            }


        }

        return $results;

    }

}
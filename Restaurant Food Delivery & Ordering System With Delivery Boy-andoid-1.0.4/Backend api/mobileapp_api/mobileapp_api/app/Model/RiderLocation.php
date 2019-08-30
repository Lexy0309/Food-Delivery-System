<?php


class RiderLocation extends AppModel
{

    public $useTable = 'rider_location';



public function getRiderLocation($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RiderLocation.user_id'=> $user_id


            ),
            'order'=>'RiderLocation.id DESC'
        ));
    }

    public function getRiderLocationAgainstID($id)
        {
            return $this->find('all', array(
                'conditions' => array(


                    'RiderLocation.id'=> $id


                ),
              
            ));
        }

}
?>
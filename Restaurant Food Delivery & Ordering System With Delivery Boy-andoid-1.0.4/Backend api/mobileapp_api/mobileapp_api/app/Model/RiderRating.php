
<?php

class RiderRating extends AppModel
{
    public $useTable = 'rider_rating';

    public $belongsTo = array(

        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'user_id',


        ),

        'Rider' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'rider_user_id',


        ),

    );




    public function getAvgRatings($rider_user_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'RiderRating.rider_user_id' => $rider_user_id,


            ),

            'fields'    => array(
                'AVG( RiderRating.star ) AS average',
                'COUNT(RiderRating.id) AS total_ratings'


            ),
            'group' => 'RiderRating.rider_user_id'
        ));


    }

    public function getComments($rider_user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'RiderRating.rider_user_id' => $rider_user_id,


            ),


            'fields'    => array(
                'RiderRating.*',

                'UserInfo.*',



            ),

        ));


    }

    public function getLastInsertedRow($id)
    {
        return $this->find('all', array(
            'conditions' => array(
                'RiderRating.id' => $id,


            ),



        ));


    }

}



?>
<?php



class RestaurantFavourite extends AppModel
{
    public $useTable = 'restaurant_favourite';

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => array('User.id','User.email','User.active')

        ),
        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'user_id',


        ),
        'Restaurant' => array(
            'className' => 'Restaurant',
            'foreignKey' => 'restaurant_id',


        ),

    );

    public function getFavouritesRestaurant($user_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain'=>array('Restaurant.Currency','Restaurant.Tax'),
            'conditions' => array(


                    'RestaurantFavourite.user_id' => $user_id,




            )

        ));


    }

    public function getFavouriteRestaurant($user_id,$restaurant_id)
    {
        
        return $this->find('all', array(

            'conditions' => array(


                'RestaurantFavourite.user_id' => $user_id,
                'RestaurantFavourite.restaurant_id' => $restaurant_id,




            )

        ));


    }

    public function getFavouriteRestaurantDetail($id)
    {
        $this->recursive = -1;
        return $this->find('all', array(

            'conditions' => array(


                'RestaurantFavourite.id' => $id,





            )

        ));


    }

    public function afterFind($results, $primary = false) {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {
        if(Lib::multi_array_key_exists('Restaurant',$results)) {

            foreach ($results as $key => $val) {


                $ratings = ClassRegistry::init('RestaurantRating')->getAvgRatings($val['Restaurant']['id']);

                if (count($ratings) > 0) {
                    $results[$key]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $results[$key]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }


            }


        }
        return $results;
    }
}
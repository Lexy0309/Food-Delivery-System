<?php

App::uses('Lib', 'Utility');

class Restaurant extends AppModel
{
    public $useTable = 'restaurant';
    public $primaryKey = 'id';

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

     'Currency' => array(
         'className'     => 'Currency',
         'foreignKey'    => 'currency_id',

     ),
     'Tax' => array(
         'className'     => 'Tax',
         'foreignKey'    => 'tax_id',

     ),
     'UserAdmin' => array(
         'className'     => 'UserAdmin',
         'foreignKey'    => 'added_by',

     )

    );

    public $hasOne = array(
        'RestaurantLocation' => array(
            'className'     => 'RestaurantLocation',
            'foreignKey'    => 'restaurant_id',

        ),



    );
public $hasMany = array(
        'RestaurantTiming' => array(
            'className' => 'RestaurantTiming',
            'foreignKey' => 'restaurant_id',



        ),

    'RestaurantMenu' => array(
        'className' => 'RestaurantMenu',
        'foreignKey' => 'restaurant_id',



    ),

    'RestaurantRating' => array(
        'className' => 'RestaurantRating',
        'foreignKey' => 'restaurant_id',




    ),

    'Order' => array(
        'className' => 'Order',
        'foreignKey' => 'restaurant_id',




    ),




    );

    var $contain = array('RestaurantTiming','RestaurantRating','RestaurantLocation','Currency','UserInfo','User','Tax');



    public function isDuplicateRecord($user_id,$name,$slogan,$phone,$about)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Restaurant.user_id' => $user_id,
                'Restaurant.name'=> $name,
                'Restaurant.slogan'=> $slogan,

                'Restaurant.phone'=> $phone,
                'Restaurant.about'=> $about,




            )
        ));
    }

    public function getRestaurantDetail($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Restaurant.id' => $id,




            )

        ));


    }
    public function getRestaurantOrders()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain'=> array('Order'),
            'order' => 'Restaurant.id DESC',

            //'fields'=>array('Order.*'),

        ));


    }

    public function getRestaurantID($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Restaurant.user_id' => $user_id,




            )

        ));


    }
    public function getRestaurantDetailInfo($id)
    {

        return $this->find('all', array(
            'conditions' => array(

                'Restaurant.id' => $id,




            ),
            'contain'=>$this->contain,
        ));


    }

    public function getRestaurantDetailInfoSuperAdmin($id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(

                'Restaurant.id' => $id,




            ),
            'contain'=> array('RestaurantTiming','RestaurantLocation','Currency','UserInfo','User','Tax','UserAdmin'),
        ));


    }

    public function getNearByRestaurants($lat,$long,$user_id=null)

    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_favourite',
                    'alias' => 'RestaurantFavourite',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Restaurant.id = RestaurantFavourite.restaurant_id',
                        'RestaurantFavourite.user_id' => $user_id,
                    )
                )
            ),

            'conditions' => array(


                'Restaurant.block'=> 0



            ),

            'contain'=>$this->contain,
            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','Restaurant.*','UserInfo.*','RestaurantLocation.*','Currency.*','Tax.*','RestaurantFavourite.*'),
            'order' => 'Restaurant.promoted DESC','distance',


            'recursive' => 0

        ));


    }

    public function getCurrentCityRestaurants($lat,$long,$user_id=null,$city)

    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_favourite',
                    'alias' => 'RestaurantFavourite',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Restaurant.id = RestaurantFavourite.restaurant_id',
                        'RestaurantFavourite.user_id' => $user_id,

                    )
                ),


            ),
            'conditions' => array(

                'RestaurantLocation.city' => $city,
                'Restaurant.block'=> 0






            ),
            'contain'=>$this->contain,
            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','Restaurant.*','UserInfo.*','RestaurantLocation.*','Currency.*','Tax.*','RestaurantFavourite.*'),
            'order' => array('distance ASC','Restaurant.promoted DESC'),


            'recursive' => 0

        ));


    }

    public function getCurrentCityRestaurantsBasedOnPromoted($lat,$long,$user_id=null,$city)

    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_favourite',
                    'alias' => 'RestaurantFavourite',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Restaurant.id = RestaurantFavourite.restaurant_id',
                        'RestaurantFavourite.user_id' => $user_id,

                    )
                ),


            ),
            'conditions' => array(

                'RestaurantLocation.city' => $city,
                'Restaurant.block'=> 0,
                'Restaurant.promoted'=> 1






            ),
            'contain'=>$this->contain,
            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','Restaurant.*','UserInfo.*','RestaurantLocation.*','Currency.*','Tax.*','RestaurantFavourite.*'),
            'order' => array('Restaurant.promoted DESC'),


            'recursive' => 0

        ));


    }

    public function getCurrentCityRestaurantsBasedOnDistance($lat,$long,$user_id=null,$city)

    {

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_favourite',
                    'alias' => 'RestaurantFavourite',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Restaurant.id = RestaurantFavourite.restaurant_id',
                        'RestaurantFavourite.user_id' => $user_id,

                    )
                ),


            ),
            'conditions' => array(

                'RestaurantLocation.city' => $city,
                'Restaurant.block'=> 0,
                'Restaurant.promoted'=> 0






            ),
            'contain'=>$this->contain,
            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','Restaurant.*','UserInfo.*','RestaurantLocation.*','Currency.*','Tax.*','RestaurantFavourite.*'),
            'order' => array('distance ASC'),


            'recursive' => 0

        ));


    }
    public function getAllRestaurants()
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'contain'=>array('User','UserInfo'),




            'recursive' => 0

        ));


    }

    public function getRestaurantsAgainstSpeciality($speciality,$lat,$long,$user_id=null)
    {



        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_favourite',
                    'alias' => 'RestaurantFavourite',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Restaurant.id = RestaurantFavourite.restaurant_id',
                        'RestaurantFavourite.user_id' => $user_id,
                    )
                )
            ),
            'conditions' => array(

                'Restaurant.speciality' => $speciality,






            ),

            'contain'=>$this->contain,
            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','Restaurant.*','UserInfo.*','RestaurantLocation.*','Currency.*','Tax.*','RestaurantFavourite.*'),
            'order' => 'Restaurant.promoted DESC','distance',


            'recursive' => 0

        ));
       

    }

    public function getRestaurantSpecialities()
    {

        return $this->find('all', array(


            'contain'=>false,

            'fields' => array('DISTINCT Restaurant.speciality'),

            'recursive' => 0,
            'group'=>'Restaurant.speciality'


        ));


    }
    public function searchRestaurant($keyword){

        return $this->find('all', array(

            'conditions' => array(
                'OR' => array(

                    array('Restaurant.name LIKE' => '%'.$keyword.'%'),
                    array('Restaurant.about LIKE' => '%'.$keyword.'%'),
                    array('Restaurant.slogan LIKE' => '%'.$keyword.'%'),

                ))
        ));

    }
    public function getRestaurantMenusForMobile($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'contain'=>array('RestaurantMenu' => array(
        'conditions' => array(
            'RestaurantMenu.has_menu_item' => 1,
            'RestaurantMenu.active' => 1 // <-- Notice this addition
        ), 'order' => 'RestaurantMenu.index ASC',

                ),'RestaurantMenu.RestaurantMenuItem'=> array(
                'conditions' => array(

                    'RestaurantMenuItem.active' => 1 // <-- Notice this addition
                ),

            ),'RestaurantMenu.RestaurantMenuItem.RestaurantMenuExtraSection.RestaurantMenuExtraItem','Currency','Tax'),
            'conditions' => array(

                'Restaurant.id' => $restaurant_id,






            ),



            'recursive' => 0

        ));


    }

    public function getRestaurantMenusForMobiletest($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'contain'=>array('RestaurantMenu' => array(
                'conditions' => array(
                    'RestaurantMenu.has_menu_item' => 1,
                    'RestaurantMenu.active' => 1 // <-- Notice this addition
                ), 'order' => 'RestaurantMenu.index ASC',

            ),'Currency','Tax'),
            'conditions' => array(

                'Restaurant.id' => $restaurant_id,






            ),



            'recursive' => 0

        ));


    }
    public function getRestaurantMenusForWeb($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(


            'contain'=>array('RestaurantMenu' => array(
                'conditions' => array(

                ), 'order' => 'RestaurantMenu.index ASC',

            ),'RestaurantMenu.RestaurantMenuItem.RestaurantMenuExtraSection.RestaurantMenuExtraItem','Currency','Tax'),
            'conditions' => array(

                'Restaurant.id' => $restaurant_id,






            ),


            'recursive' => 0

        ));


    }


   /* public function totalAnswersAgainstQuestion($question_id){

        return $this->find('count', array(
            'conditions' => array(
                'Answer.question_id' => $question_id,


            )
        ));


    }*/

    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name']) && isset($this->data[$this->alias]['slogan']) && isset($this->data[$this->alias]['about'])) {
            $name = strtolower($this->data[$this->alias]['name']);
            $slogan = strtolower($this->data[$this->alias]['slogan']);
            $about = strtolower($this->data[$this->alias]['about']);




            //$this->data['Restaurant']['name'] = ucwords($name);
            //$this->data['Restaurant']['slogan'] = ucwords($slogan);
            $this->data['Restaurant']['about'] = ucwords($about);

        }
        return true;
    }



    public function afterFind($results, $primary = false) {
        //$this->loadModel('RestaurantRating');
       // if (array_key_exists('RestaurantFavourite', $results)) {

            if(Lib::multi_array_key_exists('RestaurantFavourite',$results)){
         
            foreach ($results as $key => $val) {

                if ($val['RestaurantFavourite']['id'] !== null) {

                    $results[$key]['Restaurant']['favourite'] = $val['RestaurantFavourite']['favourite'];
                } else {

                    $results[$key]['Restaurant']['favourite'] = "0";
                }


                $ratings = ClassRegistry::init('RestaurantRating')->getAvgRatings($val['Restaurant']['id']);
                $delivery = ClassRegistry::init('Tax')->getDeliveryFee($val['RestaurantLocation']['country']);

                if (count($ratings) > 0) {
                    $results[$key]['TotalRatings']["avg"] = $ratings[0]['average'];
                    $results[$key]['TotalRatings']["totalRatings"] = $ratings[0]['total_ratings'];
                }

                if(count($delivery) > 0){
                    $distance = intval($val[0]['distance']);
                    $delivery_fee = $delivery[0]['Tax']['delivery_fee_per_km'] * $distance;
                    $results[$key]['Restaurant']['delivery_fee'] = "$delivery_fee";


                }else{
                    $results[$key]['Restaurant']['delivery_fee'] = "0";

                }

                if ($val['Restaurant']['tax_free'] == 1) {
                    $results[$key]['Tax']['tax'] = "0";
                }


            }
        }




        return $results;
    }




}
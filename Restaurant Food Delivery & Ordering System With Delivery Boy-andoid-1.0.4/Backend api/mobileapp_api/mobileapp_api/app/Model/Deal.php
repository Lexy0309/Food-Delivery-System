<?php

App::uses('Lib', 'Utility');

class Deal extends AppModel
{
    public $useTable = 'deal';

    public $belongsTo = array(

        'Restaurant' => array(
            'className' => 'Restaurant',
            'foreignKey' => 'restaurant_id',


        )
    );
    var $contain = array('Restaurant.Currency','Restaurant.Tax');

    public function  isDuplicateRecord($restaurant_id,$name,$price,$description)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Deal.restaurant_id' => $restaurant_id,

                'Deal.name'=> $name,

                'Deal.price' => $price,

                'Deal.description'=> $description,







            )
        ));
    }

    public function getDeal($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Deal.id' => $id






            )
        ));
    }

    public function deleteDeal($id,$restaurant_id)
    {
        return $this->deleteAll(
            [
                'Deal.id' => $id,
                'Deal.restaurant_id' => $restaurant_id
            ],
            false # <- single delete statement please
        );
    }

    public function getDealDetail($id,$restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Deal.restaurant_id' => $restaurant_id,
                'Deal.id' => $id






            )
        ));
    }

    public function getRestaurantDeals($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'contain'=>$this->contain,
            'conditions' => array(

                'Deal.restaurant_id' => $restaurant_id

            )
        ));
    }



    public function getDeals($lat,$long)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_location',
                    'alias' => 'RestaurantLocation',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Deal.restaurant_id = RestaurantLocation.restaurant_id',

                    )
                )
            ),
           'conditions' => array(

               //'RestaurantLocation.city' => $city
               'Restaurant.block'=>0



            ),
            'contain'=>$this->contain,

            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','RestaurantLocation.*','Restaurant.*','Deal.*'),
            'order' => 'Deal.promoted DESC','distance',


            //'order'=>'Deal.id DESC',
        ));
    }

    public function getDealsAgainstCity($lat,$long,$city)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

            'joins' => array(
                array(
                    'table' => 'restaurant_location',
                    'alias' => 'RestaurantLocation',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Deal.restaurant_id = RestaurantLocation.restaurant_id',

                    )
                )
            ),
            'conditions' => array(

                'RestaurantLocation.city' => $city,
                'Restaurant.block' => 0



            ),
            'contain'=>$this->contain,

            'fields'=>array('( 3959 * ACOS( COS( RADIANS('.$lat.') ) * COS( RADIANS( RestaurantLocation.lat ) )
                    * COS( RADIANS(RestaurantLocation.long) - RADIANS('.$long.')) + SIN(RADIANS('.$lat.'))
                    * SIN( RADIANS(RestaurantLocation.lat)))) AS distance','RestaurantLocation.*','Restaurant.*','Deal.*'),
            'order' => 'Deal.promoted DESC','distance',


            //'order'=>'Deal.id DESC',
        ));
    }


    public function afterFind($results, $primary = false) {
        //$this->loadModel('RestaurantRating');
        // if (array_key_exists('RestaurantFavourite', $results)) {

        if(Lib::multi_array_key_exists('RestaurantLocation',$results)){

            foreach ($results as $key => $val) {




                 $delivery = ClassRegistry::init('Tax')->getDeliveryFee($val['RestaurantLocation']['country']);



                if(count($delivery) > 0){
                    $distance = intval($val[0]['distance']);
                    $delivery_fee = $delivery[0]['Tax']['delivery_fee_per_km'] * $distance;
                    $results[$key]['Restaurant']['delivery_fee'] = "$delivery_fee";


                }
            }
        }
        return $results;
    }

    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name']) && isset($this->data[$this->alias]['description'])) {
            $name = strtolower($this->data[$this->alias]['name']);
            $description = strtolower($this->data[$this->alias]['description']);





            $this->data['Deal']['name'] = ucwords($name);
            $this->data['Deal']['description'] = ucwords($description);


        }
        return true;
    }


}
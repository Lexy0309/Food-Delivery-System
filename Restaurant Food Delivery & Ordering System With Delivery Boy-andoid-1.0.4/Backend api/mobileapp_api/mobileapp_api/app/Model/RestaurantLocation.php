<?php


class RestaurantLocation extends AppModel
{

    public $useTable = 'restaurant_location';
    public $primaryKey = 'restaurant_id';



    public function getRestaurantLatLong($restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantLocation.restaurant_id' => $restaurant_id,




            ),
            'fields'=>array('RestaurantLocation.lat','RestaurantLocation.long','RestaurantLocation.city'),

        ));


    }


    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['city']) && isset($this->data[$this->alias]['state']) && isset($this->data[$this->alias]['country'])) {
            $city = strtolower($this->data[$this->alias]['city']);
            $state = strtolower($this->data[$this->alias]['state']);
            $country = strtolower($this->data[$this->alias]['country']);




            $this->data['RestaurantLocation']['city'] = ucwords($city);
            $this->data['RestaurantLocation']['state'] = ucwords($state);
            $this->data['RestaurantLocation']['country'] = ucwords($country);

        }
        return true;
    }

}
?>
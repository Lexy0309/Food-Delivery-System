
<?php

class RestaurantRequest extends AppModel
{
    public $useTable = 'restaurant_request';


    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'RestaurantRequest.restaurant_name' => $data['restaurant_name'],
                'RestaurantRequest.contact_name'=> $data['contact_name'],
                'RestaurantRequest.phone'=> $data['phone'],

                'RestaurantRequest.email'=> $data['email']




            )
        ));
    }

    public function getLastInsertRow($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RestaurantRequest.id' => $id,




            )

        ));


    }
    public function getAllRestaurantRequests()
    {
        return $this->find('all', array(
            'order'=>'RestaurantRequest.id DESC'

        ));


    }
}



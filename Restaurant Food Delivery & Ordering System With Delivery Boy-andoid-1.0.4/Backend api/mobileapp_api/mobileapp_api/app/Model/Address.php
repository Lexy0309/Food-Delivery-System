<?php



class Address extends AppModel
{

 public $useTable = 'address';


    public function getUserDeliveryAddresses($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Address.user_id' => $user_id,




            ),'order' => 'Address.id DESC',

        ));


    }

    public function getAddressDetail($address_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Address.id' => $address_id,




            )

        ));


    }

    public function isDuplicateRecord($user_id,$street,$city,$apartment,$state,$country)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Address.user_id' => $user_id,
                'Address.street'=> $street,
                'Address.city'=> $city,
                'Address.apartment' => $apartment,
                'Address.state'=> $state,
                'Address.country'=> $country,



            )
        ));
    }

    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['street']) && isset($this->data[$this->alias]['city'])
            && isset($this->data[$this->alias]['apartment']) && isset($this->data[$this->alias]['state']) && isset($this->data[$this->alias]['country'])) {

            $name = strtolower($this->data[$this->alias]['street']);
            $city = strtolower($this->data[$this->alias]['city']);
            $apartment = strtolower($this->data[$this->alias]['apartment']);
            $state = strtolower($this->data[$this->alias]['state']);
            $country = strtolower($this->data[$this->alias]['country']);





            $this->data['Address']['name'] = ucwords($name);
            $this->data['Address']['city'] = ucwords($city);
            $this->data['Address']['apartment'] = ucwords($apartment);
            $this->data['Address']['state'] = ucwords($state);
            $this->data['Address']['country'] = ucwords($country);

        }
        return true;
    }
}


?>
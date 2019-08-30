<?php



class Tax extends AppModel
{
    public $useTable = 'tax';


    public function isDuplicateRecord($city,$state,$country)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Tax.city' => $city,
                'Tax.state'=> $state,
                'Tax.country'=> $country,





            )
        ));
    }

    public function getTaxID($state,$country)
    {
        return $this->find('all', array(
            'conditions' => array(


                'Tax.state LIKE'=> "%".$state."%",
                'Tax.country LIKE'=> "%".$country."%",





            )
        ));
    }

    public function getTaxDetail($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Tax.id' => $id,






            )
        ));
    }


    public function getTaxes()
    {
        return $this->find('all');
    }

    public function getCountries()
    {
        return $this->find('all',array(

            'order' => 'country ASC',

            'fields' => array('Tax.country','Tax.country_code'),
            'group' => array('Tax.country')

        ));

    }
    public function getCities()
    {
        return $this->find('all',array(

            'order' => 'city ASC',

            'fields' => array('Tax.city','Tax.state','Tax.country'),
            'group' => array('Tax.city')

        ));

    }

    public function getStates()
    {
        return $this->find('all',array(

            'order' => 'state ASC',

            'fields' => array('Tax.state'),
            'group' => array('Tax.state')

        ));

    }

    public function getDeliveryFee($country)
    {
        return $this->find('all',array(

            'fields' => array('Tax.delivery_fee_per_km'),

            'conditions' => array(



                'Tax.country LIKE'=> "%".$country."%"





            )
        ));

    }

   
    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['city'])
           && isset($this->data[$this->alias]['state']) && isset($this->data[$this->alias]['country'])) {


            $city = strtolower($this->data[$this->alias]['city']);

            $state = strtolower($this->data[$this->alias]['state']);
            $country = strtolower($this->data[$this->alias]['country']);






            $this->data['Tax']['city'] = ucwords($city);

            $this->data['Tax']['state'] = ucwords($state);
            $this->data['Tax']['country'] = ucwords($country);

        }
        return true;
    }

}
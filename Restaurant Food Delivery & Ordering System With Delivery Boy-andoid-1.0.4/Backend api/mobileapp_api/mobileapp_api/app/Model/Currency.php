<?php

class Currency extends AppModel
{
    public $useTable = 'currency';


    public function getAllCurrency()
    {


        return $this->find('all');
    }

    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Currency.country' => $data['country'],
                'Currency.currency'=> $data['currency'],
                'Currency.code'=> $data['code'],
                'Currency.symbol'=> $data['symbol']





            )
        ));
    }
    
     public function getCurrencyID($country)
    {
        return $this->find('all', array(
            'conditions' => array(


               
                'Currency.country LIKE'=> "%".$country."%",





            )
        ));
    }

    public function getCurrencyDetail($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Currency.id' => $id,






            )
        ));
    }


    public function getCurrencies()
    {
        return $this->find('all');
    }



    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['currency'])
            && isset($this->data[$this->alias]['country']) && isset($this->data[$this->alias]['code'])) {


            $currency = strtolower($this->data[$this->alias]['currency']);

            $country = strtolower($this->data[$this->alias]['country']);







            $this->data['Currency']['city'] = ucwords($currency);


            $this->data['Currency']['country'] = ucwords($country);

        }
        return true;
    }
}
?>
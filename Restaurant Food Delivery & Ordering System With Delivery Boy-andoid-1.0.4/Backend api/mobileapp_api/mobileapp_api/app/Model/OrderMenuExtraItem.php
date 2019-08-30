<?php



class OrderMenuExtraItem extends AppModel
{
    public $useTable = 'order_menu_extra_item';


    /*public $belongsTo = array(

        'RestaurantMenuExtraItem' => array(
            'className' => 'RestaurantMenuExtraItem',
            'foreignKey' => 'restaurant_menu_extra_item_id',


        ),

    );*/


    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name'])){

            $name = strtolower($this->data[$this->alias]['name']);


            $this->data['OrderMenuExtraItem']['name'] = ucwords($name);



        }
        return true;
    }


}
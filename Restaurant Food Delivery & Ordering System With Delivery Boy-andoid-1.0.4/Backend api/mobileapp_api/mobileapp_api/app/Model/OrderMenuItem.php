<?php



class OrderMenuItem extends AppModel
{
    public $useTable = 'order_menu_item';

    /*public $belongsTo = array(

        'RestaurantMenuItem' => array(
            'className' => 'RestaurantMenuItem',
            'foreignKey' => 'restaurant_menu_item_id',


        ),

    );*/

    public $hasMany = array(
        'OrderMenuExtraItem' => array(
            'className' => 'OrderMenuExtraItem',
            'foreignKey' => 'order_menu_item_id',



        ),
    );


    public function getMenuItem($order_id){
        //$this->Behaviors->attach('Containable');
        return $this->find('first', array(
            'conditions' => array(
                'OrderMenuItem.order_id' => $order_id,

            ),
            'contain'=>false,



            'recursive' => -1


        ));

    }


    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name'])){

            $name = strtolower($this->data[$this->alias]['name']);


            $this->data['OrderMenuItem']['name'] = ucwords($name);
            


        }
        return true;
    }
}
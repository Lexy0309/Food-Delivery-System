<?php 


class RestaurantMenu extends AppModel
{

 public $useTable = 'restaurant_menu';

    public $hasMany = array(
        'RestaurantMenuItem' => array(
            'className' => 'RestaurantMenuItem',
            'foreignKey' => 'restaurant_menu_id',



        ),
    );

    public function isDuplicateRecord($name,$description,$restaurant_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RestaurantMenu.name'=> $name,
                'RestaurantMenu.description'=> $description,

                'RestaurantMenu.restaurant_id'=> $restaurant_id



            )
        ));
    }

    public function getMainMenu($restaurant_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain'=>array('RestaurantMenuItem'),
            'conditions' => array(

               'RestaurantMenu.restaurant_id'=> $restaurant_id



            ),
            'order' => 'RestaurantMenu.index ASC',
        ));
    }

    public function getMainMenuFromID($id)
    {

        return $this->find('all', array(

            'conditions' => array(

                'RestaurantMenu.id'=> $id



            )
        ));
    }


    public function deleteMainMenu($menu_id,$restaurant_id){


        return $this->deleteAll([
            'RestaurantMenu.restaurant_id'=> $restaurant_id,
            'RestaurantMenu.id'=>$menu_id]);

    }

    public function removeMainMenu($menu_id,$restaurant_id,$active){

        return $this->updateAll(
            array('RestaurantMenu.active' => $active),
            array('RestaurantMenu.restaurant_id'=> $restaurant_id,
                  'RestaurantMenu.id'=>$menu_id)
        );


    }




   /* public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name']) && isset($this->data[$this->alias]['description'])) {
            $name = strtolower($this->data[$this->alias]['name']);
            $description = strtolower($this->data[$this->alias]['description']);



            $this->data['RestaurantMenu']['name'] = ucwords($name);
            $this->data['RestaurantMenu']['description'] = ucwords($description);

        }
        return true;
    }
*/




}

    ?>
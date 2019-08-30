<?php



class RestaurantMenuExtraSection extends AppModel
{
    public $useTable = 'restaurant_menu_extra_section';

    public $belongsTo = array(

        'Restaurant' => array(
            'className' => 'Restaurant',
            'foreignKey' => 'restaurant_id',


        ),
    );
  public $hasMany = array(
        'RestaurantMenuExtraItem' => array(
            'className' => 'RestaurantMenuExtraItem',
            'foreignKey' => 'restaurant_menu_extra_section_id',


        )
    );
    public function isDuplicateRecord($name, $menu_id,$restaurant_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.name' => $name,


                'RestaurantMenuExtraSection.restaurant_id' => $restaurant_id,
                'RestaurantMenuExtraSection.restaurant_menu_item_id' => $menu_id


            )
        ));
    }

    public function getRecentlyAddedSection($id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.id' => $id





            )
        ));
    }

    public function getSections($restaurant_menu_item_id=array())
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.restaurant_menu_item_id in' => $restaurant_menu_item_id





            )
        ));
    }

    public function getSectionsAgainstRestaurantMenuItem($restaurant_menu_item_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.restaurant_menu_item_id' => $restaurant_menu_item_id





            )
        ));
    }

    public function getSectionsAgainstRestaurantMenuItemMobile($restaurant_menu_item_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.restaurant_menu_item_id' => $restaurant_menu_item_id,
                'RestaurantMenuExtraSection.active' => 1





            )
        ));
    }
    public function removeSections($restaurant_menu_item_id=array(),$active)
    {



        return $this->updateAll(
            array('RestaurantMenuExtraSection.active' => $active),
            array('RestaurantMenuExtraSection.restaurant_menu_item_id' => $restaurant_menu_item_id)
        );



    }
    public function deleteSections($restaurant_menu_item_id=array())
    {

        return $this->deleteAll([
            'RestaurantMenuExtraSection.restaurant_menu_item_id in' => $restaurant_menu_item_id]);


    }

    public function removeSectionAgainstMenuItemID($restaurant_menu_item_id,$active)
    {



        return $this->updateAll(
            array('RestaurantMenuExtraSection.active' => $active),
            array('RestaurantMenuExtraSection.restaurant_menu_item_id' => $restaurant_menu_item_id)
        );


    }

    public function deleteSectionAgainstMenuItemID($restaurant_menu_item_id)
    {

        return $this->deleteAll([
            'RestaurantMenuExtraSection.restaurant_menu_item_id' => $restaurant_menu_item_id]);


    }
    public function deleteSectionAgainstID($id)
    {

        return $this->deleteAll([
            'RestaurantMenuExtraSection.id' => $id]);


    }
    public function removeSectionAgainstID($id,$active)
    {



        return $this->updateAll(
            array('RestaurantMenuExtraSection.active' => $active),
            array( 'RestaurantMenuExtraSection.id' => $id)
        );


    }

    public function getAllRestaurantSectionNames($restaurant_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraSection.restaurant_id' => $restaurant_id





            )
        ));
    }

    public function getSectionsWithItems($restaurant_id,$menu_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(


              /*  'joins' => array(
                    array(
                        'table' => 'restaurant_menu_extra_item',
                        'alias'=>'RestaurantMenuExtraItem',
                        'conditions' => 'RestaurantMenuExtraSection.id = RestaurantMenuExtraItem.restaurant_menu_extra_section_id',

                        'type' => 'LEFT'

                    )

                ),*/
                //'contain'=>array('UserInfo.DirectAnswer'),
                'conditions' => array(
                    'RestaurantMenuExtraSection.restaurant_id' => $restaurant_id,
                    'RestaurantMenuExtraSection.active' => 1,
                   'RestaurantMenuExtraSection.restaurant_menu_item_id' => $menu_id





                ),
         'contain'=>array('RestaurantMenuExtraItem','Restaurant.Currency','Restaurant.Tax'),



            )
        );


    }

    public function countRequiredOne($restaurant_id,$menu_id)
    {


        return $this->find('count', array(



                'conditions' => array(
                    'RestaurantMenuExtraSection.restaurant_id' => $restaurant_id,
                    'RestaurantMenuExtraSection.restaurant_menu_item_id' => $menu_id,
                    'RestaurantMenuExtraSection.required' => 1





                ),




            )
        );


    }

    /*public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name'])) {
            $name = strtolower($this->data[$this->alias]['name']);



            $this->data['RestaurantMenuExtraSection']['name'] = ucwords($name);

        }
        return true;
    }*/

}
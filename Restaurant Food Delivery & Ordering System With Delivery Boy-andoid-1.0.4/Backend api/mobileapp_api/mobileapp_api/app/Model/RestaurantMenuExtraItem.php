<?php



class RestaurantMenuExtraItem extends AppModel
{
    public $useTable = 'restaurant_menu_extra_item';

    public $belongsTo = array(
        'RestaurantMenuExtraSection' => array(
            'className' => 'RestaurantMenuExtraSection',
            'foreignKey' => 'restaurant_menu_extra_section_id',


        )
    );


    public function isDuplicateRecord($name,$price,$section_id)
    {
        return $this->find('count', array(
            'conditions' => array(


                'RestaurantMenuExtraItem.name'=> $name,
                


                'RestaurantMenuExtraItem.price'=> $price,
                'RestaurantMenuExtraItem.restaurant_menu_extra_section_id' => $section_id



            )
        ));
    }

    public function getMenuExtraItemFromID($id)
    {

        return $this->find('all', array(

            'conditions' => array(

                'RestaurantMenuExtraItem.id'=> $id



            )
        ));
    }

    public function getExtraItems($restaurant_menu_extra_section_id=array())
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraItem.restaurant_menu_extra_section_id in' => $restaurant_menu_extra_section_id





            )
        ));
    }
    public function getExtraItemsMobile($restaurant_menu_extra_section_id)
    {
        return $this->find('all', array(
            'conditions' => array(


                'RestaurantMenuExtraItem.restaurant_menu_extra_section_id' => $restaurant_menu_extra_section_id,
                'RestaurantMenuExtraItem.active' =>1





            )
        ));
    }
public function removeMenuExtraItems($restaurant_menu_extra_section_id = array(),$active){

   return $this->updateAll(
        array('RestaurantMenuExtraItem.active' => $active),
        array('RestaurantMenuExtraItem.restaurant_menu_extra_section_id' => $restaurant_menu_extra_section_id)
    );
}


    public function deleteMenuExtraItems($restaurant_menu_extra_section_id=array())
    {

        return $this->deleteAll([
            'RestaurantMenuExtraItem.restaurant_menu_extra_section_id in' => $restaurant_menu_extra_section_id]);



    }

    public function removeMenuExtraItemAgainstSectionID($restaurant_menu_extra_section_id,$active)
    {

        return $this->updateAll(
            array('RestaurantMenuExtraItem.active' => $active),
            array( 'RestaurantMenuExtraItem.restaurant_menu_extra_section_id' => $restaurant_menu_extra_section_id)
        );




    }

    public function deleteMenuExtraItemAgainstSectionID($restaurant_menu_extra_section_id)
    {

        return $this->deleteAll([
            'RestaurantMenuExtraItem.restaurant_menu_extra_section_id' => $restaurant_menu_extra_section_id]);



    }

    public function deleteMenuExtraItemAgainstID($id)
    {

        return $this->deleteAll([
            'RestaurantMenuExtraItem.id' => $id]);



    }

    public function removeMenuExtraItemAgainstID($id,$active)
    {

        return $this->updateAll(
            array('RestaurantMenuExtraItem.active' => $active),
            array( 'RestaurantMenuExtraItem.id' => $id)
        );

    }

    public function getMenuExtraItems($restaurant_menu_item_id)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain'=>array('RestaurantMenuExtraSection.RestaurantMenuExtraItem'),
            'conditions' => array(

                'RestaurantMenuExtraItem.restaurant_menu_item_id'=> $restaurant_menu_item_id



            )
        ));
    }

    public function getSectionsWithItems($restaurant_id,$menu_id)
    {
        $this->Behaviors->attach('Containable');

        return $this->find('all', array(


                'joins' => array(
                    array(
                        'table' => 'restaurant_menu_extra_section',

                        'conditions' => ' RestaurantMenuExtraItem.restaurant_menu_extra_section_id = restaurant_menu_extra_section.id',

                        'type' => 'LEFT'

                    )
                    /* array(
                    'table' => 'upvote_direct_question',
                    'conditions' => 'upvote_direct_question.direct_question_id = direct_question.direct_question_id',
                    //'fields'

                    'type' => 'LEFT'

                    ),*/
                ),
                //'contain'=>array('UserInfo.DirectAnswer'),
                'conditions' => array(
                    'restaurant_menu_extra_section.restaurant_id' => $restaurant_id,
                    'RestaurantMenuExtraItem.restaurant_menu_item_id' => $menu_id





                ),
                  'contain'=>array('RestaurantMenuExtraSection.RestaurantMenuExtraItem'),
              //'fields' => array('RestaurantMenuExtraSection.*','RestaurantMenuExtraItem.*',),


                // 'group'=> array('direct_question_id')


            )
        );

        /*

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(

           'contain'=>array('RestaurantMenuExtraItem' => array(
               'conditions' => array(
                   'RestaurantMenuExtraItem.restaurant_menu_item_id' == $menu_id  // <-- Notice this addition
               ))),

            'conditions' => array(


                'RestaurantMenuExtraSection.restaurant_id' => $restaurant_id,
                'RestaurantMenuExtraItem.restaurant_menu_item_id' => $menu_id






            )
        ));*/
    }

   /* public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name'])) {
            $name = strtolower($this->data[$this->alias]['name']);
            



            $this->data['RestaurantMenuExtraItem']['name'] = ucwords($name);
          

        }
        return true;
    }
*/

}


<?php



class AppSlider extends AppModel
{

    public $useTable = 'app_slider';


    public function getImages()
    {
        return $this->find('all');


    }


    public function getImageDetail($id)
    {
        return $this->find('all', array(


            // 'contain' => array('OrderMenuItem', 'Restaurant', 'OrderMenuItem.OrderMenuExtraItem', 'PaymentMethod', 'Address','UserInfo','RiderOrder.Rider'),

            'conditions' => array(



                'AppSlider.id' => $id


            ),
            ));


    }

    public function deleteAppSlider($id)
    {
        return $this->deleteAll(
            [
                'AppSlider.id' => $id
               
            ],
            false # <- single delete statement please
        );
    }
}

?>
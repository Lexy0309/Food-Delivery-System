<?php

class Subscriber extends AppModel
{

    public $useTable = 'subscriber';


    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'Subscriber.email' => $data['email']

            )
        ));
    }


    public function getLastInsertRow($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'Subscriber.id' => $id

            )
        ));
    }

}

?>

<?php

class RiderRequest extends AppModel
{
    public $useTable = 'rider_request';


    public function isDuplicateRecord($data)
    {
        return $this->find('count', array(
            'conditions' => array(

                'RiderRequest.first_name' => $data['first_name'],
                'RiderRequest.last_name'=> $data['last_name'],
                'RiderRequest.email'=> $data['email'],

                'RiderRequest.phone'=> $data['phone'],
                'RiderRequest.city'=> $data['city'],
                'RiderRequest.state'=> $data['state'],

                'RiderRequest.country'=> $data['country']




            )
        ));
    }

    public function getLastInsertRow($id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'RiderRequest.id' => $id,




            )

        ));


    }

    public function getAllRiderRequests()
    {
        return $this->find('all', array(
            'order'=>'RiderRequest.id DESC'

        ));


    }

}



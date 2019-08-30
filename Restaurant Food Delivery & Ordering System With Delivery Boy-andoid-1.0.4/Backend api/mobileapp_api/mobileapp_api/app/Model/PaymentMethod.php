<?php



class PaymentMethod extends AppModel
{
    public $useTable = 'payment_method';



    public function isUserStripeCustIDExist($user_id){

        return $this->find('count', array(
            'conditions' => array(
                'PaymentMethod.user_id' => $user_id,
                'not'=>array( 'PaymentMethod.stripe' => "",
                )),
        ));

    }

    public function getUserCards($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'PaymentMethod.user_id' => $user_id,




            ),
            'fields'=>array('stripe','id'),
        ));


    }

}
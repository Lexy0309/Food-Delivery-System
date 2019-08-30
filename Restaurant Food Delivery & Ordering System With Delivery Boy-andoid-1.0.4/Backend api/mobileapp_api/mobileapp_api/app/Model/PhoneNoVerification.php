<?php



class PhoneNoVerification extends AppModel
{
    public $useTable = 'phone_no_verification';



 public function verifyCode($phone_no,$code){

        return $this->find('count', array(
            'conditions' => array(
                 'PhoneNoVerification.phone_no' => $phone_no,
                 'PhoneNoVerification.code' => $code
            )


        ));

   } }
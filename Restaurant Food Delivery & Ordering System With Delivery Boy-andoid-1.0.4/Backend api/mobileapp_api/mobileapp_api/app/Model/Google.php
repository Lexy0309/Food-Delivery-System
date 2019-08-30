<?php


class Google extends AppModel
{
    public $useTable = 'facebook';
    //public $primaryKey = 'user_id';
    
    
   public function isEmailExist($email)
    {
        return $this->find('count', array(
            'conditions' => array(
                'email' => $email
                

            )
        ));
    }
    
      public function isTokenExist($token)
    {
        return $this->find('count', array(
            'conditions' => array(
                'token' => $token
                

            )
        ));
    }
    
    
   
    
   
    
   
    
}
?>
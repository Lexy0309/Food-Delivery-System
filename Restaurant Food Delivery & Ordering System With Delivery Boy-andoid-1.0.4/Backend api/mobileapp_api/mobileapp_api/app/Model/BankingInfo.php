<?php



class BankingInfo extends AppModel
{

    public $useTable = 'banking_info';


    public function getBankingInfo($user_id)
    {
        return $this->find('all', array(
            'conditions' => array(

                'BankingInfo.user_id'=> $user_id



            )
        ));
    }

    public function isDuplicateRecord($user_id,$name, $transit_no,$bank_no,$account_no)
    {
        return $this->find('count', array(
            'conditions' => array(

                'BankingInfo.user_id'=> $user_id,
                'BankingInfo.name'=> $name,
                'BankingInfo.transit_no'=> $transit_no,
                'BankingInfo.bank_no'=> $bank_no,
                'BankingInfo.account_no'=> $account_no



            )
        ));
    }

    public function beforeSave($options = array())
    {



        if (isset($this->data[$this->alias]['name'])) {
            $name = strtolower($this->data[$this->alias]['name']);





            $this->data['BankingInfo']['name'] = ucwords($name);


        }
        return true;
    }
}


?>
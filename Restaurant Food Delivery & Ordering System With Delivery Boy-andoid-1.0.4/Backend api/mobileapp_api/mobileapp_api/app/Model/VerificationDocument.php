<?php



class VerificationDocument extends AppModel
{

    public $useTable = 'verification_document';


    public function getDocumentDetail($id){



        return $this->find('all', array(
            'conditions' => array(

                'VerificationDocument.id'=> $id



            )
        ));
    }

    public function getDocuments($user_id){



        return $this->find('all', array(
            'conditions' => array(

                'VerificationDocument.user_id'=> $user_id



            )
        ));
    }
}
?>
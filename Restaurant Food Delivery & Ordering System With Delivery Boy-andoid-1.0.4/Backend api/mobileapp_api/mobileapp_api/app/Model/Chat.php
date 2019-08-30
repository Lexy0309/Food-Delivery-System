<?php


class Chat extends AppModel
{
    public $useTable = 'chat';
    public $primaryKey = 'id';


    public $belongsTo = array(

        'sender_info' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'sender_id',


        ),
        'receiver_info' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'receiver_id',


        )
    );

    public function getUserChat($sender_id,$receiver_id)
    {

        return $this->find('all', array(
            'conditions' => array(
                'OR' =>
                    array(
                        array('AND' => array(
                            array('Chat.sender_id' => $sender_id),
                            array('Chat.receiver_id' => $receiver_id)
                        )),
                        array('AND' => array(
                            array('Chat.sender_id' => $receiver_id),
                            array('Chat.receiver_id' => $sender_id)
                        )),
                    )),

            'order' => array('Chat.id' => 'ASC')
        ));

        /* return $this->find('all', array(
             'conditions' => array(
                 'Chat.sender_id' => $sender_id,
                 'Chat.receiver_id' => $receiver_id,


             ),
             'order' => array('id DESC'),
         ));
 */
    }

    public function getLastChat($sender_id,$receiver_id,$id)
    {

        return $this->find('all', array(
            'conditions' => array(
                'Chat.id >' => $id,
                'OR' =>
                    array(
                        array('AND' => array(
                            array('Chat.sender_id' => $sender_id),
                            array('Chat.receiver_id' => $receiver_id)
                        )),
                        array('AND' => array(
                            array('Chat.sender_id' => $receiver_id),
                            array('Chat.receiver_id' => $sender_id)
                        )),
                    )),

            'order' => array('Chat.id' => 'DESC')
        ));

        /* return $this->find('all', array(
             'conditions' => array(
                 'Chat.sender_id' => $sender_id,
                 'Chat.receiver_id' => $receiver_id,


             ),
             'order' => array('id DESC'),
         ));
 */
    }




    public function showUserInbox($user_id)


    {

        $new = array();
        $data = array();
        $converstation_id  = $this->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'Chat.sender_id' => $user_id,
                    'Chat.receiver_id' => $user_id
                )

            ),
            'fields' => array('DISTINCT conversation_id'),

            'order' => array('Chat.id' => 'DESC')
            //'group' => array('Chat.converstation_id'),


        ));
        $i=0;
        foreach($converstation_id as $convo){

            $data[$i] = $this->find('all', array(
                'conditions' => array(
                    'conversation_id' => $convo['Chat']['conversation_id']

                ),



                //'group' => array('Chat.converstation_id'),
                'order' => array('Chat.id' => 'DESC'),
                'limit'=>1

            ));

            $i++;

        }

        return $data;

    }
}
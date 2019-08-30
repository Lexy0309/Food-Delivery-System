<?php



class Notification extends AppModel
{
    public $useTable = 'notification';
    public $primaryKey = 'notification_id';


public $belongsTo = array(
       
        'FirstUser' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'first_user_id',


        ),
        'SecondUser' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'second_user_id',


        ),
    );
 
public function getAllNotifications($user_id){

        return $this->find('all', array(
            'conditions' => array(
                'Notification.first_user_id' => $user_id,
              

            ),
             'order' => 'Notification.notification_id DESC',

        ));


    }

    public function countNotification($user_id){

        return $this->find('all', array(
            'conditions' => array(
                'Notification.first_user_id' => $user_id,
                'Notification.read' => 0,


            ),


        ));


    }

    public function readNotification($user_id){


       return $this>updateAll(
            array('Notification.read' => 1),
            array('Notification.user_id' => $user_id)
        );
    }

}


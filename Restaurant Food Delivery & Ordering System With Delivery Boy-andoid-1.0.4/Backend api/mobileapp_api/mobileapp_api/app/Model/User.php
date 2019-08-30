<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('Security', 'Utility');


class User extends AppModel
{
    public $useTable = 'user';
    // public $primaryKey = 'user_id';

    public $hasOne = array(
        'UserInfo' => array(
            'className' => 'UserInfo',
            'foreignKey' => 'user_id',
            'conditions' => array('User.id = UserInfo.user_id')
        ));

   /* public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'type' => 'RIGHT',

        ));*/


//

    public function isEmailAlreadyExist($email){ /* irfan function*/

        return $this->find('count', array(
            'conditions' => array('email' => $email)
        ));

    }
    public function iSUserExist($id){ /* irfan function*/

        return $this->find('count', array(
            'conditions' => array('id' => $id)
        ));

    }

    public function findTokenAgainstEmail($email,$token){ /* irfan function*/

        return $this->find('all', array(
            'conditions' => array(
                'email' => $email,
                'token' => $token
            )
        ));

    }

    public function getAdminEmails(){ /* irfan function*/

        return $this->find('all', array(
            'conditions' => array(

                'User.role' => 'admin'
            )
        ));

    }


    public function getUsersCount($role){ /* irfan function*/

        return $this->find('count', array(
            'conditions' => array(

                'User.role' => $role)
        ));

    }

    public function getTotalUsersCount(){ /* irfan function*/

        return $this->find('count');

    }
    public function verifyPassword($email,$old_password){


        $userData = $this->findByEmail($email, array(
            'id',
            'password',
            'salt',
            'active'

        ));

        if (empty($userData) || $userData['User']['active']==3) {


            return false;

        }

        $passwordHash = Security::hash($old_password, 'blowfish', $userData['User']['password']);
        $salt = Security::hash($old_password, 'sha256', true);

        if ($passwordHash == $userData['User']['password']  && $userData['User']['block'] == 0) {


            return true;

        }else{
            return false;


        }



    }



    function updatepassword($password)
    {
        $passwordBlowfishHasher = new BlowfishPasswordHasher();
        $user['password'] = $passwordBlowfishHasher->hash($password);
        $user['salt'] = Security::hash($password, 'sha256', true);
        return $user;
    }

    public function updateUserbystdid($user,$std_id){
        return $this->updateAll($user,array('std_id' => $std_id));
    }

    public function getEmailBasedOnUserID($user_id){

        return $this->find('all', array(
            'conditions' => array(
                'User.id' => $user_id

            )
        ));


    }

    public function getAllRiders(){

        return $this->find('all', array(
            'conditions' => array(
                'User.role' => "rider",
               


            ),
            'fields' =>array('UserInfo.*')
        ));


    }
  

    public function getOnlineOfflineRiders($online){

        return $this->find('all', array(
            'conditions' => array(
                'User.role' => "rider",
                'UserInfo.online' => $online,


            ),
            'fields' =>array('UserInfo.*','User.*')
        ));


    }

    public function getAllRidersTimings(){

        $this->bindModel(
            array('hasMany' => array(
                'RiderTiming' => array(
                    'className' => 'RiderTiming',
                    'foreignKey' => 'user_id'
                    //'conditions' => array('User.id = RiderTiming.user_id')
                )
            )
            ),
            false
        );

        $this->Behaviors->attach('Containable');
        return $this->find('all', array(



            'conditions' => array(
                'User.role' => "rider"

            ),
            'contain' => array(
                'RiderTiming' => array(

                    'order' => 'RiderTiming.id DESC',

                ),'UserInfo'
            ),



            //'fields' =>array('UserInfo.*','RiderTiming.*')
        ));


    }

    public function getAdminDetails(){

        return $this->find('all', array(
            'conditions' => array(
                'User.role' => "admin"

            ),
            'fields' =>array('UserInfo.*')
        ));


    }
    public function verify($email,$user_password)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'User.email' => $email

                )
            ));


            /*$userData = $this->findByEmail($email, array(
            'user_id',
           'email',
            'password',
            'salt'
           ));*/
            if (empty($userData)) {


                return false;

            }
        }
        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['User']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['User']['password']  &&  $userData[0]['User']['block'] == 0) {
            if($userData[0]['User']['role'] == "user" || $userData[0]['User']['role'] == "rider") {
                return $userData;
            }else{

                return "203";

            }
        } else {

            return false;


        }



    }


    public function loginAllUsers($email,$user_password)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'User.email' => $email

                )
            ));


            /*$userData = $this->findByEmail($email, array(
            'user_id',
           'email',
            'password',
            'salt'
           ));*/
            if (empty($userData)) {


                return false;

            }
        }
        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['User']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['User']['password']  && $userData[0]['User']['block'] == 0) {

                return $userData;

        } else {

            return false;


        }



    }


    public function loginAllUsersExceptAdmin($email,$user_password,$role)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'User.email' => $email,
                    'User.role' => $role


                )
            ));


            /*$userData = $this->findByEmail($email, array(
            'user_id',
           'email',
            'password',
            'salt'
           ));*/
            if (empty($userData)) {


                return false;

            }
        }


        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['User']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['User']['password']  && $userData[0]['User']['block'] == 0) {


            if($userData[0]['User']['role'] !== "admin") {
                return $userData;
            }else{

                return "203";

            }
        } else {

            return false;


        }



    }

    public function loginRestaurantAndRiderAndUser($email,$user_password)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'User.email' => $email

                )
            ));

            if (empty($userData)) {


              return false;

            }
        }
        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['User']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['User']['password'] && $userData[0]['User']['block'] == 0) {
            if($userData[0]['User']['role'] == "user" || $userData[0]['User']['role'] == "hotel" || $userData[0]['User']['role'] == "rider") {
                return $userData;
            }else{

                return "203";

            }
        } else {

            return false;


        }



    }

    public function getAllUsers(){

        return $this->find('all', array(
            'order' => array('User.id DESC'),
        ));

    }


    public function findEmail($email,$role){

        return $this->find('all', array(
            'conditions' => array(
                'User.role' => $role,
                'User.email' => $email

            ),

        ));


    }



    public function beforeSave($options = array())
    {
        $passwordBlowfishHasher = new BlowfishPasswordHasher();


        if (isset($this->data[$this->alias]['password'])) {
            $password = $this->data[$this->alias]['password'];

            $salt = $password;

            $this->data['User']['password'] = $passwordBlowfishHasher->hash($password);
            $this->data['User']['salt'] = Security::hash($salt, 'sha256', true);
        }
        return true;
    }


}?>
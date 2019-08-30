<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('Security', 'Utility');


class UserAdmin extends AppModel
{
    public $useTable = 'user_admin';




    public function isEmailAlreadyExist($email){ /* irfan function*/

        return $this->find('count', array(
            'conditions' => array('email' => $email)
        ));

    }


    public function verifyPassword($email,$old_password){


        $userData = $this->findByEmail($email, array(
            'id',
            'password',
            'salt',


        ));

        if (empty($userData)) {


            return false;

        }

        $passwordHash = Security::hash($old_password, 'blowfish', $userData['UserAdmin']['password']);
        $salt = Security::hash($old_password, 'sha256', true);

        if ($passwordHash == $userData['UserAdmin']['password']) {


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


    public function getEmailBasedOnUserID($user_id){

        return $this->find('all', array(
            'conditions' => array(
                'UserAdmin.id' => $user_id

            )
        ));


    }




    public function verify($email,$user_password)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'UserAdmin.email' => $email

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
        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['UserAdmin']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['UserAdmin']['password']) {
            if($userData[0]['User']['UserAdmin'] == "user" || $userData[0]['UserAdmin']['role'] == "rider") {
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
                    'UserAdmin.email' => $email

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
        $passwordHash = Security::hash($user_password, 'blowfish', $userData[0]['UserAdmin']['password']);
        $salt = Security::hash($user_password, 'sha256', true);

        if ($passwordHash == $userData[0]['UserAdmin']['password']  && $userData[0]['UserAdmin']['active'] == 1) {

            return $userData;

        } else {

            return false;


        }



    }

    public function getUserDetailsFromID($user_id){

        return $this->find('first', array(
            'conditions' => array(
                'UserAdmin.id' => $user_id
            ),
            'recursive' => 0


        ));

    }
    public function loginAllUsersExceptAdmin($email,$user_password,$role)
    {

        if ($email != "") {
            $userData = $this->find('all', array(
                'conditions' => array(
                    'UserAdmin.email' => $email,
                    'UserAdmin.role' => $role


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

        if ($passwordHash == $userData[0]['UserAdmin']['password']) {
            if($userData[0]['UserAdmin']['role'] !== "admin") {
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

        if ($passwordHash == $userData[0]['User']['password']) {
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
            'order' => array('UserAdmin.id DESC'),
        ));

    }

    public function getAdminDetails(){

        return $this->find('first', array(
            'conditions' => array(
                'UserAdmin.role' => 0

            ),

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

            $this->data['UserAdmin']['password'] = $passwordBlowfishHasher->hash($password);
            $this->data['UserAdmin']['salt'] = Security::hash($salt, 'sha256', true);
        }
        return true;
    }


}?>
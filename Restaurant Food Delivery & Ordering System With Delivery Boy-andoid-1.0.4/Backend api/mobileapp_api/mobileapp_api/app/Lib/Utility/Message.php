<?php
class Message {


 public static function DATAALREADYEXIST(){

     $msg = array(
         'code'=> 201,
         'msg' => 'email already exist'
     );
     echo json_encode($msg);

}


    public static function DATASUCCESSFULLYSAVED(){

        $msg = array(
            'code'=> 200,
            'msg' => 'successfully saved data into db'
        );
        echo json_encode($msg);

    }

    public static function DATASUCCESSFULLYUPDATED(){

        $msg = array(
            'code'=> 200,
            'msg' => 'successfully updated data into db'
        );
        echo json_encode($msg);

    }



    public static function DATASAVEERROR(){

        $msg = array(
            'code'=> 400,
            'msg' => 'Something really went wrong in saving the data into Database'
        );
        echo json_encode($msg);

    }

    public static function ERROR(){

        $msg = array(
            'code'=> 401,
            'msg' => 'error'
        );
        echo json_encode($msg);

    }

    public static function DATAFETCHINGERROR(){

        $msg = array(
            'code'=> 400,
            'msg' => 'Something really went wrong in fetching the data into Database'
        );
        echo json_encode($msg);

    }

    public static function EMAILSENDINGERROR(){

        $msg = array(
            'code'=> 400,
            'msg' => 'Something really went wrong in sending the email'
        );
        echo json_encode($msg);

    }


    public static function INVALIDDETAILS(){

        $msg = array(
            'code'=> 201,
            'msg' => 'Incorrect login details'
        );
        echo json_encode($msg);

    }

    public static function DUPLICATEDATE(){

        $msg = array(
            'code'=> 201,
            'msg' => 'Data Already exist'
        );
        echo json_encode($msg);

    }

    public static function NOCHANGES(){

        $msg = array(
            'code'=> 201,
            'msg' => 'NO Changes in the data'
        );
        echo json_encode($msg);

    }

    public static function INCORRECTPASSWORD(){

        $msg = array(
            'code'=> 201,
            'msg' => 'Incorrect old password'
        );
        echo json_encode($msg);

    }

    public static function EmptyDATA(){

        $msg = array(
            'code'=> 201,
            'msg' => 'EMPTY: NO RECORD IN THE DATABASE'
        );
        echo json_encode($msg);

    }

public static function STRIPIDEMPTY(){

     $msg = array(
         'code'=> 202,
         'msg' => 'payment is not added by student'
     );
     echo json_encode($msg);

}

    public static function DELETEDSUCCESSFULLY()
    {

        $msg = array(
            'code' => 200,
            'msg' => 'Deleted Successfully'
        );
        echo json_encode($msg);

    }

    public static function ALREADYDELETED()
    {

        $msg = array(
            'code' => 201,
            'msg' => 'already deleted'
        );
        echo json_encode($msg);

    }

    public static function ACCESSRESTRICTED(){

        $msg = array(
            'code'=> 202,
            'msg' => 'Access Restricted'
        );
        echo json_encode($msg);

    }





}
?>
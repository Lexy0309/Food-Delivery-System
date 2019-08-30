<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Postmark', 'Utility');
App::uses('EmailTemplate', 'Utility');


class CustomEmail
{


    public static function sendEmailPlaceOrderToUser($data){
        $subject = "Your order has been placed";



        $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,SUPPORT_EMAIL);

        if($postmark->to($data['OrderDetail']['User']['email'])->subject($subject)->html_message(EmailTemplate::placeOrderEmailToUser($data))
            ->send()){

            return true;
        }else{

            return false;

        }



    }
    
     public static function testEmail(){
        $subject = "Congratulations! Emails are working";



        $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,SUPPORT_EMAIL);

        if($postmark->to(TEST_EMAIL)->subject($subject)->html_message("Email is working.")
            ->send()){

            return true;
        }else{

             return false; 

        }



    }


   public static function sendEmailRestaurantRequest($toEmail,$data){
        $subject = "New Restaurant Request";



        $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,SUPPORT_EMAIL);

        if($postmark->to($toEmail)->subject($subject)->html_message(EmailTemplate::emailRestaurantRequest($data))
            ->send()){

            return true;
        }else{

            return false;

        }



    }

public static function sendEmailResetPassword($toEmail,$key){
      $subject = "Reset Password Request";

  $hash=sha1($toEmail .rand(0,100));
    

   // $url = Router::url( array('controller'=>'api','action'=>'resetPassword'),true).'/?'.'email='.$toEmail.'&token='.$key.'#'.$hash;

    $url = RESET_PASSWORD_LINK.'?'.'email='.$toEmail.'&token='.$key.'#'.$hash;

    $ms=$url;
                        $link=wordwrap($ms,1000);

  $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,SUPPORT_EMAIL);

        if($postmark->to($toEmail)->subject($subject)->html_message(EmailTemplate::resetPassword($link,$toEmail))
            ->send()){
            //echo "Message sent";
            return true;
        }else{

return false;

        }



    }

    public static function welcomeEmail($email,$key){


        $subject = "Welcome to Foodies";

        $hash=sha1($email .rand(0,100));


        $url = Router::url( array('controller'=>'api','action'=>'emailsuccess'),true).'/?'.'email='.$email.'&token='.$key.'#'.$hash;
        $ms=$url;
        $link=wordwrap($ms,1000);




        $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,SUPPORT_EMAIL);

      
        if($postmark->to($email)->subject($subject)->html_message(EmailTemplate::registerEmailToUser($email,$link))
            ->send()){
            //echo "Message sent";
            return true;
        }else{

            return false;

        }




    }




 }   
    ?>

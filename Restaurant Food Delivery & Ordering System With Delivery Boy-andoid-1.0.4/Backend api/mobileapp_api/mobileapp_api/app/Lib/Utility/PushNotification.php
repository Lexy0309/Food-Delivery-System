<?php


class PushNotification
{



    public static function sendPushNotificationToMobileDevice($data){



        $key=FIREBASE_PUSH_NOTIFICATION_KEY;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "authorization: key=".$key."",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 85f96364-bf24-d01e-3805-bccf838ef837"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
           return "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }


    public static function sendPushNotificationToAndroid($data){



        $key="AAAAJ0quoYs:APA91bEvzSV9gUYk1ImFdSpoo3wxw8RFg7keXjSrWGZS-CSnv_IypIPxgTBxRSjSD0PqueJxyCO0Th6LGxRvBBhkIsQQgQu6w3CR3ly32FpiR9tVdqtggGTRxrF81L56X8ZySEa-XdeR";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "authorization: key=".$key."",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 85f96364-bf24-d01e-3805-bccf838ef837"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
           // echo "cURL Error #:" . $err;
        } else {
            //echo $response;
        }

    }public static function sendPushNotificationToTablet($data){



    $key="AAAAJ0quoYs:APA91bHahtFwbVzUYqzRx0t_Gobkdj19Q6C3JMywBVc-HrKBbmw4n5wNB1fMYSgP_IyFLRGgl95BBM_3FaFXpfFWUCjXxMkjNEPftDBAiut1VQ99LYHPPMXd_5v2DNZAj3Tor-eeoba9";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "authorization: key=".$key."",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 85f96364-bf24-d01e-3805-bccf838ef837"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }


}
    public static function sendPushNotificationToAndroidDevice($data, $device_token)
    {

        $firebase = new Firebase();
        $firebase_restaurant = new FirebaseRestaurant();
        $firebase_ios = new FirebaseIOS();
        $push = new Push();

        // optional payload
        $payload = array();
        $payload['team'] = 'Dinosoftlabs';
        $payload['score'] = '5.6';


        // notification title
        $title = $data['title'];
        // notification message
        $message = $data['msg'];
        // push type - single user / topic
        $push_type = "individual";
        // whether to include to image or not
        //$tokon="fqP7X71BnpA:APA91bHrKB_n26VsWv2kQ47uMQDsb9XDJtBywXRjvF0ZNFdvHXyvk682_P4DsvcPpcFJxiHhQdBg-kPnCXM2SsQWuenttYy3yF6MXgUrdc86qiYFUv0TwKuK7bQtHz1QHTwEqq3PZ49F";

        $tokon = $device_token;
        $push->setTitle($title);
        $push->setMessage($message);
        $push->setImage('');
        $push->setIsBackground(FALSE);
        $push->setPayload($payload);

        $json = '';
        $response = '';

        $json = $push->getPush();
        $response_user = $firebase->send($tokon, $json);
        $response_restaurant = $firebase_restaurant->send($tokon, $json);
        $response_ios = $firebase_ios->send($tokon, $json);

        //print_r($json);

    }

    public static function sendPushNotificationToApp($data, $device_token)
    {
        // Provide the Host Information.
        $path = $_SERVER['DOCUMENT_ROOT'];

        //$path .= '/preplyst/app/Lib/Utility/Notification/CertificatesProduction.pem';
        //$tHost = 'gateway.sandbox.push.apple.com';
        //ssl://gateway.sandbox.push.apple.com:2195
        $production = true;
        if ($production) {
            $tHost = 'gateway.push.apple.com';
            $path .= '/app/Lib/Utility/Notification/foodomia.pem';
        } else {
            $tHost = 'gateway.sandbox.push.apple.com';
            $path .= '/app/Lib/Utility/Notification/foodomia.pem';
        }
        //$tHost = 'gateway.sandbox.push.apple.com';

        $tPort = 2195;

        // Provide the Certificate and Key Data.

        //App::uses('Postmark', 'Utility');
        $tCert = $path;

        // Provide the Private Key Passphrase (alternatively you can keep this secrete

        // and enter the key manually on the terminal -> remove relevant line from code).

        // Replace XXXXX with your Passphrase

        $tPassphrase = 'apnsproduction123';

        // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).

        // Replace this token with the token of the iOS device that is to receive the notification.

        //$tToken = 'b3d7a96d5bfc73f96d5bfc73f96d5bfc73f7a06c3b0101296d5bfc73f38311b4';

        $tToken = $device_token;

        //0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134

        // The message that is to appear on the dialog.

        //$tAlert = $data['message'];

        // The Badge Number for the Application Icon (integer >=0).

        //$tBadge = 1;

        // Audible Notification Option.

        //$tSound = 'default';

        // The content that is returned by the LiveCode "pushNotificationReceived" message.

        $tPayload = 'APNS Message Handled by LiveCode';

        // Create the message content that is to be sent to the device.

        /*	$tBody['aps'] = array (

            'alert' => $tAlert,

            'badge' => $tBadge,

            'sound' => $tSound,
            'restaurant_id' => $data['restaurant_id'],
            'restaurant_name' => $data['restaurant_name'],
             'type' => $data['type'],
            'img' => $data['image']

            //'identifier' => 'SessionsVC',

            );
            */
        $tBody['aps'] = $data;

        $tBody ['payload'] = $tPayload;

        // Encode the body to JSON.

        $tBody = json_encode($tBody);

        // Create the Socket Stream.

        $tContext = stream_context_create();

        stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);

        // Remove this line if you would like to enter the Private Key Passphrase manually.

        stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);

        // Open the Connection to the APNS Server.

        $tSocket = @stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);

        // Check if we were able to open a socket.

        if (!$tSocket)

            exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);

        // Build the Binary Notification.

        $tMsg = chr(0) . chr(0) . chr(32) . @pack('H*', $tToken) . @pack('n', strlen($tBody)) . $tBody;
        // Send the Notification to the Server.

        $tResult = @fwrite($tSocket, $tMsg, strlen($tMsg));


        if ($tResult)

            return 'Delivered Message to APNS' . PHP_EOL;

        else

            return 'Could not Deliver Message to APNS' . PHP_EOL;

        //Close the Connection to the Server.

        @fclose($tSocket);


    }


    public static function sendPushNotification($token, $message)
    {
        // Provide the Host Information.
        $path = $_SERVER['DOCUMENT_ROOT'];

        //$path .= '/preplyst/app/Lib/Utility/Notification/CertificatesProduction.pem';
        //$tHost = 'gateway.sandbox.push.apple.com';
        //ssl://gateway.sandbox.push.apple.com:2195

        $production = true;
        if ($production) {
            $tHost = 'gateway.push.apple.com';
            $path .= '/app/Lib/Utility/Notification/foodomia.pem';
        } else {
            $tHost = 'gateway.sandbox.push.apple.com';
            $path .= '/app/Lib/Utility/Notification/ApnsProduction.p12';
        }
        //$tHost = 'gateway.sandbox.push.apple.com';

        $tPort = 2195;

        // Provide the Certificate and Key Data.

        //App::uses('Postmark', 'Utility');
        $tCert = $path;

        // Provide the Private Key Passphrase (alternatively you can keep this secrete

        // and enter the key manually on the terminal -> remove relevant line from code).

        // Replace XXXXX with your Passphrase

        $tPassphrase = 'apnsproduction123';

        // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).

        // Replace this token with the token of the iOS device that is to receive the notification.

        //$tToken = 'b3d7a96d5bfc73f96d5bfc73f96d5bfc73f7a06c3b0101296d5bfc73f38311b4';

        $tToken = $token;

        //0a32cbcc8464ec05ac3389429813119b6febca1cd567939b2f54892cd1dcb134

        // The message that is to appear on the dialog.

        $tAlert = $message;

        // The Badge Number for the Application Icon (integer >=0).

        $tBadge = 1;

        // Audible Notification Option.

        $tSound = 'default';

        // The content that is returned by the LiveCode "pushNotificationReceived" message.

        $tPayload = 'APNS Message Handled by LiveCode';

        // Create the message content that is to be sent to the device.

        $tBody['aps'] = array(

            'alert' => $tAlert,

            'badge' => $tBadge,

            'sound' => $tSound,


            //'identifier' => 'SessionsVC',

        );

        $tBody ['payload'] = $tPayload;

        // Encode the body to JSON.

        $tBody = json_encode($tBody);

        // Create the Socket Stream.

        $tContext = stream_context_create();

        stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);

        // Remove this line if you would like to enter the Private Key Passphrase manually.

        stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);

        // Open the Connection to the APNS Server.

        $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);

        // Check if we were able to open a socket.

        if (!$tSocket)

            exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);

        // Build the Binary Notification.

        $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;

        // Send the Notification to the Server.

        $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));

        //if ($tResult)

        //echo 'Delivered Message to APNS' . PHP_EOL;

        //else

        //echo 'Could not Deliver Message to APNS' . PHP_EOL;

        //Close the Connection to the Server.

        fclose($tSocket);
    }



}

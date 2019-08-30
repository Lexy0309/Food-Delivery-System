<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Postmark', 'Utility');
App::uses('EmailTemplate', 'Utility');





class Lib
{


    static function isJsonError($data){
        json_decode($data);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return "false";

            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';

            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';

            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';

            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';

            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';

            default:
                return ' - Unknown error';

        }
    }


    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

    static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    static function time_difference($datetime1,$datetime2) {

        $timeFirst  = strtotime($datetime1);
        $timeSecond = strtotime($datetime2);
       return  $differenceInSeconds = $timeSecond - $timeFirst;
    }


public static function sendEmailResetPassword($toEmail,$key){
      $subject = "Reset Password Request";

  $hash=sha1($toEmail .rand(0,100));
    

    $url = Router::url( array('controller'=>'api','action'=>'reset_password'),true).'/?'.'email='.$toEmail.'&token='.$key.'#'.$hash;
                        $ms=$url;
                        $link=wordwrap($ms,1000);


  $postmark = new Postmark(POSTMARK_SERVER_API_TOKEN,SUPPORT_EMAIL,"no-reply@preplyst.com");
      
        if($postmark->to($toEmail)->subject($subject)->html_message(EmailTemplate::resetPassword($link,$toEmail))
            ->send()){
            //echo "Message sent";
            return true;
        }else{

return false;

        }



    }




     public static function getDurationTimeBetweenTwoDistances($lat1,$long1,$lat2,$long2){



        //https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=31.45622259,73.12973031&destinations=31.40985980,73.11785060&key=

       $url  = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&key=".GOOGLE_MAPS_KEY;
     
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);




        $output_array = json_decode($response,'true');
        
         if (array_key_exists('error_message', $output_array)){
                return false;
                
         }


        if($output_array['rows'][0]['elements'][0]['status'] =="ZERO_RESULTS"
        || $output_array['rows'][0]['elements'][0]['status'] =="NOT_FOUND" ){
            return false;

        } else{

            return $output_array;
        }

    }


    public static  function comma_separated_to_array($string, $separator = ',')
    {
        //Explode on comma
        $vals = explode($separator, $string);

        //Trim whitespace
        foreach($vals as $key => $val) {
            $vals[$key] = trim($val);
        }
        //Return empty array if no items found
        //http://php.net/manual/en/function.explode.php#114273
        return array_diff($vals, array(""));
    }

   public static function multi_array_key_exists($needle, $haystack)
    {
        foreach ($haystack as $key => $value)
        {
            if ($needle === $key)
            {
                return $key;
            }
            if (is_array($value))
            {
                if(self::multi_array_key_exists($needle, $value))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public static function sendSmsVerificationCurl($to_number,$msg)
    {


        $id = TWILIO_ACCOUNTSID;
$token = TWILIO_AUTHTOKEN;
$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages.json";
$from = TWILIO_NUMBER;
$to = $to_number; // twilio trial verified number
$body = $msg;
$data = array (
    'From' => $from,
    'To' => $to,
    'Body' => $body,
);
$post = http_build_query($data);
$x = curl_init($url );
curl_setopt($x, CURLOPT_POST, true);
curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
curl_setopt($x, CURLOPT_POSTFIELDS, $post);
$y = curl_exec($x);

curl_close($x);
return json_decode($y,true);



        
    }

   public static function sendSmsVerification($to_number,$msg)
    {


// ==== Control Vars =======
       
        $strFromNumber = TWILIO_NUMBER;

        $strToNumber = $to_number;
        $strMsg = $msg; //Olivier accidentally pulled up a porn site on a projector
        $aryResponse = array();


        // include the Twilio PHP library - download from
        // http://www.twilio.com/docs/libraries/
        //  echo ROOT . 'app' . DS  . 'Lib' . DS . 'Utility' . DS . 'twilio' . DS . 'inc' . DS . 'services'. DS . 'Twilio.php';
        require_once(ROOT . DS . 'app' . DS . 'Lib' . DS . 'Utility' . DS . 'twilio' . DS . 'inc' . DS . 'Services' . DS . 'Twilio.php');
        //require_once ("inc/Services/Twilio.php");
        // set our AccountSid and AuthToken - from www.twilio.com/user/account
        $AccountSid = TWILIO_ACCOUNTSID;
        $AuthToken = TWILIO_AUTHTOKEN;


        //--check number first------------------


        $base_url = "https://lookups.twilio.com/v1/PhoneNumbers/" . $strToNumber;
        $ch = curl_init($base_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$AccountSid:$AuthToken");

        $response = curl_exec($ch);
        $response = json_decode($response,true);
    
        if (array_key_exists('code', $response) && array_key_exists('message', $response) ) {



            if($response['code'] == 20008 && $response['status'] == 403){

                $response['code'] = 201;
                $response['msg'] = $response['message'];
                 return $response;


          }else{

                    if($response['code'] == 20003 ){

                    $response['code'] = 201;
                    $response['msg'] = $response['detail'].' '.$response['message'];
                    return $response;

                    }

          }
      }


         
      /*  if(strlen($response) < 1){
            
        
           $aryResponse["msg"] = "Mobile number is not correct. Please add correct sender mobile number";
              $aryResponse['code'] = 201;
            return $aryResponse;
        }*/

        $response_decoded = json_decode(json_encode($response), True);
       
        if (array_key_exists('code', $response_decoded)) {
            if ($response_decoded['code'] == 20404) {

                 return $response_decoded['code'] = 201;
            }
            
             if ($response_decoded['code'] == 20003) {

                     $response_decoded['code'] = 201;
                return $response_decoded;
            }
        } else {


            //---------------


            // ini a new Twilio Rest Client
            $objConnection = new Services_Twilio($AccountSid, $AuthToken);


            // Send a new outgoinging SMS by POSTing to the SMS resource */
            $bSuccess = $objConnection->account->sms_messages->create(

                $strFromNumber,    // number we are sending From
                $strToNumber,           // number we are sending To
                $strMsg            // the sms body
            );


            $aryResponse["SentMsg"] = $strMsg;
            $aryResponse["Success"] = true;

            $aryResponse['code'] = 200;
            return true;
        }
    }
    public function addMinutesInDateTime($dateTime,$minutes){

        $time = new DateTime($dateTime);
        $time->add(new DateInterval('PT' . $minutes . 'M'));
        $added_minutes = $time->format('Y-m-d H:i:s');
        $newDateTime = date('h:i A', strtotime($added_minutes)); //get time with am and pm
        return $newDateTime;
    }

    public function addSecondsInDateTime($dateTime,$seconds){

        $time = new DateTime($dateTime);
        $time->add(new DateInterval('PT'. $seconds .'S'));
        $added_seconds = $time->format('Y-m-d H:i:s');
        $newDateTime = date('h:i A', strtotime($added_seconds)); //get time with am and pm
        return $newDateTime;

       // return date('Y-m-d H:i:s',strtotime($seconds.' seconds',strtotime($dateTime)));
    }
    public static function convertDateIntoDaysOrHours($dbdate){

        $currentdateTime = date('Y-m-d H:i:s', time() - 60 * 60 * 4);
        $date1 = new DateTime( $currentdateTime);
        $date2 = new DateTime($dbdate);
        $diff = $date1->diff($date2);

        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);


        if($hours < 24){

            return $hours.' '."hours ago";

        }else{

           return  $diff->format('%a Day');
        }
return false;
    }


    public static function getDayOfTheWeek($datetime){


//Our YYYY-MM-DD date string.


//Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($datetime);

//Get the day of the week using PHP's date function.
        $dayOfWeek = date("l", $unixTimestamp);

//Print out the day that our date fell on.
       return $dayOfWeek;



    }
public static function convertDateTimetoFullMonthAndDayNameWithYear($datetime){


    $date = new DateTime($datetime);
    $new_date_format = $date->format('F D d,Y');
    return $new_date_format; //February Tue 13,2018



}

    public static function getFullMonthAndYear($datetime){


        $date = new DateTime($datetime);
        $new_date_format = $date->format('F Y');
        return $new_date_format; //January 2018



    }

    public static function shortMonthAndDay($datetime){


        $date = new DateTime($datetime);
        $new_date_format = $date->format('M d');
        return $new_date_format; //Feb 13



    }
    public static function getOnlyDateFromDatetime($datetime){


        $date = new DateTime($datetime);
        $new_date_format = $date->format('Y-m-d');
       return $new_date_format;


    }

    public static function getTimeFromDatetime($datetime){


        $date = new DateTime($datetime);
        $new_date_format = $date->format('h:i:s');
        return $new_date_format;


    }
    
    static function uploadFileintoFolder($user_id,$data,$folder_url){



        //$ext = pathinfo('/testdir/dir2/image.gif', PATHINFO_EXTENSION);
        $fileName = uniqid();
        
        $file = base64_decode($data['file_data']);


       $folder = $folder_url.'/'.$user_id;


        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filePath = $folder."/".$fileName.'.png';
        file_put_contents($filePath, $file);
  return $filePath;

    }
    public static function getCountryCityProvinceFromLatLong($lat,$long){


        $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_MAPS_KEY.'&latlng='.$lat.','.$long.'&sensor=false';



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        $info['country'] = "";
        $info['state'] = "";
        $info['city'] = "";
        $info['location_string'] = "";

        $output_array = json_decode($response,'true');
        $output = json_decode($response);


        //if($output_array["status"] !== "INVALID_REQUEST" || $output_array["status"] !== "ZERO_RESULTS" ) {
        if(count($output_array["results"]) > 0){

            for($j=0;$j<count($output_array['results'][0]['address_components']);$j++) {
                $cn = array($output_array['results'][0]['address_components'][$j]['types'][0]);
                if (strlen($info['country']) < 1) {

                    if (in_array("country", $cn)) {

                        $country = $output_array['results'][0]['address_components'][$j]['long_name'];
                        $info['country'] = $country;
                    }
                }
                if (strlen($info['city']) < 1) {
                    if (in_array("locality", $cn)) {

                        $city = $output_array['results'][0]['address_components'][$j]['long_name'];
                        $info['city'] = $city;

                    }
                }

                if (strlen($info['state']) < 1) {
                    if (in_array("administrative_area_level_1", $cn)) {

                        $state = $output_array['results'][0]['address_components'][$j]['long_name'];
                        $info['state'] = $state;

                    }
                }
            }

            $info['location_string'] = $output_array["results"][0]['formatted_address'];
        } else {

            $info['country'] = "";
            $info['state'] = "";
            $info['city'] = "";
            $info['location_string'] = "";

        }

        return $info;


    }

    public static function compressImage($user_id,$data,$folder_url){
        $lib = new Lib;


        //$ext = pathinfo('/testdir/dir2/image.gif', PATHINFO_EXTENSION);

        $folder = $folder_url.'/'.$user_id.'/';
        /*if (file_exists($folder.$user_id.'.png')){

          unlink($folder.$user_id.'.png');

        }*/

        /*if (!is_dir(dirname($folder))) {

            if (true !== @mkdir(dirname($folder), 0777, TRUE)) {
                if (is_dir(dirname($folder))) {
                    // The directory was created by a concurrent process, so do nothing, keep calm and carry on
                } else {
                    echo "error";
                }

                // There is another problem, we manage it (you could manage it with exceptions as well)
                // $error = error_get_last();
                //trigger_error($error['message'], E_USER_WARNING);
            }

        }*/

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filedata = "data:image/png;base64,".$data['file_data'];
        $file = base64_decode($filedata);
        $new = file_get_contents($filedata);
        $imageFileName = uniqid();//$data['filename'];
        $fileName  = uniqid();//uniqid() . $imageFileName;//$user_id;
        list($width_orig, $height_orig) = getimagesizefromstring($new);


        $width = 120;


        $aspectRatio = $height_orig / $width_orig;
        $height = intval($aspectRatio * $width);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromstring(file_get_contents($filedata));
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig,$height_orig);
//header('Content-Type: image/png');
        $filePath = $folder.$fileName.'.png';
        imagejpeg( $image_p, $filePath, 100 );

//imagedestroy($image_p);
//imagedestroy($image);
        return $filePath;
        /*$upload_image = $data['filename'];

        $fileName = uniqid() . $upload_image;
         $folder = $folder_url.'/'.$folder_name.'/';
           $file = base64_decode($data['file_data']);

         //file_put_contents($folder.$fileName, $file);
        $uploadimage = $folder.$file;
        //$file = 'images/photogallery/'.$_FILES["image1"]["name"];
        //$uploadimage = $folder.$_FILES["image1"]["name"];
        $newname = $fileName;
        // Set the resize_image name
        $resize_image = $folder.$newname."_resize.png";
        $actual_image = $folder.$newname;
        // It gets the size of the image

        //list( $width,$height ) = getimagesize($uploadimage);
        // It makes the new image width of 350
        $newwidth = 350;
        // It makes the new image height of 350
        $newheight = 350;
        // It loads the images we use jpeg function you can use any function like imagecreatefromjpeg
        $thumb = imagecreatetruecolor( $newwidth, $newheight );

        $source = imagecreatefromstring($data['file_data']);
        chmod($folder.$newname."_resize.png", 0755);
        // Resize the $thumb image.
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // It then save the new image to the location specified by $resize_image variable
        imagejpeg( $thumb, $resize_image, 100 );
        // 100 Represents the quality of an image you can set and ant number in place of 100.
        // Default quality is 75
        $out_image=addslashes(file_get_contents($resize_image));

        echo "success";*/

    }

    public static function uploadImages($user_id,$image){






            $target_path = UPLOADS_FOLDER_URI."/".$user_id; //Declaring Path for uploaded images
            if (!file_exists($target_path)) {
                mkdir($target_path, 0777, true);
            }
            $file_name=$image["name"];

            $ext=pathinfo($image["name"],PATHINFO_EXTENSION);
            $file_name = str_replace(' ', '_', $image["name"]);
            if($ext == "" || $ext = null){
                $ext = "png";

                $uploadfile = $target_path.'/'. time().uniqid().basename($file_name.".".$ext);
            }else{

                $uploadfile = $target_path.'/'. time().uniqid().basename($file_name.".png");

            }


            if (move_uploaded_file($image['tmp_name'], $uploadfile))
            {
                // echo "File : ", $uploadfile ," is valid, and was successfully uploaded.\n";
                $result['path'] = $uploadfile;

            }

            else
            {
                echo "Possible file : ", $file_name, " upload attack!\n";
            }

        return $result;

    }


    
    static function getToken($length)
    {
        $token        = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[Lib::crypto_rand_secure(0, strlen($codeAlphabet))];
        }
        return $token;
    }
    
    
    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 0)
            return $min; // not so random...
        $log    = log($range, 2);
        $bytes  = (int) ($log / 8) + 1; // length in bytes
        $bits   = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }


    public static function randomNumber($length) {
        $result = '';

        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }
    
 public function uploadItemPic($file, $idUser){
    if(isset($file['file'])) {
    $org_filename = $file['file']['name'];
    $filename = uniqid() . $org_filename;
    
    $output     = array();
  
    $maxsize    = 2097152;
    $acceptable = array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );

    if(($file['file']['size'] >= $maxsize) || ($file["file"]["size"] == 0)) {
       $output['code'] = 200;
        $output['msg'] = 'File too large. File must be less than 2 megabytes.';
        
         
    }

    if(!in_array($file['file']['type'], $acceptable) && (!empty($file["file"]["type"]))) {
        $output['code'] = 200;
        $output['msg'] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
    }
        $folder_idUser = APP . 'uploads/' . $idUser . '/';
        
        if (!is_dir($folder_idUser)) {
            mkdir($folder_idUser);
        }
        
        $folder_items = APP . 'uploads/' . $idUser . '/items/';
        if (!is_dir($folder_items)) {
            mkdir($folder_items);
        }
        
        
         if (!move_uploaded_file($file['file']['tmp_name'], $folder_items . DS . $filename)) {
          $output['code'] = 200;
           $output['msg'] = 'something went wrong in saving the image into directory';
         
         }
    if(count($output) === 0) {
    
          $output['code'] = 100;
          $output['msg'] = "successfully uploaded into the folder";
          $output['filename'] = $filename;
          return $output;
          
       
    } else {
       
           return $output;
        

      
    }
}
    
    }




    public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}






  function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
   echo  $image_save_func($tmp, "$targetFile.$new_image_ext");
}

public static function createLogFile(){



$dir = new Folder(UPLOADS_FOLDER_URI);
//$dir->chmod(UPLOADS_FOLDER_URI, 0755, true, array('log.txt'));
$files = $dir->find('.*\.txt');

foreach ($files as $file) {
    $file = new File($dir->pwd() . DS . $file);
    $contents = $file->read();
    $file->write('I am overwriting the contents of this file');
   $file->append('I am adding to the bottom of this file.');
    // $file->delete(); // I am deleting this file
    $file->close(); // Be sure to close the file when you're done

}


}
  
    
}
?>
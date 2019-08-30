<?php
$baseurl = "http://localhost/mobileapp_api/publicSite";
if( isset($_GET['log']) ) { //log
    echo ($_GET['log']);

    if( $_GET['log'] == "in" ) { //login user

        $email = htmlspecialchars($_POST['eml'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['pswd'], ENT_QUOTES);

        if( !empty($email) && !empty($password) ) { 

            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json"
            );

            $data = array(
                "email" => $email, 
                "password" => $password,
                "role" => "user"
            );

            $ch = curl_init( $baseurl.'/login' );

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $return = curl_exec($ch);

            $json_data = json_decode($return, true);

            $curl_error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            
            echo ("data;");
            var_dump($json_data['msg'][0]['user']);
            //die;
            curl_close($ch);

            if($json_data['code'] !== 200){
                echo ("error1");
                // @header("Location: index.php?action=error");
                // echo "<script>window.location='index.php?action=error'</script>";

            } else {

                if($json_data['msg'][0]['user']['role'] == "user" ) { //hotel
                    
                    $_SESSION['id'] = $json_data['msg'][0]['user']['id'];
                    // $_SESSION['first_name'] = $json_data['msg'][0]['user']['UserInfo']['first_name'];
                    // $_SESSION['last_name'] = $json_data['msg'][0]['user']['UserInfo']['last_name'];
                    // $_SESSION['name'] = $json_data['msg'][0]['user']['UserInfo']['first_name']." ".$json_data['msg'][0]['user']['UserInfo']['last_name'];
                    // $_SESSION['phone'] = $json_data['msg']['UserInfo']['phone'];
                    // $_SESSION['device_token'] = $json_data['msg']['UserInfo']['device_token'];
                    // $_SESSION['online'] = $json_data['msg']['UserInfo']['online'];
                    $_SESSION['email'] =$json_data['msg'][0]['user']['email'];
                    $_SESSION['active'] =$json_data['msg'][0]['user']['active'];
                    $_SESSION['user_type'] =$json_data['msg'][0]['user']['role'];

                    @header("Location: index.php");
                    echo "<script>window.location='index.php'</script>";

                } //hotel = end
                else {
                    echo ("error2");
                    @header("Location: index.php?action=error");
                    echo "<script>window.location='index.php?action=error'</script>";
                }

            }

        } else {
            echo ("error3");
            @header("Location: index.php?action=error");
            echo "<script>window.location='index.php?action=error'</script>";
        } //

    } //login user = end


    if( $_GET['log'] == "out" ) { //logout user

        @session_destroy();
        @header("Location: index.php");
        echo "<script>window.location='index.php'</script>";

    } //logout user = end

}
 ?>
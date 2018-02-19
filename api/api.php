<?php
session_start();
include "db.php";
$url = $_GET['url'];
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if ($url=="loginUser") {
        $email=$_POST['email'];
        $password=$_POST['password'];
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $pdo->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $selected_user = $query->fetch();
        if (password_verify($password, $selected_user->password)) {
            $userid=$selected_user->id;
            $cstrong=true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $stoken = sha1($token);
            $sql2 = "INSERT INTO login_tokens (`token`, `user_id`) VALUES (:stoken , :userid )";
            $query2 = $pdo->prepare($sql2);
            $query2->bindValue(':stoken', $stoken, PDO::PARAM_STR);
            $query2->bindValue(':userid', $userid, PDO::PARAM_STR);
            $query2->execute();
            setcookie("LOGINTOKEN", $token, time()+60*60*24*365, "/", NULL, NULL, TRUE);
            $arr['token'] .= $token;
            $arr['uid'] .= $userid;
            $json=json_encode($arr);
            echo $json;
        } else {
            $arr['token'] .= "Failed";
            $json=json_encode($arr);
            echo $json;
        }
    } else if ($url=="signupUser") {
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $username=$first_name.$last_name;
        $email=$_POST['email'];
            if (!empty($username) && !empty($password) && !empty($cpassword) && !empty($first_name) && !empty($last_name) && !empty($email)) {
                if ($password==$cpassword) {
                    $password=password_hash($password, PASSWORD_DEFAULT);
                    $username=preg_replace('/[^A-Za-z0-9\-]/', '', $username);
                    $first_name=preg_replace('/[^A-Za-z0-9\-]/', '', $first_name);
                    $last_name=preg_replace('/[^A-Za-z0-9\-]/', '', $last_name);
                    $uniqid=bin2hex(openssl_random_pseudo_bytes(256));
                    $sql = "SELECT * FROM users WHERE email = :email";
                    $query = $pdo->prepare($sql);
                    $query->bindValue(':email', $email, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch();
                if ($result->email == "") {
                    $sql2 = "SELECT username FROM users WHERE username = :username";
                    $query2 = $pdo->prepare($sql2);
                    $query2->bindValue(':username', $username, PDO::PARAM_STR);
                    $query2->execute();
                    $result2 = $query2->fetch();
                if ($result->username == "") {
                    $sql3 = "INSERT INTO users (`username`, `first_name`, `last_name`, `email`, `password`, `unique_id`) VALUES (:username , :firstname , :lastname , :email , :password , :uniqid)";
                    $query3 = $pdo->prepare($sql3);
                    $query3->bindValue(':username', $username, PDO::PARAM_STR);
                    $query3->bindValue(':firstname', $first_name, PDO::PARAM_STR);
                    $query3->bindValue(':lastname', $last_name, PDO::PARAM_STR);
                    $query3->bindValue(':email', $email, PDO::PARAM_STR);
                    $query3->bindValue(':password', $password, PDO::PARAM_STR);
                    $query3->bindValue(':uniqid', $uniqid, PDO::PARAM_STR);
                    $query3->execute();
                    echo "Account Created.";
                    } else {
                        echo "Username Already Used.";
                    }
                } else {
                echo "Email Already Used.";
                }
            } else {
                echo "Passwords Must Match.";
            }
        } else {
            echo "All Fields Are Required To Sign Up.";
        }
    } else if ($url=="logoutUser") {
        if (isset($_POST['token'])) {
        $token = sha1($_POST['token']);
        $sql = "SELECT token FROM login_tokens WHERE token = :token";
        $query = $pdo->prepare($sql);
        $query->bindValue(':token', $token, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        if ($result) {
            $sql2 = 'DELETE FROM login_tokens WHERE token = :token';
            $query = $pdo->prepare($sql);
            $query->bindValue(':token', $token, PDO::PARAM_STR);
            $query->execute();
            setcookie("LOGINTOKEN", 0, time()-3600, "/", NULL, NULL, TRUE);
            echo '{ "Status": "Success" }';
            http_response_code(200);
        } else {
            echo '{ "Error": "Invalid token" }';
            http_response_code(400);
        }
        } else {
        echo '{ "Error": "Malformed request" }';
        http_response_code(400);
        }
    } else if ($url=="checkLogin") {
        if (isset($_COOKIE['LOGINTOKEN'])) {
        $token=sha1($_COOKIE['LOGINTOKEN']);
            $sql = "SELECT * FROM login_tokens WHERE token = :token";
            $query = $pdo->prepare($sql);
            $query->bindValue(':token', $token, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch();
        if ($result) {
            echo true;
        }
        } else {
        echo false;
        }
    } else if ($url=="getUserInfo") {
        $cookietoken=sha1($_COOKIE['LOGINTOKEN']);
        //Get User ID From Login Tokens
        $sql = "SELECT * FROM login_tokens WHERE token = :token";
        $query = $pdo->prepare($sql);
        $query->bindValue(':token', $cookietoken, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        //Get Logged In User's Information
        $sql2 = "SELECT * FROM users WHERE id = :id";
        $query2 = $pdo->prepare($sql2);
        $query2->bindValue(':id', $result->user_id, PDO::PARAM_STR);
        $query2->execute();
        $user = $query2->fetch();
        //Assign Values To An Array.
        $arr=array();
        $arr['info']['id'].=$user->id;
        $arr['info']['username'].=$user->username;
        $arr['info']['firstname'].=$user->first_name;
        $arr['info']['lastname'].=$user->last_name;
        $arr['info']['profile_picture'].=$user->profile_picture;
        $arr['info']['email'].=$user->email;
        $arr['info']['suspended'].=$user->suspended;
        $arr['info']['slt'].=$user->SLT;
        $arr['info']['dev'].=$user->Developer;
        $arr['info']['rank'].=$user->rank;
        $arr['info']['team'].=$user->staff_team;
        $arr['permissions']['submitReport'].=$user->sRep;
        setcookie('userArrayPHP', serialize($arr), time()+60*60*24*30, '/');
        echo json_encode($arr);
    } else if ($url=="addSuggestion") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['permissions']['submitReport']==1){
            $name = $_POST['name'];
            $suggestion = $_POST['suggestion'];
            $sql="INSERT INTO suggestions (`name`, `suggestion`) VALUES ( :name , :suggestion )";
            $exec = $pdo->prepare($sql);
            $exec->execute(['name' => $name, 'suggestion' => $suggestion]);
            echo "Suggestion Added";
        } else {
            echo "Insufficient Permissions";
        }
    } else if ($url=="addGuide") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1){
            $title = $_POST['title'];
            $body = $_POST['body'];
            $user = $logged_in['info']['firstname']." ".$logged_in['info']['lastname'];
            $sql="INSERT INTO guides (`title`, `body`, `author`) VALUES ( :title , :body , :user)";
            $query = $pdo->prepare($sql);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':body', $body, PDO::PARAM_STR);
            $query->bindValue(':user', $user, PDO::PARAM_STR);
            $query->execute();
            echo "Guide Added Successfully.";
        } else {
            echo "Insufficient Permissions.";
        }
    } else if ($url=="removeStaff"){
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
        $sql="DELETE FROM users WHERE id=?";
        $exec = $pdo->prepare($sql);
        $exec->execute([$_POST['id']]);
        }
    } else if ($url=="")
} else if ($_SERVER['REQUEST_METHOD']=='GET') {
    if ($url=="dailyCases") {
        $today=0;$yesterday=0;$twodays=0;$threedays=0;$fourdays=0;
        foreach($pdo->query('SELECT * FROM case_logs') as $r){
        $timeinseconds=strtotime($r->timestamp)-time();
        if($timeinseconds > -86400){
            $today++;
        }
        if($timeinseconds > -172800 && $timeinseconds < -86400){
            $yesterday++;
        }
        if($timeinseconds > -259200 && $timeinseconds < -172800){
            $twodays++;
        }
        if($timeinseconds > -345600 && $timeinseconds < -259200){
            $threedays++;
        }
        if($timeinseconds > -432000 && $timeinseconds < -345600){
            $fourdays++;
        }
        }
        $arr=array();
        $arr['today'] .= $today;
        $arr['yesterday'] .= $yesterday;
        $arr['twodays'] .= $twodays;
        $arr['threedays'] .= $threedays;
        $arr['fourdays'] .= $fourdays;
        echo json_encode($arr);
    } else if ($url=="weeklyCases"){
        $thisweek=0;$lastweek=0;$twoweeks=0;$threeweeks=0;$onemonth=0;
        foreach($pdo->query('SELECT * FROM case_logs') as $r){
        $timeinseconds=strtotime($r->timestamp)-time();
        if($timeinseconds > -604800){
            $thisweek++;
        }
        if($timeinseconds > -1209600 && $timeinseconds < -604800){
            $lastweek++;
        }
        if($timeinseconds > -1814400 && $timeinseconds < -1209600){
            $twoweeks++;
        }
        if($timeinseconds > -2419200 && $timeinseconds < -1814400){
            $threeweeks++;
        }
        if($timeinseconds > -3024000 && $timeinseconds < -2419200){
            $onemonth++;
        }
        }
        $arr=array();
        $arr['thisweek'] .= $thisweek;
        $arr['lastweek'] .= $lastweek;
        $arr['twoweeks'] .= $twoweeks;
        $arr['threeweeks'] .= $threeweeks;
        $arr['onemonth'] .= $onemonth;
        echo json_encode($arr);
    }
} else {
    http_response_code(400);
}
?>
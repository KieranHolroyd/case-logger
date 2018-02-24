<?php
session_start();
include "../db.php";
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
            $sql="INSERT INTO guides (`title`, `body`, `author`) VALUES (:title, :body, :author)";
            $query = $pdo->prepare($sql);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':body', $body, PDO::PARAM_STR);
            $query->bindValue(':author', $user, PDO::PARAM_STR);
            $query->execute();
            echo "Guide Added Successfully.";
        } else {
            echo "Insufficient Permissions.";
        }
    } else if ($url=="editGuide") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1){
            $id = $_POST['id'];
            $title = $_POST['title'];
            $body = $_POST['body'];
            $user = $logged_in['info']['firstname']." ".$logged_in['info']['lastname'];
            $sql="UPDATE guides SET title = :title, body = :body WHERE id=:id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':body', $body, PDO::PARAM_STR);
            $query->bindValue(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo "Guide Edited Successfully.";
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
    } else if ($url=="getFullGuide") {
        $id=$_POST['id'];
        $sql = "SELECT * FROM guides WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch();
        $arr=array();
        $arr['title'] = $r->title;
        $arr['body'] = $r->body;
        $arr['author'] = $r->author;
        $arr['time'] = $r->timestamp;
        echo json_encode($arr);
    } else if ($url=="getMoreInfo") {
        $id=$_POST['id'];
        $sql = "SELECT * FROM case_logs WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch();
        $report=array();
        if($r->points_awarded==1){$points="Yes";} else {$points="No";}
        if($r->ban_awarded==1){$ban="Yes";} else {$ban="No";}
        if($r->ts_ban==2){$ts="Yes";} else {$ts="No";}
        if($r->ingame_ban==2){$ig="Yes";} else {$ig="No";}
        if($r->website_ban==2){$wb="Yes";} else {$wb="No";}
        if($r->ban_perm==2){$perm="Yes";} else {$perm="No";}
        $report['report']['id'] .= $r->id;
        $report['report']['lead_staff'] .= $r->lead_staff;
        $report['report']['other_staff'] .= $r->other_staff;
        $report['report']['typeofreport'] .= $r->type_of_report;
        $report['report']['players'] .= $r->players;
        $report['report']['player_guid'] .= $r->player_guid;
        $report['report']['ltpr'] .= $r->link_to_player_report;
        $report['report']['doe'] .= $r->description_of_events;
        $report['report']['aop'] .= $r->amount_of_points;
        $report['report']['evidence'] .= $r->evidence_supplied;
        $report['report']['oc'] .= $r->offence_committed;
        $report['report']['bm'] .= $r->ban_message;
        $report['report']['points'] .= $points;
        $report['report']['banned'] .= $ban;
        $report['report']['ban_length'] .= $r->ban_length;
        $report['report']['ts'] .= $ts;
        $report['report']['ig'] .= $ig;
        $report['report']['wb'] .= $wb;
        $report['report']['perm'] .= $perm;
        $report['report']['timestamp'] .= $r->timestamp;
        echo json_encode($report);   
    } else if ($url=="getSearchResults") {
        $searchquery=$_POST['query'];
        $sql = "SELECT * FROM `case_logs` WHERE `lead_staff` LIKE :query OR `other_staff` LIKE :query OR `description_of_events` LIKE :query OR `player_guid` LIKE :query OR `offence_committed` LIKE :query OR `amount_of_points` LIKE :query OR `evidence_supplied` LIKE :query ORDER BY id DESC";
        $query = $pdo->prepare($sql);
        $query->bindValue(':query', '%'.$searchquery.'%', PDO::PARAM_STR);
        $query->execute();
        $rf = $query->fetchAll();
        $staffinfo=array();
        $i=1;
        foreach($rf as $r){
        $reporting_player=$r->players;
        $staffinfo['log'][$i]['id'] .= $r->id;
        $staffinfo['log'][$i]['doe'] .= $r->description_of_events;
        $staffinfo['log'][$i]['reporting_player']=$reporting_player;
        $i+=1;
        }
        echo json_encode($staffinfo);
    } else if ($url=="getStaffActivity") {
        $sql="SELECT * FROM case_logs WHERE lead_staff LIKE :name OR other_staff LIKE :name ORDER BY id DESC";
        $query = $pdo->prepare($sql);
        $query->bindValue(':name', '%'.$_POST['id'].'%', PDO::PARAM_STR);
        $query->execute();
        $rows = $query->fetchAll();
        $staffinfo=array();
        $i=1;
        foreach($rows as $r){
        if (strpos($r->other_staff, $_POST['id']) !== false) {
            $staffinfo['log'][$i]['other_staff'] = true;
        }
        $reporting_player=$r->players;
        $staffinfo['log'][$i]['id'] .= $r->id;
        $staffinfo['log'][$i]['doe'] .= $r->description_of_events;
        $staffinfo['log'][$i]['reporting_player']=$reporting_player;
        $i+=1;
        }
        echo json_encode($staffinfo);       
    } else if ($url=="getStaffMoreInfo") {
        $id=$_POST['id'];
        $sql="SELECT * FROM users WHERE id = :name";
        $query = $pdo->prepare($sql);
        $query->bindValue(':name', $_POST['id'], PDO::PARAM_STR);
        $query->execute();
        $r = $query->fetch();
        $staffname=$r->username;
        $sql2="SELECT * FROM case_logs WHERE lead_staff LIKE :name OR other_staff LIKE :name ORDER BY id DESC";
        $query2 = $pdo->prepare($sql2);
        $query2->bindValue(':name', '%'.$staffname.'%', PDO::PARAM_STR);
        $query2->execute();
        $cases = $query2->fetchAll();
        $cases_count=count($cases);
        $i=0;
        foreach($cases as $case){
        if((strtotime($case->timestamp)-time())>(-604800)){
            $i++;
            }
        }
        $staffinfo=array();
        //Check for activity warnings based on current weekly case count.
        if($r->rank_lvl >= 7){
            if($i<10){
            $staffinfo['activity_warning'] .= true;
        }
        }
        $staffinfo['id'] .= $r->id;
        $staffinfo['name'] .= $staffname;
        $staffinfo['rank'] .= $r->rank;
        $staffinfo['rank_lvl'] .= $r->rank_lvl;
        $staffinfo['team'] .= $r->staff_team;
        $staffinfo['casecount'] .= $cases_count;
        $staffinfo['casecount_week'] .= $i;
        echo json_encode($staffinfo);
    } else if ($url=="getCases") {
        $offset=$_POST['offset'];
        if($offset <= 0){
            $offset=0;
        }
        $sql = "SELECT * FROM case_logs ORDER BY id DESC LIMIT 100 OFFSET $offset";
        $query = $pdo->prepare($sql);
        $query->execute();
        $rows = $query->fetchAll();
        $row_count=count($rows);
        $reports=array();
        $reports['info']['count'] .= $row_count;
        $reports['info']['offset'] .= $offset;
        $i=1;
        foreach($rows as $row){
            $reporting_player=$row->players;
            $reports['caseno'][$i]['id'] .= $row->id;
                $reports['caseno'][$i]['lead_staff'] .= $row->lead_staff;
            $reports['caseno'][$i]['typeofreport'] .= $row->type_of_report;
            $reports['caseno'][$i]['ltpr'] .= $row->link_to_player_report;
            $reports['caseno'][$i]['pa'] .= $row->points_awarded;
            $reports['caseno'][$i]['ba'] .= $row->ban_awarded;
            $reports['caseno'][$i]['timestamp'] .= $row->timestamp;
            $reports['caseno'][$i]['reporting_player']=$reporting_player;
            $i+=1;
        }
        echo json_encode($reports);   
    } else if ($url=="setStaffTeam") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
            $sql="UPDATE users SET staff_team = :team WHERE id = :id";
            $id=$_POST['id'];
            $team=$_POST['team'];
            $exec = $pdo->prepare($sql);
        $exec->execute(['team' => $team, 'id' => $id]);
        }
    } else if ($url=="setStaffRank") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1 || $logged_in['info']['dev']==1){
         $sql="UPDATE users SET rank = :rank , rank_lvl = :rank_lvl , sRep = 1 , SLT = :slt WHERE id = :id";
	    $id=$_POST['id'];
        $rank=$_POST['rank'];
        $slt = 0;
         if($rank==9){
  	        $rankname="Trial Staff";
        } elseif($rank==8){
    	$rankname="Moderator";
        } elseif($rank==7){
  	    $rankname="Administrator";
        } elseif($rank==6){
  	    $rankname="Senior Administrator";
        $slt = 1;
        }
        $exec = $pdo->prepare($sql);
        $exec->execute(['rank' => $rankname, 'rank_lvl' => $rank, 'slt' => $slt, 'id' => $id]);
        }
    } else if ($url=="submitCase") {
        $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['permissions']['submitReport']==1){
        $ls = $_POST['lead_staff'];
        $os = $_POST['other_staff'];
        $doe = $_POST['description_of_events'];
        $guid = $_POST['player_guid'];
        $ltpr = $_POST['link_to_player_report'];
        $oc = $_POST['offence_committed'];
        $pa = $_POST['points_awarded'];
        $aop = $_POST['ammount_of_points'];
        $es = $_POST['evidence_supplied'];
        $ba = $_POST['ban_awarded'];
        $bl = $_POST['ban_length'];
        $bm = $_POST['ban_message'];
        $ts = $_POST['ts_ban'];
        $ig = $_POST['ingame_ban'];
        $wb = $_POST['website_ban'];
        $perm = $_POST['ban_perm'];
        $players = $_POST['players'];
        $playersArray = array();
        $i=0;
            foreach($players as $players){
            $i++;
            $playersArray[$i]['type'] .= $players['type'];
            $playersArray[$i]['name'] .= $players['name'];
            $playersArray[$i]['guid'] .= $players['guid'];
            }
        $torep = $_POST['type_of_report'];
            $sql = "INSERT INTO case_logs (`lead_staff`, `other_staff`, `description_of_events`, `player_guid`, `link_to_player_report`, `offence_committed`, `points_awarded`, `amount_of_points`, `evidence_supplied`, `ban_awarded`, `ban_length`, `ban_message`, `ts_ban`, `ingame_ban`, `website_ban`, `ban_perm`, `players`, `type_of_report`) VALUES (:ls, :os, :doe, :guid, :ltpr, :oc, :pa, :aop, :es, :ba, :bl, :bm, :ts, :ig, :wb, :perm, :playersArray, :torep)";
            $query=$pdo->prepare($sql);
            $query->bindValue(':ls', $ls, PDO::PARAM_STR);
            $query->bindValue(':os', $os, PDO::PARAM_STR);
            $query->bindValue(':doe', $doe, PDO::PARAM_STR);
            $query->bindValue(':guid', $guid, PDO::PARAM_STR);
            $query->bindValue(':ltpr', $ltpr, PDO::PARAM_STR);
            $query->bindValue(':oc', $oc, PDO::PARAM_STR);
            $query->bindValue(':pa', $pa, PDO::PARAM_STR);
            $query->bindValue(':aop', $aop, PDO::PARAM_STR);
            $query->bindValue(':es', $es, PDO::PARAM_STR);
            $query->bindValue(':ba', $ba, PDO::PARAM_STR);
            $query->bindValue(':bl', $bl, PDO::PARAM_STR);
            $query->bindValue(':bm', $bm, PDO::PARAM_STR);
            $query->bindValue(':ts', $ts, PDO::PARAM_STR);
            $query->bindValue(':ig', $ig, PDO::PARAM_STR);
            $query->bindValue(':wb', $wb, PDO::PARAM_STR);
            $query->bindValue(':perm', $perm, PDO::PARAM_STR);
            $query->bindValue(':playersArray', json_encode($playersArray), PDO::PARAM_STR);
            $query->bindValue(':torep', $torep, PDO::PARAM_STR);
            $query->execute();
            print_r($query->errorinfo());
            print_r($query);
        } else {
            echo "Insufficient Permissions";
        }
    } else if ($url=="addMeeting") {
      $logged_in=unserialize($_COOKIE['userArrayPHP']);
        if($logged_in['info']['slt']==1){
            $date = $_POST['date'];
            $sltonly = ($_POST['slt']==1) ? true : false;
            $points = "{}";
            $sql="INSERT INTO meetings (`date`, `slt`, `points`) VALUES (:dte, :sltonly, :points)";
            $query = $pdo->prepare($sql);
            $query->bindValue(':dte', $date, PDO::PARAM_STR);
            $query->bindValue(':sltonly', $sltonly, PDO::PARAM_STR);
            $query->bindValue(':points', $points, PDO::PARAM_STR);
            $query->execute();
            echo "Meeting Added Successfully";
        } else {
            echo "Insufficient Permissions.";
        }  
    }
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
    } else if ($url=="getGuides") {
        $guides=array();
        $i=1;
        foreach($pdo->query('SELECT * FROM guides ORDER BY title') as $r){
            $title=$r->title;
            $author=$r->author;
            $body=$r->body;
                $guides[$i]['id'] .= $r->id;
            $guides[$i]['title'] .= $title;
            $guides[$i]['author'] .= $author;
            $guides[$i]['body'] .= $body;
            $guides[$i]['time'] .= $r->timestamp;
            $i+=1;
        }
        echo json_encode($guides);
    } else if ($url=="getStaffList") {
        $staff=array();
        $i=1;
        foreach($pdo->query('SELECT * FROM users ORDER BY username') as $r){
            $staffname=$r->username;
            $staff[$i]['name'] .= $staffname;
            $i+=1;
        }
        echo json_encode($staff);       
    } else if ($url=="getStaffTeam") {
        $staff=array();
        $i=1;
        foreach($pdo->query('SELECT * FROM users ORDER BY rank_lvl, staff_team, username ASC') as $r){
            $staffname=$r->username;
            $staff[$i]['id'] .= $r->id;
            $staff[$i]['name'] .= $staffname;
            $staff[$i]['team'] .= $r->staff_team;
            $staff[$i]['rank'] .= $r->rank;
            $i+=1;
        }
        echo json_encode($staff);  
    } else if ($url=="getSuggestions") {
       $arr=array();
        $i=1;
        foreach($pdo->query('SELECT * FROM suggestions ORDER BY id DESC') as $r){
            $arr[$i]['id'] .= $r->id;
            $arr[$i]['name'] .= $r->name;
            $arr[$i]['suggestion'] .= $r->suggestion;
                $i++;
        }
        echo json_encode($arr); 
    } else if ($url=="getMeetings") {
        $arr = [];
        $i = 1;
        foreach ($pdo->query("SELECT * FROM meetings ORDER BY date DESC") as $meeting) {
            $pointCount = count(json_decode($meeting->points));
            $theDate = DateTime::createFromFormat('Y-m-d', $meeting->date);
            if(!$meeting->slt){
                $arr[$i]['date'] = $theDate->format('d/m/Y');
                $arr[$i]['wrongDate'] = $theDate->format('m/d/Y');
                $arr[$i]['points'] = $pointCount;
                $i++;
            } else {
                if(unserialize($_COOKIE['userArrayPHP'])['info']['slt'] == 1){
                    $arr[$i]['date'] = $theDate->format('d/m/Y');
                    $arr[$i]['wrongDate'] = $theDate->format('m/d/Y');
                    $arr[$i]['points'] = $pointCount;
                    $arr[$i]['slt'] = true;
                    $i++;
                }
            }
        }
        echo json_encode($arr);
    } else if ($url=="getFiles") {
        $jsonReturn = file_get_contents("https://eelis.me/a3_ah/api/files/list?apikey=dsjf83ufjosdjfkljsklfs");
        echo $jsonReturn;
    } else if ($url=="getLogs") {
        $jsonReturn = file_get_contents("https://eelis.me/a3_ah/api/logs/list?apikey=dsjf83ufjosdjfkljsklfs&file=".$_GET['name']);
        echo $jsonReturn;
    }
} else {
    http_response_code(400);
}
?>
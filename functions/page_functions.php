<?php
require("./php_includes/mysqli_connect.php");
include_once("approval_status.php");
?><?php
// Find date a week back
$aWeekago = date('Y-m-d H:i:s', strtotime('-1 week'));

$sql = "SELECT users.id, users.username, email, avatar, about, signup, lastlogin, useroptions.usertype, users.avatar FROM users INNER JOIN useroptions ON users.username=useroptions.username WHERE users.username=:username AND activated='1' LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count < 1) {
    echo "That user does not exist or is not yet activated, press back";
    exit();
}

// Fetch the user row from the query above
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $profile_id = $row["id"];
    $username = $row["username"];
    $email = $row["email"];
    $avatar = $row["avatar"];
    $about = $row['about'];
    $usertype = $row['usertype'];
    $signup = $row["signup"];
    $lastlogin = $row["lastlogin"];
    $joindate = strftime("%b %d, %Y", strtotime($signup));
    $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
?><?php
// Get the different user types
$isUser = false;
$isAgent = false;
$isAdmin = false;
$isManager = false;
$isSupervisor = false;
$isSales = false;
$isBilling = false;
$isSupport = false;
$isSuperadmin = false;
if($user_ok == true && $u == $log_username && $usertype == 'user') {
  $isUser = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'agent') {
  $isAgent = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'admin') {
  $isAdmin = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'manager') {
  $isManager = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'supervisor') {
  $isSupervisor = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'sales') {
  $isSales = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'billing') {
  $isBilling = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'support') {
  $isSupport = true;
} else if($user_ok == true && $u == $log_username && $usertype == 'superadmin') {
  $isSuperadmin = true;
}
?><?php
// Get some variables from businessdetails table
if($isUser && $isApproved) {
  $sql = "SELECT businessName, businessAlias, businessDescription, callnect_Number, website FROM businessdetails WHERE username=:logusername";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $thisbisname = $row['businessName'];
    $alias = $row['businessAlias'];
    $bizDesc = $row['businessDescription'];
    $callnect_Number = $row['callnect_Number'];
    $website = $row['website'];
    }
}
// Check if to use first name as profile name
$sql = "SELECT useBizName FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $useBizName = $rows['0'];
}

?><?php
$pageleft = '';
$pageleft = '<div class="container text-center">';
  $pageleft .= '<div class="row">';
    $pageleft .= '<div class="col-sm-3 well">';
      $pageleft .= '<div class="panel panel-default">';
        $pageleft .= '<img src="user/'.$u.'/'.$avatar.'" class="img-circle" height="85" width="85">';
        if($isUser && $isApproved && $useBizName == 'Yes') {
        $pageleft .= '<div class="panel-heading"><a href="#"><b>'.$thisbisname.'</b></a></div>';
      } else {
        $pageleft .= '<div class="panel-heading"><a href="#">'.$username.'</a></div>';
      }
      $pageleft .= '</div>';
      if($isUser && $isApproved) {
      $pageleft .= '<div class="panel panel-default">';
        $pageleft .= '<div class="panel-heading">About Us</div>';
        $pageleft .= '<div class="panel-body">'.$bizDesc.'</div>';
        $pageleft .= '</div>';
      if(isset($website) || isset($callnect_Number)) {
        $pageleft .= '<p><span style="color:#337ab7;" class="glyphicon glyphicon-globe"></span><a style="color:#337ab7;" href="'.$website.'"> '.$website.'</a></p>';
        $pageleft .= '<p style="color:#337ab7;"><span class="glyphicon glyphicon-earphone"></span> '.$callnect_Number.'</p>';
          }
          $pageleft .= '<p style="color:#337ab7;"><span class="glyphicon glyphicon-globe"></span> <a style="color:#337ab7;" href="chat.php?query='.$alias.'">My Business Page</a></p>';
      }
      if($isAgent) {
        // Get the business portfolio for this agent.
        $sql = "SELECT businessUsername, businessdetails.businessName FROM agent_business_alloc INNER JOIN businessdetails ON agent_business_alloc.businessUsername=businessdetails.username WHERE agent_business_alloc.agentUsername=:logusername LIMIT 10";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        $pageleft .= '<div class="panel panel-default">';
          $pageleft .= '<div class="panel-heading"><span class="glyphicon glyphicon-briefcase"></span> My Business Portfolio</div>';

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
          $bizusername = $row['businessUsername'];
          $bizName = $row['businessName'];
          // Check for those later than 1 week
          $sql = "SELECT id, leadDate FROM salesleads WHERE businessUsername=:businessUsername ORDER BY id DESC LIMIT 1";
          $stmt = $db_connect->prepare($sql);
          $stmt->bindParam(':businessUsername', $bizusername, PDO::PARAM_STR);
          $stmt->execute();
          $leadRows = $stmt->rowCount();

          $pageleft .= '<br>';
          $pageleft .= '<ul class="nav nav-pills nav-stacked" style="cursor:pointer;">';
          if($leadRows > 0) {
          foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if($row['leadDate'] <= $aWeekago) {
              $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="buzList(id);showAgentComments(id);agentnail(id);" style="background-color:darkorange;">'.$bizName.'</li>';
            } else {
            $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="buzList(id);showAgentComments(id);agentnail(id);">'.$bizName.'</li>';
            }
          }
        } else {
          $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="buzList(id);showAgentComments(id);agentnail(id);" style="background-color:grey;">'.$bizName.'</li>';
        }
          $pageleft .= '</ul>';
          $pageleft .= '<br style="line-height:10px; display:block;">';
        }
          $pageleft .= '</div>';
      } else if($isSales) {
        // Get the total number of the businesses for this sales person
        $sql = "SELECT id FROM fsr_business_alloc WHERE fsrUsername=:logusername";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        $fsr_biz_count = $stmt->rowCount();
        // Get the business portfolio for this sales person.
        $sql = "SELECT businessUsername, businessdetails.businessName FROM fsr_business_alloc INNER JOIN businessdetails ON fsr_business_alloc.businessUsername=businessdetails.username WHERE fsr_business_alloc.fsrUsername=:logusername LIMIT 10";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        $pageleft .= '<div class="panel panel-default">';
          $pageleft .= '<div class="panel-heading"><span class="glyphicon glyphicon-briefcase"></span> My Business Portfolio</div>';

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
          $bizusername = $row['businessUsername'];
          $bizName = $row['businessName'];

          // Check for those later than 1 week
          $sql_leads = "SELECT leadDate FROM salesleads WHERE businessUsername=:businessUsername ORDER BY id DESC LIMIT 1";
          $stmt1 = $db_connect->prepare($sql_leads);
          $stmt1->bindParam(':businessUsername', $bizusername, PDO::PARAM_STR);
          $stmt1->execute();
          $leadRows = $stmt1->rowCount();

          $pageleft .= '<br>';
          $pageleft .= '<ul class="nav nav-pills nav-stacked" style="cursor:pointer;">';
          if($leadRows > 0) {
            foreach($stmt1->fetchAll() as $rows) {
              if($rows['0'] <= $aWeekago) {
                $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="fsrFeed(id);showfsrBizComments(id);" style="background-color:darkorange;">'.$bizName.'</li>';
              } else {
              $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="fsrFeed(id);showfsrBizComments(id);">'.$bizName.'</li>';
              }
            }
            } else {
              $pageleft .= '<li class="bizlist" id="'.$bizusername.'_'.$log_username.'" onclick="fsrFeed(id);showfsrBizComments(id);" style="background-color:grey;">'.$bizName.'</li>';
            }
            //}
          $pageleft .= '</ul>';
          $pageleft .= '<br style="line-height:10px; display:block;">';
        }
        if($fsr_biz_count > 1) {
          $pageleft .= '<a href="fsr_business.php?u='.$log_username.'">view all...</a>';
        }
          $pageleft .= '</div>';
      } else if($isAdmin || $isManager) {
        $pageleft .= '<div id="sadminWidget" class="list-group">';
        $pageleft .= '<a id="manageuser" href="manageuser.php?u='.$log_username.'" class="list-group-item">Manage User</a>';
        $pageleft .= '<a id="managefsr" href="managefsr.php?u='.$log_username.'" class="list-group-item">Manage FSR</a>';
        $pageleft .= '<a id="manageagent" href="manageagent.php?u='.$log_username.'" class="list-group-item">Manage Agent</a>';
        $pageleft .= '<a id="managebuz" href="managebusiness.php?u='.$log_username.'" class="list-group-item">Manage Business</a>';
        $pageleft .= '<a id="agentbuzalloc" href="agenttobusinessalloc.php?u='.$log_username.'" class="list-group-item">Agent Business Allocation</a>';
        $pageleft .= '</div>';
      } else if($isSuperadmin) {
        $pageleft .= '<div id="sadminWidget" class="list-group">';
        $pageleft .= '<a id="manageuser" href="manageuser.php?u='.$log_username.'" class="list-group-item">Manage User</a>';
        $pageleft .= '<a id="managebilling" href="managebilling.php?u='.$log_username.'" class="list-group-item">Manage Billing</a>';
        $pageleft .= '<a id="managefsr" href="managefsr.php?u='.$log_username.'" class="list-group-item">Manage FSR</a>';
        $pageleft .= '<a id="manageagent" href="manageagent.php?u='.$log_username.'" class="list-group-item">Manage Agent</a>';
        $pageleft .= '<a id="managebuz" href="managebusiness.php?u='.$log_username.'" class="list-group-item">Manage Business</a>';
        $pageleft .= '<a id="agentbuzalloc" href="agenttobusinessalloc.php?u='.$log_username.'" class="list-group-item">Agent Business Allocation</a>';
        $pageleft .= '<a id="smscamp" href="smscampaign.php?u='.$log_username.'" class="list-group-item">SMS Campaign</a>';
        $pageleft .= '</div>';
      }
    $pageleft .= '</div>';
?>

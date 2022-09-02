<?php
require("./php_includes/mysqli_connect.php");
include_once("approval_status.php");
?><?php

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
  $sql = "SELECT * FROM businessdetails WHERE username=:logusername";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $u, PDO::PARAM_STR);
  $stmt->execute();
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $thisbisname = $row['businessName'];
    $bizDesc = $row['businessDescription'];
    $callnect_Number = $row['callnect_Number'];
    $website = $row['website'];
    }
}
// Check the if to use first name as profile name
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
      if(isset($website) && !empty($website)) {
        $pageleft .= '<p><span style="color:#337ab7;" class="glyphicon glyphicon-globe"></span><a style="color:#337ab7;" href="'.$website.'"> '.$website.'</a></p>';
      }
      if(isset($callnect_Number)) {
        $pageleft .= '<p style="color:#337ab7;"><span class="glyphicon glyphicon-earphone"></span> '.$callnect_Number.'</p>';
          }
      }
      if($isSuperadmin) {
        // Do something here...
      }
    $pageleft .= '</div>';
?>

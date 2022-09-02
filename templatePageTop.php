<?php
include_once("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");

$loginLink = '<ul class="nav navbar-nav navbar-right" id="myScrollspy">
<li class="active"><a id="Home" href="#home"><b>HOME</b></a></li>
<li><a id="aboutus" href="#about"><b>ABOUT US</b></a></li>
<li><a id="pricing" href="#price"><b>PRICING</b></a></li>
<li><a id="contactus" href="#contact"><b>CONTACT</b></a></li>
<li><a id="opensearch" style="cursor:pointer;" onclick="openSearch()"><b>SEARCH</b></a></li>
  <li><a href="#" id="myBtn"><span class="glyphicon glyphicon-log-in"></span><b> LOGIN</b></a></li>
</ul>';
if($user_ok == true) {
  $loginLink = '<ul class="nav navbar-nav navbar-right">';
  $loginLink .=  '<li><a href="user.php?u='.$log_username.'"><i class="fas fa-home fa-2x navicon" id="homeid" alt="home" title="Home"></i></a></li>';
  $loginLink .= '<li><a href="profile.php?u='.$log_username.'"><i class="fas fa-cog fa-2x navicon" id="settingsid" alt="rss" title="Profile Setting"></i></a></li>';
  // Check the notifications for this user
  if($isUser && $isApproved) {
    // Count the number of notifications
    $sql = "SELECT notification.id FROM notification INNER JOIN date_visit ON notification.target=date_visit.username WHERE (action='agentcomment' OR action='bizapproval' OR action='bizdisapproval' OR action='subscription') AND (notification.notification_date > date_visit.last_visited AND date_visit.username=:logusername)";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $notification_count = $stmt->rowCount();
   if($notification_count > 0) {
    $loginLink .= '<li id="user_note_still"><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" alt="Notes" title="Notifications"></i><span class="badge" id="userbadge">'.$notification_count.'</span></a></li> ';
  } else {
    $loginLink .= '<li><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" id="note_still" alt="Notes" title="Notifications"></i></a></li>';
    }
  } else if($isSuperadmin || $isAdmin) {
   // Count the number of notifications
   $sql = "SELECT id FROM notification WHERE action NOT LIKE '%agentcomment%'";
   $stmt = $db_connect->prepare($sql);
   $stmt->execute();
   $totalcount = $stmt->rowCount();
   $notification_count = "";
   if($totalcount > 9) {
       $notification_count = "9+";
   } else {
       $notification_count = $totalcount;
   }
   if($notification_count > 0) {
    $loginLink .= '<li id="admin_note_still"><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" alt="Notes" title="Notifications"></i><span class="badge" id="adminbadge">'.$notification_count.'</span></a></li> ';
  } else {
    $loginLink .= '<li><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" id="note_still" alt="Notes" title="Notifications"></i></a></li>';
    }
  } else if($isSales) {
    // Count the number of notifications
    $sql = "SELECT notification.id FROM notification INNER JOIN date_visit ON notification.note=date_visit.username WHERE (action='agentcomment' OR action='bizapproval' OR action='bizdisapproval' OR action='subscription') AND (date_visit.username=:logusername AND notification.notification_date > date_visit.last_visited)";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $notification_count = $stmt->rowCount();
    if($notification_count > 0) {
     $loginLink .= '<li id="sales_note_still"><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" alt="Notes" title="Notifications"></i><span class="badge" id="salesbadge">'.$notification_count.'</span></a></li> ';
   } else {
     $loginLink .= '<li><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" id="note_still" alt="Notes" title="Notifications"></i></a></li>';
     }
  } else {
    $loginLink .= '<li><a href="notification.php?u='.$log_username.'"><i class="fas fa-bell fa-2x navicon" id="note_still" alt="Notes" title="Notifications"></i></a></li>';
  }
    $loginLink .= '<li><a id="opensearch" style="cursor:pointer;" onclick="openSearch()"><i class="fas fa-search fa-2x navicon" alt="search" title="Search"></i></a></li>';
    $loginLink .= '<li><a href="logout.php"><i class="fas fa-sign-out-alt fa-2x navicon" id="logoutid" alt="logout" title="Logout"></i></a></li>
    </ul>';
}
?>
<nav class="navbar navbar-inverse navbar-fixed-top"  style="background-color:black;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <!--<a class="navbar-left" href="#"><img src="images/logo/logo-small.png"></a>-->
        <a class="navbar-left" href="#"><img src="images/logo/latestlogo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <?php
            echo $loginLink;
       ?>
    </div>
  </div>
</nav>

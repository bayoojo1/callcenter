<?php
include_once("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");

$notification_result = '';
if($user_ok == true) {
  if($isUser && $isMessage) {
    $sql = "SELECT target, action, saleslead_id, notification_date FROM notification WHERE (action='agentcomment' OR action='bizdisapproval' OR action='bizapproval') AND target=:logusername ORDER BY id DESC LIMIT 20";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $postdate = $row['notification_date'];
      $b = date_create($postdate);
      $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
      if($row['action'] == 'agentcomment') {
        $notification_result .= '<div class="panel panel-info">';
          $notification_result .= '<div class="panel-heading">Sales Lead</div>';
          $notification_result .= '<div class="panel-body">You have a sales lead waiting for your action. <a href="postpage.php?u='.$row['target'].'&id='.$row['saleslead_id'].'">Please check it</a></div>';
          $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
        $notification_result .= '</div>';
    } else if($row['action'] == 'bizapproval') {
      $notification_result .= '<div class="panel panel-success">';
        $notification_result .= '<div class="panel-heading">Approval</div>';
        $notification_result .= '<div class="panel-body">Congratulations! Your business is approved.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'bizdisapproval') {
      $notification_result .= '<div class="panel panel-danger">';
        $notification_result .= '<div class="panel-heading">Disapproval</div>';
        $notification_result .= '<div class="panel-body">We are sorry, but you need to renew your subscription.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
      }
    }
  } else if($isUser && !$isMessage) {
      $notification_result .= '<div class="alert alert-info">';
      $notification_result .= '<strong>You don\'t have any notification.</strong>';
      $notification_result .= '</div>';
  } else if($isSuperadmin || $isAdmin) {
    $sql = "SELECT initiator, target, action, detail, saleslead_id, notification_date, note FROM notification WHERE (action='bizRegistration' OR action='bizdisapproval' OR action='bizapproval' OR action='subscription' OR action='registration' OR action='modified' OR action='activateUser' OR action='deactivateUser') ORDER BY id DESC LIMIT 20";
    $stmt = $db_connect->prepare($sql);
    $stmt->execute();

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $postdate = $row['notification_date'];
      $b = date_create($postdate);
      $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
      if($row['action'] == 'bizRegistration') {
        $notification_result .= '<div class="panel panel-info">';
          $notification_result .= '<div class="panel-heading">New Business Registration</div>';
          $notification_result .= '<div class="panel-body"><b>' .$row['initiator'].'</b> just registered a business.</div>';
          $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
        $notification_result .= '</div>';
    } else if($row['action'] == 'registration') {
      $notification_result .= '<div class="panel panel-info">';
        $notification_result .= '<div class="panel-heading">New User Registration</div>';
        $notification_result .= '<div class="panel-body"><b>' .$row['initiator'].'</b> just registered as a user on CallNect.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'bizapproval') {
      $notification_result .= '<div class="panel panel-success">';
        $notification_result .= '<div class="panel-heading">Business Approval</div>';
        $notification_result .= '<div class="panel-body"> A business registered by <b>' .$row['target'].'</b> has just been approved.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'bizdisapproval') {
      $notification_result .= '<div class="panel panel-danger">';
        $notification_result .= '<div class="panel-heading">Disapproval</div>';
        $notification_result .= '<div class="panel-body">A business registered by <b>' .$row['target'].'</b> has just been disapproved.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'subscription') {
      $notification_result .= '<div class="panel panel-success">';
        $notification_result .= '<div class="panel-heading">Subscription</div>';
        $notification_result .= '<div class="panel-body"> A business owned by <b>' .$row['target'].'</b> just subscribed.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'modified') {
      $notification_result .= '<div class="panel panel-info">';
        $notification_result .= '<div class="panel-heading">Setting Modified</div>';
        $notification_result .= '<div class="panel-body">'.$row['initiator'].' just modified <b>' .$row['target'].'</b> and changed the value to <b>' .$row['note'].'</b> </div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'activateUser') {
      $notification_result .= '<div class="panel panel-info">';
        $notification_result .= '<div class="panel-heading">User Activation</div>';
        $notification_result .= '<div class="panel-body">'.$row['initiator'].' just activated <b>' .$row['target'].'</b></div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'deactivateUser') {
      $notification_result .= '<div class="panel panel-info">';
        $notification_result .= '<div class="panel-heading">User Deactivation</div>';
        $notification_result .= '<div class="panel-body">'.$row['initiator'].' just deactivated <b>' .$row['target'].'</b></div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    }
    }
  } else if($isSales) {
    $sql = "SELECT target, action, saleslead_id, notification_date, note, businessdetails.businessName FROM notification INNER JOIN businessdetails ON notification.target=businessdetails.username WHERE (action='agentcomment' OR action='bizdisapproval' OR action='bizapproval' OR action='subscription' OR action='bizRegistration') AND note=:logusername ORDER BY notification.id DESC LIMIT 20";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $notification_count = $stmt->rowCount();
    if($notification_count > 0) {

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $postdate = $row['notification_date'];
      $b = date_create($postdate);
      $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
      if($row['action'] == 'agentcomment') {
        $notification_result .= '<div class="panel panel-info">';
          $notification_result .= '<div class="panel-heading">Sales Lead</div>';
          $notification_result .= '<div class="panel-body">Your business <b>' .$row['businessName'].'</b> has a sales lead waiting. <a href="postpage.php?u='.$row['note'].'&id='.$row['saleslead_id'].'">Please check it</a></div>';
          $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
        $notification_result .= '</div>';
    } else if($row['action'] == 'bizapproval') {
      $notification_result .= '<div class="panel panel-success">';
        $notification_result .= '<div class="panel-heading">Business Approval</div>';
        $notification_result .= '<div class="panel-body">Congratulations! Your business <b>' .$row['businessName'].'</b> is approved.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'bizdisapproval') {
      $notification_result .= '<div class="panel panel-danger">';
        $notification_result .= '<div class="panel-heading">Disapproval</div>';
        $notification_result .= '<div class="panel-body">We are sorry. Your business <b>' .$row['businessName'].'</b> is disapproved. You need to follow up on subscription renewal.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'subscription') {
      $notification_result .= '<div class="panel panel-danger">';
        $notification_result .= '<div class="panel-heading">Subscription</div>';
        $notification_result .= '<div class="panel-body">Huray!. Your business <b>' .$row['businessName'].'</b> has just subscribed.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    } else if($row['action'] == 'bizRegistration') {
      $notification_result .= '<div class="panel panel-danger">';
        $notification_result .= '<div class="panel-heading">New Business Registration</div>';
        $notification_result .= '<div class="panel-body">Huray!. Your business <b>' .$row['businessName'].'</b> has just registered.</div>';
        $notification_result .= '<div class="panel-footer"><span>'.$readabledate.'</span></div>';
      $notification_result .= '</div>';
    }
    }
  } else {
    $notification_result .= '<div class="alert alert-info">';
    $notification_result .= '<strong>You don\'t have any notification.</strong>';
    $notification_result .= '</div>';
    }
  }
}
echo $notification_result;
?>

<?php
include_once("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
include("php_includes/a2billing_db.php");
$now = strtotime(date("Y-m-d H:i:s"));
// Select required variables from businessdetails table
$sql_trans = "SELECT businessdetails.id, businessEmail, businessdetails.username, businessName, address, contactName, businessdetails.mobile, users.activated FROM businessdetails INNER JOIN users ON businessdetails.username=users.username WHERE fsrUsername=:logusername AND users.activated='1'";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$bizCount = $stmt->rowCount();
echo '<div class="col-sm-7">';
if($bizCount > 0) {
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $profile_id = $row['id'];
    $email = $row['businessEmail'];
    $username = $row['username'];
    $bizName = $row['businessName'];
    $address = $row['address'];
    $bizContact = $row['contactName'];
    $mobile = $row['mobile'];

    // Get the balance credit of the business
    $sql = "SELECT credit FROM cc_card WHERE email=:email";
    $stmt = $a2billing_connect->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $credit = $row[0];

    echo '<div class="panel panel-primary">';
      echo '<div class="panel-heading"><b>'.$bizName.'</b></div>';
      echo '<div class="panel-body bizdetails"><b>Contact Name:</b> '.$bizContact.'</div>';
      echo '<div class="panel-body bizdetails"><b>Contact Mobile:</b> '.$mobile.'</div>';
      if(isset($credit)) {
        echo '<div class="panel-body bizdetails"><b>Credit Balance:</b> &#8358;'.number_format((float)$credit, 2, '.', ',').'</div>';
      }
    // Check the subscription status of this business
      $sql = "SELECT id, package, live_chat, social_media, outbound_camp, sms_camp, email_camp, beg_date, end_date FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();

      foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $rows) {
        $package = $rows['package'];
        $social = $rows['social_media'];
        $chat = $rows['live_chat'];
        $outbound = $rows['outbound_camp'];
        $sms = $rows['sms_camp'];
        $email_camp = $rows['email_camp'];
        $beg_date = $rows['beg_date'];
        $end_date = $rows['end_date'];

        if(isset($package)) {
        echo '<div class="panel-body bizdetails" style="font-weight:bold;"><span style="color:darkgreen;">Main Plan: '.$package. '</span> | <span style="color:darkred;">Social Media Page Management: ' .$social. '</span> | <span style="color:teal;">Live Chat: ' .$chat. '</span> | <span style="color:purple;">Outbound Campaign: ' .$outbound. '</span> | <span style="color:darkslateblue;">SMS Campaign: ' .$sms. '</span> | <span style="color:darkslateblue;">Email Campaign: ' .$email_camp. '</span></div>';
      }
      if(isset($beg_date) && isset($end_date)) {
      if(strtotime($beg_date) <= $now && $now <= strtotime($end_date)) {
      echo '<div class="panel-body bizdetails"><b>Main Plan Subscription:</b> Active <span class="glyphicon glyphicon-ok"></span></div>';
    } else {
      echo '<div class="panel-body bizdetails"><b>Main Plan Subscription:</b> Inactive <span class="glyphicon glyphicon-remove"></span></div>';
    }
  }
  }
  echo '</div>';
}
echo '</div>';
} else {
  echo '<div class="alert alert-info">';
  echo '<strong>You don\'t have any business in your portfolio yet. Put more effort into your sales activity to get businesses registered.</strong>';
  echo '</div>';
}
?>

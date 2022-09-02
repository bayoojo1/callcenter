<?php
include("./php_includes/mysqli_connect.php");
include_once("approval_status.php");
include("./php_includes/a2billing_db.php");
// Check if this is the logusername
if($user_ok != true || $u != $log_username){
    header("location: https://www.callnect.com");
    exit();
}
// Define time now variable
$now = strtotime(date("Y-m-d H:i:s"));
// Get the subscription status of this user
if($isSubscribed) {
$sql = "SELECT package, social_media, live_chat, outbound_camp, email_camp, sms_camp, beg_date, end_date FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $package = $row['package'];
  $social = $row['social_media'];
  $chat = $row['live_chat'];
  $outbound = $row['outbound_camp'];
  $email_camp = $row['email_camp'];
  $sms = $row['sms_camp'];
  $beg_date = $row['beg_date'];
  $end_date = $row['end_date'];
}
// Make begin date readable
$a = date_create($beg_date);
$readable_beg_date = date_format($a, 'g:ia \o\n l jS F Y');
// Make end date readable
$b = date_create($end_date);
$readable_end_date = date_format($b, 'g:ia \o\n l jS F Y');
// Get the email of this user
$sql = "SELECT email FROM users WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$email = $row[0];
// Get the credit balance of this user
$sql = "SELECT credit FROM cc_card WHERE email=:email";
$stmt = $a2billing_connect->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$credit = $row[0];

// Provide the subscription detail of this user
echo '<div class="well">';
  echo '<div>The following are your current packages:<br>
  <b>Main Plan:</b> <span style="color:darkgreen;">' . $package. '</span><br>
  <b>Available Balance:</b> <span style="color:darkgreen;">&#8358;' .number_format((float)$credit, 2, '.', ','). '</span><br>
  <span style="font-weight:bold;"><u>Optional Services:</u></span><br>
  Social Media Page Management:<b> '. $social. '</b><br>
  Outbound Campaign:<b> '. $outbound. '</b><br>
  Email Campaign:<b> '.$email_camp. '</b><br>
  SMS Campaign:<b> '. $sms. '</b><br>
  Live Chat:<b> ' .$chat.'</b><br>';
echo '</div>';
  echo '<div class="line"></div>';
  echo '<div>Your last subscription started on:<b> ' .$readable_beg_date.'</b> and ends on:<b>'.$readable_end_date.'</b></div>';
  if(isset($beg_date) && isset($end_date)) {
  if(strtotime($beg_date) <= $now && $now <= strtotime($end_date)) {
    echo '<div class="alert alert-success">You still have a valid <b>Main Plan</b> subscription.</div>';
  } else {
    echo '<div class="alert alert-danger">Your <b>Main Plan</b> subscription has expired. Please renew.</div>';
  }
}
    echo '<div><a href="subhistory.php?u='.$log_username.'">View subscription history</a></div>';
echo '</div>';
} else {
  echo '<div class="well">';
    echo '<div>You are yet to subscribe to any package.<br> Please ensure you\'ve provided all the details about your business to CallNect before making any subscription. This will enable us to serve you better.<br> You can chat with us to conclude your registration process.</div>';
  echo '</div>';
}
// Display the subscription packages
$sql = "SELECT plan, price FROM package";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $plan[] = $row['plan'];
    $price[] = $row['price'];
}
echo '<div class="alert alert-info" style="margin-bottom:-1px;">';
  echo '<strong>Billing Plan:</strong> Select a plan. Please check the detail of each plan to know what they offer.';
echo '</div>';
echo '<table class="table table-bordered">';
    echo '<thead>';
      echo '<tr>';
        echo '<th width="60%"; style="text-align:center; background-color:dodgerblue; color:white; border: 1px solid dodgerblue;">Plan</th>';
        echo '<th width="10%"; style="text-align:center; background-color:dodgerblue; color:white; border: 1px solid dodgerblue;">Choice</th>';
        echo '<th width="30%"; style="text-align:center; background-color:dodgerblue; color:white; border: 1px solid dodgerblue;">Price</th>';
      echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
      echo '<tr>';
        echo '<td><b>'.$plan[0].'</b></td>';
        echo '<td><input id="bronze" type="radio" name="optradio" value="'.$price[0].'"></td>';
        echo '<td>&#8358;'.$price[0].'</td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td><b>'.$plan[1].'</b></td>';
        echo '<td><input id="silver" type="radio" name="optradio" value="'.$price[1].'"></td>';
        echo '<td>&#8358;'.$price[1].'</td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td><b>'.$plan[2].'</b></td>';
        echo '<td><input id="gold" type="radio" name="optradio" value="'.$price[2].'"></td>';
        echo '<td>&#8358;'.$price[2].'</td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td><b>'.$plan[3].'</b></td>';
        echo '<td><input id="platinum" type="radio" name="optradio" value="'.$price[3].'"></td>';
        echo '<td>&#8358;'.$price[3].'</td>';
      echo '</tr>';
      echo '</tbody>';
    echo '</table>';
        echo '<div class="alert alert-info" style="margin-bottom:-1px;">';
          echo '<strong>Additional Service Options:</strong> Below are optional additional services offerred by CallNect. Please contact CallNect for details.';
        echo '</div>';
        echo '<table class="table table-bordered">';
            echo '<tbody>';
            echo '<tr>';
              echo '<td width="60%";><b>'.$plan[4].'</b></td>';
              echo '<td width="40%";><input type="checkbox" name="outbound" disabled></td>';
            echo '</tr>';
            echo '<tr>';
              echo '<td width="60%";><b>'.$plan[5].'</b></td>';
              echo '<td width="40%";><input type="checkbox" name="outbound" disabled></td>';
            echo '</tr>';
              echo '<tr>';
                echo '<td width="60%";><b>'.$plan[10].'</b></td>';
                echo '<td width="40%";><input type="checkbox" name="outbound" disabled></td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td width="60%";><b>'.$plan[11].'</b></td>';
                echo '<td width="40%";><input type="checkbox" name="sms" disabled></td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td width="60%";><b>'.$plan[12].'</b></td>';
                echo '<td width="40%";><input type="checkbox" name="email" disabled></td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td width="60%";><b>'.$plan[13].'</b></td>';
                echo '<td width="40%";><input type="checkbox" name="social" disabled></td>';
              echo '</tr>';
            echo '</tbody>';
          echo '</table>';
          /*echo '<table class="table table-bordered">';
              echo '<tbody>';
              echo '<tr>';
                echo '<td width="60%";><button id="calcBtn" type="button" class="btn btn-success"><b>Calculate Total</b></button></td>';
                echo '<input type="hidden" id="logusername" value="'.$log_username.'">';
                echo '<td width="40%"; style="font-size:20px; color:grey;"><b>&#8358;<span id="totalprice"></span></b></td>';
              echo '</tr>';
              echo '</tbody>';
            echo '</table>'; */
            echo '<div class="col-xs-offset-6">';
            echo '<form method="POST" action="https://voguepay.com/pay/">';
              echo '<input type="hidden" name="v_merchant_id" value="demo"/>';
              echo '<input type="hidden" id="merchant_ref" name="merchant_ref" />';
              echo '<input type="hidden" id="memo" name="memo" />';
              echo '<input type="hidden" name="notify_url" value="notify.php?u='.$log_username.'"/>';
              echo '<input type="hidden" name="success_url" value="payment_success.php?u='.$log_username.'" />';
              echo '<input type="hidden" name="fail_url" value="failed.php?u='.$log_username.'"/>';
              echo '<input type="hidden" id="total" name="total" />';
              echo '<input type="hidden" name="cur" value="NGN" />';
              echo '<input type="hidden" name="developer_code" value="5aca789f84458" />';
              echo '<input type="hidden" name="closed" value="closedFunction">';
              echo '<input type="hidden" name="success" value="successFunction">';
              echo '<input type="hidden" name="failed" value="failedFunction">';
              echo '<div class="form-group">';
              echo '<div class="col-xs-offset-4">';
                echo '<input type="hidden" id="logusername" value="'.$log_username.'">';
                echo '<input id="payNow" class="img-responsive" type="image" style="margin-bottom:10px;" onclick="filterNum();" src="./images/pay-now-button.png" alt="Submit" />';
              echo '</div>';
              echo '</div>';
            echo '</form>';
            echo '</div>';


?>

<?php
include("php_includes/mysqli_connect.php");
include("php_includes/a2billing_db.php");

if(isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
// Send request to VoguePay
$data = file_get_contents('https://voguepay.com/?v_transaction_id='.$transaction_id.'&type=json&demo=true');
$arr = json_decode($data, true);

// Get all the needed variables to be used
$merchant_id = $arr['merchant_id'];
$email = $arr['email'];
$total_amount = $arr['total_amount'];
$merchant_ref = $arr['merchant_ref'];
$memo = $arr['memo'];
$status = $arr['status'];
$date = $arr['date'];
$referrer = $arr['referrer'];
$total_credited_to_merchant = $arr['total_credited_to_merchant'];
$extra_charges_by_merchant = $arr['extra_charges_by_merchant'];
$charges_paid_by_merchant = $arr['charges_paid_by_merchant'];
$fund_maturity = $arr['fund_maturity'];
$currency = $arr['cur'];
$total_paid_by_buyer = $arr['total_paid_by_buyer'];
$total = $arr['total'];
// Get variables and plans out of merchant_ref
$log_username = explode('_', $merchant_ref)[0];
$plan = explode('_', $merchant_ref)[1];

// Set the end date of the subscription
$now = strtotime(date('Y-m-d H:i:s'));
// Get the end date of the last subscription of this user
$sql = "SELECT beg_date, end_date FROM subscription WHERE username=:logusername ORDER BY id DESC LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $beg_date = $row['beg_date'];
  $end_date = $row['end_date'];
}
if(isset($beg_date) && isset($end_date)) {
  if(strtotime($beg_date) <= $now && $now <= strtotime(date($beg_date)."+30 days")) {
  $nextMonth = date("Y-m-d H:i:s", strtotime(date($end_date)." +1 month"));
} else {
  $nextMonth = date('Y-m-d H:i:s', strtotime('+ 1 month'));
  }
} else {
  $nextMonth = date('Y-m-d H:i:s', strtotime('+ 1 month'));
}

// Get the plan paid for from the package table
if(isset($plan) && !empty($plan)) {
$sql = "SELECT plan FROM package WHERE note=:note";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':note', $plan, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$mainPlan = $row[0];
} else {
  $mainPlan = "";
}

// Do a little sanitation
if($arr['referrer'] != 'https://www.callnect.com/billing/'.$log_username) {
    header('location: http://www.callnect.com');
    exit();
}

if($status == 'Approved') {
  // Get the fsr for this business
  $sql = "SELECT businessEmail, businessName, fsrUsername FROM businessdetails WHERE username=:logusername LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  foreach ($stmt->fetchAll() as $row) {
    $businessEmail = $row['0'];
    $businessName = $row['1'];
    $fsrusername = $row['2'];
}
// Update the user a2billing credit
$sql = "UPDATE cc_card SET credit = credit + $total WHERE email=:email";
$stmt = $a2billing_connect->prepare($sql);
$stmt->bindParam(':email', $businessEmail, PDO::PARAM_STR);
$stmt->execute();

// Update the subscription table of the transaction
$stmt = $db_connect->prepare("INSERT INTO subscription (username, package, beg_date, end_date, transaction_id, merchant_id, email, total_amount, memo, status, referrer, total_credited_to_merchant, extra_charges_by_merchant, charges_paid_by_merchant, cur, total_paid_by_buyer, total)
        VALUES(:username, :package, now(), :end_date, :transaction_id, :merchant_id, :email, :total_amount, :memo, :status, :referrer, :total_credited_to_merchant, :extra_charges_by_merchant, :charges_paid_by_merchant, :cur, :total_paid_by_buyer, :total )");

$stmt->execute(array(':username' => $log_username, ':package' => $mainPlan, ':end_date' => $nextMonth, ':transaction_id' => $transaction_id, ':merchant_id' => $merchant_id, ':email' => $email, ':total_amount' => $total_amount, ':memo' => $memo, ':status' => $status, ':referrer' => $referrer, ':total_credited_to_merchant' => $total_credited_to_merchant, ':extra_charges_by_merchant' => $extra_charges_by_merchant, ':charges_paid_by_merchant' => $charges_paid_by_merchant, ':cur' => $currency, ':total_paid_by_buyer' => $total_paid_by_buyer, ':total' => $total));
// Insert current transaction into the payment table
$stmt = $db_connect->prepare("INSERT INTO payment (username, fsrUsername, plan, status, datePaid)
        VALUES(:username, :fsrUsername, :plan, :status, now())");

$stmt->execute(array(':username' => $log_username, ':fsrUsername' => $fsrusername, ':plan' => $mainPlan, ':status' => $status));
// Update the notification table
$detail = 'business subscription';
$action = 'subscription';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, notification_date)
VALUES(:initiator, :target, :action, :detail, now())");
$stmt->execute(array(':initiator' => $businessName, ':target' => $fsrusername, ':action' => $action, ':detail' => $detail));
}
// Get the email address of the fsr
$sql = "SELECT email FROM users WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $fsrusername, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll() as $row) {
  $e = $row['0'];
}
// Email the user their activation link
$email_body = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<title>CallNect Message</title>
</head>
  <body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
    <div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.callnect.com"><img src="cid:logo-small.png" width="120" height="80" alt="CallNect" style="border:none; float:left;"></a>CallNect Business Subscription</div>
    <div style="padding:24px; font-size:17px;">Hello '.$fsrusername.',
    <br />
    <br />
    Your business:<b> '.$businessName. '</b>just subscribed to:
    Plan: ' .$mainPlan.' <br>

    This would keep the business running on CallNect for the next 1 month. Follow up with other businesses in your portfolio to ensure they are maximizing the benefit of their call center. Help them grow their businesses. Remember:<br /><b>Their hapiness is your hapiness</b></div>
  </body>
</html>';
  $filePath = '/var/www/html/images/logo/logo-small.png';
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/mail1.callnect.com/messages");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-833e41bd255e7d164bbfe48f981bdf6e");
  $post = array(
      'from' => 'CallNect <info@callnect.com>',
      'to' => $e,
      'subject' => 'CallNect Business Subscription',
      'html' => $email_body,
      'inline' => curl_file_create($filePath),
  );
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

  $result = curl_exec($ch);
  if($result === false)
  {
      echo "Error Number:".curl_errno($ch)."<br>";
      echo "Error String:".curl_error($ch);
  }
  curl_close($ch);
?>

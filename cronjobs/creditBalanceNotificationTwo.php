<?php
include("/var/www/html/php_includes/mysqli_connect.php");
include("/var/www/html/php_includes/a2billing_db.php");

// Select begin date more than 30 days
$sql = "SELECT users.email, businessdetails.fsrUsername FROM subscription INNER JOIN users ON subscription.username=users.username INNER JOIN businessdetails ON subscription.username=businessdetails.username WHERE subscription.end_date = CURDATE() - INTERVAL 5 DAY";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $email = $row['email'];
  $fsrusername = $row['fsrUsername'];

// Get the business name
    $sql = "SELECT businessName FROM businessdetails WHERE email=:email";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $businessName = $row[0];

// Get the email address of the fsr
    $sql = "SELECT email FROM users WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $fsrusername, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $fsrEmail = $row[0];

// Get the credit balance of this user
    $sql = "SELECT credit FROM cc_card WHERE email=:email";
    $stmt = $a2billing_connect->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $credit = $row[0];

// Initiate email to both the business and the fsr
$email_body = '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<title>CallNect Message</title>
</head>
  <body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
    <div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.callnect.com"><img src="cid:logo-small.png" width="120" height="80" alt="CallNect" style="border:none; float:left;"></a>CallNect Business Subscription</div>
    <div style="padding:24px; font-size:17px;">Hello '.$businessName.',
    <br />
    <br />
    Today is the <b>25th days</b> into your current subscription.
    <br />
    Your current account balance is <b>&#8358;'.$credit.'</b>
    <br />
    Please ensure you renew your subscription before the end of the month to avoid service disruption, and to roll-over your balance.
    <br />
    <br />
    Best Regards,
    Team CallNect
    </div>
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
      'to' => $email,
      'cc' => $fsrEmail,
      'subject' => 'Subscription Status',
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
}
?>

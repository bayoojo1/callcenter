<?php
include("/var/www/html/php_includes/mysqli_connect.php");
include("/var/www/html/php_includes/a2billing_db.php");

$zero = '0.00';
$nowDate = date('Y-m-d H:i:s');
// Select begin date more than 30 days
$sql = "SELECT users.email, subscription.end_date FROM subscription INNER JOIN users ON subscription.username=users.username WHERE subscription.end_date = CURDATE() - INTERVAL 1 DAY";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $email = $row['email'];
  $end_date = $row['end_date'];

  // Get the username from the email
  $username = explode("@", $email);

  // Access the a2billing db and zero the credit for these emails
  $sql = "UPDATE cc_card SET credit=:zero WHERE email=:email";
  $stmt = $a2billing_connect->prepare($sql);
  $stmt->bindParam(':zero', $zero, PDO::PARAM_STR);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  // Set the end date of subscription to now
  $sql = "UPDATE subscription SET end_date=:nowDate WHERE username=:username AND beg_date = CURDATE() - INTERVAL 1 DAY";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':nowDate', $nowDate, PDO::PARAM_STR);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
}


?>

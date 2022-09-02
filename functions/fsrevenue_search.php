<?php
include("../php_includes/mysqli_connect.php");
if(isset($_POST['date'])){
    $date = preg_replace('#[^0-9-]#i', '', $_POST['date']);
    $username = preg_replace('#[^a-z0-9._-]#i', '', $_POST['username']);
} else if(!isset($date) || empty($date)) {
  echo '<span style="background-color:forestgreen; color:white;"><b>Not Available</b></span>';
  exit();
}
$cutdate = explode("-", $date);
$year = $cutdate[0];
$month = $cutdate[1];
$realdate = $year.'-'.$month;

$sql = "SELECT SUM(package.price), payment.datePaid FROM package INNER JOIN payment ON package.plan=payment.plan INNER JOIN fsr_business_alloc ON payment.fsrUsername=fsr_business_alloc.fsrUsername WHERE fsr_business_alloc.fsrUsername=:username AND datePaid LIKE :datepaid";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':datepaid', $realdate.'%', PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
  $totalpaid = $row['0'];
  $datepaid = $row['1'];
}
// Find the 5% of the $totalpaid
if(isset($totalpaid)) {
$payout = 0.2 * $totalpaid;
echo '<span style="background-color:forestgreen; color:white;"><b>'.'N'.$payout.',000'.'</b></span>';
} else if(!isset($totalpaid)) {
  echo '<span style="background-color:forestgreen; color:white;"><b>Not Available</b></span>';
}
?>

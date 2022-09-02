<?php
include("../php_includes/mysqli_connect.php");
if(isset($_POST['date'])){
    $date = preg_replace('#[^0-9-]#i', '', $_POST['date']);
} else if(!isset($date) || empty($date)) {
  echo '<span style="background-color:forestgreen; color:white;"><b>Not Available</b></span>';
  exit();
}
$cutdate = explode("-", $date);
$year = $cutdate[0];
$month = $cutdate[1];
$realdate = $year.'-'.$month;

$sql = "SELECT datePaid, SUM(package.price) FROM payment INNER JOIN package ON payment.plan = package.plan WHERE datePaid LIKE :datepaid";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':datepaid', $realdate.'%', PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
  $datepaid = $row['0'];
  $totalpaid = $row['1'];
}
if(isset($totalpaid)) {
echo '<span style="background-color:forestgreen; color:white;"><b>'.'&#8358;'.$totalpaid.',000'.'</b></span>';
} else if(!isset($totalpaid)) {
  echo '<span style="background-color:forestgreen; color:white;"><b>Not Available</b></span>';
}
?>

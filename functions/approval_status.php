<?php
include("php_includes/mysqli_connect.php");

// Check the businessDetail table in DB if user already exist.
$isRegistered = false;
$sql = "SELECT businessdetails.id, useroptions.usertype FROM businessdetails INNER JOIN useroptions ON businessdetails.username=useroptions.username WHERE usertype='user' AND businessdetails.username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count > 0) {
  $isRegistered = true;
}
// Check if the business is approved
$isApproved = false;
$sql = "SELECT approval FROM businessdetails WHERE username=:logusername AND approval='yes' LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count > 0) {
  $isApproved = true;
}
// Check if there is any message between agent and business
$isMessage = false;
$sql = "SELECT id FROM salesleads WHERE businessUsername=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count > 0) {
  $isMessage = true;
}
// Check if the user is subscripbed
$isSubscribed = false;
$sql = "SELECT id FROM subscription WHERE username=:logusername ORDER BY id DESC LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count > 0) {
  $isSubscribed = true;
}
?>

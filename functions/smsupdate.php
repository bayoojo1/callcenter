<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
if(isset($_POST['status'])){
    $status = preg_replace('#[^a-z]#i', '', $_POST['status']);
    $profile_id = preg_replace('#[^0-9]#i', '', $_POST['profile_id']);
}
?><?php
if($status == 'Yes') {
$sql = "UPDATE useroptions SET smsUpdate=:status WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
} else if($status == 'No') {
  $sql = "UPDATE useroptions SET smsUpdate=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR);
  $stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
  $stmt->execute();
}
?>

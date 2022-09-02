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
    $status = preg_replace('#[^0-9]#i', '', $_POST['status']);
    $profile_id = preg_replace('#[^0-9]#i', '', $_POST['profile_id']);
}
// Get the userame of this user
$sql = "SELECT username FROM users WHERE id=:id LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $profile_owner = $row['0'];
}
?><?php
if($status == '1') {
$sql = "UPDATE users SET activated=:status WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
// Update notification table
$detail = 'activate a user';
$action = 'activateUser';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, notification_date)
VALUES(:initiator, :target, :action, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $profile_owner, ':action' => $action, ':detail' => $detail));
} else if($status == '0') {
  $sql = "UPDATE users SET activated=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR);
  $stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
  $stmt->execute();
  // Update notification table
  $detail = 'deactivate a user';
  $action = 'deactivateUser';
  $stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, notification_date)
  VALUES(:initiator, :target, :action, :detail, now())");
  $stmt->execute(array(':initiator' => $log_username, ':target' => $profile_owner, ':action' => $action, ':detail' => $detail));
}
?>

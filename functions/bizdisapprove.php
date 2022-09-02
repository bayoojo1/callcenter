<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away
?><?php
if(isset($_POST['status'])){
    $status = preg_replace('#[^a-z]#i', '', $_POST['status']);
    $profile_id = preg_replace('#[^0-9]#i', '', $_POST['profile_id']);
}
?><?php
if($status == 'no') {
  $sql = "UPDATE businessdetails SET approval=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR);
  $stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
  $stmt->execute();

  // Get required variables from businessdetails table
  $sql = "SELECT username, fsrUsername FROM businessdetails WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll() as $rows) {
      $businessUsername = $rows['0'];
      $fsrUsername = $rows['1'];
  }

// Delete this username from the agent_business_alloc table
$sql = "DELETE FROM agent_business_alloc WHERE businessUsername=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();

// Delete this username from the fsr_business_alloc table
$sql = "DELETE FROM fsr_business_alloc WHERE businessUsername=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();

// Delete this username from the callnect_number table
$sql = "DELETE FROM callnect_numbers WHERE businessUsername=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();

// Update businessdetails table and remove agent
$sql = "UPDATE businessdetails SET agentUsername = '' WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();

// Update businessdetails table and remove fsr
$sql = "UPDATE businessdetails SET fsrUsername = '' WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();

// Update businessdetails table and remove callnect number
$sql = "UPDATE businessdetails SET callnect_Number = '' WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $businessUsername, PDO::PARAM_STR);
$stmt->execute();
// Update notification table
$detail = 'business disapproved';
$action = 'bizdisapproval';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, note, notification_date)
VALUES(:initiator, :target, :action, :detail, :note, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $businessUsername, ':action' => $action, ':detail' => $detail, ':note' => $fsrUsername));
}
?>

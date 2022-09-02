<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away
?><?php
if(isset($_POST['status'])){
    $status = preg_replace('#[^a-z]#i', '', $_POST['status']);
    $profile_id = preg_replace('#[^0-9]#i', '', $_POST['profile_id']);
}
// Check if this user is already approved
$sql = "SELECT approval FROM businessdetails WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
  $currentStatus = $row['0'];
}
// Check if this business has agent, fsr and callnect number assigned
$sql = "SELECT agentUsername, fsrUsername, callnect_Number FROM businessdetails WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
  $agentuser = $row['0'];
  $fsruser = $row['1'];
  $callnectnum = $row['2'];
}

if($currentStatus == 'yes') {
  echo 'already_approved';
  exit();
} else if(empty($agentuser) || empty($fsruser) || empty($callnectnum)) {
  echo 'empty_details';
  exit();
} else {
$sql = "UPDATE businessdetails SET approval=:status WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();

?><?php
// Get required variables from businessdetails table
$sql = "SELECT agentUsername, username, fsrUsername, callnect_Number FROM businessdetails WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $profile_id, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $agentUsername = $rows['0'];
    $businessUsername = $rows['1'];
    $fsrUsername = $rows['2'];
    $callnect_Number = $rows['3'];
}

// Insert agentUsername and corresponding businessUsername into the agent_business_alloc table
$stmt = $db_connect->prepare( "INSERT INTO agent_business_alloc (agentUsername, businessUsername, allocationDate) VALUES(:agentUsername, :businessUsername, now())");
$stmt->execute(array(':agentUsername' => $agentUsername, ':businessUsername' => $businessUsername));

// Insert fsrUsername and corrensponding businessUsername into the fsr_business_alloc table
$stmt = $db_connect->prepare( "INSERT INTO fsr_business_alloc (fsrUsername, businessUsername, allocationDate) VALUES(:fsrUsername, :businessUsername, now())");
$stmt->execute(array(':fsrUsername' => $fsrUsername, ':businessUsername' => $businessUsername));

// Insert the callnect number and businessUsername into the callnect_numbers table change the status to assigned
$stmt = $db_connect->prepare( "INSERT INTO callnect_numbers (callnect_number, businessUsername) VALUES(:callnect_number, :businessUsername)");
$stmt->execute(array(':callnect_number' => $callnect_Number, ':businessUsername' => $businessUsername));
// Update notification table
$detail = 'business approved';
$action = 'bizapproval';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, note, notification_date)
VALUES(:initiator, :target, :action, :detail, :note, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $businessUsername, ':action' => $action, ':detail' => $detail, ':note' => $fsrUsername));
// Echo success to ajax
echo 'success';
}
?>

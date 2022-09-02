<?php
include("../php_includes/mysqli_connect.php");
if(isset($_POST["bzusername"])) {
    $bzusername = $_POST['bzusername'];
    $agentUsername = $_POST['agentusername'];
}
// Check if this business username exist
$sql = "SELECT id FROM businessdetails WHERE username=:username AND approval='yes' LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $bzusername, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() < 1) {
  echo 'biz_not_exist';
  exit();
}
// Check if this agent username exist
$sql = "SELECT id FROM agentdetails WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() < 1) {
  echo 'agent_not_exist';
  exit();
}
// Check if this agent is already allocated to this business
$sql = "SELECT id FROM agent_business_alloc WHERE agentUsername=:agentUsername AND businessUsername=:businessUsername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':agentUsername', $agentUsername, PDO::PARAM_STR);
$stmt->bindParam(':businessUsername', $bzusername, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() > 0) {
  echo 'record_exist';
  exit();
}
// Insert data into agent_business_alloc table
$stmt = $db_connect->prepare("INSERT INTO agent_business_alloc (agentUsername, businessUsername, allocationDate) VALUES(:agentUsername, :businessUsername, now())");
if($stmt->execute(array(':agentUsername' => $agentUsername, ':businessUsername' => $bzusername))) {
  echo 'success';
} else {
  echo 'failed';
}
?>

<?php
//include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// Gather the variables from ajax
if(isset($_POST['agentid'])){
    $id = $_POST['agentid'];
}
// Delete the particular id
$sql = "DELETE FROM agent_business_alloc WHERE id=:id LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
if($stmt->execute()) {
  echo 'success';
} else {
  echo 'not_success';
}

?>

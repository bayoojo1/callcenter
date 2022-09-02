<?php
//include_once("functions/page_functions.php");
include("php_includes/mysqli_connect.php");
//include_once("approval_status.php");
// Get the variables to be used in this page
if(isset($_POST['id']) && !empty($_POST['id'])) {
  $username = $_POST['id'];

  // Get all details about this business
  $sql = "SELECT fsrUsername, fsr_firstname, fsr_mobile FROM fsrdetails INNER JOIN businessdetails ON businessdetails.fsrUsername=fsrdetails.username WHERE businessdetails.username=:username LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $bizFSR = $row['fsr_firstname'];
    $fsrMobile = $row['fsr_mobile'];
    $FSRUsername = $row['fsrUsername'];
  }
}
// Get the FSR avatar
$sql = "SELECT avatar FROM users INNER JOIN fsrdetails ON users.username=fsrdetails.username WHERE fsrdetails.username=:fsrusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':fsrusername', $FSRUsername, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $avatar = $row['avatar'];
}

$agentnail = '<div class="thumbnail">';
  $agentnail .= '<p style="background-color:grey; color:white;">FSR</p>';
  $agentnail .= '<img style="border-radius:10px;" src="user/'.$FSRUsername.'/'.$avatar.'" height="100" width="80">';
$agentnail .= '<p><strong>'.$bizFSR.'</strong></p>';
$agentnail .= '<p><strong>'.$fsrMobile.'</strong></p>';
$agentnail .= '</div>';
echo $agentnail;
?>

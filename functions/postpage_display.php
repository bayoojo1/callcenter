<?php
include("./php_includes/mysqli_connect.php");
$userfeed = '';
// Get the variables from the database
$sql = "SELECT * FROM salesleads WHERE (fsrUsername=:logusername OR businessUsername=:logusername) AND id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $u, PDO::PARAM_STR);
$stmt->bindParam(':id', $postid, PDO::PARAM_STR);
$stmt->execute();
$notice_count = $stmt->rowCount();
if($notice_count > 0) {

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  //$agentUsername = $row['agentUsername'];
  //$fsrUsername = $row['fsrUsername'];
  $businessUsername = $row['businessUsername'];
  $leadMobile = $row['leadMobile'];
  $info = $row['infoRequested'];
  $leadDate = $row['leadDate'];
  $b = date_create($leadDate);
  $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
// Update the user timeline

  $userfeed .= '<div class="col-sm-12">';
    $userfeed .= '<div class="panel panel-primary">';
    $userfeed .= '<div class="panel-heading" ><b>Sales Lead/Enquiry</b></div>';
    $userfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Lead\'s Mobile:</b> ' .$leadMobile.' </div>';
    $userfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Information:</b> ' .$info.' </div>';
    $userfeed .= '<div class="panel-body"> ' .$readabledate.' </div>';
    $userfeed .= '</div>';
    $userfeed .= '</div>';
  }
} else {
  $userfeed .= '<div class="alert alert-info">';
  $userfeed .= '<strong>You don\'t have any notification.</strong>';
  $userfeed .= '</div>';
}
echo $userfeed;
?>

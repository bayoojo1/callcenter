<?php
//include("./php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
if(isset($_POST['bzusername']) && !empty($_POST['bzusername'])) {
  $bizusername = preg_replace('#[^0-9a-z]#i', '', $_POST['bzusername']);
  $agentUsername = preg_replace('#[^0-9a-z.]#i', '', $_POST['agentUsername']);
  $mobile = preg_replace('#[^0-9]#i', '', $_POST['mobile']);
  $mail = preg_replace('#[^0-9a-z.@_]#i', '', $_POST['mail']);
  $name = preg_replace('#[^a-z]#i', '', $_POST['name']);
  $location = preg_replace('#[^a-z0-9:., \']#i', '', $_POST['location']);
  $keyword = preg_replace('#[^a-z0-9:., \']#i', '', $_POST['keyword']);
  $comment = preg_replace('#[^a-z0-9:.,-?@!=+ \']#i', '', $_POST['comment']);
  $smsUpdate = preg_replace('#[^a-z]#i', '', $_POST['smsUpdate']);
  $emailUpdate = preg_replace('#[^a-z]#i', '', $_POST['emailUpdate']);
  $phoneUpdate = preg_replace('#[^a-z]#i', '', $_POST['phoneUpdate']);
  $telegramUpdate = preg_replace('#[^a-z]#i', '', $_POST['telegramUpdate']);
}

// Access the salesleads DB table for agent update
$sql = "SELECT fsrUsername FROM businessdetails WHERE username=:buzusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $fsrUsername = $row['fsrUsername'];
}
// Get the agent avatar and first name
$sql = "SELECT avatar, agentdetails.agent_firstname FROM users INNER JOIN agentdetails ON users.username=agentdetails.username WHERE users.username=:agentusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':agentusername', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $avatar = $row['avatar'];
  $agentFirstname = $row['agent_firstname'];
}

// Insert variables into the salesleads db
$stmt = $db_connect->prepare( "INSERT INTO salesleads (agentUsername, fsrUsername, businessUsername, leadMobile, leadMail, leadName, leadLocation, infoRequested, leadDate, smsUpdate, emailUpdate, phoneUpdate, telegramUpdate, keyword) VALUES(:agentUsername, :fsrUsername, :businessUsername, :leadMobile, :leadMail, :leadName, :leadLocation, :infoRequested, now(), :smsUpdate, :emailUpdate, :phoneUpdate, :telegramUpdate, :keyword)");
$stmt->execute(array(':agentUsername' => $agentUsername, ':fsrUsername' => $fsrUsername, ':businessUsername' => $bizusername, ':leadMobile' => $mobile, ':leadMail' => $mail, ':leadName' => $name, ':leadLocation' => $location, ':infoRequested' => $comment, ':smsUpdate' => $smsUpdate, ':emailUpdate' => $emailUpdate, ':phoneUpdate' => $phoneUpdate, ':telegramUpdate' => $telegramUpdate, ':keyword' => $keyword));
// Get the last inserted id
$post_id = $db_connect->lastInsertId();
// Update notification table
$detail = 'sales leads';
$action = 'agentcomment';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, saleslead_id, detail, note, notification_date)
VALUES(:initiator, :target, :action, :saleslead_id, :detail, :note, now())");
$stmt->execute(array(':initiator' => $agentUsername, ':target' => $bizusername, ':action' => $action, ':saleslead_id' => $post_id, ':detail' => $detail, ':note' => $fsrUsername));

usleep(500000);
// Access the salesleads DB table for agent update
$sql = "SELECT leadMobile, infoRequested, leadDate FROM salesleads WHERE businessUsername=:buzusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $leadMobile = $row['leadMobile'];
  $info = $row['infoRequested'];
  $leadDate = $row['leadDate'];

  //$postdate = $row['notification_date'];
  $b = date_create($leadDate);
  $readabledate = date_format($b, 'g:ia \o\n l jS F Y');

  $agentfeed = '<div class="row">';
    $agentfeed .= '<div class="col-sm-3">';
       $agentfeed .= '<p>'.$agentFirstname.'</p>';
       $agentfeed .= '<img src="user/'.$agentUsername.'/'.$avatar.'" class="img-circle" height="55" width="55" alt="Avatar">';
    $agentfeed .= '</div>';
    $agentfeed .= '<div class="col-sm-9">';
      $agentfeed .= '<div class="panel panel-primary">';
      $agentfeed .= '<div class="panel-heading" ><b>Sales Lead/Inquiry</b></div>';
      $agentfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Lead\'s Mobile:</b> ' .$leadMobile.' </div>';
      $agentfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Information:</b> ' .$info.' </div>';
      $agentfeed .= '<div class="panel-body"> ' .$readabledate.' </div>';
      $agentfeed .= '</div>';
    $agentfeed .= '</div>';
  $agentfeed .= '</div>';
}
echo $agentfeed;
?>

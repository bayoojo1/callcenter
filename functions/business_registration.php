<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_POST['bzname'])){
    $bzname = preg_replace('#[^a-z0-9., ]#i', '', $_POST['bzname']);
    $bzaddr = preg_replace('#[^a-z0-9,\'.@:;/() ]#i', '', $_POST['bzaddr']);
    $bzcontact = preg_replace('#[^a-z0-9:;,. ]#i', '', $_POST['bzcontact']);
    $mobile = preg_replace('#[^0-9]#i', '', $_POST['mobile']);
    $website = preg_replace('#[^a-z0-9@./:]#i', '', $_POST['website']);
    $mail = preg_replace('#[^a-z0-9@.]#i', '', $_POST['mail']);
    $fsrmail = preg_replace('#[^a-z0-9@.]#i', '', $_POST['fsrmail']);
    $product = preg_replace('#[^a-z]#i', '', $_POST['product']);
    $service = preg_replace('#[^a-z]#i', '', $_POST['service']);
    $comment = preg_replace('#[^a-z0-9;\',:@(). ]#i', '', $_POST['comment']);
}

// Cut out frs username from the email
if(isset($fsrmail)) {
  $uparts = explode("@", $fsrmail);
  $fsrUsername = $uparts[0];
}
// Split and join the business name and change the first letter to small
$filter = str_replace(array('.', ',', ' '), '' , $bzname);
$alias = strtolower($filter);
// Check if this alias already exist
$sql = "SELECT id FROM businessdetails WHERE businessAlias=:alias LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();
if($numrows > 0) {
  $alias = $alias.''.mt_rand(10,1000);
}
// Insert the values into the DB.
$stmt = $db_connect->prepare("INSERT INTO businessdetails (businessEmail, username, businessName, businessAlias, address, contactName, mobile, product, service, website, fsrUsername, businessDescription, dateRegistered)
VALUES(:businessEmail, :username, :businessName, :businessAlias, :address, :contactName, :mobile, :product, :service, :website, :fsrUsername, :businessDescription, now())");
$stmt->execute(array(':businessEmail' => $mail, ':username' => $log_username, ':businessName' => $bzname, ':businessAlias' => $alias, ':address' => $bzaddr, ':contactName' => $bzcontact, ':mobile' => $mobile, ':product' => $product, ':service' => $service, ':website' => $website, ':fsrUsername' => $fsrUsername, ':businessDescription' => $comment));
// Update notification table
$detail = 'new business registration';
$action = 'bizRegistration';
$stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, notification_date)
VALUES(:initiator, :target, :action, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $fsrUsername, ':action' => $action, ':detail' => $detail));
// Echo status to Ajax
echo 'success';

?>

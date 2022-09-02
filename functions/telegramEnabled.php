<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: https://www.callnect.com");
    exit();
}
if(isset($_POST['username'])){
    $username = preg_replace('#[^a-z0-9._]#i', '', $_POST['username']);
}
// Get the mobile and first name of this user
$sql = "SELECT mobile, businessName FROM businessdetails WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $mobile = $rows['0'];
    $bizName = $rows['1'];
}
$m = '+234'.ltrim($mobile, '0');
$token = "959364317:AAEjHrvMc4bjFj3aZozblHJRoQoewh7RFRc";
$chat_id = '838972022';
$bot_url    = "https://api.telegram.org/bot$token/";
$url = $bot_url."sendContact?chat_id=".$chat_id."&phone_number=".$m."&first_name=".$bizName;
$data = file_get_contents($url);
$arr = json_decode($data, true);
// Get the needed variables
$userid = $arr['result']['contact']['user_id'];
// Update the businessdetails table with this userid
$sql = "UPDATE businessdetails SET telegramUserId=:userid WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
?>

<?php
include("../php_includes/mysqli_connect.php");
if(isset($_POST['bzmobile']) && !empty($_POST['bzmobile'])) {
  $bizmobile = preg_replace('#[^0-9+]#i', '', $_POST['bzmobile']);
  $mobile = preg_replace('#[^0-9+]#i', '', $_POST['mobile']);
  $comment = preg_replace('#[^a-z0-9:.,-?@!=+ \']#i', '', $_POST['comment']);
}
$message = '';
$sql = "SELECT telegramUserId FROM businessdetails WHERE mobile=:mobile";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':mobile', $bizmobile, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
  $chat_id = $row['0'];
}
if($chat_id != '') {
$token = "959364317:AAEjHrvMc4bjFj3aZozblHJRoQoewh7RFRc";
$message .= '<b>Customer Mobile:</b> ' .$mobile;
$message .="\n";
$message .= '<b>Information:</b> ' .$comment;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$token/sendMessage");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  $post = array(
      'chat_id' => $chat_id,
      'text' => $message,
      'parse_mode' => 'HTML',
  );
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

  $result = curl_exec($ch);

  if($result === false)
  {
      echo "Error Number:".curl_errno($ch)."<br>";
      echo "Error String:".curl_error($ch);
  }
  curl_close($ch);
}
?>

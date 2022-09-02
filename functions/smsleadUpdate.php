<?php
if(isset($_POST['bzmobile']) && !empty($_POST['bzmobile'])) {
  $bizmobile = preg_replace('#[^0-9+]#i', '', $_POST['bzmobile']);
  $mobile = preg_replace('#[^0-9+]#i', '', $_POST['mobile']);
  $comment = preg_replace('#[^a-z0-9:.,-?@!=+ \']#i', '', $_POST['comment']);
}

$sms_body = "Mobile: $mobile
Inquiry: $comment";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.bulksmsnigeria.com/api/v1/sms/create");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "rVxoRKLzm9lDk6EV2XkNT4hgPXxssCJ4ABgQxCFKweWqoShHrAji9yHyFZTK");
  $post = array(
      'from' => 'CallNect',
      'to' => $bizmobile,
      'body' => $sms_body,
  );
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

  $result = curl_exec($ch);

  if($result === false)
  {
      echo "Error Number:".curl_errno($ch)."<br>";
      echo "Error String:".curl_error($ch);
  }
  curl_close($ch);
?>

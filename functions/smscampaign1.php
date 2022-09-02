<?php
if(isset($_POST['mobile']) && !empty($_POST['mobile'])) {
  $mobile = preg_replace('#[^0-9+,]#i', '', $_POST['mobile']);
  $text = preg_replace('#[^a-z0-9:.,-?@!/=+ \']#i', '', $_POST['text']);
}
$array = explode(',', $mobile); // split mobile into array at comma

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.bulksmsnigeria.com/api/v1/sms/create");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "rVxoRKLzm9lDk6EV2XkNT4hgPXxssCJ4ABgQxCFKweWqoShHrAji9yHyFZTK");
  foreach($array as $m) {
  $post = array(
      'from' => 'CallNect',
      'to' => $m,
      'body' => $text,
  );

  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

  $result = curl_exec($ch);
}
  if($result === false) {
      echo "Error Number:".curl_errno($ch)."<br>";
      echo "Error String:".curl_error($ch);
  } else {
    echo "success";
  }
  curl_close($ch);
?>

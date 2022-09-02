<?php
if(isset($_POST['username'])) {
  $username = preg_replace('#[^a-z0-9.-_]#i', '', $_POST['username']);
  $ophour = preg_replace('#[^0-9-]#i', '', $_POST['ophour']);
  $duration = preg_replace('#[^0-9]#i', '', $_POST['duration']);
}
if(isset($_POST['plan'])) {
  $plan = preg_replace('#[^0-9]#i', '', $_POST['plan']);
} else {
  $plan = '';
}
if(isset($_POST['ivr'])) {
  $ivr = preg_replace('#[^0-9]#i', '', $_POST['ivr']);
} else {
  $ivr = '';
}
if(isset($_POST['chat'])) {
  $chat = preg_replace('#[^0-9]#i', '', $_POST['chat']);
} else {
  $chat = '';
}
if($ophour == '5' && $duration == '1') {
  echo number_format($plan + $ivr + $chat);
} else if($ophour == '5' && $duration == '3') {
  $sum = ($plan + $ivr + $chat);
  $twopercent =  0.02 * ($plan + $ivr + $chat);
  $discount =  $sum - $twopercent;
  echo number_format(3 * $discount);
} else if($ophour == '5' && $duration == '6') {
  $sum = ($plan + $ivr + $chat);
  $fivepercent =  0.05 * ($plan + $ivr + $chat);
  $discount =  $sum - $fivepercent;
  echo number_format(6 * $discount);
} else if($ophour == '5' && $duration == '12') {
  $sum = ($plan + $ivr + $chat);
  $tenpercent =  0.1 * ($plan + $ivr + $chat);
  $discount =  $sum - $tenpercent;
  echo number_format(12 * $discount);
} else if($ophour == '24-7' && $duration == '1') {
  echo number_format(80000 + $plan + $ivr + $chat); // Additional 2 agents at 40K naira per agent equals 80,000 extra
} else if($ophour == '24-7' && $duration == '3') {
  $sum = ($plan + $ivr + $chat);
  $twopercent =  0.02 * ($plan + $ivr + $chat);
  $discount =  $sum - $twopercent;
  echo number_format(3 * (80000 + $discount));
} else if($ophour == '24-7' && $duration == '6') {
  $sum = ($plan + $ivr + $chat);
  $fivepercent =  0.05 * ($plan + $ivr + $chat);
  $discount =  $sum - $fivepercent;
  echo number_format(6 * (80000 + $discount));
} else if($ophour == '24-7' && $duration == '12') {
  $sum = ($plan + $ivr + $chat);
  $tenpercent =  0.1 * ($plan + $ivr + $chat);
  $discount =  $sum - $tenpercent;
  echo number_format(12 * (80000 + $discount));
}
?>

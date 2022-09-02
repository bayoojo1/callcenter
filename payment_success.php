<?php
if(isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
// Send request to VoguePay
$data = file_get_contents('https://voguepay.com/?v_transaction_id='.$transaction_id.'&type=json&demo=true');
$arr = json_decode($data, true);

// Get all the needed variables to be used
$merchant_ref = $arr['merchant_ref'];
// Get variables and plans out of merchant_ref
$log_username = explode('_', $merchant_ref)[0];
header("location: https://www.callnect.com/billing/$log_username");
?>

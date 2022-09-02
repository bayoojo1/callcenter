<?php
include("php_includes/mysqli_connect.php");

if(isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
// Send request to VoguePay
$data = file_get_contents('https://voguepay.com/?v_transaction_id='.$transaction_id.'&type=json&demo=true');
$arr = json_decode($data, true);

// Get all the needed variables to be used
$merchant_id = $arr['merchant_id'];
$email = $arr['email'];
$total_amount = $arr['total_amount'];
$merchant_ref = $arr['merchant_ref'];
$memo = $arr['memo'];
$status = $arr['status'];
$date = $arr['date'];
$referrer = $arr['referrer'];
$total_credited_to_merchant = $arr['total_credited_to_merchant'];
$extra_charges_by_merchant = $arr['extra_charges_by_merchant'];
$charges_paid_by_merchant = $arr['charges_paid_by_merchant'];
$fund_maturity = $arr['fund_maturity'];
$currency = $arr['cur'];
$total_paid_by_buyer = $arr['total_paid_by_buyer'];
$total = $arr['total'];
// Get variables and plans out of merchant_ref
$log_username = explode('_', $merchant_ref)[0];
$plan = explode('_', $merchant_ref)[1];

// Set the end date of the subscription
$nextMonth = date('Y-m-d H:i:s', strtotime('+ 1 month'));

// Get the plan paid for from the package table
if(isset($plan) && !empty($plan)) {
$sql = "SELECT plan FROM package WHERE note=:note";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':note', $plan, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$mainPlan = $row[0];
} else {
  $mainPlan = "";
}

// Update the payment table of the transaction
$stmt = $db_connect->prepare("INSERT INTO subscription (username, package, beg_date, end_date, transaction_id, merchant_id, email, total_amount, memo, status, referrer, total_credited_to_merchant, extra_charges_by_merchant, charges_paid_by_merchant, cur, total_paid_by_buyer, total)
        VALUES(:username, :package, now(), :end_date, :transaction_id, :merchant_id, :email, :total_amount, :memo, :status, :referrer, :total_credited_to_merchant, :extra_charges_by_merchant, :charges_paid_by_merchant, :cur, :total_paid_by_buyer, :total )");

$stmt->execute(array(':username' => $log_username, ':package' => $mainPlan, ':end_date' => $nextMonth, ':transaction_id' => $transaction_id, ':merchant_id' => $merchant_id, ':email' => $email, ':total_amount' => $total_amount, ':memo' => $memo, ':status' => $status, ':referrer' => $referrer, ':total_credited_to_merchant' => $total_credited_to_merchant, ':extra_charges_by_merchant' => $extra_charges_by_merchant, ':charges_paid_by_merchant' => $charges_paid_by_merchant, ':cur' => $currency, ':total_paid_by_buyer' => $total_paid_by_buyer, ':total' => $total));
?>

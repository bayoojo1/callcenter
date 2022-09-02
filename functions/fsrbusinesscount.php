<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// Select the businesses in the portfolio of this fsr
$fsrbizlist = '';
$sql = "SELECT businessdetails.id, businessUsername, businessEmail, businessName, address, contactName, mobile, website, callnect_Number, businessDescription FROM businessdetails INNER JOIN fsr_business_alloc ON businessdetails.username=fsr_business_alloc.businessUsername WHERE fsr_business_alloc.fsrUsername=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
$stmt->execute();
echo $bizCount = $stmt->rowCount();
?>

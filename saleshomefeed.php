<?php
include_once("php_includes/mysqli_connect.php");
//include_once('functions/approval_status.php');
if(isset($_POST['bizusername']) && !empty($_POST['bizusername'])) {
  $username = $_POST['bizusername'];

  // Get all details about this business
  $sql = "SELECT businessEmail, businessName, businessAlias, address, contactName, mobile, website, businessDescription, agentdetails.agent_firstname, callnect_Number FROM businessdetails INNER JOIN agentdetails ON businessdetails.agentUsername=agentdetails.username WHERE businessdetails.username=:username";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();

  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $bizEmail = $row['businessEmail'];
    $bizName = $row['businessName'];
    $alias = $row['businessAlias'];
    $bizaddress = $row['address'];
    $bizcontact = $row['contactName'];
    $bizmobile = $row['mobile'];
    $bizwebsite = $row['website'];
    $bizDescription = $row['businessDescription'];
    $bizFSR = $row['agent_firstname'];
    $bizCallnectMobile = $row['callnect_Number'];
  }
}
$fsrfeed = '<div class="col-sm-7">';
    $fsrfeed .= '<div id="'.$username.'" class="panel panel-primary">';
    $fsrfeed .= '<div class="panel-heading" style="font-size:20px;"><b>'.$bizName.'</b></div>';
    $fsrfeed .= '<div class="panel-body bizdetails"><b>'.$bizDescription.'</b></div>';
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Address:</b> ' .$bizaddress.' </p>';
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Contact:</b> ' .$bizcontact.' </p>';
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Contact Mobile:</b> ' .$bizmobile.' </p>';
    if(isset($bizEmail) && !empty($bizEmail)) {
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Email:</b> '.$bizEmail.'</p>';
  }
    if(isset($bizwebsite) && !empty($bizwebsite)) {
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Website:</b><a href="'.$bizwebsite.'"> '.$bizwebsite.'</a></p>';
  }
    $fsrfeed .= '<p class="panel-body bizdetails"><b>Call Center Number:</b> '.$bizCallnectMobile.'</p>';
    $fsrfeed .= '<p class="panel-body bizdetails"><a href="/business/'.$alias.'"><b>Business Page</b></a></p>';
    $fsrfeed .= '</div>';
    $fsrfeed .= '<div id="fsragentComment"></div>';
$fsrfeed .= '</div>';
echo $fsrfeed;
?>

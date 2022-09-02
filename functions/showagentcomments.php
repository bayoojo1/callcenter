<?php
//include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if(isset($_POST['bizusername']) && !empty($_POST['bizusername'])) {
  $bizusername = $_POST['bizusername'];
  $agentUsername = $_POST['agentUsername'];
}
// Access the salesleads DB table for agent update
$sql = "SELECT agent_business_alloc.agentUsername, fsr_business_alloc.fsrUsername FROM agent_business_alloc LEFT OUTER JOIN fsr_business_alloc ON agent_business_alloc.businessUsername = fsr_business_alloc.businessUsername WHERE agent_business_alloc.businessUsername=:buzusername AND agentUsername=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $agentUsername = $row['agentUsername'];
  $fsrUsername = $row['fsrUsername'];
}
// Get the agent avatar and first name
$sql = "SELECT avatar, agentdetails.agent_firstname FROM users INNER JOIN agentdetails ON users.username=agentdetails.username WHERE users.username=:agentusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':agentusername', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $avatar = $row['avatar'];
  $agentFirstname = $row['agent_firstname'];
}
$perPage = 50;
$agentfeed = '';
// Access the salesleads DB table for agent update
$sql_statement = "SELECT leadMobile, infoRequested, leadDate FROM salesleads WHERE businessUsername=:buzusername AND agentUsername=:logusername ORDER BY id DESC";
$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();

// Select the first 4 posts
$limit = 'LIMIT ' . 0 .',' .$perPage;
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $agentUsername, PDO::PARAM_STR);
$stmt->execute();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $leadMobile = $row['leadMobile'];
  $info = $row['infoRequested'];
  $leadDate = $row['leadDate'];
  $b = date_create($leadDate);
  $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
  $agentfeed .= '<div class="row">';
    $agentfeed .= '<div class="col-sm-3">';
       $agentfeed .= '<p>'.$agentFirstname.'</p>';
       $agentfeed .= '<img src="user/'.$agentUsername.'/'.$avatar.'" class="img-circle" height="55" width="55" alt="Avatar">';
    $agentfeed .= '</div>';
    $agentfeed .= '<div class="col-sm-9">';
      $agentfeed .= '<div class="panel panel-primary">';
      $agentfeed .= '<div class="panel-heading" ><b>Update and Inquiry</b></div>';
      $agentfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Mobile:</b> ' .$leadMobile.' </div>';
      $agentfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Information:</b> ' .$info.' </div>';
      $agentfeed .= '<div class="panel-body"> ' .$readabledate.' </div>';
      $agentfeed .= '</div>';
    $agentfeed .= '</div>';
  $agentfeed .= '</div>';
//echo $agentfeed;
}
if($numrows > 50) {
$agentfeed .= '<div class="col-sm-offset-4 col-sm-7">';
$agentfeed .= '<div class="load-feed" style="background-color:forestgreen; color:white; border-radius:5px; cursor:pointer;" onclick="loadagentfeed();">Load More</div><br />';
$agentfeed .= '<input type="hidden" id="row" value="0">';
$agentfeed .= '</div>';
$agentfeed .= '<input type="hidden" id="allfeeds" value="'.$numrows.'">';
$agentfeed .= '<input type="hidden" id="buzusername" value="'.$bizusername.'">';
$agentfeed .= '<input type="hidden" id="agentusername" value="'.$agentUsername.'">';
$agentfeed .= '<input type="hidden" id="inc" value="">';
}
echo $agentfeed;
?>

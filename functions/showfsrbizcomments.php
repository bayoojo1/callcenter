<?php
//include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_POST['bizusername']) && !empty($_POST['bizusername'])) {
  $bizusername = $_POST['bizusername'];
  $log_username = $_POST['logusername'];
}
$perPage = 50;
$agentfeed = '';
// Access the salesleads DB table for agent update
$sql_statement = "SELECT salesleads.id, agentUsername, leadMobile, infoRequested, leadDate, users.avatar, agentdetails.agent_firstname FROM salesleads INNER JOIN users ON users.username=salesleads.agentUsername INNER JOIN agentdetails ON agentdetails.username=salesleads.agentUsername WHERE salesleads.fsrUsername=:logusername AND salesleads.businessUsername=:buzusername ORDER BY id DESC";
$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();

// Select the first 4 posts
$limit = 'LIMIT ' . 0 .',' .$perPage;
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':buzusername', $bizusername, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $leadMobile = $row['leadMobile'];
  $info = $row['infoRequested'];
  $leadDate = $row['leadDate'];
  $b = date_create($leadDate);
  $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
  $agentUsername = $row['agentUsername'];
  $agentFirstname = $row['agent_firstname'];
  $avatar = $row['avatar'];

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

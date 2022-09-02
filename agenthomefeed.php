<?php
include_once("php_includes/mysqli_connect.php");
if(isset($_POST['username']) && !empty($_POST['username'])) {
  $username = $_POST['username'];
  $agentUsername = $_POST['agentUsername'];


  // Get all details about this business
  $sql = "SELECT businessEmail, businessName, address, contactName, mobile, website, businessDescription, fsrdetails.fsr_firstname, fsrdetails.fsr_mobile, callnect_Number FROM businessdetails INNER JOIN fsrdetails ON businessdetails.fsrUsername=fsrdetails.username WHERE businessdetails.username=:username";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $bizEmail = $row['businessEmail'];
    $bizName = $row['businessName'];
    $bizaddress = $row['address'];
    $bizcontact = $row['contactName'];
    $bizmobile = $row['mobile'];
    $bizwebsite = $row['website'];
    $bizDescription = $row['businessDescription'];
    $bizFSR = $row['fsr_firstname'];
    $fsrMobile = $row['fsr_mobile'];
    $bizCallnectMobile = $row['callnect_Number'];

    $smsUpdate = '';
    // Check if this business should receive sms update
    $sql = "SELECT smsUpdate FROM useroptions WHERE username=:bisusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':bisusername', $username, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $rows) {
      if($rows['0'] == 'Yes') {
        $smsUpdate = true;
      } else {
        $smsUpdate = false;
      }
    }
  }
}
$agentfeed = '<div class="col-sm-7">';
    $agentfeed .= '<div id="'.$username.'" class="panel panel-primary">';
    $agentfeed .= '<div class="panel-heading" style="font-size:20px;"><b>'.$bizName.'</b></div>';
    $agentfeed .= '<div class="panel-body bizdetails"><b>'.$bizDescription.'</b></div>';
    $agentfeed .= '<p class="panel-body bizdetails"><b>Address:</b> ' .$bizaddress.' </p>';
    $agentfeed .= '<p class="panel-body bizdetails"><b>Contact:</b> ' .$bizcontact.' </p>';
    $agentfeed .= '<p class="panel-body bizdetails"><b>Contact Mobile:</b> ' .$bizmobile.' </p>';
    if(isset($bizEmail) && !empty($bizEmail)) {
    $agentfeed .= '<p class="panel-body bizdetails"><b>Email:</b> '.$bizEmail.'</p>';
  }
    if(isset($bizwebsite) && !empty($bizwebsite)) {
    $agentfeed .= '<p class="panel-body bizdetails"><b>Website:</b><a href="'.$bizwebsite.'"> '.$bizwebsite.'</a></p>';
  }
    $agentfeed .= '<p class="panel-body bizdetails"><b>Call Center Number:</b> '.$bizCallnectMobile.'</p>';
    $agentfeed .= '</div>';
      $agentfeed .= '<form class="form-horizontal">';
      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="leadmobile">Mobile:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="text" class="form-control" id="mobile1" placeholder="Enter sales lead mobile number here..." name="mobile">';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';

      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="leadmail">Email:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="text" class="form-control" id="mail1" placeholder="Enter sales lead email here..." name="mail">';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';
      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="leadname">Name:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="text" class="form-control" id="name1" placeholder="Enter sales lead name here..." name="name1">';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';
      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="leadlocation">Location:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="text" class="form-control" id="location1" placeholder="Enter sales lead location here..." name="location">';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';

      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="leadkeyword">Keyword:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="text" class="form-control" id="keyword1" placeholder="Enter inquiry keyword here..." name="keyword">';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';

      $agentfeed .= '<div id="formreset" class="form-group">';
      $agentfeed .= '<label class="control-label col-sm-2" for="comment">Inquiry:</label>';
      $agentfeed .= '<div class="col-sm-10">';
      $agentfeed .= '<input type="hidden" id="bzusername" value="'.$username.'">';
      $agentfeed .= '<input type="hidden" id="bzmobile" value="'.$bizmobile.'">';
      $agentfeed .= '<input type="hidden" id="agentUsername" value="'.$agentUsername.'">';
      $agentfeed .= '<textarea class="form-control" rows="3" id="comment1"></textarea>';
      $agentfeed .= '</div>';
      $agentfeed .= '<div class="col-sm-offset-2 col-sm-10">';
      $agentfeed .= '<label class="checkbox-inline"><input type="checkbox" id="smsupdate">SMS Update</label>';
      $agentfeed .= '<label class="checkbox-inline"><input type="checkbox" id="emailupdate">Email Update</label>';
      $agentfeed .= '<label class="checkbox-inline"><input type="checkbox" id="phoneupdate">Phone Update</label>';
      $agentfeed .= '<label class="checkbox-inline"><input type="checkbox" id="telegramupdate">Telegram Update</label>';
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';
      $agentfeed .= '<div class="form-group">';
      $agentfeed .= '<div class="col-sm-offset-2 col-sm-10">';
      if($smsUpdate) {
      $agentfeed .= '<div id="postbtn1" style="background-color:forestgreen; color:white; cursor:pointer;" onclick="agentPost();smsPost();telegramPost();">Submit</div>';
    } else {
      $agentfeed .= '<div id="postbtn1" style="background-color:mediumvioletred; color:white; cursor:pointer;" onclick="agentPost();telegramPost();">Submit</div>';
    }
      $agentfeed .= '</div>';
      $agentfeed .= '</div>';
      $agentfeed .= '<div id="submitstatus" style="text-align:center;"></div>';
      $agentfeed .= '</form>';
      $agentfeed .= '<div id="agentComment"></div>';
$agentfeed .= '</div>';
echo $agentfeed;
?>

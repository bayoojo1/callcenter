<?php
include('functions/approval_status.php');
// Get some variables to be used on this page
$sql ="SELECT agent_business_alloc.agentUsername, agent_business_alloc.businessUsername, agentdetails.agent_firstname, businessdetails.businessName FROM agent_business_alloc LEFT OUTER JOIN agentdetails ON agent_business_alloc.agentUsername=agentdetails.username LEFT OUTER JOIN businessdetails ON agent_business_alloc.businessUsername=businessdetails.username WHERE businessUsername=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $agentUsername = $row['agentUsername'];
  $businessUsername = $row['businessUsername'];
  $businessName = $row['businessName'];
  $agentFirstname = $row['agent_firstname'];
}
// Get the agent avatar for this user
$sql = "SELECT avatar FROM users WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$agt_avatar = $row[0];
// Start the process of updating the user home feed
$userfeed = '<div class="col-sm-12">';
$paginationCtrls = '<span>';
if($isMessage && $isApproved) {
  // Get some variables to be used
  $sql_trans = "SELECT * FROM salesleads WHERE businessUsername=:logusername ORDER BY id DESC";
  $stmt = $db_connect->prepare($sql_trans);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  $message_count = $stmt->rowCount();
  // Specify how many result per page
  $rpp = '10';
  // This tells us the page number of the last page
  $last = ceil($message_count/$rpp);
  // This makes sure $last cannot be less than 1
  if($last < 1){
      $last = 1;
  }
  // Define pagination control
  //$paginationCtrls = "";
  // Define page number
  $pn = "1";

  // Get pagenum from URL vars if it is present, else it is = 1
  if(isset($_GET['pn'])){
      $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
  //$searchquery = $_POST['searchquery'];
  }

  // Make the script run only if there is a page number posted to this script

  // This makes sure the page number isn't below 1, or more than our $last page
  if ($pn < 1) {
      $pn = 1;
  } else if ($pn > $last) {
  $pn = $last;
  }

  // This sets the range of rows to query for the chosen $pn
  $limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;
  // This is the query again, it is for grabbing just one page worth of rows by applying $limit
  $sql = "$sql_trans"." $limit";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  if($message_count > 0){
    //$paginationCtrls .= '<div class="col-sm-9">';
    $paginationCtrls .= '<ul class="pagination">';
    if($last != 1){
        /* First we check if we are on page one. If we are then we don't need a link to
           the previous page or the first page so we do nothing. If we aren't then we
           generate links to the first page, and to the previous page. */
        if ($pn > 1) {
            $previous = $pn - 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$previous.'">Previous</a></li> &nbsp; &nbsp;';
            // Render clickable number links that should appear on the left of the target page number
            for($i = $pn-4; $i < $pn; $i++){
                if($i > 0){
                    $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
                }
            }
        }
        // Render the target page number, but without it being a link
        $paginationCtrls .= '<li class="active"><a href="#">'.$pn.'</a></li> &nbsp; ';
        // Render clickable number links that should appear on the right of the target page number
        for($i = $pn+1; $i <= $last; $i++){
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
            if($i >= $pn+4){
                break;
            }
        }
        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
        if ($pn != $last) {
            $next = $pn + 1;
            $paginationCtrls .= ' &nbsp; &nbsp; <li><a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$next.'">Next</a></li>';
        }
    }
    $paginationCtrls .= '</ul>';
    $paginationCtrls .= '</span>';

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $agentUsername = $row['agentUsername'];
      //$agent_lead = $row['agentUsername'];
      $fsrUsername = $row['fsrUsername'];
      $businessUsername = $row['businessUsername'];
      $leadMobile = $row['leadMobile'];
      $info = $row['infoRequested'];
      $leadDate = $row['leadDate'];
      $b = date_create($leadDate);
      $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
    // Update the user timeline

    $userfeed .= '<div class="row">';
      $userfeed .= '<div class="col-sm-3">';
      if(isset($agentFirstname)) {
         $userfeed .= '<p>'.$agentFirstname.'</p>';
         $userfeed .= '<img src="user/'.$agentUsername.'/'.$agt_avatar.'" class="img-circle" height="55" width="55" alt="Avatar">';
       } else {
         $userfeed .= '<p>Alisson</p>';
         $userfeed .= '<img src="user/fsr_dummy.png" class="img-circle" height="55" width="55" alt="Avatar">';
       }
      $userfeed .= '</div>';
      $userfeed .= '<div class="col-sm-9">';
        $userfeed .= '<div class="panel panel-primary">';
        $userfeed .= '<div class="panel-heading" ><b>Update and Inquiry</b></div>';
        $userfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Mobile:</b> ' .$leadMobile.' </div>';
        $userfeed .= '<div class="panel-body" style="margin-bottom:-15px;"><b>Information:</b> ' .$info.' </div>';
        $userfeed .= '<div class="panel-body"> ' .$readabledate.' </div>';
        $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '</div>';
    }
  }
} else if($isApproved) {
      $userfeed .= '<div class="row">';
      $userfeed .= '<div class="col-sm-3">';
      if(isset($agentFirstname)) {
        $userfeed .= '<p>'.$agentFirstname.'</p>';
        $userfeed .= '<img src="user/'.$agentUsername.'/'.$agt_avatar.'" class="img-circle" height="55" width="55" alt="Avatar">';
      }
      $userfeed .= '</div>';
        $userfeed .= '<div class="col-sm-9">';
          $userfeed .= '<div class="well">';
            $userfeed .= '<p>Hello <b>' .$businessName.'</b>, my name is <b>' .$agentFirstname.'</b> and I would be your CallNect lead agent as long as your business remains on this platform. </p>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
      $userfeed .= '</div>';
    } else if($isRegistered) {
      $userfeed .= '<div class="row">';
        $userfeed .= '<div class="col-sm-12">';
          $userfeed .= '<div class="well">';
            $userfeed .= '<span style="font-size:18px;"><b>You are welcome once again to CallNect platform.</b></span><br><br> As a registered business on this platform, there is no more idle moment for your business, as we are awake 24/7 to keep your business running. As you await your registration approval, there are few steps before you start enjoying our great and affordable services.<br><br>
            A sales representative will contact you shortly to get more details about your business. This would enable us to offer you the best.<br><br>
            You can also chat with our agent in other to finalize your registration process.<br>
            Please check the available <a href="billing.php?u='.$log_username.'">plans and packages</a> that suit your need.<br> Your registration will be approved once we have every required details about your product and services, thereafter you can subscribe to a package of your choice.';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
      $userfeed .= '</div>';
    } else {
      $userfeed .= '<div class="row">';
        $userfeed .= '<div class="col-sm-12">';
          $userfeed .= '<div class="well">';
            $userfeed .= '<p>You are welcome to CallNect. We would need more information about your business in other to serve you better. Please fill the form below to provide the required information about your business.</p>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
      $userfeed .= '</div>';
      $userfeed .= '<h3>Provide Your Business Details</h3>';
      $userfeed .= '<form class="form-horizontal">';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="buzname">Business Name:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="text" class="form-control" id="buzname" placeholder="Enter business name" name="buzname" required>';
            $userfeed .= '<input type="hidden" id="logusername" value="'.$log_username.'">';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="buzAdd">Business Address:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="text" class="form-control" id="buzAdd" placeholder="Enter business address" name="buzAdd" required>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="buzContact">Business Contact:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="text" class="form-control" id="buzContact" placeholder="Enter the name of a contact person" name="buzContact" required>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="mobile">Contact Mobile:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="text" class="form-control" id="mobile" placeholder="Enter business contact mobile number" name="mobile" required>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="website">Business Website:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="text" class="form-control" id="website" placeholder="Enter business website(Optional)" name="website">';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="email">Business Email:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="email" class="form-control" id="email" placeholder="Enter business email(Optional)" name="email">';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<label class="control-label col-sm-2" for="fsremail">FSR Email:</label>';
          $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<input type="email" class="form-control" id="fsremail" placeholder="Enter CallNect FSR email(Optional)" name="fsremail">';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
              $userfeed .= '<label class="control-label col-sm-2">Business Type:</label>';
              $userfeed .= '<div class="col-sm-10">';
            $userfeed .= '<label class="checkbox-inline"><input type="checkbox" name="product" value="product">Product</label>';
            $userfeed .= '<label class="checkbox-inline"><input type="checkbox" name="service" value="service">Service</label>';
        $userfeed .= '</div>';
        $userfeed .= '</div>';
          $userfeed .= '<div class="form-group">';
            $userfeed .= '<label for="comment">A brief description of your business:</label>';
            $userfeed .= '<div class="col-sm-10 col-sm-push-2">';
            $userfeed .= '<textarea class="form-control" rows="5" id="comment" required></textarea>';
            $userfeed .= '</div>';
          $userfeed .= '</div>';
        $userfeed .= '<div class="form-group">';
          $userfeed .= '<div class="col-sm-offset-2 col-sm-10">';
            $userfeed .= '<button type="submit" id="submitbtn" onclick="submitBuzForm()" class="btn btn-success btn-block">Submit</button>';
          $userfeed .= '</div>';
        $userfeed .= '</div>';
        $userfeed .= '<div id="submitstatus" style="text-align:center;"></div>';
      $userfeed .= '</form>';
    }
    echo $paginationCtrls;
    $userfeed .= '</div>';
    echo $userfeed;
?>

<?php
include("./php_includes/mysqli_connect.php");
include("./php_includes/a2billing_db.php");
$paginationCtrls = '<span>';
$now = strtotime(date("Y-m-d H:i:s"));
// Select required variables from businessdetails table
$sql_trans = "SELECT id, businessEmail, username, businessName, businessAlias, address, contactName, mobile, product, service, website, agentUsername, fsrUsername, callnect_Number, businessDescription, dateRegistered, approval FROM businessdetails ORDER BY id DESC";
$stmt = $db_connect->prepare($sql_trans);
$stmt->execute();
$count = $stmt->rowCount();
// Specify how many result per page
$rpp = '1';
// This tells us the page number of the last page
$last = ceil($count/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
// Define page number
$pn = "1";

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
    $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
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
$stmt->execute();
if($count > 0){
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

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $profile_id = $row['id'];
    $email = $row['businessEmail'];
    $username = $row['username'];
    $bizName = $row['businessName'];
    $bizAlias = $row['businessAlias'];
    $address = $row['address'];
    $bizContact = $row['contactName'];
    $mobile = $row['mobile'];
    $product = $row['product'];
    $service = $row['service'];
    $website = $row['website'];
    $agentUsername = $row['agentUsername'];
    $fsrUsername = $row['fsrUsername'];
    $callnect_Number = $row['callnect_Number'];
    $bizDesc = $row['businessDescription'];
    $regDate = $row['dateRegistered'];
    $approval = $row['approval'];
    // Check the subscription status of this business
      $sql = "SELECT id, package, live_chat, social_media, outbound_camp, sms_camp, email_camp,  beg_date, end_date FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $rows) {
        $package = $rows['package'];
        $social = $rows['social_media'];
        $chat = $rows['live_chat'];
        $outbound = $rows['outbound_camp'];
        $sms = $rows['sms_camp'];
        $email_camp = $rows['email_camp'];
        $beg_date = $rows['beg_date'];
        $end_date = $rows['end_date'];
      }
      // Get the account balance of this user
      $sql = "SELECT credit FROM cc_card WHERE email=:email";
      $stmt = $a2billing_connect->prepare($sql);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch();
      $credit = $row[0];
      // Get the Telegram update status
      $sql = "SELECT telegramUpdate FROM useroptions WHERE username=:username";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch();
      $telegramStatus = $row[0];
      // Check if telegram userid is already in DB
      $sql = "SELECT telegramUserId FROM businessdetails WHERE username=:username";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $row = $stmt->fetch();
      $telegramUserId = $row[0];
    echo $paginationCtrls;
  echo '<div id="bizownertab">';
    echo '<table class="table table-hover table-bordered">';
      echo '<tr>';
        echo '<td>Business Name:</td>';
        echo '<td id="sadminbusname_'.$profile_id.'">'.$bizName.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Alias:</td>';
        echo '<td id="sadminbusalias_'.$profile_id.'">'.$bizAlias.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Address:</td>';
        echo '<td id="sadminbusadd_'.$profile_id.'">'.$address.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Contact Person:</td>';
        echo '<td id="sadminbuscontact_'.$profile_id.'">'.$bizContact.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Contact Mobile:</td>';
        echo '<td id="sadminbizmobile_'.$profile_id.'">'.$mobile.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Category A:</td>';
        if(isset($product) && $product == 'undefined') {
        echo '<td></td>';
      } else {
        echo '<td>'.$product.'</td>';
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Category B:</td>';
        if(isset($service) && $service == 'undefined') {
        echo '<td></td>';
      } else {
        echo '<td>'.$service.'</td>';
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Email:</td>';
        echo '<td>'.$email.'</td>';
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Username:</td>';
        echo '<td>'.$username.'</td>';
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Website:</td>';
        echo '<td id="sadminbusSite_'.$profile_id.'">'.$website.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Agent Username:</td>';
        echo '<td id="agentuser_'.$profile_id.'">'.$agentUsername.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>FSR Username:</td>';
        echo '<td id="fsruser_'.$profile_id.'">'.$fsrUsername.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>CallNect Number:</td>';
        echo '<td id="callnectnumb_'.$profile_id.'">'.$callnect_Number.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Business Description:</td>';
        echo '<td id="sadminbizdesc_'.$profile_id.'">'.$bizDesc.'</td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';

      echo '<tr>';
        echo '<td>Telegram Update Status:</td>';
        echo '<td id="telegram_'.$username.'">'.$telegramStatus.'</td>';
        if($telegramUserId == '') {
        echo '<td><button type="button" class="btn btn-danger btn-sm" onclick="enable(this);">Enable?</button></td>';
      } else {
        echo '<td><button type="button" class="btn btn-success btn-sm" onclick="enable(this);">Enabled</button></td>';
      }
        echo '<td></td>';
      echo '</tr>';

      echo '<tr>';
        echo '<td>Registered Date:</td>';
        if(isset($regDate)) {
        echo '<td>'.$regDate.'</td>';
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Subscription Package:</td>';
        if(isset($package)) {
        echo "<td>"; ?><span style='font-weight:bold;'><u>Main Plan:</u></span><br>
        <span style='color:darkgreen;'><?php echo $package ?></span><br>
        <span style='font-weight:bold;'><u>Available Balance:</u></span><br>
        <?php if(isset($credit)) { ?>
        <span style='color:darkgreen;'>&#8358;<?php echo number_format((float)$credit, 2, '.', ',') ?></span>
      <?php } ?>
        <br>
        <span style='font-weight:bold;'><u>Optional Services:</u></span><br>
        Social Media Page Management: <input type='checkbox' id='social_<?php echo $username;?>' onchange='optionalServices(id);' <?php if( $social == 'Yes'){ echo "checked"; } ?>><br>
        Live Chat: <input type='checkbox' id='chat_<?php echo $username;?>' onchange='optionalServices(id);' <?php if( $chat == 'Yes'){ echo "checked"; } ?>><br>
        Outbound Campaign: <input type='checkbox' id='outbound_<?php echo $username;?>' onchange='optionalServices(id);' <?php if( $outbound == 'Yes'){ echo "checked"; } ?>><br>
        Email Campaign: <input type='checkbox' id='email_<?php echo $username;?>' onchange='optionalServices(id);' <?php if( $email_camp == 'Yes'){ echo "checked"; } ?>><br>
        SMS Campaign: <input type='checkbox' id='sms_<?php echo $username;?>' onchange='optionalServices(id);' <?php if( $sms == 'Yes'){ echo "checked"; } ?>>
        <?php echo "</td>";
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Billing Start Date:</td>';
        if(isset($beg_date)) {
        echo '<td>'.$beg_date.'</td>';
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Billing End Date:</td>';
        if(isset($end_date)) {
        echo '<td>'.$end_date.'</td>';
      }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Main Plan Subscription Active?:</td>';
        if(isset($beg_date) && isset($end_date)) {
        if(strtotime($beg_date) <= $now && $now <= strtotime($end_date)) {
        echo '<td>Yes</td>';
      } else {
        echo '<td>No</td>';
      }
    }
        echo '<td></td>';
        echo '<td></td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Approval:</td>';
        echo '<td>'.$approval.'</td>';
        echo "<td>"; ?><div class='radio'><label><input type='radio' id='approval_yes_<?php echo $profile_id;?>' onclick='approve(id);' name='optradio' <?php if( $approval == 'yes'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
        echo "<td>"; ?><div class='radio'><label><input type='radio' id='approval_no_<?php echo $profile_id;?>' onclick='disapprove(id);' name='optradio' <?php if( $approval == 'no'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
      echo '</tr>';
    echo '</table>';
  echo '</div>';
  }
}
?>

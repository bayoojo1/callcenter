<?php
include("./php_includes/mysqli_connect.php");

// Get the agent first name and mobile number
if($isAgent) {
  $sql = "SELECT agent_firstname, agent_mobile FROM agentdetails WHERE username=:logusername";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll() as $row) {
    $agentName = $row['0'];
    $agentMobile = $row['1'];
  }
}
// Get FSR first name and mobile
if($isSales) {
  $sql = "SELECT fsr_firstname, fsr_mobile FROM fsrdetails WHERE username=:logusername";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll() as $row) {
    $fsrName = $row['0'];
    $fsrMobile = $row['1'];
  }
}
?><?php
$sql = "SELECT useFirstName FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $useFirstName = $rows['0'];
}

$sql = "SELECT useBizName FROM useroptions WHERE username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $useBizName = $rows['0'];
}

$sql = "SELECT smsUpdate FROM useroptions WHERE username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $smsUpdate = $rows['0'];
}

$sql = "SELECT telegramUpdate FROM useroptions WHERE username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $telegramUpdate = $rows['0'];
}

$sql = "SELECT tagImage FROM useroptions WHERE username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $tagImage = $rows['0'];
}
?><?php
echo '<table class="table table-hover">';
if($isSuperadmin) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Super Admin</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isUser && $isApproved) {
    echo '<tr>';
      echo '<td>Profile Status:</td>';
      echo '<td>Business Owner</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isAgent) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Agent</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isSales) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Field Sales Representative(FSR)</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isAdmin) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Administrator</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isManager) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Manager</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isSupervisor) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Supervisor</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isBilling) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Billing</td>';
    echo '<td></td>';
  echo '</tr>';
} else if($isSupport) {
  echo '<tr>';
    echo '<td>Profile Status:</td>';
    echo '<td>Technical Support</td>';
    echo '<td></td>';
  echo '</tr>';
}
  echo '<tr>';
    echo '<td>Username:</td>';
    echo '<td>'.$username.'</td>';
    echo '<td></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>Email:</td>';
    echo '<td>'.$email.'</td>';
    echo '<td></td>';
  echo '</tr>';
  if($isUser && $isApproved) {
    echo '<tr>';
      echo '<td>Business Page URL:</td>';
      echo '<td>http://localhost:8080/callcenter/chat.php?query='.$row['businessAlias'].'</td>';
      echo '<td></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td>Business Name:</td>';
      echo '<td id="bizname_'.$profile_id.'">'.$row['businessName'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td data-toggle="tooltip" title="Note: Business alias should NOT contain space, comma or full stop. It should be all small letter. Example: callnectnigltd">Business Alias:<i class="fas fa-info-circle"></i></td>';
      echo '<td id="bizalias_'.$profile_id.'">'.$row['businessAlias'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td>Use Business Name as Profile Name:</td>';
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='useBizName1_Yes_<?php echo $profile_id;?>' onclick='useBizName(id);' name='optradio' <?php if( $useBizName == 'Yes'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='useBizName2_No_<?php echo $profile_id;?>' onclick='useBizName(id);' name='optradio' <?php if( $useBizName == 'No'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
    echo '</tr>';
    echo '<tr>';
      echo '<td>SMS Lead Update:</td>';
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='smsUpdate_Yes_<?php echo $profile_id;?>' onclick='smsUpdate(id);' name='optionradio' <?php if( $smsUpdate == 'Yes'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='smsUpdate_No_<?php echo $profile_id;?>' onclick='smsUpdate(id);' name='optionradio' <?php if( $smsUpdate == 'No'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
    echo '</tr>';

    echo '<tr>';
      echo '<td>Telegram Lead Update:</td>';
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='telegramUpdate_Yes_<?php echo $profile_id;?>' onclick='telegramUpdate(id);' name='teleradio' <?php if( $telegramUpdate == 'Yes'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='telegramUpdate_No_<?php echo $profile_id;?>' onclick='telegramUpdate(id);' name='teleradio' <?php if( $telegramUpdate == 'No'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
    echo '</tr>';

    echo '<tr>';
      echo '<td>Tag Uploaded Picture:</td>';
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='tagImage_Yes_<?php echo $profile_id;?>' onclick='tagImage(id);' name='opradio' <?php if( $tagImage == 'Yes'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
      echo "<td>"; ?><div class='radio'><label><input type='radio' id='tagImage_No_<?php echo $profile_id;?>' onclick='tagImage(id);' name='opradio' <?php if( $tagImage == 'No'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
    echo '</tr>';
    echo '<tr>';
      echo '<td>Business Address:</td>';
      echo '<td id="bizaddress_'.$profile_id.'">'.$row['address'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td>Business Contact:</td>';
      echo '<td id="bizcontact_'.$profile_id.'">'.$row['contactName'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td>Contact Mobile:</td>';
      echo '<td id="contactmobile_'.$profile_id.'">'.$row['mobile'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
    echo '<tr>';
      echo '<td>Call Center Number:</td>';
      echo '<td>'.$callnect_Number.'</td>';
      echo '<td></td>';
    echo '</tr>';
    if(isset($row['website'])) {
      echo '<tr>';
        echo '<td>Website:</td>';
        echo '<td id="website_'.$profile_id.'">'.$row['website'].'</td>';
        echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
        echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
      echo '</tr>';
    }
    echo '<tr>';
      echo '<td>Business Description:</td>';
      echo '<td id="bizdescription_'.$profile_id.'">'.$row['businessDescription'].'</td>';
      echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
      echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
    echo '</tr>';
}
if($isAgent) {
  echo '<tr>';
    echo '<td>Mobile:</td>';
    echo '<td id="agentmobile_'.$profile_id.'">'.$agentMobile.'</td>';
    echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>First Name:</td>';
    echo '<td>'.$agentName.'</td>';
    echo '<td></td>';
  echo '</tr>';
  }
if($isSales) {
  echo '<tr>';
    echo '<td>Mobile:</td>';
    echo '<td id="fsrmobile_'.$profile_id.'">'.$fsrMobile.'</td>';
    echo '<td><button type="button" class="btn btn-info btn-sm" onclick="edit(this)"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-success btn-sm" style="visibility:hidden" onclick="save(this)"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>First Name:</td>';
    echo '<td>'.$fsrName.'</td>';
    echo '<td></td>';
  echo '</tr>';
}
  echo '<tr>';
    echo '<td>Change Profile Picture:</td>';
    echo '<td>';
    echo '<form enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">';
    echo '<div class="form-group">';
      echo '<input type="file" class="form-control-file border" name="avatar" required>';
      echo '</div>';
    echo '</td>';
    echo '<td>';
      echo '<button type="submit" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-upload"></span> Upload</button>';
  echo '</form>';
    echo '</td>';
  echo '</tr>';
echo '</table>';
?>

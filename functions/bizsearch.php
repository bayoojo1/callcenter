<?php
include("../php_includes/mysqli_connect.php");
$now = strtotime(date("Y-m-d H:i:s"));
if(isset($_POST['searchquery']) && !empty($_POST['searchquery'])) {
  $searchquery = preg_replace('#[^a-z0-9?@.!]#i', '', $_POST['searchquery']);
}
// Select required variables from businessdetails table
$sql = "SELECT id, businessEmail, username, businessName, businessAlias, address, contactName, mobile, product, service, website, agentUsername, fsrUsername, callnect_Number, businessDescription, dateRegistered, approval FROM businessdetails WHERE (businessEmail LIKE :businessEmail OR businessAlias LIKE :businessAlias OR callnect_Number LIKE :callnect_Number) LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':businessEmail', '%'.$searchquery.'%', PDO::PARAM_STR);
$stmt->bindValue(':businessAlias', '%'.$searchquery.'%', PDO::PARAM_STR);
$stmt->bindValue(':callnect_Number', '%'.$searchquery.'%', PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count > 0) {
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
      $sql = "SELECT id, package, beg_date, end_date FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $rows) {
        $package = $rows['package'];
        $beg_date = $rows['beg_date'];
        $end_date = $rows['end_date'];
        //$renewed = $rows['renewed'];
      }
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
        echo '<td>'.$package.'</td>';
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
        echo '<td>Subscription Active?:</td>';
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
  }
} else {
  echo '<div class="alert alert-warning">';
    echo '<strong>Warning!</strong> The business you are searching does not exist. You can search with business alias, business email or call center number, etc.';
  echo '</div>';
}
?>

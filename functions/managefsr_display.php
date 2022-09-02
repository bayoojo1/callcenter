<?php
include("../php_includes/mysqli_connect.php");

if(isset($_POST['searchfsr']) && !empty($_POST['searchfsr'])){
        $searchfsr = preg_replace('#[^a-z0-9.@?!]#i', '', $_POST['searchfsr']);
        $sql = "SELECT users.id, email, users.username, useroptions.usertype, fsrdetails.fsr_firstname, fsrdetails.fsr_mobile FROM users INNER JOIN useroptions ON users.username=useroptions.username INNER JOIN fsrdetails ON users.username=fsrdetails.username WHERE (users.username LIKE :username OR email LIKE :email) AND useroptions.usertype=:usertype";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':username', '%'.$searchfsr.'%', PDO::PARAM_STR);
    $stmt->bindValue(':email', '%'.$searchfsr.'%', PDO::PARAM_STR);
    $stmt->bindValue(':usertype', 'sales', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $profile_id = $row['id'];
        $email = $row['email'];
        $username = $row['username'];
        $usertype = $row['usertype'];
        $fsrFirstname = $row['fsr_firstname'];
        $fsrMobile = $row['fsr_mobile'];
}

echo '<table class="table table-hover">';
  echo '<tr>';
    echo '<td>Email:</td>';
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
    echo '<td>User Type:</td>';
    echo '<td>'.$usertype.'</td>';
    echo '<td></td>';
    echo '<td></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>FSR First Name:</td>';
    if(isset($fsrFirstname)) {
    echo '<td id="fsrFName_'.$username.'">'.$fsrFirstname.'</td>';
  } else {
    echo '<td id="fsrFName_'.$username.'"></td>';
  }
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>FSR Mobile:</td>';
    if(isset($fsrMobile)) {
    echo '<td id="fsrMobile_'.$username.'">'.$fsrMobile.'</td>';
  } else {
    echo '<td id="fsrMobile_'.$username.'"></td>';
  }
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
echo '</table>';
  } else {
    echo '<div class="alert alert-danger">';
    echo '<strong>Error</strong> The username or email you entered does not exist or not an FSR.';
    echo '</div>';
  }
}
?>

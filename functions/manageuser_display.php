<?php
include("../php_includes/mysqli_connect.php");

if(isset($_POST['searchquery']) && !empty($_POST['searchquery'])){
        $searchquery = preg_replace('#[^a-z0-9.@?!]#i', '', $_POST['searchquery']);
        $sql = "SELECT users.id, email, users.username, useroptions.usertype, useroptions.useBizName, useroptions.useFirstName FROM users INNER JOIN useroptions ON users.username=useroptions.username WHERE (users.username LIKE :username OR email LIKE :email)";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':username', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':email', '%'.$searchquery.'%', PDO::PARAM_STR);
    //$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $profile_id = $row['id'];
        $email = $row['email'];
        $username = $row['username'];
        $usertype = $row['usertype'];
        $useBizName = $row['useBizName'];
        $useFirstName = $row['useFirstName'];
}
// Check approval status of this user

$sql = "SELECT activated FROM users WHERE username=:username OR email=:email";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $rows) {
    $activate = $rows['0'];
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
    echo '<td id="usertype_'.$profile_id.'">'.$usertype.'</td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="editusertype(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
  echo '<tr>';
  echo '<td>Use Business Name as Profile Name:</td>';
  echo '<td>'.$useBizName.'</td>';
  echo '<td></td>';
  echo '<td></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>Use First Name as Profile Name:</td>';
    echo '<td>'.$useFirstName.'</td>';
    echo '<td></td>';
    echo '<td></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>Deactivate User:</td>';
    echo "<td>"; ?><div class='radio'><label><input type='radio' id='deactivate_0_<?php echo $profile_id;?>' onclick='deactivate(id);' name='optradio' <?php if( $activate == '0'){ echo "checked"; } ?>>Yes</label></div><?php echo "</td>";
    echo "<td>"; ?><div class='radio'><label><input type='radio' id='activate_1_<?php echo $profile_id;?>' onclick='activate(id);' name='optradio' <?php if( $activate == '1'){ echo "checked"; } ?>>No</label></div><?php echo "</td>";
  echo '</tr>';
echo '</table>';
  } else {
    echo '<div class="alert alert-danger">';
    echo '<strong>Error</strong> No such user on this platform!';
    echo '</div>';
  }
}
//echo $searchresult;
?>

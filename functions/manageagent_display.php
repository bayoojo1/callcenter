<?php
include("../php_includes/mysqli_connect.php");

if(isset($_POST['searchagent']) && !empty($_POST['searchagent'])){
        $searchagent = preg_replace('#[^a-z0-9.@?!]#i', '', $_POST['searchagent']);
        $sql = "SELECT users.id, email, users.username, useroptions.usertype, agentdetails.agent_firstname, agentdetails.agent_mobile FROM users INNER JOIN useroptions ON users.username=useroptions.username INNER JOIN agentdetails ON users.username=agentdetails.username WHERE (users.username LIKE :username OR email LIKE :email) AND useroptions.usertype=:usertype";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':username', '%'.$searchagent.'%', PDO::PARAM_STR);
    $stmt->bindValue(':email', '%'.$searchagent.'%', PDO::PARAM_STR);
    $stmt->bindValue(':usertype', 'agent', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $profile_id = $row['id'];
        $email = $row['email'];
        $username = $row['username'];
        $usertype = $row['usertype'];
        $agentFirstname = $row['agent_firstname'];
        $agentMobile = $row['agent_mobile'];
}
// Get the number of business assigned to this agent
$sql = "SELECT id FROM agent_business_alloc WHERE agentUsername=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$bizcount = $stmt->rowCount();

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
    echo '<td>My Business Portfolio:</td>';
    echo '<td>'.$bizcount.'</td>';
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
    echo '<td>Agent First Name:</td>';
    if(isset($agentFirstname)) {
    echo '<td id="agentFName_'.$username.'">'.$agentFirstname.'</td>';
  } else {
    echo '<td id="agentFName_'.$username.'"></td>';
  }
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
  echo '<tr>';
    echo '<td>Agent Mobile:</td>';
    if(isset($agentMobile)) {
    echo '<td id="agentMobile_'.$username.'">'.$agentMobile.'</td>';
  } else {
    echo '<td id="agentMobile_'.$username.'"></td>';
  }
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
echo '</table>';
  } else {
    echo '<div class="alert alert-danger">';
    echo '<strong>Error:</strong> The username or email you entered does not exist or not an Agent.';
    echo '</div>';
  }
}
?>

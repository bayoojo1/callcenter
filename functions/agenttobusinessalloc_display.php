<?php
include("../php_includes/mysqli_connect.php");

if(isset($_POST['searchagent']) && !empty($_POST['searchagent'])){
        $searchagent = preg_replace('#[^a-z0-9.@?!]#i', '', $_POST['searchagent']);
        $sql = "SELECT agent_business_alloc.id, agentUsername, agentdetails.agent_firstname, agentdetails.agent_mobile FROM agent_business_alloc INNER JOIN agentdetails ON agentdetails.username=agent_business_alloc.agentUsername WHERE agent_business_alloc.businessUsername LIKE :username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':username', '%'.$searchagent.'%', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $id = $row['id'];
        $agentUsername = $row['agentUsername'];
        $agentFirstname = $row['agent_firstname'];
        $agentMobile = $row['agent_mobile'];

echo '<table class="table table-hover">';
  echo '<tr>';
    echo '<td width="30%"; style="text-align:left;">'.$agentUsername.'</td>';
    echo '<td width="30%"; style="text-align:left;">'.$agentFirstname.'</td>';
    echo '<td width="30%"; style="text-align:left;">'.$agentMobile.'</td>';
    echo '<td width="10%"; style="text-align:left;"><button id="'.$id.'" type="button" class="btn btn-danger btn-sm agtdelbtn" onclick="delagentbiz(this.id);"><i class="fas fa-trash-alt"></i> Delete</button></td>';
  echo '</tr>';
echo '</table>';
    }
  } else {
    echo '<div class="alert alert-danger">';
    echo '<strong>Error:</strong> The username or email you entered does not exist or not an Agent.';
    echo '</div>';
  }
}
?>

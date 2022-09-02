<?php
session_start();
include("../php_includes/mysqli_connect.php");
// Get the alias
if(isset($_POST['searchquery']) && !empty($_POST['searchquery'])) {
  $searchquery = preg_replace('#[^a-z0-9?@.!-]#i', '', $_POST['searchquery']);
  $alias = preg_replace('#[^a-z0-9]#i', '', $_POST['alias']);
}

// Get the username for this business
$sql = "SELECT username FROM businessdetails WHERE businessAlias=:alias";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $u = $row['0'];
}

$sql = "SELECT id, username, imageUrl, description, imageTag FROM user_images WHERE (description LIKE :description OR imageTag LIKE :imageTag) ORDER BY RAND() LIMIT 50";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':description', '%'.$searchquery.'%', PDO::PARAM_STR);
$stmt->bindValue(':imageTag', '%'.$searchquery.'%', PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();
if($numrows > 0) {
  echo '<div class="row" id="imageRow">';
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $picture_id = $row['id'];
  $user = $row['username'];
  $imageUrl = $row['imageUrl'];
  $description = $row['description'];
  $tag = $row['imageTag'];

  echo '<div class="col-sm-offset-2 col-sm-8">';
  echo '<input type="hidden" id="imageInput" value="'.$alias.'">';
  echo '<div class="panel panel-default">';
  echo '<div id="panel" class="panel-body"><img src="user/'.$u.'/'.$imageUrl.'" class="img-responsive" alt="Image">';
  if(isset($tag) && !empty($tag)) {
  echo '<span id="tag"><i class="fas fa-tags" style="margin-left:2px;"></i> '.$tag.'</span>';
  }
    if(isset($_SESSION['username']) && $_SESSION['username'] == $user) {
    echo '<button type="button" id="image_'.$picture_id.'" class="btn btn-danger btn-sm" onclick="deleteImg(this.id);"><span class="glyphicon glyphicon-edit"></span> Delete</button>';
  }
  echo '</div>';
  echo '<div class="panel-footer">'.$description.'</div>';
    if(isset($_SESSION['username']) && $_SESSION['username'] == $user) {
      echo '<button type="button" style="float:left;" class="btn btn-info btn-sm" onclick="editdesc(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button>';
      echo '<button type="button" style="float:right; visibility:hidden;" class="btn btn-success btn-sm" onclick="savedesc(this);"><span class="glyphicon glyphicon-edit"></span> Save</button>';
    }
  echo '</div>';
  echo '<br>';
  echo '</div>';
  }
  echo '</div>';
} else {
  echo '<div class="alert alert-warning">';
    echo '<strong>Warning!</strong> No picture matches the search term used! Try again.';
  echo '</div>';
}
?>

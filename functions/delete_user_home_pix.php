<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// Gather the variables from ajax
if(isset($_POST['picture_id'])){
    $id = $_POST['picture_id'];
}
// Get this picture from the db
$sql = "SELECT imageUrl FROM user_images WHERE id=:id LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$imageUrl = $row[0];
// Delete the image
$sql = "DELETE FROM user_images WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
// Delete the image from the folder
unlink("../user/$log_username/$imageUrl");
?>

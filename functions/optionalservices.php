<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// Collect variables
if(isset($_POST['service']) && ($_POST['service'] == 'social')) {
$username = $_POST['username'];
$service = $_POST['service'];
$checked = $_POST['checked'];

// Get the last update entry of subscription table
$sql = "SELECT id FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$id = $row[0];

if($checked == '0') {
$sql = "UPDATE subscription SET social_media=:status WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':status', 'No', PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();

} else if($checked == '1') {
    $sql = "UPDATE subscription SET social_media=:status WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':status', 'Yes', PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
  }
} else if(isset($_POST['service']) && ($_POST['service'] == 'chat')) {
  $username = $_POST['username'];
  $service = $_POST['service'];
  $checked = $_POST['checked'];

  // Get the last update entry of subscription table
  $sql = "SELECT id FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row[0];

  if($checked == '0') {
  $sql = "UPDATE subscription SET live_chat=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindValue(':status', 'No', PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();

  } else if($checked == '1') {
      $sql = "UPDATE subscription SET live_chat=:status WHERE id=:id";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindValue(':status', 'Yes', PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
    }
} else if(isset($_POST['service']) && ($_POST['service'] == 'email')) {
  $username = $_POST['username'];
  $service = $_POST['service'];
  $checked = $_POST['checked'];

  // Get the last update entry of subscription table
  $sql = "SELECT id FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row[0];

  if($checked == '0') {
  $sql = "UPDATE subscription SET email_camp=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindValue(':status', 'No', PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();

  } else if($checked == '1') {
      $sql = "UPDATE subscription SET email_camp=:status WHERE id=:id";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindValue(':status', 'Yes', PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
    }

} else if(isset($_POST['service']) && ($_POST['service'] == 'sms')) {
  $username = $_POST['username'];
  $service = $_POST['service'];
  $checked = $_POST['checked'];

  // Get the last update entry of subscription table
  $sql = "SELECT id FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row[0];

  if($checked == '0') {
  $sql = "UPDATE subscription SET sms_camp=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindValue(':status', 'No', PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();

  } else if($checked == '1') {
      $sql = "UPDATE subscription SET sms_camp=:status WHERE id=:id";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindValue(':status', 'Yes', PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
    }

} else if(isset($_POST['service']) && ($_POST['service'] == 'outbound')) {
  $username = $_POST['username'];
  $service = $_POST['service'];
  $checked = $_POST['checked'];

  // Get the last update entry of subscription table
  $sql = "SELECT id FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row[0];

  if($checked == '0') {
  $sql = "UPDATE subscription SET outbound_camp=:status WHERE id=:id";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindValue(':status', 'No', PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_STR);
  $stmt->execute();

  } else if($checked == '1') {
      $sql = "UPDATE subscription SET outbound_camp=:status WHERE id=:id";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindValue(':status', 'Yes', PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
    }
}
?>

<?php
session_start();
include("../php_includes/mysqli_connect.php");
// Check if still session
if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
  echo 'Not in session';
    exit();
}
// Get the user alias
$sql = "SELECT businessAlias FROM businessdetails WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$alias = $row[0];

if (isset($_FILES["banner"]["name"]) && $_FILES["banner"]["tmp_name"] != ""){
    $fileName = $_FILES["banner"]["name"];
    $fileTmpLoc = $_FILES["banner"]["tmp_name"];
    $fileType = $_FILES["banner"]["type"];
    $fileSize = $_FILES["banner"]["size"];
    $fileErrorMsg = $_FILES["banner"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 5 || $height < 5){
        header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();
    }
    $db_file_name = rand(100000000000,999999999999).".".$fileExt;
    if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
    } else if($fileSize > 1000000) {
        header("location: ../message.php?msg=ERROR: Your image file was larger than 1mb");
        unlink($fileTmpLoc);
        exit();
    } else if (!preg_match("/\.(gif|jpg|png|jpeg)$/i", $fileName) ) {
        header("location: ../message.php?msg=ERROR: Your image file was not jpg, gif or png type");
        unlink($fileTmpLoc);
        exit();
    } else if ($fileErrorMsg == 1) {
        header("location: ../message.php?msg=ERROR: An unknown error occurred");
        exit();
    }
    $sql = "SELECT bannerUrl FROM useroptions WHERE username=:username LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $u, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $banner = $row[0];
    if($banner != ""){
        $picurl = "../user/$u/$banner";
        if (file_exists($picurl)) { unlink($picurl); }
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "../user/$u/$db_file_name");
    if ($moveResult != true) {
        header("location: ../message.php?msg=ERROR: File upload failed");
        unlink($fileTmpLoc);
        exit();
    }
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    include_once("../php_includes/image_resize.php");
    $target_file = "../user/$u/$db_file_name";
    $resized_file = "../user/$u/$db_file_name";
    $wmax = 1024;
    $hmax = 204;
    img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    $sql = "UPDATE useroptions SET bannerUrl=:bannerUrl WHERE username=:username LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':bannerUrl', $db_file_name, PDO::PARAM_STR);
    $stmt->bindParam(':username', $u, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    header("location: ../chat.php?query=$alias");
    exit();
}
?>

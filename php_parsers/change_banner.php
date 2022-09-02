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
    $fileExt = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
    $sourceProperties = getimagesize($fileTmpLoc);
    if($sourceProperties[0] < 1024 || $sourceProperties[1] < 400){
        header("location: ../message.php?msg=ERROR: That image is below recommended dimension");
        exit();
    }
    $imageType = $sourceProperties[2];
    $folderPath = "../user/$u/";
    $fileNewName = rand(100000000000,999999999999);
    $db_file_name = $fileNewName.".".$fileExt;
    $fileUrl = $fileNewName. "_banner.". $fileExt;
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

    switch ($imageType) {

        case IMAGETYPE_PNG:
            $imageResourceId = imagecreatefrompng($fileTmpLoc);
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagepng($targetLayer,$folderPath. $fileNewName. "_banner.". $fileExt);
            break;

        case IMAGETYPE_GIF:
            $imageResourceId = imagecreatefromgif($fileTmpLoc);
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagegif($targetLayer,$folderPath. $fileNewName. "_banner.". $fileExt);
            break;

        case IMAGETYPE_JPEG:
            $imageResourceId = imagecreatefromjpeg($fileTmpLoc);
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagejpeg($targetLayer,$folderPath. $fileNewName. "_banner.". $fileExt);
            break;

        default:
            echo "Invalid Image type.";
            exit;
            break;
    }
    // Remove the current image from the folder
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
    $moveResult = move_uploaded_file($fileTmpLoc, $folderPath. $db_file_name);
    if ($moveResult != true) {
        header("location: ../message.php?msg=ERROR: File upload failed");
        unlink($fileTmpLoc);
        exit();
    }
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    // Update the new image name in the database
    $sql = "UPDATE useroptions SET bannerUrl=:bannerUrl WHERE username=:username LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':bannerUrl', $fileUrl, PDO::PARAM_STR);
    $stmt->bindParam(':username', $u, PDO::PARAM_STR);
    $stmt->execute();
    // Delete the original image file
    $picurl = $folderPath.$db_file_name;
    unlink($picurl);
    $db_connect = null;
    header("location: ../chat.php?query=$alias");
    exit();
}
function imageResize($imageResourceId,$width,$height) {

    $targetWidth = 1900;
    $targetHeight = 400;

    $targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);
    imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);


    return $targetLayer;
}
?>

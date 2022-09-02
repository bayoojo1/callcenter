<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
if($user_ok != true || $log_username == "") {
    exit();
}
?><?php
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
    $fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
    $fileType = $_FILES["avatar"]["type"];
    $fileSize = $_FILES["avatar"]["size"];
    $fileErrorMsg = $_FILES["avatar"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 5 || $height < 5){
        header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();
    }
    $db_file_name = rand(100000000000,999999999999).".".$fileExt;
    if($fileSize > 5242880) {
        header("location: ../message.php?msg=ERROR: Your image file was larger than 5mb");
        exit();
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
        header("location: ../message.php?msg=ERROR: Your image file was not jpg, gif or png type");
        exit();
    } else if ($fileErrorMsg == 1) {
        header("location: ../message.php?msg=ERROR: An unknown error occurred");
        exit();
    }
    $sql = "SELECT avatar FROM users WHERE username=:logusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    //$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $avatar = $row[0];
    if($avatar != ""){
        $picurl = "../user/$log_username/$avatar";
        if (file_exists($picurl)) { unlink($picurl); }
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
    if ($moveResult != true) {
        header("location: ../message.php?msg=ERROR: File upload failed");
        exit();
    }
    include_once("../php_includes/image_resize.php");
    $target_file = "../user/$log_username/$db_file_name";
    $resized_file = "../user/$log_username/$db_file_name";
    $wmax = 200;
    $hmax = 300;
    img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    $sql = "UPDATE users SET avatar=:avatar WHERE username=:logusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':avatar', $db_file_name, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    header("location: ../user.php?u=$log_username");
    exit();
}
?>

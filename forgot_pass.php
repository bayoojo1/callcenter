<?php
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
    header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["e"])){
    $e = $_POST['e'];
    $sql = "SELECT id, username FROM users WHERE email=:email AND activated='1' LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':email', $e, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();
    if($numrows > 0){
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $id = $row["id"];
            $u = $row["username"];
        }
        $emailcut = substr($e, 0, 4);
        $randNum = rand(10000,99999);
        $tempPass = "$emailcut$randNum";
        $hashTempPass = password_hash($tempPass, PASSWORD_DEFAULT);
        $sql = "UPDATE useroptions SET temp_pass=:temp_pass WHERE username=:user LIMIT 1";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':temp_pass', $hashTempPass, PDO::PARAM_STR);
        $stmt->bindParam(':user', $u, PDO::PARAM_STR);
        $stmt->execute();

        // Send a mail to the user notifying of the password change request
        $email_body = '<!DOCTYPE html>
        <html>
        <head>
          <meta charset="UTF-8">
          <title>CallNet Message</title>
        </head>
        <body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
          <div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.callnet.com"><img src="/images/logo.png" width="36" height="30" style="border:none; float:left;"></a>CallNet - Password Change Request
          </div>
          <div style="padding:24px; font-size:17px;">

        <h2>Hello '.$u.'</h2><p>This is an automated message from CallNet. If you did not recently initiate the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="http://localhost:8080/callcenter/forgot_pass.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown above to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>

            </div>
        </body>
        </html>';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/mail1.naatcast.com/messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-833e41bd255e7d164bbfe48f981bdf6e");
        $post = array(
            'from' => 'No Reply <no-reply@callnet.com>',
            'to' => $e,
            'subject' => 'Temporary Password',
            'html' => $email_body,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        if($result !== false) {
            //echo "Error Number:".curl_errno($ch)."<br>";
            //echo "Error String:".curl_error($ch);
            echo "success";
            exit();
        } else {
          echo "email_send_failed";
          exit();
        }
        curl_close($ch);
    } else {
  echo "no_exist";
  }
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
    $temppasshash = $_GET['p'];
    if(strlen($temppasshash) < 10){
        exit();
    }
    $sql = "SELECT id FROM useroptions WHERE username=:user AND temp_pass=:temp_pass LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->bindParam(':temp_pass', $temppasshash, PDO::PARAM_STR);
    $stmt->execute();

    $numrows = $stmt->rowCount();
    if($numrows == 0){
        header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
        exit();
    } else {
        $row = $stmt->fetch();
        $id = $row[0];
        $sql = "UPDATE users SET password=:password WHERE id=:id AND username=:user LIMIT 1";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':password', $temppasshash, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user', $u, PDO::PARAM_STR);
        $stmt->execute();
        // Update useroptions table
        $temporarypass = '';
        $sql = "UPDATE useroptions SET temp_pass=:temp_pass WHERE username=:user LIMIT 1";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':temp_pass', $temporarypass, PDO::PARAM_STR);
        $stmt->bindParam(':user', $u, PDO::PARAM_STR);
        $stmt->execute();
        header("location: index.php");
        exit();
    }
}
?>

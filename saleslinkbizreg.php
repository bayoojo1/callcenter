<?php
// If user is logged in, header them away
if(isset($_SESSION["email"])){
    header("location: message.php?msg=NO to that weenis");
    exit();
}
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["e"])){
    // CONNECT TO THE DATABASE
    include("php_includes/mysqli_connect.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = $_POST['e'];
    $parts = explode("@", $e);
    $u = $parts[0]; // Username
    $p = $_POST['p']; // Password
    // GET USER IP ADDRESS AND OTHER VARIABLES
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    $bzname = preg_replace('#[^a-z0-9., ]#i', '', $_POST['bzname']);
    $bzaddr = preg_replace('#[^a-z0-9,\'.@:;/() ]#i', '', $_POST['bzaddr']);
    $bzcontact = preg_replace('#[^a-z0-9:;,. ]#i', '', $_POST['bzcontact']);
    $mobile = preg_replace('#[^0-9]#i', '', $_POST['mobile']);
    $website = preg_replace('#[^a-z0-9@./:]#i', '', $_POST['website']);
    $mail = preg_replace('#[^a-z0-9@.]#i', '', $_POST['mail']);
    $fsrmail = preg_replace('#[^a-z0-9@.]#i', '', $_POST['fsrmail']);
    $product = preg_replace('#[^a-z]#i', '', $_POST['product']);
    $service = preg_replace('#[^a-z]#i', '', $_POST['service']);
    $comment = preg_replace('#[^a-z0-9;\',:@(). ]#i', '', $_POST['comment']);
    // DUPLICATE DATA CHECKS FOR EMAIL
    $sql = "SELECT id FROM users WHERE email=:email LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':email', $e, PDO::PARAM_STR);
    $stmt->execute();
    $e_check = $stmt->rowCount();
    // DATA CHECK FOR USERNAME
    $sql = "SELECT id FROM users WHERE username=:username LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $u, PDO::PARAM_STR);
    $stmt->execute();
    $u_check = $stmt->rowCount();

    // FORM DATA ERROR HANDLING
     if($e == "" || $p == ""){
        echo '<strong style="color:red;">The form submission is missing values.</strong>';
        exit();
    } else if ($e_check > 0){
        echo '<strong style="color:red;">That email address: ' .$e.' is already in use in the system</strong>';
        exit();
    } else if ($u_check > 0){
        echo '<strong style="color:red;">This username: ' .$u.' is already in use in the system. Please use another email with a different username part.</strong>';
        exit();
    } else if (strlen($e) < 3 || strlen($e) > 30) {
        echo '<strong style="color:red;">Email can only be 3 to 30 characters please</strong>';
        exit();
    } else if (is_numeric($e[0])) {
        echo '<strong style="color:red;">Email must begin with a letter</strong>';
        exit();
      } else {
    // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        // Hash the password and apply your own mysterious unique salt
        // $cryptpass = crypt($p);
        // include_once ("php_includes/randStrGen.php");
        $p_hash = password_hash($p, PASSWORD_DEFAULT);
        // Generate some md5 from string
        $callnect = "CallNect";
        $somestr = "0987654321";
        $secret = "You cannot hack this! So stop it. $u";
        $string = trim("$u"."$e"."$callnect"."$somestr");
        $str1 = md5 ($string);
        $str2 = md5 ($secret);
        $hash = "$str1"."$str2";

        // Add user info into the database table for the main site table
        $av = 'avatardefault.png';
        $stmt = $db_connect->prepare("INSERT INTO users (username, email, password, hash, ip, signup, lastlogin, notescheck, avatar)
        VALUES(:username, :email, :password, :hash, :ip, now(), now(), now(), :avatar)");
        $stmt->execute(array(':username' => $u, ':email' => $e, ':password' => $p_hash, ':hash' => $hash, ':ip' => $ip, ':avatar' => $av));
        //$uid = mysqli_insert_id($db_connect);
        $uid = $db_connect->lastInsertId();
        // Establish their row in the useroptions table
        $stmt = $db_connect->prepare("INSERT INTO useroptions (id, username, temp_pass) VALUES (:id, :username, :temp_pass)");
        $stmt->execute(array(':id' => $uid, ':username' => $u, ':temp_pass' => $hash));

        // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
        if (!file_exists("user/$u")) {
            mkdir("user/$u", 0755);
        }
        if (!file_exists("uploads/$u")){
            mkdir("uploads/$u", 0755);
        }
        // Copy avatar
        $avatar = "images/avatardefault.png";
        $avatar2 = "user/$u/avatardefault.png";
        if (!copy($avatar, $avatar2)) {
            echo "failed to create avatar.";
        }
        // Insert the user info into date_visit table
        $stmt = $db_connect->prepare("INSERT INTO date_visit (username, latest_visit) VALUES (:username, now())");
        $stmt->execute(array(':username' => $u));
        // GET VARIABLES TO BE USED IN BUSINESS DETAILS DB
        // Get fsr username from the email
        $uparts = explode("@", $fsrmail);
        $fsrUsername = $uparts[0];
        // Split and join the business name and change the first letter to small
        $filter = str_replace(array('.', ',', ' '), '' , $bzname);
        $alias = strtolower($filter);
        // Insert the values into the DB.
        $stmt = $db_connect->prepare("INSERT INTO businessdetails (businessEmail, username, businessName, businessAlias, address, contactName, mobile, product, service, website, fsrUsername, businessDescription, dateRegistered)
        VALUES(:businessEmail, :username, :businessName, :businessAlias, :address, :contactName, :mobile, :product, :service, :website, :fsrUsername, :businessDescription, now())");
        $stmt->execute(array(':businessEmail' => $e, ':username' => $u, ':businessName' => $bzname, ':businessAlias' => $alias, ':address' => $bzaddr, ':contactName' => $bzcontact, ':mobile' => $mobile, ':product' => $product, ':service' => $service, ':website' => $website, ':fsrUsername' => $fsrUsername, ':businessDescription' => $comment));
        // Update notification table
        $detail = 'new business registration';
        $action = 'bizRegistration';
        $stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, detail, notification_date)
        VALUES(:initiator, :target, :action, :detail, now())");
        $stmt->execute(array(':initiator' => $u, ':target' => $fsrUsername, ':action' => $action, ':detail' => $detail));
        // Echo this to Ajax
        echo 'success';
        // Email the user their activation link
        $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>CallNet Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://10.32.0.17"><img src="/images/logo.png" width="36" height="30" alt="CallNect" style="border:none; float:left;"></a>CallNet Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account before the next 24 hours. The link would become unusable after then.:<br /><br /><a href="http://www.callnect.com/activation.php?id='.$uid.'&u='.$u.'&hash='.$hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/mail1.callnect.com/messages");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-833e41bd255e7d164bbfe48f981bdf6e");
          $post = array(
              'from' => 'Account Activation <no-reply@callnect.com>',
              'to' => $e,
              'subject' => 'CallNect Account Activation',
              'html' => $email_body,
          );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

          $result = curl_exec($ch);
          if($result === false)
          {
              echo "Error Number:".curl_errno($ch)."<br>";
              echo "Error String:".curl_error($ch);
          }
          curl_close($ch);
      }
}
?>

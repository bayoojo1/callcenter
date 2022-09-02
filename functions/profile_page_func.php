<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
if (isset($_GET["status"])){
    $status = $_GET["status"];
}


if($status == "Save"){
  $id = $_GET['id'];
  $value = $_GET['value'];


if(strpos($id, 'bizname_') !== false) {
    $sql = "UPDATE businessdetails SET businessName=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'bizalias_') !== false) {
  // Prepare the value before DB update
    $filter = str_replace(array('.', ',', ' '), '' , $value);
    $alias = strtolower($filter);
    // Check if this alias already exist
    $sql = "SELECT id FROM businessdetails WHERE businessAlias=:alias LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();
    if($numrows > 0) {
      $alias = $alias.''.mt_rand(10,1000);
    }
    $sql = "UPDATE businessdetails SET businessAlias=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $alias, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'bizaddress_') !== false) {
    $sql = "UPDATE businessdetails SET address=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'bizcontact_') !== false) {
    $sql = "UPDATE businessdetails SET contactName=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'contactmobile_') !== false) {
    $sql = "UPDATE businessdetails SET mobile=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'website_') !== false) {
    $sql = "UPDATE businessdetails SET website=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'bizdescription_') !== false) {
    $sql = "UPDATE businessdetails SET businessDescription=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'agentmobile_') !== false) {
    $sql = "UPDATE agentdetails SET agent_mobile=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'fsrmobile_') !== false) {
    $sql = "UPDATE fsrdetails SET fsr_mobile=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'usertype_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE useroptions SET usertype=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    // Get the username of this user
    $sql = "SELECT username FROM users WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $rows) {
        $thisusername = $rows['0'];
    }
    if($value == 'agent') {
      // Insert detail into agentdetails table
      $stmt = $db_connect->prepare( "INSERT INTO agentdetails (username) VALUES(:username)");
      $stmt->execute(array(':username' => $thisusername));
    } else if($value == 'sales') {
      // Get the md5 of the username
      $sales_string = md5($thisusername);
      // Insert detail into fsrdetails table
      $stmt = $db_connect->prepare( "INSERT INTO fsrdetails (username, username_string) VALUES(:username, :username_string)");
      $stmt->execute(array(':username' => $thisusername, ':username_string' => $sales_string));
    }
    $db_connect = null;
} else if(strpos($id, 'agentuser_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET agentUsername=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    /*
    //Get the username of this business
    $sql = "SELECT username FROM businessdetails WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $bizusername = $row[0];
    //Insert the agent business allocation table with the new info
    $stmt = $db_connect->prepare("INSERT INTO agent_business_alloc (agentUsername, businessUsername) VALUES(:agentUsername, :businessUsername)");
    $stmt->execute(array(':agentUsername' => $value, ':businessUsername' => $bizusername));
    */
    $db_connect = null;
} else if(strpos($id, 'fsruser_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET fsrUsername=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    /*
    //Get the username of this business
    $sql = "SELECT username FROM businessdetails WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $bizusername = $row[0];
    //Update the fsr business allocation table with the new info
    $sql ="UPDATE fsr_business_alloc SET fsrUsername=:value WHERE businessUsername=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':username', $bizusername, PDO::PARAM_STR);
    $stmt->execute();
    */
    $db_connect = null;
} else if(strpos($id, 'callnectnumb_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET callnect_Number=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'sadminbizdesc_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET businessDescription=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'sadminbusSite_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET website=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'sadminbusname_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET businessName=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'sadminbusalias_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET businessAlias=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'sadminbusadd_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET address=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  }  else if(strpos($id, 'sadminbizmobile_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET mobile=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'sadminbuscontact_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE businessdetails SET contactName=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'fsrFName_') !== false) {
    $username = explode("_", $id)[1];
    $sql = "UPDATE fsrdetails SET fsr_firstname=:value WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'fsrMobile_') !== false) {
    $username = explode("_", $id)[1];
    $sql = "UPDATE fsrdetails SET fsr_mobile=:value WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'agentFName_') !== false) {
    $username = explode("_", $id)[1];
    $sql = "UPDATE agentdetails SET agent_firstname=:value WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'agentMobile_') !== false) {
    $username = explode("_", $id)[1];
    $sql = "UPDATE agentdetails SET agent_mobile=:value WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'Plan_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE package SET price=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  } else if(strpos($id, 'image_') !== false) {
    $actual_id = explode("_", $id)[1];
    $sql = "UPDATE user_images SET description=:value WHERE id=:id";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':id', $actual_id, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
  }
  //Update the notification table
  $action = 'modified';
  $detail = 'setting modification';
  include("../php_includes/mysqli_connect.php");
  $stmt = $db_connect->prepare("INSERT INTO notification (initiator, target, action, note, detail, notification_date) VALUES(:initiator, :target, :action, :note, :detail, now())");
  $stmt->execute(array(':initiator' => $log_username, ':target' => $id, ':action' => $action, ':note' => $value, ':detail' => $detail));
}
?>

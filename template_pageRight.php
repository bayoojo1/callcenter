<?php
include_once("functions/page_functions.php");
include("php_includes/mysqli_connect.php");
include("functions/approval_status.php");
// Get some variables for this page
$sql = "SELECT agentUsername, fsrUsername, agentdetails.agent_firstname, fsrdetails.fsr_firstname, fsrdetails.fsr_mobile FROM businessdetails INNER JOIN agentdetails ON agentdetails.username=businessdetails.agentUsername INNER JOIN fsrdetails ON fsrdetails.username=businessdetails.fsrUsername WHERE businessdetails.username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $agentUsername = $row['agentUsername'];
  $fsrUsername = $row['fsrUsername'];
  $fsrFirstname = $row['fsr_firstname'];
  $fsrMobile = $row['fsr_mobile'];
  $agentFirstname = $row['agent_firstname'];
}
// Get the avatar of fsr for this user
$sql = "SELECT avatar FROM users WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $fsrUsername, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$fsr_avatar = $row[0];
// Get the avatar of fsr for this user
$sql = "SELECT avatar FROM users WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $agentUsername, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetch();
$agent_avatar = $rows[0];
?>
<div class="col-sm-2 well">
  <?php  if($isUser) { ?>
      <div class="thumbnail">
        <p style="background-color:grey; color:white;">My Sales Rep</p>
      <?php  if(isset($fsrUsername) && $isApproved) { ?>
        <?php echo '<img style="border-radius:10px;" src="user/'.$fsrUsername.'/'.$fsr_avatar.'" height="100" width="80">' ?>
    <?php  } else { ?>
              <?php echo '<img style="border-radius:10px;" src="user/fsr_dummy.png" height="100" width="80">' ?>
    <?php  } ?>
      <?php  if(isset($fsrFirstname) && $isApproved) { ?>
      <?php echo  '<p><strong>'.$fsrFirstname.'</strong></p>' ?>
    <?php } else { ?>
            <?php echo '<p><strong>Mary Kay</strong></p>' ?>
    <?php  } ?>
    <?php  if(isset($fsrMobile) && $isApproved) { ?>
    <?php echo  '<p><strong>'.$fsrMobile.'</strong></p>' ?>
  <?php } ?>
      </div>
      <div class="thumbnail">
        <p style="background-color:grey; color:white;">My Agent</p>
      <?php  if(isset($agentUsername) && $isApproved) { ?>
        <?php echo '<img style="border-radius:10px;" src="user/'.$agentUsername.'/'.$agent_avatar.'" height="100" width="80">' ?>
    <?php  } else { ?>
              <?php echo '<img style="border-radius:10px;" src="user/agent_dummy.png" height="100" width="80">' ?>
  <?php  } ?>
      <?php  if(isset($agentFirstname) && $isApproved) { ?>
      <?php echo  '<p><strong>'.$agentFirstname.'</strong></p>' ?>
  <?php } else { ?>
            <?php echo '<p><strong>Alisson Smith</strong></p>' ?>
  <?php  } ?>
      </div>
<?php  } else if($isAgent) { ?>
          <div id="agentthumbnail" class="thumbnail"></div>
<?php } ?>
      <div class="well">
        <p>ADS</p>
      </div>
      <div class="alert alert-success fade in">';
      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
      <p><strong>Place your ads here...</strong></p>
      Place your ads here...
      </div>
      <div class="alert alert-info alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Info!</strong> This alert box could indicate a neutral informative change or action.
  </div>
</div>

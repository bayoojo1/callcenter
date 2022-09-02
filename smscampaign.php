<?php
include_once("php_includes/check_login_status.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/callcenter/index.php");
    exit();
}
if(isset($_GET['u'])){
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
}else{
    exit();
}
include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>SMS Campaign</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon_io/favicon-16x16.png">
  <link rel="icon" sizes="16x16" href="images/favicon_io/favicon.ico">
  <link rel="manifest" href="images/favicon_io/site.webmanifest">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script
  src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
</head>
<body>
<?php include_once('templatePageTop.php'); ?>
<br />
<br />
<br />
<br />
<?php echo $pageleft; ?>
<div class="col-sm-7">
  <br>
  <br>
    <div class="panel panel-primary">
      <div class="panel-heading">Option 1: Copy and Paste numbers</div>
        <div class="panel-body">
          <textarea class="form-control" rows="3" id="text1" maxlength="160" placeholder="Enter the text message here..." required></textarea>
          <br>
          <textarea class="form-control" rows="3" name="mobile" id="mobile" placeholder="Enter each mobile number separated by comma" required></textarea>
          <br>
          <button type="button" class="btn btn-primary btn-xs" id="smscampaign1">Run Campaign</button>
          <div id="camp1"></div>
        </div>
      </div>
      <br>
      <br>
      <br>
      <div class="panel panel-primary">
        <div class="panel-heading">Option 2: Upload CSV file</div>
          <div class="panel-body">
            <form id="smsPostForm">
            <textarea class="form-control" rows="3" id="text2" name="text2" maxlength="160" placeholder="Enter the text message here..." required></textarea>
            <br>
            <input type="file" name="csv" id="csv" required>
            <br>
            <button type="button" class="btn btn-primary btn-xs" id="smscampaign2">Run Campaign</button>
          </form>
            <div id="camp2"></div>
          </div>
        </div>
</div>
<?php include_once("template_pageRight.php"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

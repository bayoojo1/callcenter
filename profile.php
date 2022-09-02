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
include_once("functions/profile_page_left_functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon_io/favicon-16x16.png">
  <link rel="icon" sizes="16x16" href="images/favicon_io/favicon.ico">
  <link rel="manifest" href="images/favicon_io/site.webmanifest">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script
  src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
  <script src="js/functions.js"></script>
</head>
<body>
<?php include_once('templatePageTop.php'); ?>
<br />
<br />
<br />
<br />
<?php echo $pageleft; ?>
<div class="col-sm-7">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <ul class="nav navbar-nav profile">
      <li class="dropdown">
        <a class="dropdown-toggle active" data-toggle="dropdown" href="#">My Profile
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php if($isUser) { ?>
          <li><a href="billing.php?u=<?php echo $log_username ?>">Billing</a></li>
          <?php } ?>
          <li><a href="#">Report</a></li>
        </ul>
      </li>
    </ul>
    </div>
  </nav>
  <br>
  <br>
  <?php include_once("functions/profile_display.php"); ?>
  <?php if($isSales) { ?>
    <div class="panel panel-primary">
      <div class="panel-heading">Business Registration Email Link</div>
        <div class="panel-body">
          <input type="hidden" id="emaillinkhidden" name="emaillinkhidden" value="<?php echo $log_username ?>">
          <textarea class="form-control" rows="1" name="emaillink" id="emaillink" placeholder="Enter emails, separated by comma, with no space in-between"></textarea>
          <button type="button" class="btn btn-primary btn-xs" id="emaillinkbtn">Post</button>
        </div>
    </div>
    <p id="emaillinkstatus"></p>
    <div class="panel panel-primary">
      <div class="panel-heading">My Monthly Revenue Calculator</div>
        <div class="panel-body">
          <input type="hidden" id="fsrhidden" name="fsrhidden" value="<?php echo $log_username ?>">
          <input type="text" class="datepickerfsr" name="fsrevenue" id="fsrevenue">
          <button type="button" class="btn btn-primary btn-xs" id="fsrevenuebtn">Search</button>
        </div>
    </div>
    <p id="fsrmonthlyrev"></p>
  <?php } ?>
</div>
<?php include_once("template_pageRight.php"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

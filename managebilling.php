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
  <title>Manage Billing</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
  <link rel="stylesheet" href="style/style.css">
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
    <div class="col-sm-12">
      <div class="panel panel-primary">
        <div class="panel-heading">Monthly Subscriptions</div>
          <div class="panel-body">
            <input type="text" class="datepicker" name="searchbilling" id="searchbilling">
            <button type="button" class="btn btn-primary btn-xs" id="searchbillingbtn">Search</button>
          </div>
    </div>
    </div>

  <br>
  <br>
    <p id="billingsearch"></p>
    <div>
      <?php include_once("functions/managebilling_display.php"); ?>
    </div>
    <div class="panel panel-primary">
      <div class="panel-heading">FSR Monthly Revenue</div>
        <div class="panel-body">
          <input type="text" name="fsrusername" id="fsrusername" placeholder="FSR Username">
          <input type="text" class="datepickeradm" name="fsrmonthly" id="fsrmonthly">
          <button type="button" class="btn btn-primary btn-xs" id="fsrmonthlybtn">Search</button>
        </div>
  </div>
    <p id="fsrmonthlysearch"></p>
</div>
<?php include_once("template_pageRight.php"); ?>

</body>
</html>

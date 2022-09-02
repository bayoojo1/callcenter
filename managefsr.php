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
  <title>Manage FSR</title>
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
      <div class="input-group">
        <input type="text" id="searchfsr" class="form-control" placeholder="Search fsr by username or email" onkeypress="key_down_fsr(event)">
          <div class="input-group-btn">
            <button type="button" class="btn btn-default" onclick="searchFSR();">
              <span class="glyphicon glyphicon glyphicon-search"></span>
            </button>
          </div>
      </div>
    </div>
  <br>
  <br>
  <br>
  <br>
  <div id="fsrmgt">
    <table class="table table-hover">
      <tr>
        <td>Email:</td>
        <td></td>
        <td>Edit</td>
        <td></td>
      </tr>
      <tr>
        <td>Username:</td>
        <td></td>
        <td>Edit</td>
        <td></td>
      </tr>
      <tr>
        <td>User Type:</td>
        <td></td>
        <td>Edit</td>
        <td></td>
      </tr>
      <tr>
        <td>FSR First Name:</td>
        <td></td>
        <td>Edit</td>
        <td></td>
      </tr>
      <tr>
        <td>FSR Mobile:</td>
        <td></td>
        <td>Edit</td>
        <td></td>
      </tr>
    </table>
  </div>
</div>
<?php include_once("template_pageRight.php"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

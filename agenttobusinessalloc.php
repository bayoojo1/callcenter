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
  <title>Manage Agent Business Allocation</title>
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
    <div class="col-sm-12">
      <div class="input-group">
        <input type="text" id="searchforBizagents" class="form-control" placeholder="Search by Business username" onkeypress="key_down_agent_biz_alloc(event)">
          <div class="input-group-btn">
            <button type="button" class="btn btn-default" onclick="searchforBizagents();">
              <span class="glyphicon glyphicon glyphicon-search"></span>
            </button>
          </div>
      </div>
    </div>
  <br>
  <br>
  <br>
  <br>
  <table class="table table-hover">
    <tr>
      <th width='30%';>Agent Username</th>
      <th width='30%';>First Name</th>
      <th width='30%';>Mobile</th>
      <th width='10%';></th>
    </tr>
  </table>
  <div id="agentbizsearch"></div>
  <br>
  <br>
  <br>
  <br>
  <br>
    <div class="panel panel-primary">
      <div class="panel-heading">Agent to Business Allocation</div>
        <div class="panel-body">
          <input type="text" name="bzusername" id="bzusername" placeholder="Business Username">
          <input type="text" name="agtusername" id="agtusername" placeholder="Agent Username">
          <button type="button" class="btn btn-primary btn-xs" id="assigntbtn">Assign</button>
        </div>
      </div>
        <p id="agentbusinessalloc"></p>
</div>
<?php include_once("template_pageRight.php"); ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

<?php
include_once("template_pageLeft.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>ContactHub Home</title>
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
</head>
<body>
  <?php include_once("templatePageTop.php"); ?>
  <br />
  <br />
  <br />
  <br />
  <?php echo $pageleft; ?>
 <?php if($isUser) { ?>
   <div class="col 7 col-sm-7">
     <?php include("userhomefeed.php"); ?>
   </div>
<?php } else if($isAgent) { ?>
   <div id="agenthomefeed"></div>
<?php  } else if($isAdmin)  { ?>
  <?php include("admin.php"); ?>
<?php } else if($isManager) { ?>
    <?php include("admin.php"); ?>
<?php  } else if($isSupervisor) { ?>
  <?php echo 'I\'m a Supervisor'; ?>
<?php } else if($isSales) { ?>
    <div id="saleshomefeed"></div>
<?php } else if($isSupport) { ?>
  <?php echo 'I\'m a Technical Support'; ?>
<?php } else if($isBilling) { ?>
  <?php echo 'I\'m a Billing'; ?>
<?php } else if($isSuperadmin) { ?>
    <?php include("superadmin.php"); ?>
<?php } ?>

  <?php include_once("template_pageRight.php"); ?>

  <div id="myOverlay" class="overlay">
    <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
    <div class="overlay-content">
      <div style="color:white; font-size:20px; margin-bottom:20px;">CallNect Business Search</div>
      <form>
        <input type="text" id="searchbiz" onkeypress="key_down_searchbiz(event)" placeholder="Search with business name, product or service, business location, business call center number...">
        <button type="submit" onclick="searchbusiness()"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>
  <link rel="stylesheet" href="style/style.css">
  <script src="js/functions.js"></script>
</body>

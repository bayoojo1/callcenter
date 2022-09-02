<?php
// This page provides the functions that enable the search of users on NaatCast //
include("php_includes/mysqli_connect.php");
?><?php
$searchbiz = "";
if(isset($_GET['query']) && !empty($_GET['query'])) {
    $alias = preg_replace('#[^a-z0-9]#i', '', $_GET['query']);
}
  /*  $sql = "SELECT username FROM businessdetails WHERE businessAlias=:businessAlias";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':businessAlias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $row) {
        $username = $row['0'];
    } */
// Get the business name for this user
    $sql = "SELECT businessName, address, website, callnect_Number, businessDescription FROM businessdetails WHERE businessAlias=:businessAlias";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':businessAlias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      $bizName = $row['businessName'];
      $address = $row['address'];
      $website = $row['website'];
      $callnectNum = $row['callnect_Number'];
      $bizDes = $row['businessDescription'];
    }
$searchbiz .= '<br>';
$searchbiz .= '<br>';
$searchbiz .= '<br>';
$searchbiz .= '<br>';
$searchbiz .= '<div class="container-fluid">';
$searchbiz .= '<div class="row">';
$searchbiz .= '<div class="col-sm-12">';
  $searchbiz .= '<div class="panel panel-primary text-center">';
    $searchbiz .= '<div class="panel-heading heading"><i class="fas fa-briefcase"></i> '.  $bizName.'</div>';
    $searchbiz .= '<div class="panel-body" style="font-size:16px; font-weight:bold; color:grey;"><i class="fas fa-map-marker-alt"></i> '.$address.'</div>';
    $searchbiz .= '<div class="panel-body" style="font-size:12px; color:grey;">'.$bizDes.'</div>';
    $searchbiz .= '<div class="panel-body" style="font-size:12px; color:grey;"><i class="fas fa-phone"></i> '.$callnectNum.'</div>';
    if(isset($website)) {
    $searchbiz .= '<div class="panel-body" style="font-size:12px; color:grey;"><i class="fas fa-globe"></i><a href="'.$website.'"> '.$website.'</a></div>';
  }
  $searchbiz .= '</div>';
$searchbiz .= '</div>';
$searchbiz .= '</div>';
$searchbiz .= '</div>';
?>
<!DOCTYPE html>
<html>
<head>
  <title>CallNect</title>
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
  <nav class="navbar navbar-inverse navbar-fixed-top"  style="background-color:black;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-left" href="#"><img src="images/logo/logo-small.png"></a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <form class="navbar-form navbar-left">
          <div class="col-sm-12 col-lg-12 col-sm-offset-2">
          <div class="input-group">
            <input type="text" id="searchbiz" onkeypress="key_down_searchbiz(event)" class="form-control" placeholder="Search">
            <div class="input-group-btn">
              <button class="btn btn-default" type="submit" onclick="searchbusiness()">
                <i class="glyphicon glyphicon-search"></i>
              </button>
            </div>
          </div>
          </div>
        </form>
        <ul class="nav navbar-nav navbar-right" id="myScrollspy">
        <li class="active"><a id="Home" href="index.php"><b>Home</b></a></li>
        <li><a id="aboutus" href="index.php#about"><b>About Us</b></a></li>
        <li><a id="pricing" href="index.php#price"><b>Pricing</b></a></li>
        <li><a id="contactus" href="index.php#contact"><b>Contact</b></a></li>
          <li><a href="#" id="myBtn"><span class="glyphicon glyphicon-log-in"></span><b> Login</b></a></li>
        </ul>
      </div>
    </div>
  </nav>
<?php echo $searchbiz; ?>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
<!-- Start of LiveChat (www.livechatinc.com) code -->
<script type="text/javascript">
window.__lc = window.__lc || {};
window.__lc.license = 10706942;
(function() {
  var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
  lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
<noscript>
<a href="https://www.livechatinc.com/chat-with/10706942/" rel="nofollow">Chat with us</a>,
powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a>
</noscript>
<!-- End of LiveChat code -->
</body>
</html>

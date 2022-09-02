<?php
// This page provides the functions that enable the search of users on NaatCast //
include("php_includes/mysqli_connect.php");
?><?php
$searchbiz = "";
if(!isset($_GET['searchquery']) || empty($_GET['searchquery'])){
    $searchbiz .= '<br>';
    $searchbiz .= '<br>';
    $searchbiz .= '<br>';
    $searchbiz .= '<div class="alert alert-warning">';
      $searchbiz .= '<strong>Warning!</strong> You did not enter any search term! You can search with business name, product or service, office address, business call center number, etc.';
    $searchbiz .= '</div>';
  } else if(isset($_GET['searchquery']) && !empty($_GET['searchquery'])) {
    $searchquery = preg_replace('#[^a-z 0-9?!]#i', '', $_GET['searchquery']);
    $sql_statement = "SELECT username, businessName, address, businessDescription, callnect_Number FROM businessdetails WHERE (businessName LIKE :businessName OR address LIKE :address OR businessDescription LIKE :businessDescription OR callnect_Number LIKE :callnect_Number) AND approval=:approval";
    $stmt = $db_connect->prepare($sql_statement);
    $stmt->bindValue(':businessName', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':address', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':businessDescription', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':callnect_Number', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':approval', 'yes', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();

    // Specify how many result per page
    $rpp = '10';
    // This tells us the page number of the last page
    $last = ceil($count/$rpp);
    // This makes sure $last cannot be less than 1
    if($last < 1){
        $last = 1;
    }
    // Define pagination control
    //$paginationCtrls = "";
    // Define page number
    $pn = "1";

    // Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
    $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
    //$searchquery = $_POST['searchquery'];
}

// Make the script run only if there is a page number posted to this script

// This makes sure the page number isn't below 1, or more than our $last page
    if ($pn < 1) {
        $pn = 1;
    } else if ($pn > $last) {
    $pn = $last;
}

// This sets the range of rows to query for the chosen $pn
    $limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;

// This is the query again, it is for grabbing just one page worth of rows by applying $limit
    $sql = "$sql_statement"." $limit";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':businessName', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':address', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':businessDescription', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':callnect_Number', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':approval', 'yes', PDO::PARAM_STR);
    $stmt->execute();
    //var_dump($stmt);
    // Establish the $paginationCtrls variable
    $paginationCtrls = '';

if($count > 0){
    $searchbiz .= "<br>";
    $searchbiz .= "<br>";
    $searchbiz .= "<br>";
    $searchbiz .= '<div class="alert alert-success text-center">';
      $searchbiz .= "<strong>Success!</strong> $count results for <strong>$searchquery</strong>";
    $searchbiz .= '</div>';
    $paginationCtrls .= '<div id="paginationctrls">';
    if($last != 1){
    /* First we check if we are on page one. If we are then we don't need a link to
       the previous page or the first page so we do nothing. If we aren't then we
       generate links to the first page, and to the previous page. */
    if ($pn > 1) {
        $previous = $pn - 1;
        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'&searchquery='.$searchquery.'">Previous</a> &nbsp; &nbsp; ';
        // Render clickable number links that should appear on the left of the target page number
        for($i = $pn-4; $i < $pn; $i++){
            if($i > 0){
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&searchquery='.$searchquery.'">'.$i.'</a> &nbsp; ';
            }
        }
    }
    // Render the target page number, but without it being a link
    $paginationCtrls .= ''.$pn.' &nbsp; ';
    // Render clickable number links that should appear on the right of the target page number
    for($i = $pn+1; $i <= $last; $i++){
        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&searchquery='.$searchquery.'">'.$i.'</a> &nbsp; ';
        if($i >= $pn+4){
            break;
        }
    }
    // This does the same as above, only checking if we are on the last page, and then generating the "Next"
    if ($pn != $last) {
        $next = $pn + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'&searchquery='.$searchquery.'">Next</a> ';
    }
}
$paginationCtrls .= '</div>';
//echo $paginationCtrls;
//echo '<br />';
$searchbiz .= '<div class="container-fluid">';
$searchbiz .= '<div class="row">';
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $username = $row['username'];
    $searchbizName = $row['businessName'];
    $searchbizaddress = $row['address'];
    $searchbizDes = $row['businessDescription'];
    $searchbizContNum = $row['callnect_Number'];

// Check if the business subscribed to Live chat
    $sql = "SELECT live_chat FROM subscription WHERE username=:username ORDER BY id DESC LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $rows) {
        $live_chat = $rows['0'];
    }
// Get the hash string for this user
    $sql = "SELECT businessAlias FROM businessdetails WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    foreach($stmt->fetchAll() as $rows) {
        $alias = $rows['0'];
    }

    $searchbiz .= '<div class="col-sm-4">';
      $searchbiz .= '<div class="panel panel-primary text-center">';
        $searchbiz .= '<div class="panel-heading heading"><i class="fas fa-briefcase"></i> '.  $searchbizName.'</div>';
        $searchbiz .= '<div class="panel-body" style="font-size:16px; font-weight:bold; color:grey;"><i class="fas fa-map-marker-alt" style="color:red;"></i> '.$searchbizaddress.'</div>';
        $searchbiz .= '<div class="panel-body" style="font-size:12px; color:grey;">'.$searchbizDes.'</div>';
        $searchbiz .= '<div class="panel-body" style="font-size:16px; color:grey;"><i class="fas fa-phone"></i> '.$searchbizContNum.'</div>';
        if(isset($live_chat) && $live_chat == 'Yes') {
        $searchbiz .= '<div class="panel-footer" id="'.$alias.'" style="color:dodgerblue; font-size:20px; cursor:pointer;" title="chat with agent" onclick="chatAgent(this.id)">Chat with agent: <i class="fas fa-comment"></i></div>';
      } else {
        $searchbiz .= '<div class="panel-footer" id="'.$alias.'" style="color:dodgerblue; font-size:20px; cursor:pointer;" title="visit our page" onclick="chatAgent(this.id)">Visit our page</div>';
      }
      $searchbiz .= '</div>';
    $searchbiz .= '</div>';
  }
  $searchbiz .= '</div>';
  $searchbiz .= '</div>';
?><?php
} else {
  $searchbiz .= '<br>';
  $searchbiz .= '<br>';
  $searchbiz .= '<br>';
  $searchbiz .= '<div class="alert alert-info text-center">';
    $searchbiz .= '<strong>Info!</strong> There is no such business, product, service, address or call center number. Please search again.';
  $searchbiz .= '</div>';
}
}
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
        <ul class="nav navbar-nav navbar-right" id="myScrollspy">
        <li class="active"><a id="Home" href="index.php"><b>HOME</b></a></li>
        <li><a id="aboutus" href="index.php#about"><b>ABOUT US</b></a></li>
        <li><a id="pricing" href="index.php#price"><b>PRICING</b></a></li>
        <li><a id="contactus" href="index.php#contact"><b>CONTACT</b></a></li>
        <li><a id="opensearch" style="cursor:pointer;" onclick="openSearch()"><b>SEARCH</b></a></li>
          <li><a href="#" id="myBtn"><span class="glyphicon glyphicon-log-in"></span><b> LOGIN</b></a></li>
        </ul>
      </div>
    </div>
  </nav>
<?php echo $searchbiz; ?>
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
</html>

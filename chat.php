<?php
session_start();
// This page provides the functions that enable the search of users on NaatCast //
include("php_includes/mysqli_connect.php");
$paginationCtrls = '<span>';
?><?php
if(isset($_GET['query']) && !empty($_GET['query'])) {
    $alias = preg_replace('#[^a-z0-9]#i', '', $_GET['query']);
}

// Get the username for this business
$sql = "SELECT username FROM businessdetails WHERE businessAlias=:alias";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $username = $row['0'];
}
// Get the business name for this user
$sql = "SELECT businessName, address, businessdetails.website, callnect_Number, businessDescription, users.avatar, useroptions.bannerUrl FROM businessdetails INNER JOIN users ON businessdetails.username=users.username INNER JOIN useroptions ON businessdetails.username=useroptions.username WHERE businessAlias=:businessAlias AND businessdetails.username=:username AND approval='yes'";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':businessAlias', $alias, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $bizName = $row['businessName'];
  $address = $row['address'];
  $website = $row['website'];
  $callnectNum = $row['callnect_Number'];
  $bizDes = $row['businessDescription'];
  $avatar = $row['avatar'];
  $banner = $row['bannerUrl'];
}
?><?php
// Get the uploaded images
$pix = '';
$sql_trans = "SELECT id, username, imageUrl, description, imageTag FROM user_images WHERE username=:username ORDER BY id DESC";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();
// Specify how many result per page
$rpp = '20';
// This tells us the page number of the last page
$last = ceil($numrows/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
// Define page number
$pn = "1";

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
    $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
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
$sql = "$sql_trans"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
if($numrows > 0) {
  //$paginationCtrls .= '<div class="col-sm-9">';
  $paginationCtrls .= '<ul class="pagination">';
  if($last != 1){
      /* First we check if we are on page one. If we are then we don't need a link to
         the previous page or the first page so we do nothing. If we aren't then we
         generate links to the first page, and to the previous page. */
      if ($pn > 1) {
          $previous = $pn - 1;
          $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?query='.$alias.'&pn='.$previous.'">Previous</a></li> &nbsp; &nbsp;';
          // Render clickable number links that should appear on the left of the target page number
          for($i = $pn-4; $i < $pn; $i++){
              if($i > 0){
                  $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?query='.$alias.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
              }
          }
      }
      // Render the target page number, but without it being a link
      $paginationCtrls .= '<li class="active"><a href="#">'.$pn.'</a></li> &nbsp; ';
      // Render clickable number links that should appear on the right of the target page number
      for($i = $pn+1; $i <= $last; $i++){
          $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?query='.$alias.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
          if($i >= $pn+4){
              break;
          }
      }
      // This does the same as above, only checking if we are on the last page, and then generating the "Next"
      if ($pn != $last) {
          $next = $pn + 1;
          $paginationCtrls .= ' &nbsp; &nbsp; <li><a href="'.$_SERVER['PHP_SELF'].'?query='.$alias.'&pn='.$next.'">Next</a></li>';
      }
  }
  $paginationCtrls .= '</ul>';
  $paginationCtrls .= '</span>';

  $pix .= '<div class="input-group col-sm-offset-2 col-sm-8">';
    $pix .= '<input type="text" id="bizImage" onkeypress="key_down_searchImg(event)" class="form-control" placeholder="Search picture by label number or description...">';
    $pix .= '<div class="input-group-btn">';
      $pix .= '<button class="btn btn-info" type="submit" onclick="searchImage()">
        <i class="glyphicon glyphicon-search"></i>';
      $pix .= '</button>';
    $pix .= '</div>';
  $pix .= '</div>';
  $pix .= '<br>';
  $pix .= '<div class="row" id="imageRow">';

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $picture_id = $row['id'];
  $user = $row['username'];
  $imageUrl = $row['imageUrl'];
  $description = $row['description'];
  $tag = $row['imageTag'];

  $pix .= '<div class="col-sm-offset-2 col-sm-8">';
  $pix .= '<input type="hidden" id="imageInput" value="'.$alias.'">';
  $pix .= '<div class="panel panel-default">';
  $pix .= '<div id="panel" class="panel-body"><img src="user/'.$username.'/'.$imageUrl.'" class="img-responsive" alt="Image">';
  if(isset($tag) && !empty($tag)) {
  $pix .= '<span id="tag"><i class="fas fa-tags" style="margin-left:2px;"></i> '.$tag.'</span>';
  }
    if(isset($_SESSION['username']) && $_SESSION['username'] == $username) {
    $pix .= '<button type="button" id="image_'.$picture_id.'" class="btn btn-danger btn-sm" onclick="deleteImg(this.id);"><span class="glyphicon glyphicon-edit"></span> Delete</button>';
  }
  $pix .= '</div>';
  $pix .= '<div class="panel-footer">'.$description.'</div>';
    if(isset($_SESSION['username']) && $_SESSION['username'] == $username) {
      $pix .= '<button type="button" style="float:left;" class="btn btn-info btn-sm" onclick="editdesc(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button>';
      $pix .= '<button type="button" style="float:right; visibility:hidden;" class="btn btn-success btn-sm" onclick="savedesc(this);"><span class="glyphicon glyphicon-edit"></span> Save</button>';
    }
$pix .= '</div>';
$pix .= '<br>';
$pix .= '</div>';
}
$pix .= '</div>';
} else {
  $pix .= '<div class="row">';
  $pix .= '<div class="col-sm-6">';
  $pix .= '<div class="panel panel-default">';
  $pix .= '<div class="panel-body"><img src="https://placehold.it/150x80?text=Coming Soon!" class="img-responsive" style="width:100%" alt="Image"></div>';
  $pix .= '</div>';
  $pix .= '</div>';
  $pix .= '<div class="col-sm-6">';
  $pix .= '<div class="panel panel-default">';
  $pix .= '<div class="panel-body"><img src="https://placehold.it/150x80?text=Coming Soon!" class="img-responsive" style="width:100%" alt="Image"></div>';
  $pix .= '</div>';
  $pix .= '</div>';
  $pix .= '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CallNect-<?php echo $alias ?></title>
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
  <style>
    /* Add a gray background color and some padding to the footer */
    .jumbotron {
      margin-bottom: 40px;
    }
  </style>
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
        <a class="navbar-left" href="index.php"><img src="images/logo/logo-small.png"></a>
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
      <a class="navbar-right" href="http://localhost:8080/callcenter/chat.php?query=<?php echo $alias ?>"><img style="margin-top:5px; margin-right:2px;" src="user/<?php echo $username ?>/<?php echo $avatar ?>" class="img-circle" height="40" width="40"></a>
      </div>
    </div>
  </nav>
<br>
<br>
<br>
<div class="jumbotron col-sm-10 col-sm-offset-1 img-responsive" style="background-image:url('user/<?php echo $username ?>/<?php echo $banner ?>'); background-repeat: no-repeat; background-position: center center; background-size: cover;">
  <div class="container text-center bizheading">
    <h2 style="color:white;"><?php echo $bizName ?></h2>
    <p style="font-size:14px; color:white;"><?php echo $address ?></p>
  </div>
  <?php if(isset($_SESSION['username']) && $_SESSION['username'] == $username) { ?>
  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#changeBanner"><i class="fas fa-pen-square"></i> Edit</button>

  <?php } ?>
  <!-- Modal for changing user banner -->
    <div class="modal fade" id="changeBanner" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="padding:20px 40px;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3><span class="glyphicon glyphicon-upload"></span> Upload New Banner</h3>
          </div>
          <div class="modal-body" style="padding:30px 40px; text-align:center;">
            <form enctype="multipart/form-data" method="post" action="php_parsers/change_banner.php">
              <div class="form-group">
                <input type="file" class="form-control-file border" name="banner" required>
              </div>
                <button type="submit" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-upload"></span> Upload</button>
                <br><br>
                <div class="alert alert-warning">
                  <strong>NOTE!</strong> For better quality, your image size should be minimum 1900x400 pixels.
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Ends here -->
</div>
<br>
<br>
<br>
<div class="container">
  <div class="row">
    <div class="col-sm-3 well">
      <div class="text-center">
        <a href="http://localhost:8080/callcenter/chat.php?query=<?php echo $alias ?>"><img style="margin-top:-15px;" src="user/<?php echo $username ?>/<?php echo $avatar ?>" class="img-circle" height="100" width="100"></a>
        <div style="margin-top:5px;" class="panel panel-default">
          <div class="panel-heading">About Us</div>
          <div class="panel-body"><?php echo $bizDes ?></div>
        </div>
        <?php if(isset($website) || isset($callnectNum)) { ?>
        <p style="color:#337ab7"><span class="glyphicon glyphicon-earphone"></span> <?php echo $callnectNum ?></p>
        <p style="color:#337ab7"><span class="glyphicon glyphicon-globe"></span><a href="<?php echo $website ?>"> <?php echo $website ?></a></p>
      <?php } ?>
      </div>
    </div>
    <div class="col-sm-9">
      <?php if(isset($_SESSION['username']) && $_SESSION['username'] == $username) { ?>
      <div class="row">
        <div class="col-sm-offset-4 col-sm-10">
          <button type="submit" class="btn btn-info btn-lg" data-toggle="modal" data-target="#imageupload"><span class="glyphicon glyphicon-upload"></span> Upload Image</button>
        </div>
      </div>
      <br>
      <?php } ?>
      <!-- Modal for uploading images -->
        <div class="modal fade" id="imageupload" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="padding:20px 40px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3><span class="glyphicon glyphicon-upload"></span> Upload New Image</h3>
              </div>
              <div class="modal-body" style="padding:30px 40px; text-align:center;">
                <form enctype="multipart/form-data" method="post" action="php_parsers/upload_images.php">
                  <div class="form-group">
                    <input type="file" class="form-control-file border" name="userimage" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="desc" name="desc" placeholder="Write image description here..." required>
                  </div>
                    <button type="submit" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-upload"></span> Upload</button>
                    <br>
                    <br>
                    <div class="alert alert-warning">
                      <strong>NOTE</strong> For better quality, your image should be minimum 500x500 pixels.
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
              </div>
            </div>
          </div>
        </div>
      <!-- Ends here -->
          <?php
          echo '<div class="col-sm-offset-4 col-sm-10">';
          echo $paginationCtrls;
          echo '</div>';
          echo $pix;
          ?>
  </div>
</div>
</div><br>
<br><br>

<footer class="container-fluid text-center" style="background-color:black; color:white;">
  Copyright &copy<?php echo date("Y"); ?> - <?php echo $bizName; ?>
</footer>
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

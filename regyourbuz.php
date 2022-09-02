<?php
include("php_includes/mysqli_connect.php");
if (isset($_GET['sid'])) {
  $sid = preg_replace('#[^a-z0-9]#i', '', $_GET['sid']);
}
// Get the username of this sales guy from the db
$sql = "SELECT users.email FROM users INNER JOIN fsrdetails ON users.username=fsrdetails.username WHERE fsrdetails.username_string=:userstring";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':userstring', $sid, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $fsremail = $row['0'];
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
        <ul class="nav navbar-nav navbar-right" id="myScrollspy">
        <li class="active"><a id="Home" href="index.php"><b>Home</b></a></li>
        <li><a id="aboutus" href="index.php#about"><b>About Us</b></a></li>
        <li><a id="pricing" href="index.php#price"><b>Pricing</b></a></li>
        <li><a id="contactus" href="index.php#contact"><b>Contact</b></a></li>

        </ul>
      </div>
    </div>
  </nav>
  <br />
  <br />
  <br />
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <img class="img-responsive" src="images/banners/newlogo/c1.png" alt="" width="100%" height="400" />
      <br>
    </div>
  </div>
  <div class="col-sm-8 col-sm-offset-2" style="border: 1px solid grey;">
  <form class="form-horizontal">
    <h3 style="text-align: center; color: dodgerblue;">Provide Your Business Details</h3>
    <br>
    <br>
    <div class="form-group">
      <label class="control-label col-sm-4" for="usrname"><i class="fas fa-envelope"></i> Email</label>
      <div class="col-sm-8">
      <input type="text" class="form-control" id="signupusrname" placeholder="Enter email">
    </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="psw"><i class="fas fa-lock"></i> Password</label>
      <div class="col-sm-8">
      <input type="password" class="form-control" id="signuppsw" placeholder="Enter password">
    </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="psw"><i class="fas fa-lock"></i> Repeat Password</label>
      <div class="col-sm-8">
      <input type="password" class="form-control" id="signuppsw1" placeholder="Repeat password">
    </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="buzname"><i class="fas fa-file-alt"></i> Terms & Conditions:</label>
      <div class="col-sm-8">
      <input id="checkbox_id" type="checkbox" value="">
    </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="buzname"><i class="fas fa-briefcase"></i> Business Name:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="buzname" placeholder="Enter business name" name="buzname" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="buzAdd"><i class="fas fa-map-marker-alt"></i> Business Address:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="buzAdd" placeholder="Enter business address" name="buzAdd" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="buzContact"><i class="fas fa-id-card-alt"></i> Business Contact:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="buzContact" placeholder="Enter the name of a contact person" name="buzContact" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="mobile"><i class="fas fa-mobile"></i> Contact Mobile:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="mobile" placeholder="Enter business contact mobile number" name="mobile" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="website"><i class="fas fa-globe"></i> Business Website:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="website" placeholder="Enter business website(Optional)" name="website">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="email"><i class="fas fa-envelope-open"></i> Business Email:</label>
      <div class="col-sm-8">
        <input type="email" class="form-control" id="email" placeholder="Enter business email(Optional)" name="email">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="fsremail"><i class="fas fa-envelope-open"></i> FSR Email:</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="fsremail" value="<?php echo $fsremail; ?>" placeholder=<?php echo $fsremail; ?> disabled>
      </div>
    </div>
    <div class="form-group">
          <label class="control-label col-sm-4"><i class="fab fa-delicious"></i> Business Type:</label>
          <div class="col-sm-8">
        <label class="checkbox-inline"><input type="checkbox" name="product" value="product">Product</label>
        <label class="checkbox-inline"><input type="checkbox" name="service" value="service">Service</label>
    </div>
    </div>
      <div class="form-group">
        <label class="control-label col-sm-4" for="comment"><i class="fas fa-edit"></i> A brief description of your business:</label>
        <div class="col-sm-8">
        <textarea class="form-control" rows="5" id="comment" required></textarea>
        </div>
      </div>
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-8">
        <button type="submit" id="salesbizregbtn" class="btn btn-success btn-block">Submit</button>
      </div>
    </div>
  <div id="salesbizregstatus" style="text-align:center;"></div>
</form>
</div>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

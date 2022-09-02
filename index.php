<?php
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
    header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
    // CONNECT TO THE DATABASE
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $e = $_POST['e'];
    $p = ($_POST['p']);
    $r = ($_POST['r']);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // FORM DATA ERROR HANDLING
    if($e == "" || $p == ""){
        echo "login_failed";
        exit();
    } else {
    // END FORM DATA ERROR HANDLING
    include("php_includes/mysqli_connect.php");
    $sql = "SELECT id, username, password, email FROM users WHERE email=:email AND activated=:activated LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':email', $e, PDO::PARAM_STR);
            $stmt->bindValue(':activated', '1', PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt->fetchAll() as $row) {
                 $db_id = $row['0'];
                 $db_username = $row['1'];
                 $db_pass_str = $row['2'];
                 $db_email = $row['3'];

            }
            $db_connect = null;

        if(!password_verify($p, $db_pass_str)){
            echo "invalid";
            exit();
        } else {
            // CREATE THEIR SESSIONS AND COOKIES
            $_SESSION['userid'] = $db_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['email'] = $db_email;
            $_SESSION['password'] = $db_pass_str;
            if($r == 'true') {
            setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("mail", $db_email, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
          } else if($r == 'false') {
            setcookie("id", '', strtotime( '-5 days' ), '/');
            setcookie("user", '', strtotime( '-5 days' ), '/');
            setcookie("mail", '', strtotime( '-5 days' ), '/');
            setcookie("pass", '', strtotime( '-5 days' ), '/');
          }
            // UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
            include("php_includes/mysqli_connect.php");
            $sql = "UPDATE users SET ip=:ip, lastlogin=now() WHERE email=:email LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':email', $db_email, PDO::PARAM_STR);
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();
            // Get the last visit and update date_visit table
            $sql = "SELECT latest_visit FROM date_visit WHERE username=:username LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':username', $db_username, PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
              $last_visit = $row['latest_visit'];
            }
            // Update the info in date_visit table
            $sql = "UPDATE date_visit SET last_visited='$last_visit', latest_visit=now() WHERE username=:username LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':username', $db_username, PDO::PARAM_STR);
            $stmt->execute();

            echo $db_username;
            exit();
            $db_connect = null;
        }
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
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
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

  <!-- Load Facebook SDK for JavaScript -->
        <div id="fb-root"></div>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              xfbml            : true,
              version          : 'v4.0'
            });
          };

          (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <!-- Your customer chat code -->
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="106131617414771"
    logged_in_greeting="Hi! How can we help you?"
    logged_out_greeting="Hi! How can we help you?">
        </div>

<?php include_once('templatePageTop.php'); ?>

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

<!-- Modal for login-->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Login</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form">
            <div class="form-group">
              <label for="usrname"><span class="glyphicon glyphicon-envelope"></span> Email</label>
              <input type="text" class="form-control" id="usrname" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
              <input type="password" class="form-control" id="psw" placeholder="Enter password">
            </div>
            <div class="checkbox">
              <label><input id="checkbx" type="checkbox">Remember me for 30 days</label>
            </div>
              <button type="submit" id="logbtn" onclick="login()" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
              <p id="loginstatus" style="text-align:center;"></p>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <p>Need a Contact Center? <a id="regBtn" href="#">Sign Up</a></p>
          <p>Forgot <a id="forgotpassword" href="#">Password?</a></p>
        </div>
      </div>

    </div>
  </div>
<!-- Ends here -->

<!-- Modal for signup -->
<div class="modal fade" id="regModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding:35px 50px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4><span class="glyphicon glyphicon-lock"></span> Sign Up</h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">
        <form role="form">
          <div class="form-group">
            <label for="usrname"><span class="glyphicon glyphicon-envelope"></span> Email</label>
            <input type="text" class="form-control" id="signupusrname" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
            <input type="password" class="form-control" id="signuppsw" placeholder="Enter password">
          </div>
          <div class="form-group">
            <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Repeat Password</label>
            <input type="password" class="form-control" id="signuppsw1" placeholder="Enter password">
          </div>
          <div class="checkbox">
            <label><input id="checkbox_id" type="checkbox" value="">Terms & Conditions</label>
          </div>
            <button type="submit" id="signupbtn" onclick="signup()" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Register</button>
            <p id="regstatus" style="text-align:center;"></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
      </div>
    </div>

  </div>
</div>
<!-- Ends Here -->
<!-- Modal for forgot Password -->
<div class="modal fade" id="forgotModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding:35px 50px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4><span class="glyphicon glyphicon-lock"></span> Forgot Password</h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">
        <form role="form">
          <div class="form-group">
            <label for="usrname"><span class="glyphicon glyphicon-envelope"></span> Email</label>
            <input type="text" class="form-control" id="forgotmail" placeholder="Enter email">
          </div>
            <button type="submit" id="forgotbtn" onclick="forgotpwd()" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Send</button>
            <p id="forgotstatus" style="text-align:center;"></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Ends Here -->

<div id="home" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#home" data-slide-to="0" class="active"></li>
      <li data-target="#home" data-slide-to="1"></li>
      <li data-target="#home" data-slide-to="2"></li>
      <li data-target="#home" data-slide-to="3"></li>
      <li data-target="#home" data-slide-to="4"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active" style="height=400px; width=1200px;">
        <img src="images/banners/newlogo/c1.png" alt="Image" height="400" width="1200">
      </div>
      <div class="item" style="height=400px; width=1200px;">
        <img src="images/banners/newlogo/c2.png" alt="Image" height="400" width="1200">
      </div>
      <div class="item" style="height=400px; width=1200px;">
        <img src="images/banners/newlogo/c3.png" alt="Image" height="400" width="1200">
      </div>
      <div class="item" style="height=400px; width=1200px;">
        <img src="images/banners/newlogo/c4.png" alt="Image" height="400" width="1200">
      </div>
      <div class="item" style="height=400px; width=1200px;">
        <img src="images/banners/newlogo/c5.jpg" alt="Image" height="400" width="1200">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#home" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#home" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<div id="about" class="container-fluid bg-3" style="min-height:500px;">
  <h3 style="text-align:center;">About Us</h3><br>
  <div class="well" style="background-color:#C71585; color:white; font-size:16px;">
    <div class="row">
    <div class="col-sm-6">
    <h3 style="text-align:center;"><u>Our mission</u></h3>
      <ul>
        <li>Our objective is to be the crucial link between our customers and their client, providing clients satisfaction, enhancing service delivery and being the voice for businesses on our platform.</li>
        <li>To provide our employees with opportunity, giving them voice to express their talent, passion, and commitment to excellence.</li>
        <li>To develop remarkable business communication solutions, using our expertise in other to serve our customers and help in growing their businesses.</li>
        <li>To give your business a voice.</li>
    </ul>
    </div>
    <div class="col-sm-6">
      <h3 style="text-align:center;"><u>Who we are</u></h3>
      <p>Callnect is an African focused global brand with vast experience of IT, Telecoms and customer services across levels. Business tools, applications, marketing campaigns and digital marketing are some of our area of expertise.
      </p>
      <p>We are focused on providing call center solutions such as inbounds, outbounds, agent training and consumer survey at affordable, convenient and rewardable values for all businesses, various organizations and bodies thereby getting timely action, reaction and interaction from their target audiences.
      </p>
      <p>Callnect is determined to take away the burden of call agents, consumer reachability, customer complain e.t.c off the shoulder of our clients without derail from their visions.
      </p>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <h3 style="text-align:center;"><u>Our values</u></h3>
      <ul>
        <li>Value: We hold our customers and team members in highest esteem and treat them with respect and dignity.
        </li>
        <li>Ownership: We stand behind what we say and the services we provide, and hold ourselves accountable for our resources and our actions.
        </li>
        <li>Integrity: We operate with uncompromising integrity in every conversation and transaction, and are guided by truth, honesty, and sincerity.
        </li>
        <li>Commitment: We are dedicated to developing trust-based relationships with our customers and team members.
        </li>
        <li>Excellence: We are self-motivated in our actions, disciplined in our decision-making, and directed by our values.
        </li>
      </ul>
    </div>
  </div>
</div>
</div>


<div id="price" class="container-fluid bg-3 text-center" style="min-height:500px; background-color: white;">
  <div class="col-sm-8 col-sm-offset-2">
  <h3>Features, Packages and Pricing</h3><br>

  <div class="row">
    <div class="col-sm-3">
      <div class="panel panel-default price">
        <div class="panel-heading price-heading"><h3>Bronze</h3></div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="You get a dedicated call center number through which customers can reach your agent(s)." style="background-color:whitesmoke"><span class="glyphicon glyphicon-ok"></span> Dedicated Contact Center Number</div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="A dedicated agent is assigned to your business. Additional agent(s) is at a fee."><span class="glyphicon glyphicon-ok"></span> Dedicated Agent(s)</div>
        <div style="background-color:whitesmoke" class="panel-body" data-toggle="tooltip" data-placement="auto" title="A dedicated sales representative is assigned to your business to ensure you get value for your contact center."><span class="glyphicon glyphicon-ok"></span> Dedicated Sales Rep</div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="Customers can chat with your agent"><span class="glyphicon glyphicon-ok"></span> 100 Inbound Calls</div>
        <div style="background-color:whitesmoke" class="panel-body" data-toggle="tooltip" data-placement="auto" title="You get a real time leads web notifications from your agent(s)"><span class="glyphicon glyphicon-ok"></span> Realtime Web Leads Notification</div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="You get a real time leads sms notifications from your agent(s)"><span class="glyphicon glyphicon-remove"></span> Realtime SMS Leads Notification</div>
        <div style="background-color:whitesmoke" class="panel-body" data-toggle="tooltip" data-placement="auto" title="A customized music on hold that tells customers about your business whenever they call in to your contact center."><span class="glyphicon glyphicon-ok"></span> Personalized Music on Hold</div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="Agent returns customer enquiry calls."><span class="glyphicon glyphicon-remove"></span> Agent Follow Up calls</div>
        <div style="background-color:whitesmoke" class="panel-body" data-toggle="tooltip" data-placement="auto" title="We run email campaign on your products and services."><span class="glyphicon glyphicon-remove"></span> Email Campaign</div>
        <div class="panel-body" data-toggle="tooltip" data-placement="auto" title="You get regular activity report based on your susbscribed package."><span class="glyphicon glyphicon-ok"></span> Report</div>
        <div style="background-color:#4682B4; color:#fff; border-radius:0;" class="panel-footer">
          <h3 style="margin-left:-10px;">&#8358;10,000</h3>
          <b>per month</b>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default price">
        <div class="panel-heading price-heading"><h3>Silver</h3></div>
        <div class="panel-body" style="background-color:whitesmoke"><span class="glyphicon glyphicon-ok"></span> Dedicated Contact Center Number</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Agent(s)</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Sales Rep</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> 500 Inbound Calls</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime Web Leads Notification</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime SMS Leads Notification</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Personalized Music on Hold</div>
        <div class="panel-body"><span class="glyphicon glyphicon-remove"></span> Agent Follow Up calls</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-remove"></span> Email Campaign</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Report</div>
        <div style="background-color:#4682B4; color:#fff; border-radius:0;" class="panel-footer">
          <h3 style="margin-left:-10px;">&#8358;50,000</h3>
          <b>per month</b>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default price">
        <div class="panel-heading price-heading"><h3>Gold</h3></div>
        <div class="panel-body" style="background-color:whitesmoke"><span class="glyphicon glyphicon-ok"></span> Dedicated Contact Center Number</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Agent(s)</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Sales Rep</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> 1000 Inbound Calls</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime Web Leads Notification</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime SMS Leads Notification</div>
        <div style="background-color:whitesmoke"  class="panel-body"><span class="glyphicon glyphicon-ok"></span> Personalized Music on Hold</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Agent Follow Up calls</div>
        <div style="background-color:whitesmoke"  class="panel-body"><span class="glyphicon glyphicon-remove"></span> Email Campaign</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Report</div>
        <div style="background-color:#4682B4; color:#fff; border-radius:0;" class="panel-footer">
          <h3 style="margin-left:-10px;">&#8358;100,000</h3>
          <b>per month</b>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default price">
        <div class="panel-heading price-heading"><h3>Platinum</h3></div>
        <div class="panel-body" style="background-color:whitesmoke"><span class="glyphicon glyphicon-ok"></span> Dedicated Contact Center Number</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Agent(s)</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Dedicated Sales Rep</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> 2000 Inbound Calls</div>
        <div style="background-color:whitesmoke" class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime Web Leads Notification</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Realtime SMS Leads Notification</div>
        <div style="background-color:whitesmoke"  class="panel-body"><span class="glyphicon glyphicon-ok"></span> Personalized Music on Hold</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Agent Follow Up calls</div>
        <div style="background-color:whitesmoke"  class="panel-body"><span class="glyphicon glyphicon-ok"></span> Email Campaign</div>
        <div class="panel-body"><span class="glyphicon glyphicon-ok"></span> Report</div>
        <div style="background-color:#4682B4; color:#fff; border-radius:0;" class="panel-footer">
          <h3 style="margin-left:-10px;">&#8358;200,000</h3>
          <b>per month</b>
        </div>
      </div>
    </div>
  </div>
</div>
<!--
<div class="col-sm-12">
  <div class="row">
    <div class="well servicememo" style="background-color:#C71585; color:#fff; font-size:16px; border-radius:60px; border: 1px solid #C71585;">
      All plans come with 1 dedicated agent, each additional agent(s) in situation of increased call volumes attracts a fee.
Each plan has an eight hours a day, 5 days a week call center operation. Weekend and 24/7 operations is also available on request.
    </div>
  </div>
</div> -->
  <div class="col-sm-8 col-sm-offset-2">
    <div style="margin-bottom:10px; font-size:2em; color:grey;">Other Available Services</div>
  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">Outbound Call Campaign</div>
        <div class="panel-body pbody">
          For telemarketing, sales, fund raising, creating public awareness etc. Outbound campaign can help your business to reach out to existing or prospective customers. At CallNect, it's our responsibility to help our clients grow their businesses and open new frontiers for greater opportunity.
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">SMS Campaign</div>
        <div class="panel-body pbody">
          We engage short messages to reach out to your existing and prospective customers. With SMS campaign, you can reach people on the go, as your customers are most likely to read the text message.
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">Email Campaign</div>
        <div class="panel-body pbody">
          With a well design email describing your products and services, callnect would ensure that your business is well represented to the target audience. Email campaign can greatly increase the awareness for your organizations.
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">Social Media Page Management</div>
        <div class="panel-body pbody">
          There is no denying the fact that social media marketing is here with us to stay. We can project your image on major social media platforms - Facebook, Twitter, Youtube. Do you have a dormant page, or you don't even have any? Callnect will manage them for you to increase your customer base.
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">IVR Language Option</div>
        <div class="panel-body pbody">
          Do you want to communicate to your customers in their local language? Callnect can provide an interractive voice response in major Nigeria languages - Hausa, Yoruba and Igbo.
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-default optionals">
        <div class="panel-heading opt-heading">Live Chat</div>
        <div class="panel-body pbody">
          Over 63% of consumers reported that they are more likely to return to a website that offers live chat. At CallNect, we provide a live chat page for your business on our platform, we can also integrate same live chat on your official website. Our agents are available to answer your customers' enquiry.
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<div class="col-sm-8 col-sm-offset-2 text-center">
  <button type="button" class="btn btn-success btn-lg" id="userReg">Begin now...</button>
</div>
<div id="contact" class="container-fluid bg-3 text-center" style="min-height:500px;">
  <h3 style="color:white;">Contact Us</h3><br>
  <div class="row">
    <div class="col-sm-6">
      <div class="jumbotron text-left">
        <h2>CallNect International Limited</h2>
        <p style="font-size:18px;"><i>...your business voice</i></p>
        <br>
        <p><i class="far fa-envelope"></i><span> info@callnect.com</span></p>
        <p><i class="fas fa-mobile-alt"></i><span> +014400067</span></p>
        <br>
        <a href="https://www.facebook.com/callnectinternational"><i class="fab fa-facebook-square fa-2x social"></i></a>
        <a href="https://twitter.com/callnectint"><i class="fab fa-twitter-square fa-2x social"></i></a>
        <a href="https://linkedin.com/company/callnectinternational"><i class="fab fa-linkedin fa-2x social"></i></a>
        <a href="https://www.youtube.com/channel/UC7uohRTwyIZpi7NbwEbkAGg"><i class="fab fa-youtube-square fa-2x social"></i></a>
        <a href="https://m.me/106131617414771"><i class="fab fa-2x fa-facebook-messenger"></i></a>
    </div>
    </div>
    <div class="col-sm-6">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d869.643020235206!2d3.8650725281306655!3d7.302359712248918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398e57ffb1466b%3A0x72ec7982bde48aab!2s99+Old+Lagos+Road%2C+Ibadan!5e1!3m2!1sen!2sng!4v1555584872457!5m2!1sen!2sng" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
  </div>
</div>

<footer style="background-color:black; color:white;" class="container-fluid text-center">
  <p>Copyright &copy<?php echo date("Y"); ?> - CallNect</p>
</footer>
<link rel="stylesheet" href="style/style.css">
<script src="js/functions.js"></script>
</body>
</html>

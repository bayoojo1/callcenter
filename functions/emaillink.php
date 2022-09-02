<?php
include("../php_includes/mysqli_connect.php");
if(isset($_POST['email']) && !empty($_POST['email'])){
    $email = preg_replace('#[^a-z0-9@.,_]#i', '', $_POST['email']);
    $username = preg_replace('#[^a-z0-9._-]#i', '', $_POST['username']);
} else {
  echo '<span style="background-color:forestgreen; color:white;"><b>Please enter the email(s)</b></span>';
  exit();
}
// Do some email checking
if(strpos($email, '@') === false) {
  echo '<span style="background-color:red; color:white;"><b>Are you sure that\'s an email address?</b></span>';
  exit();
}
// Select the username string of the sales person
$sql = "SELECT username_string FROM fsrdetails WHERE username=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $user_string = $row['0'];
}

$email_body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>CallNect Contact Center</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>
<body style="text-align: center; font-family:Tahoma, Geneva, sans-serif;">
    <p><img style="text-align: center;" src="cid:c1.png" alt="" width="1200" height="400" /></p>
<h2 style="text-align: center;"><em><strong><span style="color: #1E90FF;">We offer the following services:</span></strong></em></h2>
<ul style="list-style-type: disc;">
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Dedicated contact center number:</span></h3>
<p><span style="color: #000000;">Your business is allocated a dedicated contact center number through which customers/clients can reach your assigned dedicated agent.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Dedicated agent:</span></h3>
<p><span style="color: #000000;">Your business is assigned a dedicated agent who answers calls inbound to your call center, and provides requested information to your customers.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Dedicated sales representative:</span></h3>
<p><span style="color: #000000;">A dedicated sales representative who acts as point of contact between your business and callNect. Helps in making sure you fully utilize the benefits of your call center solutions.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Live chat with agent:</span></h3>
<p><span style="color: #000000;">Customers can have a real time chat with your agent and get the needed information.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Realtime leads notification:</span></h3>
<p><span style="color: #000000;">You get a realtime leads notification both on your CallNect page and via SMS. This ensure you don\'t miss any sales or enquiry opportunity about your business.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Personalized Music-on-Hold:</span></h3>
<p><span style="color: #000000;">When customers call into your call center, a personalized Music-on-Hold is played to them, advertising your products and services, before the agent picks the call.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Agent follow up calls:</span></h3>
<p><span style="color: #000000;">Your agent follow up with you and your customers with an outgoing calls to provide needed information from customers.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Outbound campaign:</span></h3>
<p><span style="color: #000000;">We carry out targeted outbound products/services campaign to create awareness about your new products and services. This could also be for disseminating any type of information about your business. </span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">SMS campaign:</span></h3>
<p><span style="color: #000000;">We carry out targeted SMS products and services campaign for your business.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Email campaign:</span></h3>
<p><span style="color: #000000;">We carry out targeted email products and services campaign for your business.</span></p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Social media page management:</span></h3>
<p><span style="color: #000000;">We help in creating/managing your business social media pages. This includes periodic ads campaign.</p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">IVR languages option:</span></h3>
<p><span style="color: #000000;">Depending on your choice, we provide IVR in 3 major Nigeria languages to serve your call-in customers in whatever language they want.</p>
</li>
<li style="text-align: left;">
<h3><span style="color: #C71585; text-decoration: underline;">Report:</span></h3>
<p><span style="color: #000000;">Detail report of your call center activities is provided to you.</p>
</ul>
<h2 style="text-align: center;"><a href="https://www.callnect.com/regyourbuz.php?sid='.$user_string.'" target="_blank">Register your business today</a></h2>
</body>
</html>
';
// Send email to the mail list
$filepath = '/wamp64/www/callcenter/images/banners/newlogo/c1.png';
$array = explode(',', $email); //split emails into array seperated by ','
foreach($array as $e) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/mail1.callnect.com/messages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-833e41bd255e7d164bbfe48f981bdf6e");
    $post = array(
        'from' => 'CallNect <info@callnect.com>',
        'to' => $e,
        'subject' => 'CallNect Contact Center Solutions',
        'html' => $email_body,
        'inline' => curl_file_create($filepath),
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $result = curl_exec($ch);

    if($result === false)
    {
        echo "Error Number:".curl_errno($ch)."<br>";
        echo "Error String:".curl_error($ch);
    } else {
      echo 'success';
    }
    curl_close($ch);
}
?>

<?php
include("./php_includes/mysqli_connect.php");
// Select the businesses in the portfolio of this fsr
$fsrbizlist = '';
// Get a week ago date
$aWeekago = date('Y-m-d H:i:s', strtotime('-1 week'));
$paginationCtrls = '<span>';

$sql_trans = "SELECT businessdetails.id, businessUsername, businessEmail, businessName, address, contactName, mobile, website, callnect_Number, businessDescription FROM businessdetails INNER JOIN fsr_business_alloc ON businessdetails.username=fsr_business_alloc.businessUsername WHERE fsr_business_alloc.fsrUsername=:username";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();
$bizCount = $stmt->rowCount();
// Specify how many result per page
$rpp = '10';
// This tells us the page number of the last page
$last = ceil($bizCount/$rpp);
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
$sql = "$sql_trans"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();
if($bizCount > 0){
  //$paginationCtrls .= '<div class="col-sm-9">';
  $paginationCtrls .= '<ul class="pagination">';
  if($last != 1){
      /* First we check if we are on page one. If we are then we don't need a link to
         the previous page or the first page so we do nothing. If we aren't then we
         generate links to the first page, and to the previous page. */
      if ($pn > 1) {
          $previous = $pn - 1;
          $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$previous.'">Previous</a></li> &nbsp; &nbsp;';
          // Render clickable number links that should appear on the left of the target page number
          for($i = $pn-4; $i < $pn; $i++){
              if($i > 0){
                  $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
              }
          }
      }
      // Render the target page number, but without it being a link
      $paginationCtrls .= '<li class="active"><a href="#">'.$pn.'</a></li> &nbsp; ';
      // Render clickable number links that should appear on the right of the target page number
      for($i = $pn+1; $i <= $last; $i++){
          $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a></li> &nbsp;';
          if($i >= $pn+4){
              break;
          }
      }
      // This does the same as above, only checking if we are on the last page, and then generating the "Next"
      if ($pn != $last) {
          $next = $pn + 1;
          $paginationCtrls .= ' &nbsp; &nbsp; <li><a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$next.'">Next</a></li>';
      }
  }
  $paginationCtrls .= '</ul>';
  $paginationCtrls .= '</span>';

//$fsrbizlist .= '<div class="panel panel-primary">';
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $bizEmail = $row['businessEmail'];
  $bizusername = $row['businessUsername'];
  $bizName = $row['businessName'];
  $bizaddress = $row['address'];
  $bizcontact = $row['contactName'];
  $bizmobile = $row['mobile'];
  $bizwebsite = $row['website'];
  $bizDescription = $row['businessDescription'];
  $bizCallnectMobile = $row['callnect_Number'];

  $fsrbizlist .= '<div class="panel panel-primary">';
  // Check for those later than 1 week
  $sql = "SELECT id, leadDate FROM salesleads WHERE businessUsername=:businessUsername ORDER BY id DESC LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':businessUsername', $bizusername, PDO::PARAM_STR);
  $stmt->execute();
  $leadRows = $stmt->rowCount();
  if($leadRows > 0) {

  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    if($row['leadDate'] <= $aWeekago) {
  $fsrbizlist .= '<div class="panel-heading" style="font-size:20px; background-color:darkorange;"><b>'.$bizName.'</b></div>';
} else {
  $fsrbizlist .= '<div class="panel-heading" style="font-size:20px;"><b>'.$bizName.'</b></div>';
    }
  }
} else {
  $fsrbizlist .= '<div class="panel-heading" style="font-size:20px; background-color:grey;"><b>'.$bizName.'</b></div>';
}
  $fsrbizlist .= '<div class="panel-body bizdetails"><b>'.$bizDescription.'</b></div>';
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Address:</b> ' .$bizaddress.' </p>';
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Contact:</b> ' .$bizcontact.' </p>';
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Contact Mobile:</b> ' .$bizmobile.' </p>';
  if(isset($bizEmail) && !empty($bizEmail)) {
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Email:</b> '.$bizEmail.'</p>';
}
  if(isset($bizwebsite) && !empty($bizwebsite)) {
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Website:</b><a href="'.$bizwebsite.'"> '.$bizwebsite.'</a></p>';
}
  $fsrbizlist .= '<p class="panel-body bizdetails"><b>Call Center Number:</b> '.$bizCallnectMobile.'</p>';
  $fsrbizlist .= '</div>';
  }
}
echo $paginationCtrls;
echo $fsrbizlist;
?>

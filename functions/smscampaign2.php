<?php
if(isset($_FILES["csv"])) {
  $csv_uploadDir = '/wamp64/www/callcenter/csv/';
  $csv_fileName = $_FILES["csv"]["name"];
  $csv_fileTmpLoc = $_FILES["csv"]["tmp_name"];
  $csv_fileType = $_FILES["csv"]["type"];
  $csv_fileSize = $_FILES["csv"]["size"];
  $csv_fileErrorMsg = $_FILES["csv"]["error"];
  $kaboom = explode(".", $csv_fileName);
  $csv_fileExt = end($kaboom);

  $csv_file_name = rand(100000000000,999999999999).".".$csv_fileExt;
  $csv_db_file_name = $csv_uploadDir . $csv_file_name;

  if(!$csv_fileTmpLoc) {
      echo "ERROR: You need to upload your sample csv file.";
      exit();
  } else if ($csv_fileSize > 2097152) {
      echo "ERROR: Your csv file was larger than 20mb.";
      exit();
  } else if (!preg_match("/\.(csv)$/i", $csv_fileName) ) {
      echo "ERROR: Your file was not csv type.";
      exit();
  } else if ($csv_fileErrorMsg == 1) {
      echo "ERROR: An unknown error occurred.";
      exit();
  }
// Move uploaded file to the final directories
$moveResult = move_uploaded_file($csv_fileTmpLoc, $csv_db_file_name);
if ($moveResult != true) {
    echo "ERROR: File upload failed.";
    exit();
}
// Gather the remaining posted variables
if(isset($_POST['text2']) && !empty($_POST['text2'])) {
  $text = preg_replace('#[^a-z0-9:.,-?@!/=+ \']#i', '', $_POST['text2']);
}

$file = fopen($csv_db_file_name, "r");
   while (($csvData = fgetcsv($file, 1000000, ",")) !== FALSE) {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, "https://www.bulksmsnigeria.com/api/v1/sms/create");
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
     curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "rVxoRKLzm9lDk6EV2XkNT4hgPXxssCJ4ABgQxCFKweWqoShHrAji9yHyFZTK");
     $post = array(
         'from' => 'CallNect',
         'to' => $csvData[0],
         'body' => $text,
     );
     curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
     $result = curl_exec($ch);
     if($result === false) {
         echo "Error Number:".curl_errno($ch)."<br>";
         echo "Error String:".curl_error($ch);
     } else {
       echo "Campaign successfully started";
     }
     curl_close($ch);
   }
   fclose($file);
} else {
    echo "ERROR: You need to upload your csv file.";
    exit();
}
?>

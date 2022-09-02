<?php
include("./php_includes/mysqli_connect.php");
// Get the current package settings
$sql = "SELECT * FROM package";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
echo '<table class="table table-hover table-bordered">';
echo '<thead>';
echo '<tr>';
echo '<th style="text-align:center;">Plan</th>';
echo '<th style="text-align:center;">Price</th>';
echo '</tr>';
echo '</th>';
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $rows) {
  $id = $rows['id'];
  $plan = $rows['plan'];
  $price = $rows['price'];

echo '<tbody>';
  echo '<tr>';
    echo '<td>'.$plan.'</td>';
    echo '<td id="Plan_'.$id.'">'.$price.'</td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" onclick="edit(this);"><span class="glyphicon glyphicon-edit"></span> Edit</button></td>';
    echo '<td><button type="button" class="btn btn-default btn-sm" style="visibility:hidden" onclick="save(this);"><span class="glyphicon glyphicon-floppy-save"></span> Save</button></td>';
  echo '</tr>';
echo '</tbody>';
}
echo '</table>';

?>

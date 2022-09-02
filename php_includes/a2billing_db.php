<?php
try {
    $a2billing_connect = new PDO('mysql:host=10.32.0.10;dbname=callnecta2billing', 'a2billinguser', 'C@LLn3ct');
    $a2billing_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage()."<br>";
    die();
}
?>

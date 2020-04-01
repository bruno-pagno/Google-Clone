<?php
ob_start();
try {

    $con = new PDO("mysql:dbname=broogle;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch(PDOExeption $e) {
    echo "Connectio failed: " .$e->getMessage();
}
?>
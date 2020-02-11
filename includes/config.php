<?php
ob_start(); // Turns on output buffering
session_start();

date_default_timezone_set("Europe/London");

try {
    // connection à la base de données
    $con = new PDO("mysql:dbname=teamproject;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
?>
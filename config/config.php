<?php
session_start();
ob_start();
setlocale(LC_TIME, 'fr_FR.utf8','fra'); 

ini_set('error_reporting', E_ALL);
ini_set( 'display_errors', 1 );
	

$timezone = date_default_timezone_set("Europe/Paris");

try {
    $db = new PDO("mysql:host=localhost;dbname=slotify", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to the database " . $e->getMessage();
}

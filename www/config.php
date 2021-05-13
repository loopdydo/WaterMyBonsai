<?php
/* Database credentials. Change webuser password for production. */
define('DB_SERVER', '192.168.2.12');
define('DB_USERNAME', 'webuser');
define('DB_PASSWORD', 'web');
define('DB_NAME', 'bonsai');
 
/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
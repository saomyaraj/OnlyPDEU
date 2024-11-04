<?php
    require_once('../config.php');

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }
?>
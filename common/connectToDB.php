<?php

function connectToDB(){
    include(dirname(__DIR__)."/localSettings.php");
    
    $conn = new mysqli($databaseAddress,  $databaseUsername, $databasePassword, $databaseName);  

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
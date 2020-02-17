<?php

function connectToDB(){
    include("localSettings.php");
    
    $conn = new mysqli($databaseAddress,  $databaseUsername, $databasePassword, $databaseName);  

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $db_name = "resume";

    /* 1. Server connection (without DB) */
    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* 2. Create database */
    $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
    if (!$conn->query($sql)) {
        die("Database creation failed: " . $conn->error);
    }

    /* 3. Connect with database */
    $conn = new mysqli($host, $username, $password, $db_name);

    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    }
?>

<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $db_name = "resume";

    /* Connect to MySQL */
    $conn = new mysqli($host, $username, $password);

    /* Connection check */
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* Create database */
    $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";

    if ($conn->query($sql) === TRUE) {
        $conn->select_db($db_name);
    } else {
        die("Database creation failed: " . $conn->error);
    }
?>
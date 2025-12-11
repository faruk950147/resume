<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "resume";

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $db_name");

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>

<?php
$host = 'localhost'; // or 127.0.0.1
$user = 'root';      // your DB username
$pass = '';          // your DB password
$dbname = 'job_applcation';  // your DB name

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();

}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);


?>





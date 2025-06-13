<?php
$host = 'localhost';
$dbname = 'dbiczf6lipynsk';
$username = 'uxgukysg8xcbd';
$password = '6imcip8yfmic';

try {
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

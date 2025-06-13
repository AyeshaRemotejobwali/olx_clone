<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$listing_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "UPDATE listings SET status='deleted' WHERE id='$listing_id' AND user_id='{$_SESSION['user_id']}'";
if (mysqli_query($conn, $query)) {
    echo "<script>window.location.href='index.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - OLX Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-container p {
            margin: 10px 0;
        }
        .profile-container a {
            color: #002f34;
            text-decoration: none;
        }
        .profile-container a:hover {
            text-decoration: underline;
        }
        .my-listings {
            margin-top: 20px;
        }
        .listing-card {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .listing-card img {
            max-width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .profile-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
        <div class="my-listings">
            <h3>My Listings</h3>
            <?php
            $query = "SELECT * FROM listings WHERE user_id = '$user_id' AND status = 'active'";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='listing-card'>";
                echo "<img src='{$row['image']}' alt='{$row['title']}'>";
                echo "<p><strong>{$row['title']}</strong></p>";
                echo "<p>Price: PKR {$row['price']}</p>";
                echo "<a href='#' onclick=\"redirectTo('view_ad.php?id={$row['id']}')\">View</a>";
                echo "</div>";
            }
            ?>
        </div>
        <p><a href="#" onclick="redirectTo('index.php')">Back to Home</a></p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

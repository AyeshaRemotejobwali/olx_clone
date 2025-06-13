<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLX Clone - Marketplace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f5f5f5;
        }
        .header {
            background-color: #002f34;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 24px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .search-bar {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-bar input[type="text"] {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-bar select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-left: 10px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #002f34;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .listings {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .listing-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .listing-card:hover {
            transform: scale(1.05);
        }
        .listing-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
        .listing-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .listing-card p {
            color: #555;
        }
        .listing-card button {
            background-color: #002f34;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .footer {
            background-color: #002f34;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            .search-bar input[type="text"] {
                width: 100%;
            }
            .search-bar select, .search-bar button {
                margin-top: 10px;
                width: 100%;
            }
            .header h1 {
                font-size: 20px;
            }
            .nav a {
                font-size: 14px;
                margin-left: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>OLX Clone</h1>
        <div class="nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="redirectTo('profile.php')">Profile</a>
                <a href="#" onclick="redirectTo('post_ad.php')">Post Ad</a>
                <a href="#" onclick="redirectTo('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="redirectTo('login.php')">Login</a>
                <a href="#" onclick="redirectTo('signup.php')">Signup</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="search-bar">
        <form action="index.php" method="GET">
            <input type="text" name="search" placeholder="Search for products...">
            <select name="category">
                <option value="">All Categories</option>
                <option value="Electronics">Electronics</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Furniture">Furniture</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="listings">
        <?php
        $query = "SELECT * FROM listings WHERE status = 'active'";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $query .= " AND title LIKE '%$search%'";
        }
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = mysqli_real_escape_string($conn, $_GET['category']);
            $query .= " AND category = '$category'";
        }
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='listing-card'>";
            echo "<img src='uploads/{$row['image']}' alt='{$row['title']}'>";
            echo "<h3>{$row['title']}</h3>";
            echo "<p>Price: PKR {$row['price']}</p>";
            echo "<p>Category: {$row['category']}</p>";
            echo "<button onclick=\"redirectTo('view_ad.php?id={$row['id']}')\">View Details</button>";
            echo "</div>";
        }
        ?>
    </div>
    <div class="footer">
        <p>&copy; 2025 OLX Clone. All rights reserved.</p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

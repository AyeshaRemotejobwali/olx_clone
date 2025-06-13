<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $user_id = $_SESSION['user_id'];

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $query = "INSERT INTO listings (user_id, title, description, price, category, image) VALUES ('$user_id', '$title', '$description', '$price', '$category', '$image')";
    if (mysqli_query($conn, $query)) {
        echo "<script>window.location.href='index.php';</script>";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Ad - OLX Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .post-ad-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-ad-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .post-ad-container input, .post-ad-container textarea, .post-ad-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .post-ad-container button {
            width: 100%;
            padding: 10px;
            background-color: #002f34;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .post-ad-container button:hover {
            background-color: #004f54;
        }
        .error {
            color: red;
            text-align: center;
        }
        @media (max-width: 768px) {
            .post-ad-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="post-ad-container">
        <h2>Post a New Ad</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Ad Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price (PKR)" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Electronics">Electronics</option>
                <option value="Vehicles">Vehicles</option>
                <option value="Furniture">Furniture</option>
            </select>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post Ad</button>
        </form>
        <p><a href="#" onclick="redirectTo('index.php')">Back to Home</a></p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

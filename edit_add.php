<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$listing_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM listings WHERE id = '$listing_id' AND user_id = '{$_SESSION['user_id']}'";
$result = mysqli_query($conn, $query);
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $image = $listing['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $query = "UPDATE listings SET title='$title', description='$description', price='$price', category='$category', image='$image' WHERE id='$listing_id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>window.location.href='view_ad.php?id=$listing_id';</script>";
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
    <title>Edit Ad - OLX Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .edit-ad-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .edit-ad-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .edit-ad-container input, .edit-ad-container textarea, .edit-ad-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .edit-ad-container button {
            width: 100%;
            padding: 10px;
            background-color: #002f34;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-ad-container button:hover {
            background-color: #004f54;
        }
        .error {
            color: red;
            text-align: center;
        }
        @media (max-width: 768px) {
            .edit-ad-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="edit-ad-container">
        <h2>Edit Ad</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" value="<?php echo $listing['title']; ?>" required>
            <textarea name="description" required><?php echo $listing['description']; ?></textarea>
            <input type="number" name="price" value="<?php echo $listing['price']; ?>" required>
            <select name="category" required>
                <option value="Electronics" <?php if ($listing['category'] == 'Electronics') echo 'selected'; ?>>Electronics</option>
                <option value="Vehicles" <?php if ($listing['category'] == 'Vehicles') echo 'selected'; ?>>Vehicles</option>
                <option value="Furniture" <?php if ($listing['category'] == 'Furniture') echo 'selected'; ?>>Furniture</option>
            </select>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Update Ad</button>
        </form>
        <p><a href="#" onclick="redirectTo('view_ad.php?id=<?php echo $listing_id; ?>')">Back to Ad</a></p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

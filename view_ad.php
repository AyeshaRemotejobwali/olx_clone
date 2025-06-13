<?php
session_start();
include 'db.php';
if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$listing_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT listings.*, users.username, users.phone FROM listings JOIN users ON listings.user_id = users.id WHERE listings.id = '$listing_id' AND listings.status = 'active'";
$result = mysqli_query($conn, $query);
$listing = mysqli_fetch_assoc($result);

if (!$listing) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $listing['user_id'];
    $query = "INSERT INTO messages (sender_id, receiver_id, listing_id, message) VALUES ('$sender_id', '$receiver_id', '$listing_id', '$message')";
    mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ad - OLX Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .ad-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .ad-container img {
            max-width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
        }
        .ad-container h2 {
            margin: 10px 0;
        }
        .ad-container p {
            margin: 5px 0;
        }
        .chat-box {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .chat-box textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .chat-box button {
            padding: 10px 20px;
            background-color: #002f34;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .chat-box button:hover {
            background-color: #004f54;
        }
        .messages {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .message {
            padding: 10px;
            background-color: #f9f9f9;
            margin: 5px 0;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .ad-container {
                margin: 20px;
            }
            .ad-container img {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="ad-container">
        <h2><?php echo $listing['title']; ?></h2>
        <img src="<?php echo $listing['image']; ?>" alt="<?php echo $listing['title']; ?>">
        <p><strong>Price:</strong> PKR <?php echo $listing['price']; ?></p>
        <p><strong>Category:</strong> <?php echo $listing['category']; ?></p>
        <p><strong>Description:</strong> <?php echo $listing['description']; ?></p>
        <p><strong>Seller:</strong> <?php echo $listing['username']; ?></p>
        <p><strong>Contact:</strong> <?php echo $listing['phone']; ?></p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $listing['user_id']): ?>
            <div class="chat-box">
                <h3>Contact Seller</h3>
                <div class="messages">
                    <?php
                    $query = "SELECT messages.*, users.username FROM messages JOIN users ON messages.sender_id = users.id WHERE listing_id = '$listing_id' ORDER BY created_at";
                    $messages = mysqli_query($conn, $query);
                    while ($msg = mysqli_fetch_assoc($messages)) {
                        echo "<div class='message'><strong>{$msg['username']}:</strong> {$msg['message']}</div>";
                    }
                    ?>
                </div>
                <form method="POST">
                    <textarea name="message" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $listing['user_id']): ?>
            <button onclick="redirectTo('edit_ad.php?id=<?php echo $listing_id; ?>')">Edit Ad</button>
            <button onclick="if(confirm('Are you sure?')) redirectTo('delete_ad.php?id=<?php echo $listing_id; ?>')">Delete Ad</button>
        <?php endif; ?>
        <p><a href="#" onclick="redirectTo('index.php')">Back to Home</a></p>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

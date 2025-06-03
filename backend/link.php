<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$existing_link = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_link'])) {
        $link_url = $_POST['link_url'];

        // Check if a link already exists
        $check_sql = "SELECT id FROM research_links LIMIT 1";
        $result = $conn->query($check_sql);
        
        if ($result->num_rows > 0) {
            // Update existing link
            $link_id = $result->fetch_assoc()['id'];
            $sql = "UPDATE research_links SET link_url='$link_url' WHERE id=$link_id";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='message'>Link updated successfully</p>";
            } else {
                echo "<p class='message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        } else {
            // Insert new link
            $sql = "INSERT INTO research_links (link_url) VALUES ('$link_url')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='message'>New link added successfully</p>";
            } else {
                echo "<p class='message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }
    } elseif (isset($_POST['update_link'])) {
        $link_id = $_POST['link_id'];
        $link_url = $_POST['link_url'];

        // Update existing link
        $sql = "UPDATE research_links SET link_url='$link_url' WHERE id=$link_id";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='message'>Link updated successfully</p>";
        } else {
            echo "<p class='message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['delete_link'])) {
        $link_id = $_POST['link_id'];

        // Delete link
        $sql = "DELETE FROM research_links WHERE id=$link_id";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='message'>Link deleted successfully</p>";
        } else {
            echo "<p class='message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

// Fetch existing link if present
$check_sql = "SELECT id, link_url FROM research_links LIMIT 1";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    $existing_link = $result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Research Links</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="url"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            color: #fff;
            background-color: #007bff;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            background-color: #e0ffe0;
            color: #333;
        }
        .error {
            background-color: #ffe0e0;
            color: #d9534f;
        }
        .hidden {
            display: none;
        }
        .admin-dashboard-link {
            display: inline-block;
            padding: 12px 25px;
            color: #fff;
            background-color: #1d4ed8; /* Blue color */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Bold text */
            font-size: 18px; /* Font size */
            border-radius: 50px; /* Rounded corners */
            transition: all 0.3s ease; /* Smooth transitions */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Shadow effect */
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }
        .admin-dashboard-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1), rgba(255,255,255,0.3));
            transition: all 0.5s ease;
        }
        .admin-dashboard-link:hover::before {
            left: 100%;
        }
        .admin-dashboard-link:hover {
            background-color: #0d2a7f;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }
        .admin-dashboard-link-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Manage Research Links</h1>

    <?php if (!$existing_link): ?>
    <form method="POST" id="add-link-form">
        <label for="link_url">Link URL:</label>
        <input type="url" id="link_url" name="link_url" required>
        <button type="submit" name="add_link">Add Link</button>
    </form>
    <?php else: ?>
    <p class="message">A link already exists. You can update or delete it below.</p>
    <?php endif; ?>

    <h2>Update or Delete Links</h2>
    <form method="POST">
        <?php if ($existing_link): ?>
        <label for="link_id">Link ID:</label>
        <input type="number" id="link_id" name="link_id" value="<?php echo $existing_link['id']; ?>" readonly>
        <br>

        <label for="link_url">Current Link URL:</label>
        <input type="url" id="link_url" name="link_url" value="<?php echo $existing_link['link_url']; ?>" required>
        <br>
        <button type="submit" name="update_link">Update Link</button>
        <button type="submit" name="delete_link">Delete Link</button>
        <?php else: ?>
        <p>No existing link to update or delete.</p>
        <?php endif; ?>
    </form>
    <div class="admin-dashboard-link-container">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
</body>
</html>

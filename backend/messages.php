<?php
// Database connection
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include "db.php"; // Adjust the path as per your setup

// Function to fetch messages from database
function getMessages() {
    global $mysqli;
    $sql = "SELECT * FROM contacts";
    $result = mysqli_query($mysqli, $sql);
    $messages = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = $row;
        }
    }
    return $messages;
}

// Function to delete a message by ID
function deleteMessage($id) {
    global $mysqli;
    $id = mysqli_real_escape_string($mysqli, $id);
    $sql = "DELETE FROM contacts WHERE id = '$id'";
    if (mysqli_query($mysqli, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Handle message deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    if (deleteMessage($delete_id)) {
        echo '<script>alert("Message deleted successfully.");</script>';
    } else {
        echo '<script>alert("Failed to delete message.");</script>';
    }
}

// Fetch messages from database
$messages = getMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f7f7f7;
            color: #555;
        }
        table td.message {
            max-width: 300px; /* Adjust as needed */
            word-wrap: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .delete-btn:hover {
            background-color: #cc0000;
            transform: scale(1.05);
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
    <div class="container">
        <h2>Client Messages</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?php echo $message['id']; ?></td>
                    <td><?php echo $message['nom']; ?></td>
                    <td><?php echo $message['email']; ?></td>
                    <td class="message"><?php echo $message['message']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="delete_id" value="<?php echo $message['id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="admin-dashboard-link-container">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
    </div>
</body>
</html>

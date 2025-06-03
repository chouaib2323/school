<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Database connection
$host = 'localhost';
$dbname = 'school';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'add_about_us') {
        $text = $_POST['text'];
        // Delete existing entry if any
        $stmt = $pdo->prepare("DELETE FROM about_us WHERE id = 1");
        $stmt->execute();
        // Insert new entry with id = 1
        $stmt = $pdo->prepare("INSERT INTO about_us (id, text) VALUES (1, :text)");
        $stmt->execute(['text' => $text]);
    } elseif ($action === 'update_about_us') {
        $text = $_POST['text'];
        $stmt = $pdo->prepare("UPDATE about_us SET text = :text WHERE id = 1");
        $stmt->execute(['text' => $text]);
    } elseif ($action === 'delete_about_us') {
        $stmt = $pdo->prepare("DELETE FROM about_us WHERE id = 1");
        $stmt->execute();
    }
}

// Fetch the current About Us text
$stmt = $pdo->query("SELECT * FROM about_us WHERE id = 1");
$about_us = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - About Us</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            color: #444;
        }
        textarea {
            width: 100%;
            height: 200px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
        }
        button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-family: 'Arial', sans-serif;
        }
        button.delete {
            background-color: #f44336;
        }
        button.save {
            background-color: #4CAF50;
        }
        button:hover {
            transform: scale(1.05);
            opacity: 0.8;
        }
        .admin-dashboard-link {
            display: inline-block;
            padding: 12px 25px;
            color: #fff;
            background-color: #1d4ed8;
            text-decoration: none;
            font-weight: bold;
            font-size: 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Manage About Us</h1>
        
        <?php if ($about_us): ?>
            <h2>Update or Delete About Us Text</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="update_about_us">
                <textarea name="text"><?php echo htmlspecialchars($about_us['text']); ?></textarea>
                <button type="submit" class="save">Update</button>
            </form>
            <form method="post" action="">
                <input type="hidden" name="action" value="delete_about_us">
                <button type="submit" class="delete">Delete</button>
            </form>
        <?php else: ?>
            <h2>Add About Us Text</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="add_about_us">
                <textarea name="text" placeholder="Enter about us text"></textarea>
                <button type="submit" class="save">Save</button>
            </form>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
    </div>
</body>
</html>

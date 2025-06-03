<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Database connection
$host = 'localhost';
$db = 'school';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload'])) {
        // Handle upload
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO laboratories (name, description, image_url, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $description, $target_file]);
            // Redirect to avoid resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p class='error'>Sorry, there was an error uploading your file.</p>";
        }
    } elseif (isset($_POST['update'])) {
        // Handle update
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        if ($image) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $stmt = $conn->prepare("UPDATE laboratories SET name = ?, description = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$name, $description, $target_file, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE laboratories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $id]);
        }
        // Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['delete'])) {
        // Handle delete
        $id = $_POST['id'];
        
        $stmt = $conn->prepare("DELETE FROM laboratories WHERE id = ?");
        $stmt->execute([$id]);
        // Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch laboratories for update and delete options
$stmt = $conn->prepare("SELECT * FROM laboratories");
$stmt->execute();
$labs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Laboratories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], textarea, input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #5cb85c;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            background-color: #e0ffe0;
            color: #333;
        }
        .error {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            background-color: #ffe0e0;
            color: #d9534f;
        }
        .laboratory-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .laboratory-item img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
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
        <h1>Upload New Laboratory</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="name">Laboratory Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            
            <button type="submit" name="upload">Upload</button>
        </form>

        <h2>Update or Delete Laboratories</h2>
        <?php foreach ($labs as $lab): ?>
        <div class="laboratory-item">
            <h3><?php echo htmlspecialchars($lab['name']); ?></h3>
            <p><?php echo htmlspecialchars($lab['description']); ?></p>
            <?php if ($lab['image_url']): ?>
                <img src="<?php echo htmlspecialchars($lab['image_url']); ?>" alt="Laboratory Image">
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $lab['id']; ?>">
                
                <label for="name-<?php echo $lab['id']; ?>">New Laboratory Name</label>
                <input type="text" id="name-<?php echo $lab['id']; ?>" name="name" value="<?php echo htmlspecialchars($lab['name']); ?>" required>
                
                <label for="description-<?php echo $lab['id']; ?>">New Description</label>
                <textarea id="description-<?php echo $lab['id']; ?>" name="description" rows="4" required><?php echo htmlspecialchars($lab['description']); ?></textarea>
                
                <label for="image-<?php echo $lab['id']; ?>">New Image (Optional)</label>
                <input type="file" id="image-<?php echo $lab['id']; ?>" name="image" accept="image/*">
                
                <button type="submit" name="update">Update</button>
                <button type="submit" name="delete">Delete</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="admin-dashboard-link-container">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; 

$class_level = '';
$subject = '';
$teacher_name = '';
$details = '';
$email = '';

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $mysqli->prepare("SELECT * FROM levles WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $level = $result->fetch_assoc();
        $class_level = $level['class_level'];
        $subject = $level['subject'];
        $teacher_name = $level['teacher_name'];
        $details = $level['details'];
        $email = $level['email'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_level'])) {
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $teacher_name = $_POST['teacher_name'];
    $details = $_POST['details'];
    $email = $_POST['email'];

    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
    }

    $stmt = $mysqli->prepare("INSERT INTO levles (class_level, subject, teacher_name, details, email, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $class_level, $subject, $teacher_name, $details, $email, $photo);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_level'])) {
    $id = $_POST['id'];
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $teacher_name = $_POST['teacher_name'];
    $details = $_POST['details'];
    $email = $_POST['email'];

    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
    }

    if ($photo) {
        $stmt = $mysqli->prepare("UPDATE levles SET class_level=?, subject=?, teacher_name=?, details=?, email=?, photo=? WHERE id=?");
        $stmt->bind_param("ssssssi", $class_level, $subject, $teacher_name, $details, $email, $photo, $id);
    } else {
        $stmt = $mysqli->prepare("UPDATE levles SET class_level=?, subject=?, teacher_name=?, details=?, email=? WHERE id=?");
        $stmt->bind_param("sssssi", $class_level, $subject, $teacher_name, $details, $email, $id);
    }

    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_level'])) {
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("DELETE FROM levles WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Levels</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h3 {
            margin-bottom: 15px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], textarea, select, input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        button {
            padding: 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
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
<div class="admin-dashboard-link-container">
        <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
    </div>
    <div class="container">
        <h2>Manage Levels</h2>

        <!-- Add Level Form -->
        <div class="form-container">
            <h3>Add Level</h3>
            <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
                <label for="class_level">Class Level:</label>
                <input type="text" name="class_level" required>

                <label for="subject">Subject:</label>
                <input type="text" name="subject" required>

                <label for="teacher_name">Teacher Name:</label>
                <input type="text" name="teacher_name" required>

                <label for="details">Details:</label>
                <textarea name="details" required></textarea>

                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="photo">Upload Photo:</label>
                <input type="file" name="photo" accept="image/*">

                <button type="submit" name="add_level">Add Level</button>
            </form>
        </div>

        <!-- Update Level Form -->
        <div class="form-container">
            <h3>Update Level</h3>
            <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
                <label for="id">Select Level:</label>
                <select name="id" onchange="location = this.value;" required>
                    <option value="">Select Level</option>
                    <?php
                    $result = $mysqli->query("SELECT * FROM levles");
                    while ($level = $result->fetch_assoc()) {
                        echo "<option value='level.php?edit_id=" . $level['id'] . "'>" . $level['class_level'] . " - " . $level['subject'] . "</option>";
                    }
                    ?>
                </select>

                <label for="class_level">Class Level:</label>
                <input type="text" name="class_level" value="<?= $class_level ?>" required>

                <label for="subject">Subject:</label>
                <input type="text" name="subject" value="<?= $subject ?>" required>

                <label for="teacher_name">Teacher Name:</label>
                <input type="text" name="teacher_name" value="<?= $teacher_name ?>" required>

                <label for="details">Details:</label>
                <textarea name="details" required><?= $details ?></textarea>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $email ?>" required>

                <label for="photo">Upload Photo (optional):</label>
                <input type="file" name="photo" accept="image/*">

                <button type="submit" name="update_level">Update Level</button>
            </form>
        </div>

        <!-- Delete Level Form -->
        <div class="form-container">
            <h3>Delete Level</h3>
            <form method="POST" action="admin_dashboard.php">
                <label for="id">Select Level to Delete:</label>
                <select name="id" required>
                    <?php
                    $result = $mysqli->query("SELECT * FROM levles");
                    while ($level = $result->fetch_assoc()) {
                        echo "<option value='" . $level['id'] . "'>" . $level['class_level'] . " - " . $level['subject'] . "</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="delete_level">Delete Level</button>
            </form>
        </div>
    </div>
</body>
</html>

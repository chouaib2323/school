<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle file uploads
function uploadFiles($files) {
    $uploaded_files = [];
    $upload_dir = 'uploads/';

    foreach ($files['name'] as $key => $name) {
        $tmp_name = $files['tmp_name'][$key];
        $path = $upload_dir . basename($name);

        if (move_uploaded_file($tmp_name, $path)) {
            $uploaded_files[] = $path;
        }
    }

    return $uploaded_files;
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'add_club') {
        $name = $_POST['name'];
        $activity = $_POST['activity'];
        $photos = isset($_FILES['photos']) ? uploadFiles($_FILES['photos']) : [];

        $sql = "INSERT INTO clubs (name, details) VALUES ('$name', '$activity')";
        if ($conn->query($sql) === TRUE) {
            $club_id = $conn->insert_id;
            foreach ($photos as $photo) {
                $conn->query("INSERT INTO club_photos (club_id, photo) VALUES ('$club_id', '$photo')");
            }
        }
    } elseif ($action == 'update_club') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $activity = $_POST['activity'];
        $photos = isset($_FILES['photos']) ? uploadFiles($_FILES['photos']) : [];

        $sql = "UPDATE clubs SET name='$name', details='$activity' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            foreach ($photos as $photo) {
                $conn->query("INSERT INTO club_photos (club_id, photo) VALUES ('$id', '$photo')");
            }
        }
    } elseif ($action == 'delete_club') {
        $id = $_POST['id'];
        $sql = "DELETE FROM clubs WHERE id='$id'";
        $conn->query($sql);
    }
}

// Fetch clubs and their photos
$clubs = $conn->query("SELECT * FROM clubs");
$club_photos = $conn->query("SELECT * FROM club_photos");
$photos_by_club = [];
while ($photo = $club_photos->fetch_assoc()) {
    $photos_by_club[$photo['club_id']][] = $photo['photo'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Clubs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1, h2 {
            text-align: center;
        }
        form div {
            margin-bottom: 15px;
        }
        form div label {
            display: inline-block;
            width: 100px;
        }
        form div input, form div textarea, form div select {
            width: calc(100% - 110px);
            padding: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons button {
            padding: 5px 10px;
            cursor: pointer;
        }
        #updateForm {
            display: none;
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .admin-dashboard-link {
            display: inline-block;
            padding: 12px 25px;
            color: #fff;
            background-color: #1d4ed8;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
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
        <h1>Manage Clubs</h1>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_club">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="activity">Activity:</label>
                <textarea id="activity" name="activity" required></textarea>
            </div>
            <div>
                <label for="photos">Photos:</label>
                <input type="file" id="photos" name="photos[]" multiple>
            </div>
            <button type="submit">Add Club</button>
        </form>
        
        <h2>Club List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Activity</th>
                    <th>Photos</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $clubs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['details']; ?></td>
                        <td>
                            <?php if (isset($photos_by_club[$row['id']])): ?>
                                <?php foreach ($photos_by_club[$row['id']] as $photo): ?>
                                    <img src="<?= $photo; ?>" alt="Club Photo" style="width: 50px; height: 50px; margin-right: 5px;">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_club">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit">Delete</button>
                                </form>
                                <button onclick="openUpdateForm(<?= $row['id']; ?>, '<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>', '<?= htmlspecialchars($row['details'], ENT_QUOTES); ?>')">Update</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="updateForm">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_club">
                <input type="hidden" id="update_id" name="id">
                <div>
                    <label for="update_name">Name:</label>
                    <input type="text" id="update_name" name="name" required>
                </div>
                <div>
                    <label for="update_activity">Activity:</label>
                    <textarea id="update_activity" name="activity" required></textarea>
                </div>
                <div>
                    <label for="update_photos">Photos:</label>
                    <input type="file" id="update_photos" name="photos[]" multiple>
                </div>
                <button type="submit">Update Club</button>
                <button type="button" onclick="closeUpdateForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openUpdateForm(id, name, activity) {
            document.getElementById('updateForm').style.display = 'block';
            document.getElementById('update_id').value = id;
            document.getElementById('update_name').value = name;
            document.getElementById('update_activity').value = activity;
        }
        
        function closeUpdateForm() {
            document.getElementById('updateForm').style.display = 'none';
        }
    </script>
      <div class="admin-dashboard-link-container">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
</body>
</html>

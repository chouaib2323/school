<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Database connection
$mysqli = new mysqli("localhost", "root", "", "school");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle adding announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $subject = $_POST['subject'];

    // Insert announcement information
    $stmt = $mysqli->prepare("INSERT INTO anouncment (title, subject) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $subject);
    $stmt->execute();
    $announcement_id = $stmt->insert_id;
    $stmt->close();

    // Insert announcement links
    if (!empty($_POST['links'])) {
        foreach ($_POST['links'] as $link) {
            $link_title = $link['title'];
            $link_url = $link['url'];
            $stmt = $mysqli->prepare("INSERT INTO anouncement_links (anouncement_id, link_title, link_url) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $announcement_id, $link_title, $link_url);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Handle multiple photo uploads
    foreach ($_FILES['photos']['name'] as $key => $photo) {
        $target = "uploads/" . basename($photo);
        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target)) {
            $stmt = $mysqli->prepare("INSERT INTO anouncement_photos (anouncement_id, photo) VALUES (?, ?)");
            $stmt->bind_param("is", $announcement_id, $photo);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Handle updating announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_announcement'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $subject = $_POST['subject'];

    // Update announcement information
    $stmt = $mysqli->prepare("UPDATE anouncment SET title=?, subject=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $subject, $id);
    $stmt->execute();
    $stmt->close();

    // Delete existing links
    $stmt = $mysqli->prepare("DELETE FROM anouncement_links WHERE anouncement_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Insert new links
    if (!empty($_POST['links'])) {
        foreach ($_POST['links'] as $link) {
            $link_title = $link['title'];
            $link_url = $link['url'];
            $stmt = $mysqli->prepare("INSERT INTO anouncement_links (anouncement_id, link_title, link_url) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id, $link_title, $link_url);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Handle new photo uploads
    foreach ($_FILES['photos']['name'] as $key => $photo) {
        $target = "uploads/" . basename($photo);
        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target)) {
            $stmt = $mysqli->prepare("INSERT INTO anouncement_photos (anouncement_id, photo) VALUES (?, ?)");
            $stmt->bind_param("is", $id, $photo);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Handle deleting announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_announcement'])) {
    $id = $_POST['id'];

   

    // Delete related links and photos
    $stmt = $mysqli->prepare("DELETE FROM anouncement_links WHERE anouncement_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM anouncement_photos WHERE anouncement_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
     // Delete announcement
    $stmt = $mysqli->prepare("DELETE FROM anouncment WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .operation {
            margin-bottom: 20px;
        }
        form {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
        h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            height: 100px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .link {
            margin-bottom: 10px;
        }
        /* Menu styles */
        .menu {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }
        .menu button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .menu button.active {
            background-color: #0056b3;
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

    <!-- Menu -->
    <div class="menu">
        <button onclick="showSection('addAnnouncement')" class="active">Add Announcement</button>
        <button onclick="showSection('updateAnnouncement')">Update Announcement</button>
        <button onclick="showSection('deleteAnnouncement')">Delete Announcement</button>
    </div>

    <!-- Add Announcement Section -->
    <div id="addAnnouncement" class="operation">
        <form method="POST" action="" enctype="multipart/form-data">
            <h2>Add Announcement</h2>
            <label for="title">Title:</label>
            <input type="text" name="title" required>
            <label for="subject">Subject:</label>
            <textarea name="subject" required></textarea>

            <!-- Links Section -->
            <div id="links-section">
                <h3>Links:</h3>
                <div class="link">
                    <label for="link_title">Link Title:</label>
                    <input type="text" name="links[0][title]" required>
                    <label for="link_url">Link URL:</label>
                    <input type="url" name="links[0][url]" required>
                </div>
            </div>
            <button type="button" onclick="addLink()">Add Another Link</button>

            <!-- Photos Section -->
            <label for="photos">Photos:</label>
            <input type="file" name="photos[]" multiple>

            <button type="submit" name="add_announcement">Add Announcement</button>
        </form>
    </div>

    <!-- Update Announcement Section -->
    <div id="updateAnnouncement" class="operation" style="display:none;">
        <?php
        $mysqli = new mysqli("localhost", "root", "", "school");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $result = $mysqli->query("SELECT * FROM anouncment");
        while ($announcement = $result->fetch_assoc()) {
            $announcement_id = $announcement['id'];
        ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <h2>Update Announcement</h2>
                <input type="hidden" name="id" value="<?php echo $announcement_id; ?>">
                <label for="title">Title:</label>
                <input type="text" name="title" value="<?php echo $announcement['title']; ?>" required>
                <label for="subject">Subject:</label>
                <textarea name="subject" required><?php echo $announcement['subject']; ?></textarea>

                <!-- Links Section -->
                <div id="links-section-<?php echo $announcement_id; ?>">
                    <h3>Links:</h3>
                    <?php
                    $links_result = $mysqli->query("SELECT * FROM anouncement_links WHERE anouncement_id=$announcement_id");
                    $link_index = 0;
                    while ($link = $links_result->fetch_assoc()) {
                    ?>
                        <div class="link">
                            <label for="link_title">Link Title:</label>
                            <input type="text" name="links[<?php echo $link_index; ?>][title]" value="<?php echo $link['link_title']; ?>" required>
                            <label for="link_url">Link URL:</label>
                            <input type="url" name="links[<?php echo $link_index; ?>][url]" value="<?php echo $link['link_url']; ?>" required>
                        </div>
                    <?php
                        $link_index++;
                    }
                    ?>
                </div>
                <button type="button" onclick="addLink(<?php echo $announcement_id; ?>)">Add Another Link</button>

                <!-- Photos Section -->
                <label for="photos">Photos:</label>
                <input type="file" name="photos[]" multiple>

                <button type="submit" name="update_announcement">Update Announcement</button>
            </form>
        <?php
        }
        $mysqli->close();
        ?>
    </div>

    <!-- Delete Announcement Section -->
    <div id="deleteAnnouncement" class="operation" style="display:none;">
        <?php
        $mysqli = new mysqli("localhost", "root", "", "school");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        $result = $mysqli->query("SELECT * FROM anouncment");
        while ($announcement = $result->fetch_assoc()) {
        ?>
            <form method="POST" action="">
                <h2>Delete Announcement</h2>
                <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                <p><?php echo $announcement['title']; ?></p>
                <button type="submit" name="delete_announcement">Delete</button>
            </form>
        <?php
        }
        $mysqli->close();
        ?>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.operation');
            sections.forEach(section => {
                section.style.display = section.id === sectionId ? 'block' : 'none';
            });

            const buttons = document.querySelectorAll('.menu button');
            buttons.forEach(button => {
                button.classList.toggle('active', button.textContent.toLowerCase().includes(sectionId));
            });
        }

        function addLink(announcementId = '') {
            const linksSection = announcementId ? document.getElementById(`links-section-${announcementId}`) : document.getElementById('links-section');
            const linkCount = linksSection.querySelectorAll('.link').length;
            const newLink = document.createElement('div');
            newLink.className = 'link';
            newLink.innerHTML = `
                <label for="link_title">Link Title:</label>
                <input type="text" name="${announcementId ? `links[${linkCount}][title]` : `links[${linkCount}][title]`}" required>
                <label for="link_url">Link URL:</label>
                <input type="url" name="${announcementId ? `links[${linkCount}][url]` : `links[${linkCount}][url]`}" required>
            `;
            linksSection.appendChild(newLink);
        }
    </script>

<div class="admin-dashboard-link-container">
            <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
        </div>
</body>
</html>

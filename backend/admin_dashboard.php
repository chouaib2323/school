<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_level'])) {
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $teacher_name = $_POST['teacher_name'];
    $details = $_POST['details'];
    $email = $_POST['email'];

    // Handle the photo upload
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

// Handle updating a level
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_level'])) {
    $id = $_POST['id'];
    $class_level = $_POST['class_level'];
    $subject = $_POST['subject'];
    $teacher_name = $_POST['teacher_name'];
    $details = $_POST['details'];
    $email = $_POST['email'];

    // Handle the photo update (optional)
    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = basename($_FILES['photo']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $photo;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
    }

    // Update query with the photo, if provided
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

// Handle deleting a level
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_level'])) {
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("DELETE FROM levles WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle adding video
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_video'])) {
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $video = $_POST['video'];

    // Handle file upload for the video file
    

    $stmt = $mysqli->prepare("INSERT INTO video (title, subject, video_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $subject, $video);
    
    if ($stmt->execute()) {
        echo "Video added successfully.";
        redirect($_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
function redirect($url) {
    header("Location: $url");
    exit();
}
// Handle adding information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_information'])) {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $twitter = $_POST['twitter'];
    $youtube = $_POST['youtube'];
    $school = $_POST['school'];

    $stmt = $mysqli->prepare("INSERT INTO informations (id,phone, email, facebook, instagram, twiter,youtube , school) VALUES (1,?, ?, ?, ?, ?,?,?)");
    $stmt->bind_param("sssssss", $phone, $email, $facebook, $instagram, $twitter,$youtube,$school);
    
    if ($stmt->execute()) {
        echo "Information added successfully.";
        redirect($_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
// Handle updating information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_information'])) {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $twitter = $_POST['twitter'];
    $youtube = $_POST['youtube'];
    $school = $_POST['school'];

    $stmt = $mysqli->prepare("UPDATE informations SET phone=?, email=?, facebook=?, instagram=?, twiter=?, youtube=?,school=? WHERE id=1"); // Assuming id=1 is your information row
    $stmt->bind_param("sssssss", $phone, $email, $facebook, $instagram, $twitter,$youtube,$school);
    
    if ($stmt->execute()) {
        echo "Information updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}





// Handle updating video
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_video'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $video = $_POST['video'];

    $stmt = $mysqli->prepare("UPDATE video SET title=?, subject=?, video_url=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $subject, $video, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle deleting video
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_video'])) {
    $id = $_POST['id'];

    $stmt = $mysqli->prepare("DELETE FROM video WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle deleting information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_information'])) {
    $stmt = $mysqli->prepare("DELETE FROM informations");
    $stmt->execute();
    $stmt->close();
}

// Check for existing video
$existingVideo = $mysqli->query("SELECT * FROM video")->fetch_assoc();

// Check for existing information
$existingInformation = $mysqli->query("SELECT * FROM informations")->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dropdown {
            background-color: black;
            border: 1px solid #ddd;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Operations</h2>
        <ul>
            <!-- Other dropdown items -->
            <li>
            <li>
                <a href="/school/anouncement.php" onclick="toggleDropdown('announcementDropdown')">Announcement</a>
            </li>
            <li>
                <a href="/school/post.php" onclick="toggleDropdown('postWithPhotoDropdown')">Post with Photo</a>
            </li>
           
            <li>
                <a href="#" onclick="toggleDropdown('videoDropdown')">Video</a>
                <ul id="videoDropdown" class="dropdown hidden">
                    <?php if (!$existingVideo): ?>
                        <li><a href="#" onclick="showForm('addVideo')">Add Video</a></li>
                    <?php else: ?>
                        <li><a href="#" onclick="showForm('updateVideo')">Update Video</a></li>
                        <li><a href="#" onclick="showForm('deleteVideo')">Delete Video</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="#" onclick="toggleDropdown('informationDropdown')">Information</a>
                <ul id="informationDropdown" class="dropdown hidden">
                    <?php if (!$existingInformation): ?>
                        <li><a href="#" onclick="showForm('addInformation')">Add Information</a></li>
                    <?php else: ?>
                        <li><a href="#" onclick="showForm('updateInformation')">Update Information</a></li>
                        <li><a href="#" onclick="showForm('deleteInformation')">Delete Information</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="/school/level.php" onclick="toggleDropdown('elearningDropdown')">level</a>
            </li>
            <li>
                <a href="/school/about_us.php" onclick="toggleDropdown('elearningDropdown')">about us</a>
            </li>
            <li>
                <a href="/school/messages.php" onclick="toggleDropdown('elearningDropdown')">client messages</a>
            </li>
            <li>
                <a href="/school/extend_operations.php" onclick="toggleDropdown('elearningDropdown')">E-Learning - library- direcors</a>
            </li>
            <li>
                <a href="/school/student.php" onclick="toggleDropdown('elearningDropdown')">student_reasearch</a>
            </li>
            <li>
                <a href="/school/club.php" onclick="toggleDropdown('elearningDropdown')">club section</a>
            </li>
            <li>
                <a href="/school/labo.php" onclick="toggleDropdown('elearningDropdown')">laboratories</a>
            </li>
            <li>
                <a href="/school/link.php" onclick="toggleDropdown('elearningDropdown')">reasearch link</a>
            </li>
            <li>
                <a href="/school/sports.php" onclick="toggleDropdown('elearningDropdown')">sports</a>
            </li>
            <li>
            <a href="/school/module_management.php" onclick="toggleDropdown('elearningDropdown')">modules</a>
              
            </li>
            <li>
            <a href="/school/logo.php" onclick="toggleDropdown('elearningDropdown')">logo</a>
              
            </li>
        </ul>
    </div>
    <div class="content">
        <!-- Forms for each operation -->


        <div id="addVideo" class="operation hidden">
            <!-- Add Video Form -->
            <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
                <h2>Add Video</h2>
                <label for="title">Title:</label>
                <input type="text" name="title" required>
                <label for="subject">Subject:</label>
                <textarea name="subject" required></textarea>
                <label for="video">Video link :</label>
                <input type="link" name="video" required><br/><br/>
              
                <button type="submit" name="add_video" <?php echo $existingVideo ? 'disabled' : ''; ?>>Add Video</button>
            </form>
        </div>
        <div id="updateVideo" class="operation hidden">
            <!-- Update Video Form -->
            <?php
            $result = $mysqli->query("SELECT * FROM video");
            while ($video = $result->fetch_assoc()) {
            ?>
                <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
                    <h2>Update Video</h2>
                    <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
                    <label for="title">Title:</label>
                    <input type="text" name="title" value="<?php echo $video['title']; ?>" required>
                    <label for="subject">Subject:</label>
                    <textarea name="subject" required><?php echo $video['subject']; ?></textarea>
                    <label for="video">Video links:</label>
                <input type="link" name="video" required>
                    <button type="submit" name="update_video">Update Video</button>
                </form>
            <?php } ?>
        </div>
        <div id="deleteVideo" class="operation hidden">
            <!-- Delete Video Form -->
            <?php
            $result = $mysqli->query("SELECT * FROM video");
            while ($video = $result->fetch_assoc()) {
            ?>
                <form method="POST" action="admin_dashboard.php">
                    <h2>Delete Video</h2>
                    <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
                    <p>Title: <?php echo $video['title']; ?></p>
                    <p>Subject: <?php echo $video['subject']; ?></p>
                    <button type="submit" name="delete_video">Delete Video</button>
                </form>
            <?php } ?>
        </div>
        <div id="addInformation" class="operation hidden">
            <!-- Add Information Form -->
            <form method="POST" action="admin_dashboard.php">
                <h2>Add Information</h2>
                <label for="phone">Phone:</label>
                <input type="text" name="phone" required>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="facebook">Facebook:</label>
                <input type="text" name="facebook">
                <label for="instagram">Instagram:</label>
                <input type="text" name="instagram">
                <label for="twitter">Twitter:</label>
                <input type="text" name="twitter">
                <label for="youtube">youtube:</label>
                <input type="text" name="youtube">
                <label for="school">school name:</label>
                <input type="text" name="school">
                <button type="submit" name="add_information" <?php echo $existingInformation ? 'disabled' : ''; ?>>Add Information</button>
            </form>
        </div>
        <div id="updateInformation" class="operation hidden">
            <!-- Update Information Form -->
            <?php
            $result = $mysqli->query("SELECT * FROM informations");
            while ($info = $result->fetch_assoc()) {
            ?>
                <form method="POST" action="admin_dashboard.php">
                    <h2>Update Information</h2>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" value="<?php echo $info['phone']; ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $info['email']; ?>" required>
                    <label for="facebook">Facebook:</label>
                    <input type="text" name="facebook" value="<?php echo $info['facebook']; ?>">
                    <label for="instagram">Instagram:</label>
                    <input type="text" name="instagram" value="<?php echo $info['instagram']; ?>">
                    <label for="twitter">Twitter:</label>
                    <input type="text" name="twitter" value="<?php echo $info['twiter']; ?>">
                    <label for="youtube">youtube:</label>
                <input type="text" name="youtube" value="<?php echo $info['youtube']; ?>">
                <label for="school">school name:</label>
                <input type="text" name="school" value="<?php echo $info['school']; ?>">
                    <button type="submit" name="update_information">Update Information</button>
                </form>
            <?php } ?>
        </div>
        <div id="deleteInformation" class="operation hidden">
            <!-- Delete Information Form -->
            <form method="POST" action="admin_dashboard.php">
                <h2>Delete Information</h2>
                <button type="submit" name="delete_information">Delete Information</button>
            </form>
        </div>
    
    </div>
    <script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
    }

    function showForm(formId) {
        // Hide all forms
        const forms = document.querySelectorAll('.operation');
        forms.forEach(form => form.classList.add('hidden'));

        // Show the selected form
        const form = document.getElementById(formId);
        form.classList.remove('hidden');
    }
</script>

</body>
</html>


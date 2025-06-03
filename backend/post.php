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

// Function to handle redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Handle adding a post
if (isset($_POST['add_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $subject = $conn->real_escape_string($_POST['subject']);

    $stmt = $conn->prepare("INSERT INTO posts_with_photo (title, subject) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $subject);
    
    if ($stmt->execute()) {
        $post_id = $stmt->insert_id;
        $stmt->close();

        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['name'] as $key => $name) {
                $tmp_name = $_FILES['photos']['tmp_name'][$key];
                $photo_name = time() . "_" . basename($name);
                $upload_path = "uploads/$photo_name";

                if (move_uploaded_file($tmp_name, $upload_path)) {
                    $stmt = $conn->prepare("INSERT INTO post_photos (post_id, photo) VALUES (?, ?)");
                    $stmt->bind_param("is", $post_id, $photo_name);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Failed to upload file $name.";
                }
            }
        }
        redirect($_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle updating a post
if (isset($_POST['update_post'])) {
    $post_id = (int)$_POST['post_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $subject = $conn->real_escape_string($_POST['subject']);

    $stmt = $conn->prepare("UPDATE posts_with_photo SET title = ?, subject = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $subject, $post_id);

    if ($stmt->execute()) {
        $stmt->close();

        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['name'] as $key => $name) {
                $tmp_name = $_FILES['photos']['tmp_name'][$key];
                $photo_name = time() . "_" . basename($name);
                $upload_path = "uploads/$photo_name";

                if (move_uploaded_file($tmp_name, $upload_path)) {
                    $stmt = $conn->prepare("INSERT INTO post_photos (post_id, photo) VALUES (?, ?)");
                    $stmt->bind_param("is", $post_id, $photo_name);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Failed to upload file $name.";
                }
            }
        }
        redirect($_SERVER['PHP_SELF']);
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle deleting a post
if (isset($_GET['delete_post'])) {
    $post_id = (int)$_GET['delete_post'];

    // First, delete all photos associated with the post
    $stmt = $conn->prepare("SELECT photo FROM post_photos WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result_photos = $stmt->get_result();

    while ($row = $result_photos->fetch_assoc()) {
        $file_path = "uploads/" . $row['photo'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    $stmt->close();

    // Delete photos from the database
    $stmt = $conn->prepare("DELETE FROM post_photos WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();

    // Now delete the post itself
    $stmt = $conn->prepare("DELETE FROM posts_with_photo WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();

    redirect($_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts with Photos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .post-list {
            list-style-type: none;
            padding: 0;
        }
        .post-list li {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .post-list h2 {
            color: #007bff;
            margin: 0 0 10px;
        }
        .post-list p {
            margin: 0 0 10px;
        }
        .post-list a {
            color: #dc3545;
            text-decoration: none;
        }
        .post-list a:hover {
            text-decoration: underline;
        }
        .form-container {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .form-container h2 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }
        .form-container input[type="text"], .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .form-container input[type="file"] {
            margin-top: 10px;
        }
        .form-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #218838;
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
        <!-- Add Post Form -->
        <div class="form-container" id="addPostForm">
            <h2>Add New Post</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="add_post" value="1">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
                <label for="subject">Subject:</label>
                <textarea name="subject" id="subject" required></textarea>
                <label for="photos">Photos:</label>
                <input type="file" name="photos[]" id="photos" multiple>
                <input type="submit" value="Add Post">
            </form>
        </div>

        <h1>Posts</h1>
        <a href="#addPostForm">Add New Post</a>
        <ul class="post-list" id="postList">
            <?php
            $sql = "SELECT * FROM posts_with_photo";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li id='post-".htmlspecialchars($row['id'])."'>";
                    echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                    echo "<p>" . htmlspecialchars($row['subject']) . "</p>";
                    echo "<a href='?edit_post=" . htmlspecialchars($row['id']) . "' class='edit-link'>Edit</a> | ";
                    echo "<a href='#' class='delete-link' data-post-id='" . htmlspecialchars($row['id']) . "'>Delete</a>";
                    echo "</li>";
                }
            } else {
                echo "No posts found.";
            }
            ?>
        </ul>

        <!-- Edit Post Form -->
        <?php
        if (isset($_GET['edit_post'])) {
            $post_id = (int)$_GET['edit_post'];
            $stmt = $conn->prepare("SELECT * FROM posts_with_photo WHERE id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $post = $result->fetch_assoc();
            $stmt->close();

            if ($post) {
                ?>
                <div class="form-container" id="editPostForm">
                    <h2>Edit Post</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_post" value="1">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                        <label for="subject">Subject:</label>
                        <textarea name="subject" id="subject" required><?php echo htmlspecialchars($post['subject']); ?></textarea>
                        <label for="photos">Photos:</label>
                        <input type="file" name="photos[]" id="photos" multiple>
                        <input type="submit" value="Update Post">
                    </form>
                </div>
                <?php
            } else {
                echo "Post not found.";
            }
        }
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const postId = this.getAttribute('data-post-id');
                    if (confirm('Are you sure you want to delete this post?')) {
                        window.location.href = '?delete_post=' + postId;
                    }
                });
            });
        });
    </script>
    <div class="admin-dashboard-link-container">
        <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'school';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_post'])) {
        $title = $_POST['title'];
        $subject = $_POST['subject'];

        // Insert post details into the posts table
        $stmt = $pdo->prepare("INSERT INTO posts (title, subject) VALUES (?, ?)");
        $stmt->execute([$title, $subject]);
        $post_id = $pdo->lastInsertId();

        // Handle file uploads
        if (isset($_FILES['images'])) {
            $total_files = count($_FILES['images']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                $file_name = $_FILES['images']['name'][$i];
                $file_tmp = $_FILES['images']['tmp_name'][$i];
                $file_parts = explode('.', $file_name);
                $file_ext = strtolower(end($file_parts));
                $new_name = uniqid() . '.' . $file_ext;
                $file_path = 'uploads/' . $new_name;

                // Save file to the uploads folder
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Insert file details into the photos table
                    $stmt = $pdo->prepare("INSERT INTO photos (post_id, file_name, file_type) VALUES (?, ?, ?)");
                    $stmt->execute([$post_id, $new_name, $file_ext]);
                }
            }
        }

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle update post
    if (isset($_POST['update_post'])) {
        $post_id = $_POST['post_id'];
        $title = $_POST['title'];
        $subject = $_POST['subject'];

        // Update post details in the posts table
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, subject = ? WHERE id = ?");
        $stmt->execute([$title, $subject, $post_id]);

        // Handle file uploads
        if (isset($_FILES['images'])) {
            $total_files = count($_FILES['images']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                $file_name = $_FILES['images']['name'][$i];
                $file_tmp = $_FILES['images']['tmp_name'][$i];
                $file_parts = explode('.', $file_name);
                $file_ext = strtolower(end($file_parts));
                $new_name = uniqid() . '.' . $file_ext;
                $file_path = 'uploads/' . $new_name;

                // Save file to the uploads folder
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Insert file details into the photos table
                    $stmt = $pdo->prepare("INSERT INTO photos (post_id, file_name, file_type) VALUES (?, ?, ?)");
                    $stmt->execute([$post_id, $new_name, $file_ext]);
                }
            }
        }

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Handle delete post
    if (isset($_POST['delete_post'])) {
        $post_id = $_POST['post_id'];

        // Delete post and associated photos
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch posts and their photos
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Section Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #444;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .form-group input[type="file"] {
            padding: 3px;
        }
        .form-group button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #4cae4c;
        }
        .post-list {
            margin-top: 30px;
        }
        .post-item {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .post-item h2 {
            margin-top: 0;
        }
        .post-item .photos img {
            max-width: 100px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .post-item form {
            display: inline-block;
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
        <h1>Manage Sport Updates</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <textarea id="subject" name="subject" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="images">Upload Images:</label>
                <input type="file" id="images" name="images[]" multiple>
            </div>
            <div class="form-group">
                <button type="submit" name="add_post">Submit</button>
            </div>
        </form>

        <div class="post-list">
            <h2>Existing Posts</h2>
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($post['subject'])); ?></p>
                    <div class="photos">
                        <?php
                        $photos = $pdo->prepare("SELECT * FROM photos WHERE post_id = ?");
                        $photos->execute([$post['id']]);
                        foreach ($photos->fetchAll(PDO::FETCH_ASSOC) as $photo) {
                            echo '<img src="uploads/' . htmlspecialchars($photo['file_name']) . '" alt="Photo">';
                        }
                        ?>
                    </div>
                    <form action="" method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" name="delete_post" style="background-color: #d9534f;">Delete</button>
                    </form>
                    <button onclick="showUpdateForm(<?php echo $post['id']; ?>)" style="background-color: #f0ad4e;">Update</button>

                    <form id="update-form-<?php echo $post['id']; ?>" action="" method="POST" enctype="multipart/form-data" style="display: none; margin-top: 20px;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <textarea name="subject" rows="5" required><?php echo htmlspecialchars($post['subject']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="images">Upload New Images:</label>
                            <input type="file" name="images[]" multiple>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="update_post">Update Post</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showUpdateForm(postId) {
            var form = document.getElementById('update-form-' + postId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>


</body>
</html>
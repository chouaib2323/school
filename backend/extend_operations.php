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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_elearning'])) {
        $level_id = $_POST['level_id'];
        $module = $_POST['module'];
        $course_title = $_POST['course_title'];
        $description = $_POST['description'];
        $pdf = $_FILES['pdf']['name'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["pdf"]["name"]);
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_file);

        $sql = "INSERT INTO elearning (level_id, module, course_title, description, pdf) VALUES ('$level_id', '$module', '$course_title', '$description', '$pdf')";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['update_elearning'])) {
        $id = $_POST['id'];
        $level_id = $_POST['level_id'];
        $module = $_POST['module'];
        $course_title = $_POST['course_title'];
        $description = $_POST['description'];
        $pdf = $_FILES['pdf']['name'];

        if (!empty($pdf)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["pdf"]["name"]);
            move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_file);
            $sql = "UPDATE elearning SET level_id='$level_id', module='$module', course_title='$course_title', description='$description', pdf='$pdf' WHERE id='$id'";
        } else {
            $sql = "UPDATE elearning SET level_id='$level_id', module='$module', course_title='$course_title', description='$description' WHERE id='$id'";
        }

        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['delete_elearning'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM elearning WHERE id='$id'";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['add_library'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $pdf = $_FILES['pdf']['name'];
        $image = $_FILES['image']['name'];

        $target_dir = "uploads/";
        $target_pdf = $target_dir . basename($_FILES["pdf"]["name"]);
        $target_image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_pdf);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_image);

        $sql = "INSERT INTO library (title, author, pdf, image) VALUES ('$title', '$author', '$pdf', '$image')";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['update_library'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $pdf = $_FILES['pdf']['name'];
        $image = $_FILES['image']['name'];

        if (!empty($pdf) && !empty($image)) {
            $target_dir = "uploads/";
            $target_pdf = $target_dir . basename($_FILES["pdf"]["name"]);
            $target_image = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_pdf);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_image);
            $sql = "UPDATE library SET title='$title', author='$author', pdf='$pdf', image='$image' WHERE id='$id'";
        } elseif (!empty($pdf)) {
            $target_dir = "uploads/";
            $target_pdf = $target_dir . basename($_FILES["pdf"]["name"]);
            move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_pdf);
            $sql = "UPDATE library SET title='$title', author='$author', pdf='$pdf' WHERE id='$id'";
        } elseif (!empty($image)) {
            $target_dir = "uploads/";
            $target_image = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_image);
            $sql = "UPDATE library SET title='$title', author='$author', image='$image' WHERE id='$id'";
        } else {
            $sql = "UPDATE library SET title='$title', author='$author' WHERE id='$id'";
        }

        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['delete_library'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM library WHERE id='$id'";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['add_director'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];$modules = $_POST['modules'];
        $biography = $_POST['biography'];
        $image = $_FILES['image']['name'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $sql = "INSERT INTO directors (name, biography, image,email,modules) VALUES ('$name', '$biography', '$image','$email','$modules')";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['update_director'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];$modules = $_POST['modules'];
        $biography = $_POST['biography'];
        $image = $_FILES['image']['name'];

        if (!empty($image)) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $sql = "UPDATE directors SET name='$name', biography='$biography', image='$image' , email='$email', modules='$modules' WHERE id='$id'";
        } else {
            $sql = "UPDATE directors SET name='$name', biography='$biography' , email='$email', modules='$modules' WHERE id='$id'";
        }

        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    if (isset($_POST['delete_director'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM directors WHERE id='$id'";
        $conn->query($sql);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
$host = 'localhost';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
// Fetch current links
$stmt = $pdo->query("SELECT * FROM elearning_links WHERE id = 1 LIMIT 1");
$links = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link1 = $_POST['link_1'];
    $link2 = $_POST['link_2'];
    $link_name1 = $_POST['link_name1'];
    $link_name2 = $_POST['link_name2'];

    // Update or insert the links
    if ($links) {
        $stmt = $pdo->prepare("UPDATE elearning_links SET link_1 = ?, link_2 = ?, link_name1 = ?, link_name2 = ? WHERE id = 1");
    } else {
        $stmt = $pdo->prepare("INSERT INTO elearning_links (id, link_1, link_2, link_name1, link_name2) VALUES (1, ?, ?, ?, ?)");
    }

    if ($stmt->execute([$link1, $link2, $link_name1, $link_name2])) {
        echo "Links updated successfully!";
        // Refresh the links after update
        $links = $pdo->query("SELECT * FROM elearning_links WHERE id = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Failed to update links.";
    }
}



$elearnings = $conn->query("SELECT * FROM elearning");
$libraries = $conn->query("SELECT * FROM library");
$directors = $conn->query("SELECT * FROM directors");
$levels = $conn->query("SELECT * FROM levles");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .menu {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .menu button {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .menu button:hover {
            background-color: #0056b3;
        }
        .dropdown {
            display: none;
            margin-bottom: 20px;
        }
        .dropdown button {
            padding: 10px;
            cursor: pointer;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .dropdown button:hover {
            background-color: #218838;
        }
        form {
            display: none;
            margin-bottom: 20px;
        }
        form h2 {
            margin-top: 0;
        }
        form input, form select, form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="file"] {
            padding: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function showDropdown(category) {
            document.querySelectorAll('.dropdown').forEach(function(dropdown) {
                dropdown.style.display = 'none';
            });
            document.querySelector('#' + category + '-dropdown').style.display = 'block';
        }

        function showForm(formId) {
            document.querySelectorAll('form').forEach(function(form) {
                form.style.display = 'none';
            });
            document.querySelector('#' + formId).style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="menu">
            <button onclick="showDropdown('elearning')">E-Learning</button>
            <button onclick="showDropdown('library')">Library</button>
            <button onclick="showDropdown('directors')">Directors</button>
        </div>

        <div id="elearning-dropdown" class="dropdown">
            <button onclick="showForm('add-elearning-form')">Add E-Learning</button>
            <button onclick="showForm('update-elearning-form')">Update E-Learning</button>
            <button onclick="showForm('delete-elearning-form')">Delete E-Learning</button>
        </div>
        <div id="library-dropdown" class="dropdown">
            <button onclick="showForm('add-library-form')">Add Library</button>
            <button onclick="showForm('update-library-form')">Update Library</button>
            <button onclick="showForm('delete-library-form')">Delete Library</button>
        </div>
        <div id="directors-dropdown" class="dropdown">
            <button onclick="showForm('add-director-form')">Add Director</button>
            <button onclick="showForm('update-director-form')">Update Director</button>
            <button onclick="showForm('delete-director-form')">Delete Director</button>
        </div>

        <form id="add-elearning-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Add E-Learning</h2>
            <select name="level_id" required>
                <option value="">Select Level</option>
                <?php while ($row = $levels->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['class_level']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="module" placeholder="Module" required>
            <input type="text" name="course_title" placeholder="Course Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="file" name="pdf" accept=".pdf" required>
            <input type="submit" name="add_elearning" value="Add E-Learning">
        </form>

        <form id="update-elearning-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Update E-Learning</h2>
            <select name="id" required>
                <option value="">Select E-Learning</option>
                <?php while ($row = $elearnings->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['course_title']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="level_id" required>
                <option value="">Select Level</option>
                <?php $levels->data_seek(0); while ($row = $levels->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['class_level']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="module" placeholder="Module" required>
            <input type="text" name="course_title" placeholder="Course Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="file" name="pdf" accept=".pdf">
            <input type="submit" name="update_elearning" value="Update E-Learning">
        </form>

        <form id="delete-elearning-form" action="" method="POST">
            <h2>Delete E-Learning</h2>
            <select name="id" required>
                <option value="">Select E-Learning</option>
                <?php $elearnings->data_seek(0); while ($row = $elearnings->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['course_title']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="delete_elearning" value="Delete E-Learning">
        </form>
        <div class="menu">
            <button onclick="showForm('add-links-form')">add eLearning Links</button>
           
        </div>

        
    <form id='add-links-form' method="POST">
    <h1>Manage eLearning Links</h1>
        <label for="link_1">Link 1:</label>
        <input type="text" id="link_1" name="link_1" value="<?php echo htmlspecialchars($links['link_1'] ?? ''); ?>" required><br>

        <label for="link_name1">Link Name 1:</label>
        <input type="text" id="link_name1" name="link_name1" value="<?php echo htmlspecialchars($links['link_name1'] ?? ''); ?>" required><br>

        <label for="link_2">Link 2:</label>
        <input type="text" id="link_2" name="link_2" value="<?php echo htmlspecialchars($links['link_2'] ?? ''); ?>" required><br>

        <label for="link_name2">Link Name 2:</label>
        <input type="text" id="link_name2" name="link_name2" value="<?php echo htmlspecialchars($links['link_name2'] ?? ''); ?>" required><br>

        <button type="submit">Update Links</button>
    </form>

        <form id="add-library-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Add Library</h2>
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <h3>pfd file : </h3>
            <input type="file" name="pdf" accept=".pdf" required > 
            <h3>image file file : </h3>
            <input id='bookimg' type="file" name="image" accept=".jpg,.jpeg,.png" required>
            <input type="submit" name="add_library" value="Add Library">
        </form>

        <form id="update-library-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Update Library</h2>
            <select name="id" required>
                <option value="">Select Library</option>
                <?php while ($row = $libraries->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="author" placeholder="Author" required>
           <p>book file: </p>
            <input type="file" name="pdf" accept=".pdf">
            <p>book image: </p>
            <input type="file" name="image" accept=".jpg,.jpeg,.png">
            <input type="submit" name="update_library" value="Update Library">
        </form>

        <form id="delete-library-form" action="" method="POST">
            <h2>Delete Library</h2>
            <select name="id" required>
                <option value="">Select Library</option>
                <?php $libraries->data_seek(0); while ($row = $libraries->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="delete_library" value="Delete Library">
        </form>

        <form id="add-director-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Add Director</h2>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="email" placeholder="email" required>
            <input type="text" name="modules" placeholder="modules" required>
            <textarea name="biography" placeholder="Biography" required></textarea>
            <input type="file" name="image" accept=".jpg,.jpeg,.png" required>
            <input type="submit" name="add_director" value="Add Director">
        </form>

        <form id="update-director-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Update Director</h2>
            <select name="id" required>
                <option value="">Select Director</option>
                <?php while ($row = $directors->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="email" placeholder="email" required>
            <input type="text" name="modules" placeholder="modules" required>
            <textarea name="biography" placeholder="Biography" required></textarea>
            <input type="file" name="image" accept=".jpg,.jpeg,.png">
            <input type="submit" name="update_director" value="Update Director">
        </form>

        <form id="delete-director-form" action="" method="POST">
            <h2>Delete Director</h2>
            <select name="id" required>
                <option value="">Select Director</option>
                <?php $directors->data_seek(0); while ($row = $directors->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="delete_director" value="Delete Director">
        </form>
        <a href='/school/admin_dashboard.php' style="
    color: #1d4ed8; /* Blue color */
    text-decoration: none; /* Remove underline */
    font-weight: bold; /* Bold text */
    font-size: 16px; /* Font size */
    padding: 8px 12px; /* Padding for spacing */
    border-radius: 4px; /* Rounded corners */
    transition: color 0.3s, background-color 0.3s; /* Smooth transitions */
">Admin Dashboard</a>


    </div>
</body>
</html>

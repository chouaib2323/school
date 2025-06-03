<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$levels = [];
$levelQuery = "SELECT * FROM levles"; // Corrected table name
$levelResult = $conn->query($levelQuery);
while ($row = $levelResult->fetch_assoc()) {
    $levels[] = $row;
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'add_student') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $level_id = $_POST['level_id'];
        $research_title = $_POST['research_title'];
        $research_details = $_POST['research_details'];
        
        $sql = "INSERT INTO students (first_name, last_name, level_id, research_title, research_details) VALUES ('$first_name', '$last_name', '$level_id', '$research_title', '$research_details')";
        $conn->query($sql);
        
        $student_id = $conn->insert_id;
        
        if (!empty($_FILES['research_file']['name'])) {
            $file_name = $_FILES['research_file']['name'];
            $file_tmp = $_FILES['research_file']['tmp_name'];
            $file_path = "uploads/" . $file_name;
            move_uploaded_file($file_tmp, $file_path);
            
            $sql = "INSERT INTO research_files (student_id, file_name, file_path) VALUES ('$student_id', '$file_name', '$file_path')";
            $conn->query($sql);
        }
    } elseif ($action == 'update_student') {
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $level_id = $_POST['level_id'];
        $research_title = $_POST['research_title'];
        $research_details = $_POST['research_details'];
        
        $sql = "UPDATE students SET first_name='$first_name', last_name='$last_name', level_id='$level_id', research_title='$research_title', research_details='$research_details' WHERE id='$id'";
        $conn->query($sql);
        
        if (!empty($_FILES['research_file']['name'])) {
            $file_name = $_FILES['research_file']['name'];
            $file_tmp = $_FILES['research_file']['tmp_name'];
            $file_path = "uploads/" . $file_name;
            move_uploaded_file($file_tmp, $file_path);
            
            $sql = "INSERT INTO research_files (student_id, file_name, file_path) VALUES ('$id', '$file_name', '$file_path')";
            $conn->query($sql);
        }
    } elseif ($action == 'delete_student') {
        $id = $_POST['id'];
        $sql = "DELETE FROM students WHERE id='$id'";
        $conn->query($sql);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch students
$students = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
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
            margin-left: 10px;
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
        <h1>Manage Students</h1>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_student">
            <div>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name">
            </div>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name">
            </div>
            <div>
                <label for="level_id">Level:</label>
                <select id="level_id" name="level_id">
                    <?php foreach ($levels as $level): ?>
                        <option value="<?= $level['id'] ?>"><?= $level['class_level'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="research_title">Research Title:</label>
                <input type="text" id="research_title" name="research_title">
            </div>
            <div>
                <label for="research_details">Research Details:</label>
                <textarea id="research_details" name="research_details"></textarea>
            </div>
            <div>
                <label for="research_file">Research File:</label>
                <input type="file" id="research_file" name="research_file">
            </div>
            <button type="submit">Add Student</button>
        </form>
        
        <h2>Student List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Level</th>
                    <th>Research Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <tr data-id="<?= $row['id']; ?>"
                        data-first_name="<?= $row['first_name']; ?>"
                        data-last_name="<?= $row['last_name']; ?>"
                        data-level_id="<?= $row['level_id']; ?>"
                        data-research_title="<?= $row['research_title']; ?>"
                        data-research_details="<?= $row['research_details']; ?>">
                        <td><?= $row['id']; ?></td>
                        <td class="first_name"><?= $row['first_name']; ?></td>
                        <td class="last_name"><?= $row['last_name']; ?></td>
                        <td class="level_id"><?= $row['level_id']; ?></td>
                        <td class="research_title"><?= $row['research_title']; ?></td>
                        <td class="research_details"><?= $row['research_details']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="editStudent(<?= $row['id']; ?>)">Edit</button>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_student">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div id="updateForm">
            <h2>Update Student</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_student">
                <input type="hidden" id="update_id" name="id">
                <div>
                    <label for="update_first_name">First Name:</label>
                    <input type="text" id="update_first_name" name="first_name">
                </div>
                <div>
                    <label for="update_last_name">Last Name:</label>
                    <input type="text" id="update_last_name" name="last_name">
                </div>
                <div>
                    <label for="update_level_id">Level:</label>
                    <select id="update_level_id" name="level_id">
                        <?php foreach ($levels as $level): ?>
                            <option value="<?= $level['id'] ?>"><?= $level['class_level'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="update_research_title">Research Title:</label>
                    <input type="text" id="update_research_title" name="research_title">
                </div>
                <div>
                    <label for="update_research_details">Research Details:</label>
                    <textarea id="update_research_details" name="research_details"></textarea>
                </div>
                <div>
                    <label for="update_research_file">Research File:</label>
                    <input type="file" id="update_research_file" name="research_file">
                </div>
                <button type="submit">Update Student</button>
            </form>
        </div>
        
        <div class="admin-dashboard-link-container">
            <a href="admin_dashboard.php" class="admin-dashboard-link">Back to Admin Dashboard</a>
        </div>
    </div>

    <script>
        function editStudent(id) {
            // Get the row with the student data
            const row = document.querySelector(`tr[data-id="${id}"]`);
            
            // Fetch student data from the row's data attributes
            const firstName = row.getAttribute('data-first_name');
            const lastName = row.getAttribute('data-last_name');
            const levelId = row.getAttribute('data-level_id');
            const researchTitle = row.getAttribute('data-research_title');
            const researchDetails = row.getAttribute('data-research_details');
            
            // Populate update form with student data
            document.getElementById('update_id').value = id;
            document.getElementById('update_first_name').value = firstName;
            document.getElementById('update_last_name').value = lastName;
            document.getElementById('update_level_id').value = levelId;
            document.getElementById('update_research_title').value = researchTitle;
            document.getElementById('update_research_details').value = researchDetails;
            
            // Show the update form
            document.getElementById('updateForm').style.display = 'block';
        }
    </script>
</body>
</html>

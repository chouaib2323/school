<?php
// Database connection
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "school";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if we're editing an existing module
$module_to_edit = null;
if (isset($_GET['edit'])) {
    $module_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM module_info WHERE id = $module_id");
    $module_to_edit = $result->fetch_assoc();
}

// Handle form submission to add, update, or delete a module and its themes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Delete the module and its themes
        $module_id = $_POST['module_id'];
        $conn->query("DELETE FROM module_info WHERE id = $module_id");
        $conn->query("DELETE FROM themes WHERE module_name = (SELECT name FROM module_info WHERE id = $module_id)");
    } else {
        $module_name = $_POST['module_name'];
        $introduction = $_POST['introduction'];
        $module_id = $_POST['module_id'];

        if ($module_id) {
            // Update the existing module
            $update = $conn->prepare("UPDATE module_info SET name = ?, introduction = ? WHERE id = ?");
            $update->bind_param("ssi", $module_name, $introduction, $module_id);
            $update->execute();
        } else {
            // Insert a new module
            $insert = $conn->prepare("INSERT INTO module_info (name, introduction) VALUES (?, ?)");
            $insert->bind_param("ss", $module_name, $introduction);
            $insert->execute();
            $module_id = $conn->insert_id; // Get the new module ID
        }

        // Manage themes (update or insert)
        for ($i = 0; $i < count($_POST['year']); $i++) {
            $year = $_POST['year'][$i];
            $details = $_POST['details'][$i];

            $theme_check = $conn->prepare("SELECT id FROM themes WHERE module_name = ? AND year = ?");
            $theme_check->bind_param("ss", $module_name, $year);
            $theme_check->execute();
            $theme_check->store_result();

            if ($theme_check->num_rows > 0) {
                // Update theme if it exists
                $update_theme = $conn->prepare("UPDATE themes SET details = ? WHERE module_name = ? AND year = ?");
                $update_theme->bind_param("sss", $details, $module_name, $year);
                $update_theme->execute();
            } else {
                // Insert new theme if it does not exist
                $insert_theme = $conn->prepare("INSERT INTO themes (module_name, year, details) VALUES (?, ?, ?)");
                $insert_theme->bind_param("sss", $module_name, $year, $details);
                $insert_theme->execute();
            }
        }
    }

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Fetch all modules to display in the form
$modules_result = $conn->query("SELECT * FROM module_info");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

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
<body class="bg-gray-50 font-sans leading-normal tracking-normal">
<div class="admin-dashboard-link-container">
        <a href='/school/admin_dashboard.php' class="admin-dashboard-link">Admin Dashboard</a>
    </div>
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-8">Module Management</h2>

        <form method="POST" action="" class="bg-white shadow-md rounded-lg p-8 mb-8 animate-fadeIn transition duration-500">
            <input type="hidden" name="module_id" value="<?php echo $module_to_edit ? $module_to_edit['id'] : ''; ?>">

            <div class="mb-6">
                <label for="module_name" class="block text-gray-700 font-bold mb-2">Module Name:</label>
                <input type="text" name="module_name" id="module_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" value="<?php echo $module_to_edit ? $module_to_edit['name'] : ''; ?>">
            </div>

            <div class="mb-6">
                <label for="introduction" class="block text-gray-700 font-bold mb-2">Module Introduction:</label>
                <textarea name="introduction" id="introduction" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"><?php echo $module_to_edit ? $module_to_edit['introduction'] : ''; ?></textarea>
            </div>

            <div class="theme-group mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Module Themes</h3>

                <div class="mb-6">
                    <label for="year" class="block text-gray-700 font-bold mb-2">Year:</label>
                    <input type="text" name="year[]" id="year" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                </div>

                <div class="mb-6">
                    <label for="details" class="block text-gray-700 font-bold mb-2">Details:</label>
                    <textarea name="details[]" id="details" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"></textarea>
                </div>
            </div>

            <button type="button" onclick="addTheme()" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">Add Another Theme</button>
            <br><br>

            <button type="submit" class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition duration-300 ease-in-out">
                <?php echo $module_to_edit ? 'Update Module' : 'Save Module Information'; ?>
            </button>
        </form>

        <h3 class="text-2xl font-bold text-gray-700 mb-4">Existing Modules</h3>
        <ul class="bg-white shadow-md rounded-lg p-4">
            <?php while ($row = $modules_result->fetch_assoc()): ?>
                <li class="mb-2 border-b pb-2">
                    <span class="text-lg font-bold"><?php echo $row['name']; ?></span> - <span class="text-gray-600"><?php echo $row['introduction']; ?></span>
                    <a href="?edit=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white py-1 px-2 rounded-lg ml-4 hover:bg-yellow-600 transition duration-300">Edit</a>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="module_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" class="bg-red-500 text-white py-1 px-2 rounded-lg ml-2 hover:bg-red-600 transition duration-300">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <script>
        function addTheme() {
            const themeGroup = document.querySelector('.theme-group');
            const newTheme = themeGroup.cloneNode(true);
            themeGroup.parentNode.insertBefore(newTheme, themeGroup.nextSibling);
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

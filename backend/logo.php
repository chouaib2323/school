<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'school');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['logo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . "logo.png"; // Save as a fixed name "logo.png"
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES['logo']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES['logo']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only PNG files
    if ($imageFileType != "png") {
        echo "Sorry, only PNG files are allowed.";
        $uploadOk = 0;
    }

    // Try to upload the file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            echo "The file " . basename($_FILES['logo']['name']) . " has been uploaded as logo.png.";

            // Check if a logo already exists in the database
            $check_logo = "SELECT * FROM logo WHERE id = 1";
            $result = $conn->query($check_logo);

            if ($result->num_rows > 0) {
                // Logo exists, update the row
                $sql = "UPDATE logo SET filename = 'logo.png', uploaded_at = NOW() WHERE id = 1";
            } else {
                // No logo exists, insert a new row
                $sql = "INSERT INTO logo (id, filename, uploaded_at) VALUES (1, 'logo.png', NOW())";
            }

            if ($conn->query($sql) === TRUE) {
                echo "Database updated successfully.";
            } else {
                echo "Error updating database: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle logo deletion
if (isset($_POST['delete_logo'])) {
    $file_path = "uploads/logo.png";
    if (file_exists($file_path)) {
        unlink($file_path); // Delete the logo
        echo "Logo deleted successfully.";

        // Optionally clear the database entry
        $sql = "UPDATE logo SET filename = '', uploaded_at = NULL WHERE id = 1";
        if (!$conn->query($sql)) {
            echo "Error updating database: " . $conn->error;
        }
    } else {
        echo "Logo does not exist.";
    }
}

// Fetch current logo from database
$result = $conn->query("SELECT filename FROM logo WHERE id = 1");
$logo_file = "uploads/logo.png"; // Default logo path
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (!empty($row['filename'])) {
        $logo_file = "uploads/" . $row['filename'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="container mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-center">Logo Management</h1>

    <!-- Display current logo -->
    <div class="mb-6 text-center">
        <?php if (file_exists($logo_file)) { ?>
            <img src="<?php echo $logo_file; ?>" alt="Logo" class="mx-auto mb-4" style="max-height: 150px;">
        <?php } else { ?>
            <p>No logo uploaded yet.</p>
        <?php } ?>
    </div>

    <!-- Upload Form -->
    <form method="POST" enctype="multipart/form-data" class="mb-6">
        <label class="block mb-2 text-gray-700 font-bold">Upload New Logo (PNG only):</label>
        <input type="file" name="logo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload Logo</button>
    </form>

    <!-- Delete Form -->
    <form method="POST">
        <button type="submit" name="delete_logo" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
            Delete Logo
        </button>
    </form>
</div>

</body>
</html>

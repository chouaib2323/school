<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database connection settings
$host = "localhost";
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$database = "school"; // Replace with your actual database name
$port = 3306; // Default MySQL port

// Create connection
$mysqli = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// List of tables to query
$tables = [
    'anouncment',
    'levles',
    'posts_with_photo',
    'video',
    'informations',
    'elearning',
    'library',
    'directors',
    'about_us',
    'students',
    'clubs',
    'laboratories',
   'research_links',
   'elearning_links',
   'posts',
   'logo'
];

// Array to hold the results
$results = [];

// Fetch data from each table
foreach ($tables as $table) {
    $sql = "SELECT * FROM $table";
    if ($result = $mysqli->query($sql)) {
        $tableData = [];
        $fields = $result->fetch_fields();
        $keys = array_column($fields, 'name');

        while ($row = $result->fetch_assoc()) {
            $tableData[] = array_combine($keys, $row);
        }

        $results[$table] = $tableData;
        $result->free();
    } else {
        echo json_encode(["error" => "Error fetching data from $table: " . $mysqli->error]);
        $mysqli->close();
        exit();
    }
}

// Fetch data for `anouncement_links` table and associate with `anouncment`
$sql = "SELECT * FROM anouncement_links";
if ($result = $mysqli->query($sql)) {
    $announcementLinks = [];
    while ($row = $result->fetch_assoc()) {
        $announcementLinks[$row['anouncement_id']][] = $row;
    }
    foreach ($results['anouncment'] as &$announcement) {
        $announcement['links'] = isset($announcementLinks[$announcement['id']]) ? $announcementLinks[$announcement['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from anouncement_links: " . $mysqli->error]);
    $mysqli->close();
    exit();
}

// Fetch data for `anouncement_photos` table and associate with `anouncment`
$sql = "SELECT * FROM anouncement_photos";
if ($result = $mysqli->query($sql)) {
    $announcementPhotos = [];
    while ($row = $result->fetch_assoc()) {
        $announcementPhotos[$row['anouncement_id']][] = $row;
    }
    foreach ($results['anouncment'] as &$announcement) {
        $announcement['photos'] = isset($announcementPhotos[$announcement['id']]) ? $announcementPhotos[$announcement['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from anouncement_photos: " . $mysqli->error]);
    $mysqli->close();
    exit();
}

// Fetch data for `post_photos` table and associate with `posts_with_photo`
$sql = "SELECT * FROM post_photos";
if ($result = $mysqli->query($sql)) {
    $postPhotos = [];
    while ($row = $result->fetch_assoc()) {
        $postPhotos[$row['post_id']][] = $row;
    }
    foreach ($results['posts_with_photo'] as &$post) {
        $post['photos'] = isset($postPhotos[$post['id']]) ? $postPhotos[$post['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from post_photos: " . $mysqli->error]);
    $mysqli->close();
    exit();
}

// Fetch data for `club_photos` table and associate with `clubs`
$sql = "SELECT * FROM club_photos";
if ($result = $mysqli->query($sql)) {
    $clubPhotos = [];
    while ($row = $result->fetch_assoc()) {
        $clubPhotos[$row['club_id']][] = $row;
    }
    foreach ($results['clubs'] as &$club) {
        $club['photos'] = isset($clubPhotos[$club['id']]) ? $clubPhotos[$club['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from club_photos: " . $mysqli->error]);
    $mysqli->close();
    exit();
}

// Fetch data for `research_files` table and associate with `students`
$sql = "SELECT * FROM research_files";
if ($result = $mysqli->query($sql)) {
    $researchFiles = [];
    while ($row = $result->fetch_assoc()) {
        $researchFiles[$row['student_id']][] = $row;
    }
    foreach ($results['students'] as &$student) {
        $student['research_files'] = isset($researchFiles[$student['id']]) ? $researchFiles[$student['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from research_files: " . $mysqli->error]);
    $mysqli->close();
    exit();
}
// Fetch data for `club_photos` table and associate with `clubs`
$sql = "SELECT * FROM photos";
if ($result = $mysqli->query($sql)) {
    $clubPhotos = [];
    while ($row = $result->fetch_assoc()) {
        $clubPhotos[$row['post_id']][] = $row;
    }
    foreach ($results['posts'] as &$club) {
        $club['file_name'] = isset($clubPhotos[$club['id']]) ? $clubPhotos[$club['id']] : [];
    }
    $result->free();
} else {
    echo json_encode(["error" => "Error fetching data from club_photos: " . $mysqli->error]);
    $mysqli->close();
    exit();
}

// Output results as JSON
echo json_encode($results);

$mysqli->close();
?>

<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');

// Fetch dropdown values
$regions = getUniqueValues('Region', 'nationalparks', $connect);
$park_types = getUniqueValues('Type', 'nationalparks', $connect);
$ecosystem_types = getUniqueValues('EcosystemType', 'ecological', $connect);
$integrity_statuses = getUniqueValues('IntegrityStatus', 'ecological', $connect);
$integrity_trends = getUniqueValues('IntegrityTrend', 'ecological', $connect);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $park_name = mysqli_real_escape_string($connect, $_POST['park_name']);
    $park_type = mysqli_real_escape_string($connect, $_POST['park_type']);
    $region = mysqli_real_escape_string($connect, $_POST['region']);
    $date_founded = intval($_POST['date_founded']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $ecosystem_type = mysqli_real_escape_string($connect, $_POST['ecosystem_type']);
    $integrity_status = mysqli_real_escape_string($connect, $_POST['integrity_status']);
    $integrity_trend = mysqli_real_escape_string($connect, $_POST['integrity_trend']);

    // Handle Image Upload
    $imagePath = "uploads/default.jpg"; // Default image
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2097152) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $snake_case_name = strtolower(str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9\s]/", "", $park_name))); // Convert to snake case
            $newFileName = $snake_case_name . "." . $ext;
            $imagePath = "uploads/" . $newFileName;
            move_uploaded_file($_FILES['image']['tmp_name'], "../" . $imagePath);
        } else {
            echo "Invalid file type or size too large!";
            exit();
        }
    }

    // Insert park details into nationalparks table
    $query = "INSERT INTO nationalparks (ParkName, Type, Region, DateFounded, Description, ImagePath) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssiss", $park_name, $park_type, $region, $date_founded, $description, $imagePath);


    // Insert park details into ecological table
    if ($stmt->execute()) {
        $eco_query = "INSERT INTO ecological (ParkName, EcosystemType, IntegrityStatus, IntegrityTrend) VALUES (?, ?, ?, ?)";
        $eco_stmt = $connect->prepare($eco_query);
        $eco_stmt->bind_param("ssss", $park_name, $ecosystem_type, $integrity_status, $integrity_trend);
        $eco_stmt->execute();

        header("Location: manageParks.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Park</title>
</head>
<body>
    <?php include('../reusable/nav.php'); ?>
    <h2>Add a New Park</h2>
    <form method="POST" action="addPark.php">
        <label>Park Name:</label>
        <input type="text" name="park_name" required>

        <label>Type:</label>
        <select name="park_type" required>
            <?php foreach ($park_types as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Region:</label>
        <select name="region" required>
            <?php foreach ($regions as $region): ?>
                <option value="<?= htmlspecialchars($region) ?>"><?= htmlspecialchars($region) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Date Founded:</label>
        <input type="number" name="date_founded" min="1700" max="<?= date('Y'); ?>" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Ecosystem Type:</label>
        <select name="ecosystem_type" required>
            <?php foreach ($ecosystem_types as $ecosystem): ?>
                <option value="<?= htmlspecialchars($ecosystem) ?>"><?= htmlspecialchars($ecosystem) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Ecological Integrity Status:</label>
        <select name="integrity_status" required>
            <?php foreach ($integrity_statuses as $status): ?>
                <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Ecological Integrity Trend:</label>
        <select name="integrity_trend" required>
            <?php foreach ($integrity_trends as $trend): ?>
                <option value="<?= htmlspecialchars($trend) ?>"><?= htmlspecialchars($trend) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Add Park</button>
    </form>

    <a href="manageParks.php">Back to Manage Parks</a>
</body>
</html>

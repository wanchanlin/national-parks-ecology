<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');


if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);

// Fetch existing park details
$query = "SELECT * FROM nationalparks WHERE ID = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$park = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $park_name = mysqli_real_escape_string($connect, $_POST['park_name']);
    $park_type = mysqli_real_escape_string($connect, $_POST['park_type']);
    $region = mysqli_real_escape_string($connect, $_POST['region']);
    $date_founded = mysqli_real_escape_string($connect, $_POST['date_founded']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $ecosystem_type = mysqli_real_escape_string($connect, $_POST['ecosystem_type']);
    $integrity_status = mysqli_real_escape_string($connect, $_POST['integrity_status']);
    $integrity_trend = mysqli_real_escape_string($connect, $_POST['integrity_trend']);
    $imagePath = $park['ImagePath'];

    // Handle Image Upload (if a new file is uploaded)
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2097152) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid("park_") . "." . $ext;
            $imagePath = "uploads/" . $newFileName;

            // Remove old image if exists
            if ($park['ImagePath'] !== "uploads/default.jpg") {
                unlink("../" . $park['ImagePath']);
            }

            move_uploaded_file($_FILES['image']['tmp_name'], "../" . $imagePath);
        } else {
            echo "Invalid file type or size too large!";
            exit();
        }
    }

    $query = "UPDATE nationalparks SET ParkName=?, Type=?, Region=?, DateFounded=?, Description=?, ImagePath=? WHERE ID=?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ssssssi", $park_name, $park_type, $region, $date_founded, $description, $imagePath, $id);
    
    if ($stmt->execute()) {
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
    <title>Update Park</title>
</head>
<body>
<?php include('../reusable/nav.php'); ?>

    <h2>Update Park</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Park Name:</label>
        <input type="text" name="park_name" value="<?php echo htmlspecialchars($park['ParkName']); ?>" required>
        <label>Upload New Image (optional):</label>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update Park</button>
    </form>
    <a href="manageParks.php">Back to Manage Parks</a>
</body>
</html>

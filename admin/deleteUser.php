<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['is_admin'] !== 'Yes') {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$user_id = intval($_GET['id']);

// Prevent admins from deleting themselves
if ($_SESSION['id'] == $user_id) {
    die("You cannot delete your own account.");
}

// Delete user
$delete_query = "DELETE FROM users WHERE id = ?";
$stmt = $connect->prepare($delete_query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: manageUsers.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>

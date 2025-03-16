<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../public/index.php"); // Redirect to login if not logged in
    exit();
}

include('../reusable/connection.php');

if (isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);

    // Get ParkName before deleting 
    $get_park_name_query = "SELECT ParkName FROM nationalparks WHERE ID = ?";
    $get_stmt = $connect->prepare($get_park_name_query);
    $get_stmt->bind_param("i", $delete_id);
    $get_stmt->execute();
    $result = $get_stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $park_name = $row['ParkName'];

        // Delete related data from ecological table
        $delete_eco_query = "DELETE FROM ecological WHERE ParkName = ?";
        $delete_eco_stmt = $connect->prepare($delete_eco_query);
        $delete_eco_stmt->bind_param("s", $park_name);
        if (!$delete_eco_stmt->execute()) {
            die("Error deleting from ecological: " . $delete_eco_stmt->error);
        }

        // Now delete from nationalparks
        $delete_query = "DELETE FROM nationalparks WHERE ID = ?";
        $delete_stmt = $connect->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);
        if ($delete_stmt->execute()) {
            header("Location: manageParks.php?delete_success=1");
            exit();
        } else {
            die("Error deleting from nationalparks: " . $delete_stmt->error);
        }
    } else {
        die("Park not found.");
    }
} else {
    die("Invalid request.");
}
?>

<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['is_admin'] !== 'Yes') {
    header("Location: ../index.php"); // Redirect non-admins to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
</head>
<body>
<?php include('../reusable/nav.php'); ?>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>

    <ul>
        <li><a href="../users/manageParks.php">Manage Parks</a></li>
        <li><a href="manageUsers.php">Manage Users</a></li>
        <li><a href="../users/manageAccount.php">Manage My Account</a></li>
        <li><a href="../users/logout.php">Logout</a></li>
    </ul>
</body>
</html>

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
    <link rel="stylesheet" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include('../reusable/nav.php'); ?>
<section class="container-fluid">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>

    <ul>
        <li><a href="../users/manageParks.php">Manage Parks</a></li>
        <li><a href="manageUsers.php">Manage Users</a></li>
        <li><a href="../users/manageAccount.php">Manage My Account</a></li>
        <li><a href="../users/logout.php">Logout</a></li>
    </ul>
    </section>
    <?php include('../reusable/footer.php'); ?>
</body>


</html>

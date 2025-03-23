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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
</head>
<body>
<?php include('../reusable/nav.php'); ?>
<section class="container-fluid ">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>

    <div class="card-grid">
        <a href="../users/manageParks.php" class="card">
            <div class="card-content">
            <i class="fa fa-lg fa-tree" aria-hidden="true"></i>
                <h3 class="no-underline">Manage Parks</h3>
            </div>
        </a>
        <a href="manageUsers.php" class="card">
            <div class="card-content">
            <i class="fa fa-lg fa-user" aria-hidden="true"></i>
                <h3 class="no-underline">Manage Users</h3>
            </div>
        </a>
        <a href="../users/manageAccount.php" class="card">
            <div class="card-content">
            <i class="fa fa-lg fa-cog" aria-hidden="true"></i>
                <h3 class="no-underline">Manage My Account</h3>
            </div>
        </a>
        <a href="../users/logout.php" class="card">
            <div class="card-content">
            <i class="fa fa-lg fa-sign-out" aria-hidden="true"></i>
                <h3 class="no-underline">Logout</h3>
            </div>
        </a>
    </div>
</section>
    <?php include('../reusable/footer.php'); ?>
</body>


</html>

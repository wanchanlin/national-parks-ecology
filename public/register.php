<?php
// register.php - User Registration
include('../reusable/connection.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = mysqli_real_escape_string($connect, $_POST['first']);
    $last = mysqli_real_escape_string($connect, $_POST['last']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = md5($_POST['password']);
    $is_admin = 'No';
    $dateadded = date('Y-m-d H:i:s');

    $query = "INSERT INTO users (first, last, email, password, is_admin, dateAdded) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ssssss", $first, $last, $email, $password, $is_admin, $dateadded);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../styles.css">
    
</head>
<body>
    <h1>Register</h1>
    <section class="register-form">
    <?php if (isset($_SESSION['error'])) { echo "<p style='color:red'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first" required>
        <br>
        <label>Last Name:</label>
        <input type="text" name="last" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
    <a href="../index.php">Back to Login</a>
</section>
</body>
</html>

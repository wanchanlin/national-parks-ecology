<?php
// index.php - Login Page
session_start();
if (isset($_SESSION['id'])) {
    header("Location: " . ($_SESSION['is_admin'] === 'Yes' ? 'admin/adminDashboard.php' : 'users/manageParks.php'));
    exit();
}
include('reusable/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ? AND password = ? LIMIT 1";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows) {
        $record = $result->fetch_assoc();
        $_SESSION['id'] = $record['id'];
        $_SESSION['email'] = $record['email'];
        $_SESSION['is_admin'] = $record['is_admin'];
        $_SESSION['first'] = $record['first'];
        $_SESSION['last'] = $record['last'];
        
        header("Location: " . ($record['is_admin'] === 'Yes' ? 'admin/adminDashboard.php' : 'users/manageParks.php'));
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($_SESSION['error'])) { echo "<p style='color:red'>" . $_SESSION['error'] . "</p>"; unset($_SESSION['error']); } ?>
    <form method="POST">
        <label>Email:</label>
        <input type="text" name="email" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <a href="public/register.php">Register</a>
</body>
</html>
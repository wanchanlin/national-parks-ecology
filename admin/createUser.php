<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['is_admin'] !== 'Yes') {
    header("Location: ../index.php");
    exit();
}

include('../reusable/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = mysqli_real_escape_string($connect, $_POST['first']);
    $last = mysqli_real_escape_string($connect, $_POST['last']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = md5($_POST['password']);
    $is_admin = $_POST['is_admin'];

    $query = "INSERT INTO users (first, last, email, password, is_admin, dateAdded) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssss", $first, $last, $email, $password, $is_admin);

    if ($stmt->execute()) {
        header("Location: manageUsers.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add User</title>
</head>
<body>
<?php include('../reusable/nav.php'); ?>
    <h2>Add New User</h2>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first" required>
        
        <label>Last Name:</label>
        <input type="text" name="last" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <label>Admin Status:</label>
        <select name="is_admin">
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
        
        <button type="submit">Create User</button>
    </form>
    <a href="manageUsers.php">Back to Manage Users</a>
</body>
</html>

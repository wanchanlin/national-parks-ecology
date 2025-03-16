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

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = mysqli_real_escape_string($connect, $_POST['first']);
    $last = mysqli_real_escape_string($connect, $_POST['last']);
    $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];
    $is_admin = $_POST['is_admin'];

    $update_query = "UPDATE users SET first=?, last=?, password=?, is_admin=? WHERE id=?";
    $stmt = $connect->prepare($update_query);
    $stmt->bind_param("ssssi", $first, $last, $password, $is_admin, $user_id);
    
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
    <title>Update User</title>
</head>
<body>
<?php include('../reusable/nav.php'); ?>
    <h2>Update User</h2>
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first" value="<?php echo htmlspecialchars($user['first']); ?>" required>
        
        <label>Last Name:</label>
        <input type="text" name="last" value="<?php echo htmlspecialchars($user['last']); ?>" required>
        
        <label>Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        
        <label>New Password (leave blank to keep current password):</label>
        <input type="password" name="password">
        
        <label>Admin Status:</label>
        <select name="is_admin">
            <option value="No" <?php echo ($user['is_admin'] === 'No') ? 'selected' : ''; ?>>No</option>
            <option value="Yes" <?php echo ($user['is_admin'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
        </select>
        
        <button type="submit">Update User</button>
    </form>
    <a href="manageUsers.php">Back to Manage Users</a>
</body>
</html>

<?php
session_start();
include('../reusable/connection.php');

if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

// Determine if the user is an admin
$user_id = $_GET['id'] ?? $_SESSION['id']; 
$is_admin = $_SESSION['is_admin'] === 'Yes';

// Restrict access: Users can only edit themselves, but admins can edit anyone
if (!$is_admin && $_SESSION['id'] != $user_id) {
    die("Unauthorized access.");
}

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = mysqli_real_escape_string($connect, $_POST['first']);
    $last = mysqli_real_escape_string($connect, $_POST['last']);
    $password = !empty($_POST['password']) ? md5($_POST['password']) : $user['password'];
    $is_admin_value = ($is_admin && isset($_POST['is_admin'])) ? $_POST['is_admin'] : $user['is_admin'];

    $update_query = "UPDATE users SET first=?, last=?, password=?, is_admin=? WHERE id=?";
    $stmt = $connect->prepare($update_query);
    $stmt->bind_param("ssssi", $first, $last, $password, $is_admin_value, $user_id);
    $stmt->execute();

    // Update session variables if the user is updating their own account
    if ($_SESSION['id'] == $user_id) {
        $_SESSION['first'] = $first;
        $_SESSION['last'] = $last;
    }

    // Redirect based on role
    header("Location: " . ($is_admin ? '../admin/adminDashboard.php' : 'manageParks.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body>
<?php include('../reusable/nav.php'); ?>
<section class="container-fluid ">
    <h2>Edit Profile</h2>
    <a href="<?php echo $is_admin ? '../admin/adminDashboard.php' : 'manageParks.php'; ?>">Back</a>
    
    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first" value="<?php echo htmlspecialchars($user['first']); ?>" required>

        <label>Last Name:</label>
        <input type="text" name="last" value="<?php echo htmlspecialchars($user['last']); ?>" required>

        <label>Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

        <label>New Password (leave blank to keep current password):</label>
        <input type="password" name="password">

        <?php if ($is_admin) { ?>
            <label>Admin Status:</label>
            <select name="is_admin">
                <option value="No" <?php echo ($user['is_admin'] === 'No') ? 'selected' : ''; ?>>No</option>
                <option value="Yes" <?php echo ($user['is_admin'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
            </select>
        <?php } ?>

        <button type="submit">Update Profile</button>
    </form>
    </section>
    <?php include('../reusable/footer.php'); ?>
</body>
</html>

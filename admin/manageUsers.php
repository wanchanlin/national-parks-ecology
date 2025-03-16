<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['is_admin'] !== 'Yes') {
    header("Location: ../index.php"); // Redirect non-admins
    exit();
}

include('../reusable/connection.php');

// Initialize filters
$filters = [];
$whereClauses = [];
$params = [];
$paramTypes = "";

// Collect selected filters from GET request
if (!empty($_GET['name'])) {
    $filters['name'] = $_GET['name'];
    $whereClauses[] = "(first LIKE ? OR last LIKE ?)";
    $params[] = "%" . $_GET['name'] . "%";
    $params[] = "%" . $_GET['name'] . "%";
    $paramTypes .= "ss";
}
if (!empty($_GET['email'])) {
    $filters['email'] = $_GET['email'];
    $whereClauses[] = "email LIKE ?";
    $params[] = "%" . $_GET['email'] . "%";
    $paramTypes .= "s";
}
if (!empty($_GET['is_admin'])) {
    $filters['is_admin'] = $_GET['is_admin'];
    $whereClauses[] = "is_admin = ?";
    $params[] = $_GET['is_admin'];
    $paramTypes .= "s";
}

// Construct WHERE SQL if filters are present
$whereSQL = (!empty($whereClauses)) ? " WHERE " . implode(" AND ", $whereClauses) : "";

// Build SQL query with filters
$query = "SELECT id, first, last, email, is_admin, dateAdded FROM users $whereSQL ORDER BY dateAdded DESC";
$stmt = $connect->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<?php include('../reusable/nav.php'); ?>
    <h2>Manage Users</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>
    <a href="createUser.php">Add New User</a>
    

    <!-- Filter Form -->
    <form method="GET">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">
        <label>Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
        <label>Admin Status:</label>
        <select name="is_admin">
            <option value="">All</option>
            <option value="Yes" <?php echo (isset($_GET['is_admin']) && $_GET['is_admin'] === 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo (isset($_GET['is_admin']) && $_GET['is_admin'] === 'No') ? 'selected' : ''; ?>>No</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <!-- Display User List -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Admin Status</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first'] . " " . $row['last']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['is_admin']); ?></td>
                    <td><?php echo htmlspecialchars($row['dateAdded']); ?></td>
                    <td>
                        <a href="updateUser.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="deleteUser.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$connect->close();
?>

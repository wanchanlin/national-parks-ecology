<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

include('../reusable/connection.php');
include('../reusable/functions.php');


// fetch filter options from database
$parkNames = getFilterOptions($connect, 'nationalparks', 'ParkName');
$parkTypes = getFilterOptions($connect, 'nationalparks', 'Type');
$regions = getFilterOptions($connect, 'nationalparks', 'Region');
$ecosystemTypes = getFilterOptions($connect, 'ecological', 'EcosystemType');
$integrityStatuses = getFilterOptions($connect, 'ecological', 'IntegrityStatus');
$integrityTrends = getFilterOptions($connect, 'ecological', 'IntegrityTrend');



// fnitialize filters
$filters = [];
$whereClauses = [];
$params = [];
$paramTypes = "";

// collect selected filters from GET request
if (!empty($_GET['park_name'])) {
    $filters['park_name'] = $_GET['park_name'];
    $whereClauses[] = "np.ParkName LIKE ?";
    $params[] = "%" . $_GET['park_name'] . "%";
    $paramTypes .= "s";
}
if (!empty($_GET['park_type'])) {
    $filters['park_type'] = $_GET['park_type'];
    $whereClauses[] = "np.Type = ?";
    $params[] = $_GET['park_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['region'])) {
    $filters['region'] = $_GET['region'];
    $whereClauses[] = "np.Region = ?";
    $params[] = $_GET['region'];
    $paramTypes .= "s";
}
if (!empty($_GET['ecosystem_type'])) {
    $filters['ecosystem_type'] = $_GET['ecosystem_type'];
    $whereClauses[] = "e.EcosystemType = ?";
    $params[] = $_GET['ecosystem_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_status'])) {
    $filters['integrity_status'] = $_GET['integrity_status'];
    $whereClauses[] = "e.IntegrityStatus = ?";
    $params[] = $_GET['integrity_status'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_trend'])) {
    $filters['integrity_trend'] = $_GET['integrity_trend'];
    $whereClauses[] = "e.IntegrityTrend = ?";
    $params[] = $_GET['integrity_trend'];
    $paramTypes .= "s";
}

// construct WHERE SQL if filters are present
$whereSQL = (!empty($whereClauses)) ? " WHERE " . implode(" AND ", $whereClauses) : "";

// build SQL query with filters
$query = "SELECT np.ID, np.ParkName, np.Type AS ParkType, np.Description, np.DateFounded, np.Region, np.ImagePath, np.ImageSource, 
                 e.EcosystemType, e.IntegrityStatus, e.IntegrityTrend
          FROM nationalparks np 
          LEFT JOIN ecological e ON np.ParkName = e.ParkName
          $whereSQL
          GROUP BY np.ID, np.ParkName, np.Type, np.Description, np.DateFounded, np.Region, np.ImagePath, np.ImageSource, 
                   e.EcosystemType, e.IntegrityStatus, e.IntegrityTrend";

// Prepare and execute the query
$stmt = $connect->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM nationalparks WHERE ID = ?";
    $delete_stmt = $connect->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    header("Location: manageParks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Parks</title>
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
        .thumbnail {
            width: 100px;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>

<body>
<?php include('../reusable/nav.php'); ?>
    <h2>Manage Parks</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['first'] . " " . $_SESSION['last']); ?>!</p>
    <a href="addPark.php">Add New Park</a>
    

    <!-- filter form -->
    <form method="GET">
        <label>Park Name:</label>
        <select name="park_name">
            <option value="">All</option>
            <?php foreach ($parkNames as $name) : ?>
                <option value="<?= htmlspecialchars($name); ?>" <?= ($_GET['park_name'] ?? '') == $name ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Park Type:</label>
        <select name="park_type">
            <option value="">All</option>
            <?php foreach ($parkTypes as $type) : ?>
                <option value="<?= htmlspecialchars($type); ?>" <?= ($_GET['park_type'] ?? '') == $type ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($type); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Region:</label>
        <select name="region">
            <option value="">All</option>
            <?php foreach ($regions as $region) : ?>
                <option value="<?= htmlspecialchars($region); ?>" <?= ($_GET['region'] ?? '') == $region ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($region); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ecosystem Type:</label>
        <select name="ecosystem_type">
            <option value="">All</option>
            <?php foreach ($ecosystemTypes as $type) : ?>
                <option value="<?= htmlspecialchars($type); ?>" <?= ($_GET['ecosystem_type'] ?? '') == $type ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($type); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ecological Integrity Status:</label>
        <select name="integrity_status">
            <option value="">All</option>
            <?php foreach ($integrityStatuses as $status) : ?>
                <option value="<?= htmlspecialchars($status); ?>" <?= ($_GET['integrity_status'] ?? '') == $status ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($status); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ecological Integrity Trend:</label>
        <select name="integrity_trend">
            <option value="">All</option>
            <?php foreach ($integrityTrends as $trend) : ?>
                <option value="<?= htmlspecialchars($trend); ?>" <?= ($_GET['integrity_trend'] ?? '') == $trend ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($trend); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <!-- Display Park List -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Park Name</th>
                <th>Type</th>
                <th>Region</th>
                <th>Date Founded</th>
                <th>Description</th>
                <th>Ecosystem Type</th>
                <th>Integrity Status</th>
                <th>Integrity Trend</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                    <img src="<?= htmlspecialchars('../' . ltrim($row['ImagePath'], '/')); ?>" class="thumbnail" alt="<?= htmlspecialchars($row['ParkName']); ?>" width="100">

                    </td>
                    <td><?php echo htmlspecialchars($row['ParkName']); ?></td>
                    <td><?php echo htmlspecialchars($row['ParkType']); ?></td>
                    <td><?php echo htmlspecialchars($row['Region']); ?></td>
                    <td><?php echo htmlspecialchars($row['DateFounded']); ?></td>
                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                    <td><?php echo htmlspecialchars($row['EcosystemType'] ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['IntegrityStatus'] ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['IntegrityTrend'] ?: 'N/A'); ?></td>
                    <td>
                        <a href="updatePark.php?id=<?php echo $row['ID']; ?>">Edit</a> | 
                        <a href="deletePark.php?id=<?php echo urlencode($row['ID']); ?>" 
                        onclick="return confirm('Are you sure you want to delete this park?');">Delete</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No parks found.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$connect->close();
?>

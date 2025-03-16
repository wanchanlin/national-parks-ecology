<?php
include('reusable/connection.php');
include('reusable/functions.php');


// Fetch filter options dynamically
$parkNames = getFilterOptions($connect, 'nationalparks', 'ParkName');
$parkTypes = getFilterOptions($connect, 'nationalparks', 'Type');
$regions = getFilterOptions($connect, 'nationalparks', 'Region');
$ecosystemTypes = getFilterOptions($connect, 'ecological', 'EcosystemType');
$integrityStatuses = getFilterOptions($connect, 'ecological', 'IntegrityStatus');
$integrityTrends = getFilterOptions($connect, 'ecological', 'IntegrityTrend');

// Initialize filters
$whereClauses = [];
$params = [];
$paramTypes = "";

// Collect selected filters from GET request
if (!empty($_GET['park_name'])) {
    $whereClauses[] = "np.ParkName = ?";
    $params[] = $_GET['park_name'];
    $paramTypes .= "s";
}
if (!empty($_GET['park_type'])) {
    $whereClauses[] = "np.Type = ?";
    $params[] = $_GET['park_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['region'])) {
    $whereClauses[] = "np.Region = ?";
    $params[] = $_GET['region'];
    $paramTypes .= "s";
}
if (!empty($_GET['ecosystem_type'])) {
    $whereClauses[] = "e.EcosystemType = ?";
    $params[] = $_GET['ecosystem_type'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_status'])) {
    $whereClauses[] = "e.IntegrityStatus = ?";
    $params[] = $_GET['integrity_status'];
    $paramTypes .= "s";
}
if (!empty($_GET['integrity_trend'])) {
    $whereClauses[] = "e.IntegrityTrend = ?";
    $params[] = $_GET['integrity_trend'];
    $paramTypes .= "s";
}

// Construct WHERE SQL if filters are present
$whereSQL = (!empty($whereClauses)) ? " WHERE " . implode(" AND ", $whereClauses) : "";

// Build SQL query with filters
$query = "SELECT np.ID, np.ParkName, np.Type, np.Description, np.DateFounded, np.Region, np.ImagePath, 
                 e.EcosystemType, e.IntegrityStatus, e.IntegrityTrend
          FROM nationalparks np 
          LEFT JOIN ecological e ON np.ParkName = e.ParkName
          $whereSQL
          ORDER BY np.ParkName";

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
    <title>List of Parks</title>
</head>
<body>
    <h2>List of Parks</h2>

    <!-- Filter Form -->
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
                <th>Park Name</th>
                <th>Type</th>
                <th>Region</th>
                <th>Date Founded</th>
                <th>Description</th>
                <th>Ecosystem Type</th>
                <th>Integrity Status</th>
                <th>Integrity Trend</th>
                <th>Image</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['ParkName']); ?></td>
                    <td><?= htmlspecialchars($row['Type']); ?></td>
                    <td><?= htmlspecialchars($row['Region']); ?></td>
                    <td><?= htmlspecialchars($row['DateFounded']); ?></td>
                    <td><?= htmlspecialchars($row['Description']); ?></td>
                    <td><?= htmlspecialchars($row['EcosystemType'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['IntegrityStatus'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['IntegrityTrend'] ?: 'N/A'); ?></td>
                    <td>
                   
                        <img src="<?= htmlspecialchars($row['ImagePath']); ?>" class="thumbnail" alt="<?= htmlspecialchars($row['ParkName']); ?>" width="100">
                        
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

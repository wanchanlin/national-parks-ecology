<?php

// function to get unique values for a column to populate dropdowns
function getUniqueValues($column, $table, $connection) {
    $query = "SELECT DISTINCT $column FROM $table ORDER BY $column ASC";
    $result = mysqli_query($connection, $query);
    $values = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $values[] = $row[$column];
    }
    return $values;
}

// fetch dropdown values for filters dynamically from the database
function getFilterOptions($connect, $table, $column) {
    $query = "SELECT DISTINCT $column FROM $table WHERE $column IS NOT NULL AND $column != '' ORDER BY $column";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row[$column];
    }
    return $options;
}
?>

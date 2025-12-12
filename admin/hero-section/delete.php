<?php
include_once "../config/db.php";

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id']; // Cast to integer to prevent SQL injection

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM hero_section WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Check if a row was deleted
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?status=deleted");
    } else {
        header("Location: index.php?status=notfound");
    }
    exit;
} else {
    echo "Invalid request.";
}
?>

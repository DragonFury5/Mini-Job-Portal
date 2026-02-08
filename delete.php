<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header('Location: jobs.php');
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: jobs.php");
    exit;
} else {
    echo "Error deleting job: " . htmlspecialchars($stmt->error);
}

$stmt->close();
?>

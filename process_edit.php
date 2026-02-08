<?php
include 'config.php';

if (!isset($_POST['submit'])) {
    header('Location: jobs.php');
    exit;
}

$id          = intval($_POST['id']);
$title       = trim($_POST['title']);
$company     = trim($_POST['company']);
$location    = trim($_POST['location']);
$description = trim($_POST['description']);

$stmt = $conn->prepare(
    "UPDATE jobs 
     SET title = ?, company = ?, location = ?, description = ? 
     WHERE id = ?"
);
$stmt->bind_param("ssssi", $title, $company, $location, $description, $id);

if ($stmt->execute()) {
    header("Location: jobs.php");
    exit;
} else {
    echo "Error updating job: " . htmlspecialchars($stmt->error);
}

$stmt->close();
?>

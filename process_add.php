<?php
include 'config.php';

if (isset($_POST['submit'])) {
    // 1. Grab & sanitize form inputs
    $title       = trim($_POST['title']);
    $company     = trim($_POST['company']);
    $location    = trim($_POST['location']);
    $description = trim($_POST['description']);

    // 2. Prepare and execute the INSERT (uses today's date for posted_date)
    $stmt = $conn->prepare(
        "INSERT INTO jobs (title, company, location, description, posted_date)
         VALUES (?, ?, ?, ?, CURDATE())"
    );
    $stmt->bind_param("ssss", $title, $company, $location, $description);

    if ($stmt->execute()) {
        // 3. On success, redirect back to the full list
        header("Location: jobs.php");
        exit;
    } else {
        // 4. On error, show the error message
        echo "Error adding job: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    // If someone hits this page directly, just go back
    header("Location: add.php");
    exit;
}
?>

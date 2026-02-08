<?php
// Include your database config or any PHP logic here
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application - Mini Job Portal</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Mini Job Portal</h1>
        <nav>
            <a href="jobs.php">See More Jobs</a>
            <a href="add.php">Add Job</a>
            <a href="support.php">Customer Support</a>
            <a href="settings.php">Settings</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <!-- Apply Form Container -->
    <div class="apply-container">
        <h2>Job Application Form</h2>
        <form id="applyForm" enctype="multipart/form-data" method="post" action="thankyou.php">
            <!-- Name Input -->
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <!-- Age Input -->
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" class="form-control" required>
            </div>

            <!-- Proposal Textarea -->
            <div class="form-group">
                <label for="proposal">Proposal/Message:</label>
                <textarea id="proposal" name="proposal" class="form-control" rows="5" required></textarea>
            </div>

            <!-- File Upload -->
            <div class="form-group">
                <label for="resume">Upload Your Application Paper:</label>
                <input type="file" id="resume" name="resume" class="form-control-file" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

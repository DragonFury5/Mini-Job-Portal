<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Job Postings – Mini Job Portal</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
    }
    header {
      background-color: #0d6efd;
      color: white;
      padding: 20px;
      text-align: center;
    }
    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }
    nav a:hover {
      text-decoration: underline;
    }
    main {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .job-table {
      width: 100%;
      border-collapse: collapse;
    }
    .job-table th, .job-table td {
      padding: 12px 8px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    .job-table th {
      background-color: #0d6efd;
      color: white;
    }
    .actions a {
      margin-right: 10px;
      color: #0d6efd;
      text-decoration: none;
      font-weight: bold;
    }
    .actions a:hover {
      text-decoration: underline;
    }
    .btn-add {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 20px;
      background-color: #0d6efd;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }
    .btn-add:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>

  <header>
    <h1>Mini Job Portal</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="add.php">Add Job</a>
      <a href="support.php">Customer Support</a>
      <a href="settings.php">Settings</a>
    </nav>
  </header>

  <main>
    <h2>All Job Postings</h2>
    <a href="add.php" class="btn-add">+ Add New Job</a>

    <table class="job-table">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Company</th>
        <th>Location</th>
        <th>Description</th>
        <th>Posted Date</th>  
      </tr>

      <?php
      $sql = "SELECT * FROM jobs ORDER BY posted_date DESC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($job = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $job['id']; ?></td>
        <td><?= htmlspecialchars($job['title']); ?></td>
        <td><?= htmlspecialchars($job['company']); ?></td>
        <td><?= htmlspecialchars($job['location']); ?></td>
        <td><?= nl2br(htmlspecialchars($job['description'])); ?></td>
        <td><?= $job['posted_date']; ?></td>
            
        </td>
      </tr>
      <?php
        endwhile;
      else:
      ?>
      <tr>
        <td colspan="7">No job postings found.</td>
      </tr>
      <?php endif; ?>
    </table>
  </main>

</body>
</html>

<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Job – Mini Job Portal</title>
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
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    input, textarea {
      padding: 10px;
      font-size: 16px;
      width: 100%;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    button {
      padding: 12px;
      background-color: #0d6efd;
      color: white;
      font-size: 18px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>

  <header>
    <h1>Mini Job Portal</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="jobs.php">See More Jobs</a>
      <a href="support.php">Customer Support</a>
      <a href="settings.php">Settings</a>
    </nav>
  </header>

  <main>
    <h2>Add New Job</h2>

    <form action="process_add.php" method="POST">
      <label for="title">Job Title:</label>
      <input type="text" name="title" id="title" required>

      <label for="company">Company:</label>
      <input type="text" name="company" id="company" required>

      <label for="location">Location:</label>
      <input type="text" name="location" id="location" required>

      <label for="description">Description:</label>
      <textarea name="description" id="description" rows="4" required></textarea>

      <button type="submit" name="submit">Add Job</button>
    </form>
  </main>

</body>
</html>

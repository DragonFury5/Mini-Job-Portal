<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Support – Mini Job Portal</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      display: flex;
      flex-direction: column;
      height: 100vh;
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
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      text-align: center;
    }
    main h1 {
      font-size: 4rem;
      color: #333;
      margin: 0;
    }
    main p {
      font-size: 1.2rem;
      color: #666;
    }
  </style>
</head>
<body>

  <header>
    <h1>Mini Job Portal</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="jobs.php">See More Jobs</a>
      <a href="add.php">Add Job</a>
      <a href="settings.php">Settings</a>
    </nav>
  </header>

  <main>
    <h1>404 Not Found</h1>
    <p>Sorry, Customer Support couldn’t be located.</p>
  </main>

</body>
</html>

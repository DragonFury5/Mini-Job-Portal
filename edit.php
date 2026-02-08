<?php include 'config.php'; 

// If no ID provided, go back
if (!isset($_GET['id'])) {
    header('Location: jobs.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch the job
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No such job
    header('Location: jobs.php');
    exit;
}

$job = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Job – Mini Job Portal</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Copy your existing add.php styles here */
    body { margin:0; font-family:Segoe UI, Tahoma, sans-serif; background:#f4f6f9; }
    header { background:#0d6efd; color:#fff; padding:20px; text-align:center; }
    nav a { color:#fff; margin:0 10px; text-decoration:none; font-weight:bold; }
    nav a:hover { text-decoration:underline; }
    main { max-width:1000px; margin:40px auto; padding:20px; background:#fff; border-radius:12px;
           box-shadow:0 2px 10px rgba(0,0,0,0.1); }
    form { display:flex; flex-direction:column; gap:20px; }
    input, textarea { padding:10px; font-size:16px; width:100%; border-radius:8px; border:1px solid #ccc; }
    button { padding:12px; background:#0d6efd; color:#fff; font-size:18px; border-radius:8px; border:none; cursor:pointer; }
    button:hover { background:#0b5ed7; }
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
    <h2>Edit Job #<?= $job['id']; ?></h2>
    <form action="process_edit.php" method="POST">
      <input type="hidden" name="id" value="<?= $job['id']; ?>">

      <label for="title">Job Title:</label>
      <input type="text" name="title" id="title" value="<?= htmlspecialchars($job['title']); ?>" required>

      <label for="company">Company:</label>
      <input type="text" name="company" id="company" value="<?= htmlspecialchars($job['company']); ?>" required>

      <label for="location">Location:</label>
      <input type="text" name="location" id="location" value="<?= htmlspecialchars($job['location']); ?>" required>

      <label for="description">Description:</label>
      <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($job['description']); ?></textarea>

      <button type="submit" name="submit">Update Job</button>
    </form>
  </main>
</body>
</html>

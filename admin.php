<?php
session_start();
include 'config.php';

$admin_user = 'Holtav';
$admin_pass = 'admin123';
$error = '';

// Handle logout
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: admin.php');
  exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
  if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
    $_SESSION['is_admin'] = true;
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin – Mini Job Portal</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-5">
    <?php if (!empty($_SESSION['is_admin'])): ?>
      <h2>Admin Panel</h2>
      <form method="POST" class="mb-4">
        <button name="logout" class="btn btn-secondary">Logout</button>
      </form>
      <table class="table table-bordered">
        <thead>
          <tr><th>ID</th><th>Title</th><th>Company</th><th>Location</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php
          $res = $conn->query("SELECT * FROM jobs ORDER BY posted_date DESC");
          while ($job = $res->fetch_assoc()):
        ?>
          <tr>
            <td><?= $job['id'] ?></td>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars($job['company']) ?></td>
            <td><?= htmlspecialchars($job['location']) ?></td>
            <td>
              <a href="edit.php?id=<?= $job['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="delete.php?id=<?= $job['id'] ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Delete this job?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h2>Admin Login</h2>
      <?php if ($error): ?><p class="text-danger"><?= $error ?></p><?php endif; ?>
      <form method="POST" style="max-width:400px;">
        <div class="form-group">
          <label>Username</label>
          <input name="username" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Login</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>

<?php 
session_start();
include 'config.php';

// Check if user is logged in via cookie or session
$isLoggedIn = false;
$current_user = null;

if (isset($_COOKIE['jobportal_user'])) {
    // Verify cookie with database
    $user_id = intval($_COOKIE['jobportal_user']);
    $stmt = $conn->prepare("SELECT id, username, email, created_at, last_login FROM users WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $isLoggedIn = true;
        $current_user = $result->fetch_assoc();
        $_SESSION['user_id'] = $current_user['id'];
        $_SESSION['username'] = $current_user['username'];
    }
    $stmt->close();
} elseif (isset($_SESSION['user_id'])) {
    // Verify session with database
    $stmt = $conn->prepare("SELECT id, username, email, created_at, last_login FROM users WHERE id = ? AND is_active = 1");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $isLoggedIn = true;
        $current_user = $result->fetch_assoc();
    }
    $stmt->close();
}

// Redirect to login if not logged in
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

// Update last login time
$update_stmt = $conn->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
$update_stmt->bind_param("i", $current_user['id']);
$update_stmt->execute();
$update_stmt->close();

// Set cookie on first visit (30 days)
if (!isset($_COOKIE['jobportal_user'])) {
    setcookie('jobportal_user', $current_user['id'], time() + (86400 * 30), "/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mini Job Portal</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Modern Blue Strip Design */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f0f8ff 0%, #e6f2ff 100%);
      color: #333;
      line-height: 1.6;
      min-height: 100vh;
      position: relative;
    }
    
    /* Welcome Bar with Profile Icon */
    .welcome-bar {
      background: linear-gradient(90deg, #4361ee 0%, #3a0ca3 100%);
      color: white;
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(67, 97, 238, 0.3);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .welcome-text {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .welcome-bar .username {
      color: #4cc9f0;
      font-weight: 700;
    }
    
    .user-controls {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    /* Profile Icon */
    .profile-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid rgba(255, 255, 255, 0.3);
      position: relative;
    }
    
    .profile-icon:hover {
      transform: scale(1.1);
      background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .profile-icon i {
      font-size: 1.2rem;
      color: white;
    }
    
    /* Profile Popup */
    .profile-popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      animation: fadeIn 0.3s ease;
    }
    
    .profile-popup.active {
      display: flex;
    }
    
    .profile-content {
      background: white;
      border-radius: 20px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      animation: slideUp 0.4s ease;
    }
    
    .profile-header {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      padding: 25px;
      text-align: center;
      position: relative;
    }
    
    .profile-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #4cc9f0, #4361ee, #7209b7);
    }
    
    .profile-avatar {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
      border-radius: 50%;
      margin: 0 auto 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 4px solid white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .profile-avatar i {
      font-size: 2.5rem;
      color: white;
    }
    
    .profile-header h3 {
      font-size: 1.8rem;
      margin-bottom: 5px;
      font-weight: 700;
    }
    
    .profile-header p {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.95rem;
    }
    
    .profile-details {
      padding: 30px;
    }
    
    .profile-info {
      margin-bottom: 25px;
    }
    
    .info-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }
    
    .info-label {
      color: #666;
      font-weight: 600;
    }
    
    .info-value {
      color: #1e3c72;
      font-weight: 600;
    }
    
    .profile-actions {
      display: flex;
      gap: 15px;
      margin-top: 25px;
    }
    
    .profile-btn {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      text-decoration: none;
    }
    
    .profile-btn.logout {
      background: linear-gradient(135deg, #ff6b6b 0%, #ee5d5d 100%);
      color: white;
    }
    
    .profile-btn.logout:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(255, 107, 107, 0.4);
    }
    
    .profile-btn.close {
      background: #f0f5ff;
      color: #4361ee;
      border: 2px solid #e0e7ff;
    }
    
    .profile-btn.close:hover {
      background: #e6f2ff;
      transform: translateY(-2px);
    }
    
    .logout-btn {
      background: rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 8px 20px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
      text-decoration: none;
      color: white;
    }
    
    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Header */
    header {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      text-align: center;
      padding: 40px 0 20px;
      margin-bottom: 40px;
      box-shadow: 0 4px 20px rgba(30, 60, 114, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #4cc9f0, #4361ee, #7209b7);
    }
    
    header h1 {
      font-weight: 800;
      font-size: 3rem;
      letter-spacing: -0.5px;
      margin-bottom: 20px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: relative;
      display: inline-block;
    }
    
    header h1::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: #4cc9f0;
      border-radius: 2px;
    }
    
    /* Navigation */
    nav {
      display: flex;
      justify-content: center;
      gap: 25px;
      flex-wrap: wrap;
      padding: 0 20px;
      margin-top: 10px;
    }
    
    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.05rem;
      padding: 12px 25px;
      border-radius: 30px;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.15);
      position: relative;
      overflow: hidden;
    }
    
    nav a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }
    
    nav a:hover::before {
      left: 100%;
    }
    
    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px 60px;
    }
    
    /* Section Headers */
    h2 {
      color: #1e3c72;
      font-weight: 700;
      margin: 40px 0 25px;
      padding-bottom: 15px;
      border-bottom: 3px solid #4361ee;
      position: relative;
      font-size: 1.8rem;
    }
    
    h2::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 100px;
      height: 3px;
      background: #4cc9f0;
    }
    
    /* Blue Strip Job Boxes */
    .job {
      background: white;
      border-radius: 15px;
      padding: 0;
      margin-bottom: 30px;
      box-shadow: 0 8px 25px rgba(30, 60, 114, 0.12);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
      border-left: 8px solid #4361ee;
      position: relative;
    }
    
    .job:hover {
      transform: translateY(-10px) scale(1.01);
      box-shadow: 0 15px 35px rgba(30, 60, 114, 0.25);
    }
    
    .job::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #4cc9f0, #4361ee, #7209b7);
    }
    
    .job .row {
      margin: 0;
      align-items: stretch;
    }
    
    .job-content {
      padding: 30px;
      position: relative;
    }
    
    .job h3 {
      color: #1e3c72;
      font-weight: 800;
      margin-bottom: 15px;
      font-size: 1.6rem;
    }
    
    .job p {
      margin-bottom: 10px;
      color: #555;
      line-height: 1.7;
    }
    
    .job .text-muted {
      color: #777 !important;
      font-style: italic;
      font-size: 0.95rem;
    }
    
    /* Apply Button */
    .apply-btn-container {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px;
      background: linear-gradient(135deg, #f8faff 0%, #f0f5ff 100%);
      position: relative;
      overflow: hidden;
    }
    
    .apply-btn-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 100%;
      background: linear-gradient(135deg, transparent 0%, rgba(67, 97, 238, 0.05) 100%);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      border: none;
      border-radius: 30px;
      padding: 14px 40px;
      font-weight: 700;
      font-size: 1.1rem;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
      color: white;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
      z-index: 1;
    }
    
    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.6s ease;
      z-index: -1;
    }
    
    .btn-primary:hover::before {
      left: 100%;
    }
    
    .btn-primary:hover {
      transform: translateY(-4px) scale(1.05);
      box-shadow: 0 12px 30px rgba(67, 97, 238, 0.6);
      background: linear-gradient(135deg, #3a0ca3 0%, #7209b7 100%);
    }
    
    /* See More Jobs Button */
    .see-more-container {
      text-align: center;
      margin: 50px 0;
      position: relative;
    }
    
    .see-more-container::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, #4cc9f0, transparent);
      transform: translateY(-50%);
    }
    
    .see-more-btn {
      background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
      border: none;
      border-radius: 35px;
      padding: 16px 60px;
      font-weight: 700;
      font-size: 1.2rem;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
      color: white;
      position: relative;
      z-index: 1;
      overflow: hidden;
    }
    
    .see-more-btn:hover {
      transform: translateY(-5px) scale(1.08);
      box-shadow: 0 15px 35px rgba(67, 97, 238, 0.6);
      color: white;
      text-decoration: none;
    }
    
    /* Recommended Jobs Cards */
    .card-deck {
      margin: 40px 0;
    }
    
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(30, 60, 114, 0.12);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
      height: 100%;
      border-top: 5px solid #4361ee;
      margin-bottom: 25px;
      position: relative;
    }
    
    .card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 15px 35px rgba(30, 60, 114, 0.25);
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #4cc9f0, #4361ee);
    }
    
    .card-body {
      padding: 25px;
      position: relative;
      z-index: 1;
    }
    
    .card-title {
      color: #1e3c72;
      font-weight: 700;
      font-size: 1.4rem;
      margin-bottom: 15px;
      min-height: 3.5rem;
    }
    
    .card-text {
      color: #666;
      margin-bottom: 10px;
    }
    
    .card .flex-grow-1 {
      color: #777;
      line-height: 1.6;
    }
    
    /* Animation for job cards */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .job {
      animation: fadeInUp 0.6s ease-out;
    }
    
    .job:nth-child(2) { animation-delay: 0.1s; }
    .job:nth-child(3) { animation-delay: 0.2s; }
    .card { animation: fadeInUp 0.6s ease-out; }
    .card:nth-child(2) { animation-delay: 0.1s; }
    .card:nth-child(3) { animation-delay: 0.2s; }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      header h1 {
        font-size: 2.2rem;
      }
      
      nav {
        gap: 10px;
        padding: 0 15px;
      }
      
      nav a {
        padding: 10px 20px;
        font-size: 1rem;
        width: 100%;
        text-align: center;
      }
      
      .job-content, .apply-btn-container {
        padding: 20px;
      }
      
      .apply-btn-container {
        justify-content: flex-start;
        padding-top: 0;
      }
      
      .btn-primary {
        width: 100%;
        padding: 12px 20px;
      }
      
      .see-more-btn {
        padding: 14px 40px;
        font-size: 1.1rem;
      }
      
      h2 {
        font-size: 1.6rem;
      }
      
      .job h3 {
        font-size: 1.3rem;
      }
      
      .welcome-bar {
        padding: 10px 15px;
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
      
      .user-controls {
        justify-content: center;
      }
      
      .profile-content {
        width: 95%;
        margin: 20px;
      }
    }
    
    /* Company name styling */
    .company-name {
      color: #2a5298;
      font-weight: 600;
    }
    
    /* Location styling */
    .job-location {
      color: #666;
      display: flex;
      align-items: center;
    }
    
    .job-location::before {
      content: '📍';
      margin-right: 8px;
    }
    
    /* Date styling */
    .job-date {
      font-size: 0.9rem;
      color: #777;
      display: flex;
      align-items: center;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }
    
    .job-date::before {
      content: '📅';
      margin-right: 8px;
    }
    
    /* Footer */
    .footer {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: white;
      text-align: center;
      padding: 30px 20px;
      margin-top: 60px;
      position: relative;
    }
    
    .footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #4cc9f0, #4361ee, #7209b7);
    }
    
    .footer p {
      margin: 5px 0;
    }
    
    .footer a {
      color: #4cc9f0;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .footer a:hover {
      color: white;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Welcome Bar with Profile Icon -->
  <div class="welcome-bar">
    <div class="welcome-text">
      <i class="fas fa-user-circle"></i>
      Welcome, <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!
    </div>
    <div class="user-controls">
      <!-- Profile Icon -->
      <div class="profile-icon" id="profileIcon">
        <i class="fas fa-user"></i>
      </div>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>

  <!-- Profile Popup -->
  <div class="profile-popup" id="profilePopup">
    <div class="profile-content">
      <div class="profile-header">
        <div class="profile-avatar">
          <i class="fas fa-user"></i>
        </div>
        <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        <p>Job Portal Member</p>
      </div>
      <div class="profile-details">
        <div class="profile-info">
          <div class="info-row">
            <span class="info-label">Username:</span>
            <span class="info-value"><?php echo htmlspecialchars($current_user['username']); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value"><?php echo htmlspecialchars($current_user['email']); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Member Since:</span>
            <span class="info-value"><?php echo date('F j, Y', strtotime($current_user['created_at'])); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Last Login:</span>
            <span class="info-value"><?php echo $current_user['last_login'] ? date('F j, Y g:i A', strtotime($current_user['last_login'])) : 'First login'; ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Account Status:</span>
            <span class="info-value" style="color: #38b000;">Active</span>
          </div>
        </div>
        <div class="profile-actions">
          <button class="profile-btn close" id="closeProfile">Close</button>
          <a href="logout.php" class="profile-btn logout">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <header>
    <h1>Mini Job Portal</h1>
    <nav>
      <a href="jobs.php">See More Jobs</a>
      <a href="support.php">Customer Support</a>
      <a href="settings.php">Settings</a>
    </nav>
  </header>

  <main class="container">

    <!-- Top 3 Latest Jobs -->
    <h2>Top 3 Latest Job Postings</h2>
    <div class="job-list mb-5">
      <?php
      $sql = "SELECT * FROM jobs WHERE is_active = 1 ORDER BY posted_date DESC LIMIT 3";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="job row align-items-center">
          <div class="col-md-10 job-content">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><strong class="company-name">Company:</strong> <?= htmlspecialchars($row['company']) ?></p>
            <p class="job-location"><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <p class="job-date"><em>Posted on <?= date('F j, Y', strtotime($row['posted_date'])) ?></em></p>
          </div>
          <div class="col-md-2 apply-btn-container">
            <a href="apply.php?job_id=<?= $row['id'] ?>" class="btn btn-primary">Apply Now</a>
          </div>
        </div>
      <?php 
        endwhile;
      } else {
        echo '<p class="text-center">No job postings available.</p>';
      }
      ?>
    </div>

    <div class="see-more-container">
      <a href="jobs.php" class="btn see-more-btn">Browse All Jobs</a>
    </div>

    <!-- Recommended Jobs Section -->
    <h2>Recommended for You</h2>
    <div class="card-deck mb-5">
      <?php
      $rec = $conn->query("SELECT * FROM jobs WHERE is_active = 1 ORDER BY RAND() LIMIT 3");
      if ($rec->num_rows > 0) {
        while ($job = $rec->fetch_assoc()):
      ?>
        <div class="card">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
            <p class="card-text mb-1"><strong class="company-name">Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
            <p class="card-text mb-1 job-location"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
            <p class="card-text flex-grow-1"><?= nl2br(htmlspecialchars(substr($job['description'],0,100))) ?>…</p>
            <div class="mt-3">
              <a href="apply.php?job_id=<?= $job['id'] ?>" class="btn btn-primary btn-sm">Apply Now</a>
            </div>
          </div>
        </div>
      <?php 
        endwhile;
      } else {
        echo '<p class="text-center col-12">No recommended jobs available.</p>';
      }
      ?>
    </div>

  </main>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date('Y'); ?> Nobel Gaming Company. All rights reserved.</p>
    <p><a href="#">Terms & Conditions</a> | <a href="#">Privacy Policy</a></p>
  </div>

  <!-- Bootstrap JS + dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
  <script>
    // Profile popup functionality
    const profileIcon = document.getElementById('profileIcon');
    const profilePopup = document.getElementById('profilePopup');
    const closeProfile = document.getElementById('closeProfile');
    
    // Open profile popup
    profileIcon.addEventListener('click', () => {
      profilePopup.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });
    
    // Close profile popup
    closeProfile.addEventListener('click', () => {
      profilePopup.classList.remove('active');
      document.body.style.overflow = 'auto'; // Re-enable scrolling
    });
    
    // Close popup when clicking outside
    profilePopup.addEventListener('click', (e) => {
      if (e.target === profilePopup) {
        profilePopup.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });
    
    // Close popup with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && profilePopup.classList.contains('active')) {
        profilePopup.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });
    
    // Enhanced hover effects
    document.querySelectorAll('.job, .card, .btn, nav a').forEach(element => {
      element.addEventListener('mouseenter', function() {
        this.style.cursor = 'pointer';
      });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>
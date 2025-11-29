<?php include 'includes/header.php'; ?>

<?php
session_start();

// if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>

  <main class="dashboard-main">
    <div class="dboard-head">
      <div class="container">
        <div class="d-flex justify-content-center align-items-center">
          <h2>Welcome to your Dashboard, <?php echo $_SESSION['email']; ?> </h2>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="dashboard-container">
        <div class="posts-container">
          <?php include('templates/dashboard-posts.php') ?>
        </div>
        <div>
          <a class="logout-btn" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </main>
  
<?php include 'includes/footer.php'; ?>

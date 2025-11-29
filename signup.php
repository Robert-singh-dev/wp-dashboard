<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Sign-up successful! <a href='login.php'>Go to Login</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include 'includes/header.php'; ?>

  <main>
    <div class="form-container">
      <form method="POST" action="">
        <h2 class="login-heading">Sign up</h2>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Set password" required>
        <input type="submit" value="Sign Up">
      </form>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>


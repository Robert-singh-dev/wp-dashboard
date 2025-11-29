<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Invalid password";
        }
    } else {
        $error = "❌ No account found with that email";
    }
}
?>

<?php include 'includes/header.php'; ?>

    <main>
        <div class="form-container">
            <form method="POST" action="">
                <h2 class="login-heading">Login</h2>

                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Enter your password" required>
                <input type="submit" value="Login">
            </form>
            <a href="signup.php" class="sign-up">Sign-up</a>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>


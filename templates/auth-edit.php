<?php
session_start();

// Get post ID (if coming from Edit button)
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get values correctly
    $username = trim($_POST['username']);
    $app_pass = trim($_POST['app_pass']);
    $post_id  = intval($_POST['post_id']); 

    // Save credentials
    $_SESSION['wp_auth'] = base64_encode("$username:$app_pass");

    // Redirect directly to the single post editor
    header("Location: edit-single.php?id=$post_id");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<h2>WordPress Authentication</h2>

<form method="POST">
    <!-- Carry post ID forward -->
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

    <label>WordPress Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Application Password</label><br>
    <input type="text" name="app_pass" required><br><br>

    <button type="submit">Login</button>
</form>

<?php include 'includes/footer.php'; ?>

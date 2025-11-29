<link rel="stylesheet" href="../styles/style.css">

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $app_pass = trim($_POST['app_pass']);

    // Build Auth Header
    $auth_header = base64_encode("$username:$app_pass");

    // Test authentication by calling /users/me
    $api_url = "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/users/me";

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $auth_header"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check login
    if ($http_code == 200) {
        // Save session
        $_SESSION['wp_auth'] = $auth_header;

        // Redirect to edit page
        $post_id = $_GET['post_id'] ?? 0;
        header("Location: edit-posts.php?post_id=" . $post_id);
        exit;
    } else {
        $error = "Invalid username or application password.";
    }
}
?>

<div class="form-container">
    <form method="POST">
        <label>Username</label><br>
        <input type="text" name="username" required><br><br>

        <label>Application Password</label><br>
        <input type="password" name="app_pass" required><br><br>

        <button type="submit">Login</button>
    </form>
</div>

<?php if (!empty($error)) { ?>
<p style="color:red;"><?= $error ?></p>
<?php } ?>

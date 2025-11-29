<?php
session_start();

// Block if not logged in
if (!isset($_SESSION['wp_auth'])) {
    echo "Not authenticated. <a href='auth-edit.php'>Login first</a>";
    exit;
}

$auth = $_SESSION['wp_auth'];

// Get post ID from URL
if (!isset($_GET['id'])) {
    echo "Invalid Post ID";
    exit;
}

$post_id = intval($_GET['id']);

// WordPress REST API URL for a single post
$post_url = "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/blog/$post_id";

// Step 1: Fetch the post data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth"
]);

$response = curl_exec($ch);
curl_close($ch);

$post = json_decode($response, true);

if (!$post || isset($post['code'])) {
    echo "Error fetching post.";
    exit;
}

// Step 2: Process form submit (update request)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $updated_title = $_POST['title'];
    $updated_content = $_POST['content'];

    $data = [
        "title" => $updated_title,
        "content" => $updated_content
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // WordPress uses POST for updates
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $auth",
        "Content-Type: application/json"
    ]);

    $update_response = curl_exec($ch);
    curl_close($ch);

    $updated_post = json_decode($update_response, true);

    if (isset($updated_post['id'])) {
        echo "<p style='color:green;'>Post updated successfully!</p>";
        // Reload updated content
        $post = $updated_post;
    } else {
        echo "<p style='color:red;'>Failed to update post.</p>";
    }
}

?>

<h2>Edit Post (ID: <?php echo $post_id; ?>)</h2>

<form method="POST">
    <label>Title</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']['rendered']); ?>" style="width:100%;padding:8px;"><br><br>

    <label>Content</label><br>
    <textarea name="content" rows="10" style="width:100%;padding:8px;"><?php echo htmlspecialchars($post['content']['rendered']); ?></textarea><br><br>

    <button type="submit" style="padding:10px 20px;background:#0073aa;color:#fff;border:none;border-radius:5px;">Update Post</button>
</form>


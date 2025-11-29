<link rel="stylesheet" href="../styles/style.css">

<?php
session_start();

if (!isset($_SESSION['wp_auth'])) {
    die("Not authenticated");
}

if (!isset($_GET["post_id"])) {
    die("No post ID provided.");
}

$post_id = intval($_GET["post_id"]);

// Fetch the single post
$api_url = "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/blog/$post_id?_fields=id,title,content";

// cURL GET
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$post = json_decode($response);

// If POST (updating)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $update_data = [
        "title"   => $_POST["title"]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/blog/$post_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($update_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . $_SESSION["wp_auth"],
        "Content-Type: application/json"
    ]);

    $update_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        // Refetch updated post so the title field shows new value
        $api_url = "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/blog/$post_id?_fields=id,title,content";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $api_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $new_response = curl_exec($ch2);
        curl_close($ch2);
        $post = json_decode($new_response);

        echo "<p style='color:green;'>Post updated successfully!</p>";
        echo "<a href='../dashboard.php' style='background:black;color:white;padding:8px 14px;display:inline-block;margin-top:10px;text-decoration:none;border-radius:4px;'>Back to Dashboard</a>";
    } else {
        echo "<p style='color:red;'>Failed to update post.</p>";
        echo "<pre>$update_response</pre>";
    }
}
?>

<div class="form-container">

<h2>Edit Post <?= $post->id ?></h2>

    <form method="POST">
        <label>Title</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($post->title->rendered) ?>" style="width:400px;"><br><br>
        <button type="submit">Update Post</button>
    </form>
</div>


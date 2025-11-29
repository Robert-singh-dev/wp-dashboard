<?php
// WordPress REST API endpoint
$api_url = "https://staging.supercode.in/zetwerk-blogs/wp-json/wp/v2/blog?status=publish&_fields=id,title";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute request
$response = curl_exec($ch);
curl_close($ch);

// Decode JSON
$posts = json_decode($response);
?>

<ul>
<?php
if (!empty($posts)) {
    foreach ($posts as $post) {
        echo "<li>" . htmlspecialchars($post->title->rendered) . "</li>";
        echo '<a class="edit-btn" href="templates/auth-login.php?post_id=' . $post->id . '">Edit</a>';
    }
} else {
    echo "<li>No posts found.</li>";
}
?>
</ul>



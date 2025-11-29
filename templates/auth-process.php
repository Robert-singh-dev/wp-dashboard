<?php
session_start();

$_SESSION["wp_user"] = $_POST["username"];
$_SESSION["wp_app_pass"] = $_POST["app_pass"];

header("Location: edit-post-list.php");
exit;

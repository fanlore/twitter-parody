<?php
include_once('includes/functions.php');
$title = 'Сначала старые посты';
$error = get_error_message();
$posts = get_posts(0,true);
include_once('includes/header.php');
include_once('includes/posts.php');
include_once('includes/footer.php');
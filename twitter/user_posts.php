<?php
include_once('includes/functions.php');

$error = get_error_message();
$title = 'Твиты пользователя';
$user_id = 0;
$userIsAvailable = isset($_GET['user']) && !empty($_GET['user']);
if ($userIsAvailable){
    $user_id = $_GET['user'];
}else if(isset($_SESSION['user']['id']) && !empty($_SESSION['user'])){
    $user_id = $_SESSION['user']['id'];
}else {
    $user_id = 0;
}


$posts = get_posts($user_id);
if (!empty($posts)){
    $title = 'Посты ' . $posts[0]['login'];
}
include_once('includes/header.php');
if (logged_in()){
    include_once('includes/tweet_form.php');
}
include_once('includes/posts.php');
include_once('includes/footer.php');



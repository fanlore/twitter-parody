<?php
include_once('config.php');


function debug($var,$stop = false){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    if ($stop) die;
}
function redirect($link = HOST){
    header("Location: " . $link);
    die;
}
function logged_in(){
    return isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']);
}
function get_url($page = ''){
    return HOST . "/$page";
}
function get_page_title($title){
    if (!empty($title)){
        return SITE_NAME . " - $title";
    }else {
        return SITE_NAME;
    }
}
function db(){
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ';charset=utf8',DB_USER,DB_PASS,
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }catch (PDOException $e){
        die($e->getMessage());
    }
}
function db_query($sql, $exec = false){
    if (empty($sql)) return false;
    if ($exec) return db()->exec($sql);
    return db()->query($sql);
}
function get_posts($user_id = 0,$sort = false){
    $sorting = 'DESC';
    if ($sort){
        $sorting = 'ASC';
    }
    if ($user_id > 0) return db_query("SELECT posts.*,users.name,users.login,users.avatar FROM `posts` JOIN `users` ON users.id = $user_id AND $user_id = posts.user_id ORDER BY `posts`.`date` $sorting;")->fetchAll();
    return db_query("SELECT posts.*,users.name,users.login,users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id ORDER BY `posts`.`date` $sorting;")->fetchAll();
}
function get_user_info($login){
    return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
}
function add_user($login,$pass){
    $login = trim($login);
    $password = password_hash($pass,PASSWORD_DEFAULT);
    $name = ucfirst($login);
    return db_query("INSERT INTO `users` (`id`, `login`, `pass`, `name`) VALUES (NULL, '$login', '$password', '$name');",true);
}
function register_user($auth_data){
    if (empty($auth_data) || !isset($auth_data['login']) || !isset($auth_data['password']) || !isset($auth_data['repeat_password'])) return false;
    $user = get_user_info($auth_data['login']);
    if (!empty($user)){
        $_SESSION['error'] = 'Пользователь ' . $auth_data['login'] . ' уже существует';
        header("Location: " . get_url('register.php'));
        die;
    }
    if ($auth_data['password'] !== $auth_data['repeat_password']){
        $_SESSION['error'] = 'Пароли не совпадают';
        header("Location: " . get_url('register.php'));
        die;
    }
    if (add_user($auth_data['login'],$auth_data['password'])){
        header("Location: " . get_url(''));
        die;
    }

}
function get_error_message(){
    $error = '';
    if (!empty($_SESSION['error']) && isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        $_SESSION['error'] = '';
    }
    return $error;
}
function login($auth_data){
    if (empty($auth_data) || !isset($auth_data['login']) || !isset($auth_data['password'])) return false;
    $user = get_user_info($auth_data['login']);
    if (empty($user)){
        $_SESSION['error'] = 'Пользователь ' . $auth_data['login'] .' не найден';
        header("Location: " . get_url(''));
        die;
    }
    if (password_verify($auth_data['password'],$user['pass'])){
        $_SESSION['user'] = $user;
        $_SESSION['error'] = '';
        header("Location: " . get_url('user_posts.php?user=' . $user['id']));
        die;
    }else {
        $_SESSION['error'] = 'Пароль неверный';
        header("Location: " . get_url(''));
    }
}
function add_post($text,$image){
    $text = trim($text);
    $text = preg_replace('/\s+/', ' ', $text);
    $words_count = explode(' ',$text);
    if(count($words_count) > 50){
        array_splice( $words_count,49);
    }
    $text = implode(' ',$words_count);
    if (mb_strlen($text) > 255 ){
        $text = mb_substr($text,0,250) . ' ...';
    }

    $user_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO `posts` (`id`, `user_id`, `text`, `image`) VALUES (NULL, '$user_id', '$text', '$image');";

    return db_query($sql,true);
}
function delete_post($id){
    $id = (int) $id;
    if (is_int($id) && $id > 0){
        $user_id = $_SESSION['user']['id'];
        return db_query("DELETE FROM `posts` WHERE `posts`.`id` = $id AND `user_id` = $user_id;");
    }
}
function get_likes_count($post_id){
    if (empty($post_id)) return 0;
    return db_query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = $post_id;")->fetchColumn();
}
function is_post_liked($post_id){
    $user_id = $_SESSION['user']['id'];
    if (empty($user_id)) return false;

    return db_query("SELECT * FROM `likes` WHERE `post_id` = $post_id AND `user_id` = $user_id;")->rowCount() > 0;
}
function add_like($post_id){
    $user_id = $_SESSION['user']['id'];
    if (empty($user_id)) return false;

    $sql = "INSERT INTO `likes` (`user_id`, `post_id`) VALUES ($user_id,$post_id);";
    return db_query($sql,true);
}
function delete_like($post_id){
        $user_id = $_SESSION['user']['id'];
        if (empty($post_id)) return false;
        return db_query("DELETE FROM `likes` WHERE `post_id` = $post_id AND `user_id` = $user_id;",true);
}
function get_liked_posts(){
    $user_id = $_SESSION['user']['id'];

    $sql = "SELECT posts.*,users.name,users.login,users.avatar FROM `likes` JOIN `posts` ON posts.id = likes.post_id JOIN `users` ON users.id = posts.user_id WHERE likes.user_id = $user_id;";

    return db_query($sql)->fetchAll();
}

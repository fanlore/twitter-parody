<?php
include_once('functions.php');

if (!logged_in()) redirect();

if (isset($_GET['id']) && !empty($_GET['id'])){
    if (!add_like($_GET['id'])){
        $_SESSION['error'] = 'Упс...Что-то пошло не так';
    }
}
redirect();

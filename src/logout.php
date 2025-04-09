<?php
session_start();
if(isset($_COOKIE['remember_me'])){
    setcookie('remember_me', '', time() - 3600, "/");
}
if(isset($_COOKIE['id'])){
    setcookie('id', '', time() - 3600, "/");
}
session_destroy();
header("Location: login.php");
exit();


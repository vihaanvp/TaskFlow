<?php
session_start();

function login($user_id) {
    $_SESSION['user_id'] = $user_id;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}
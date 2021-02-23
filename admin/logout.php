<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    unset($_SESSION['admin_login']);
    header('Location:login.php');
    exit();
}

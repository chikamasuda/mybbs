<?php
session_start();
unset($_SESSION['admin_login']);
header('Location:login.php');
exit();

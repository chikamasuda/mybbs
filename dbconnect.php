<?php
require_once 'dbconfig.php';

try {
    $db = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE, USERNAME, PASSWORD);
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}

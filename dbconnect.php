<?php
try{
    $db = new PDO("mysql:dbname=admin;host=localhost;charset=utf8", "admin", "admin");
} catch(PDOException $e){
  echo 'DB接続エラー：' .$e->getMessage();
}
?>
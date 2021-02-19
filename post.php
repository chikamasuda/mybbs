<?php
require('dbconnect.php');

$name=filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$title=filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$text=filter_input(INPUT_POST, 'text', FILTER_SANITIZE_SPECIAL_CHARS);
$delete_key=filter_input(INPUT_POST, 'delete_key', FILTER_SANITIZE_SPECIAL_CHARS);
$ip_address = $_SERVER['REMOTE_ADDR'];
$id = $_REQUEST['id'];

// //バリデーション
if($_SERVER['REQUEST_METHOD']=='POST'){
  if(empty($name)){
    $error['name']='blank';
    header('Location: index.php');
    exit();
  }
  if(empty($title)){
    $error['title']='blank';
    header('Location: index.php');
    exit();
  }
  if(empty($text)){
    $error['text']='blank';
    header('Location: index.php');
    exit();
  }
  if(empty($delete_key)){
    $error['delete_key']='blank';
    header('Location: index.php');
    exit();
  }
  if(strlen($name) > 255){
    $error['name']='length';
    header('Location: index.php');
    exit();
  }
  if(strlen($title) > 255){
    $error['title']='length';
    header('Location: index.php');
    exit();
  }
  if(strlen($delete_key) > 255){
    $error['delete_key']='length';
    header('Location: index.php');
    exit();
  }
 
  //投稿データをDBに追加
  if(empty($error)){
    $sql=$db->prepare("INSERT INTO posts SET name=:name, title=:title, text=:text, ip_address=:ip_address, delete_key=:delete_key, created_at=now()");
    
    $sql -> bindValue(':name', $name, PDO::PARAM_STR);
    $sql -> bindValue(':title', $title, PDO::PARAM_STR);
    $sql -> bindValue(':text', $text, PDO::PARAM_STR);
    $sql -> bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $sql -> bindValue(':delete_key', $delete_key, PDO::PARAM_STR);
    $sql -> execute();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <title>ウェブ掲示板</title>
</head>

<body>
  <section class="container mb-3 mt-5" style="padding:0; border: solid 1px #ccc;">
    <h1 class="pl-5 bg-primary text-white pt-3 pb-3">投稿完了</h1>
    <div class="pl-5 pr-5">
      <h2 class="mt-4 mb-4">投稿が完了しました。</h2>
      <a href="index.php" class="btn btn-md btn-primary mb-4">投稿一覧画面に戻る</a>
    </div>
  </section>
</body>

</html>
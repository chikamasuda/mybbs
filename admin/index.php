<?php
require('../dbconnect.php');
$password=filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$id=filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

if($_SERVER['REQUEST_METHOD']=='POST'){
  if($id == 1 && $password == 'admin') {
    header('Location:index.php');
  } else {
    header('Location:login.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <title>ウェブ掲示板</title>
</head>

<body>
  <section class="container mb-5 mt-5" style="padding:0; border: solid 1px #ccc;">
    <h2 class="pl-5 bg-info text-white pt-3 pb-3">管理画面</h2>
    <div class="mt-3 pl-5 pb-5 pr-5">
      <p class="mt-5">ようこそ管理画面へ</p>
      <a href="" class="btn btn-md btn-info mb-4">全投稿を削除する</a>
    </div>
  </section>
</body>

</html>
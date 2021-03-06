<?php
require_once '../../dbconnect.php';
require_once '../functions.php';
session_start();

//ログインしていないときはログインページに戻る
if (!$_SESSION['admin_login']) {
    header('Location:login.php');
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

//数字以外の値がパラメータで送られてきた場合、トップ画面に戻す
if (!preg_match('/^[0-9]+$/', $id)) {
    header('Location:index.php');
    exit();
}

//投稿の削除処理
if (isset($_POST["delete"])) {
    admin_delete($db, $id);
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
    <h2 class="pl-5 bg-info text-white pt-3 pb-3">投稿内容削除（ID:<?=$id?>)</h2>
    <div class="mt-3 pl-5 pb-4 pr-5">
      <form action="" method="post">
        <p class="mt-4 mb-4">投稿内容を削除してよろしいですか？</p>
        <input type="submit" value="削除する" name="delete" class="btn btn-md btn-info">
        <a href="index.php" class="btn btn-info btn-md ml-3">キャンセルして戻る</a>
      </form>
    </div>
  </section>
</body>

</html>
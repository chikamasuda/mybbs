<?php
require_once '../dbconnect.php';
require_once 'functions.php';

$name = (string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$title = (string) filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$text = (string) $_POST['text'];
$delete_key = (string) filter_input(INPUT_POST, 'delete_key', FILTER_SANITIZE_SPECIAL_CHARS);
$ip_address = $_SERVER['REMOTE_ADDR'];
$error = [];

//バリデーション
if (isset($_POST['insert'])) {
    if (empty($name)) {
        $error['name'] = 'blank';
    }
    if (empty($title)) {
        $error['title'] = 'blank';
    }
    if (empty($text)) {
        $error['text'] = 'blank';
    }
    if (empty($delete_key)) {
        $error['delete_key'] = 'blank';
    }

    define("stringMaxSize", 255);
    if (strlen($name) > stringMaxSize) {
        $error['name'] = 'length';
    }
    if (strlen($title) > stringMaxSize) {
        $error['title'] = 'length';
    }
    if (strlen($delete_key) > stringMaxSize) {
        $error['delete_key'] = 'length';
    }

    //投稿データをDBに追加
    if (empty($error)) {
        try {
            $db->beginTransaction();
            $sql = $db->prepare("INSERT INTO posts SET name=:name, title=:title, text=:text, ip_address=:ip_address, delete_key=:delete_key, created_at=now()");

            $sql->bindValue(':name', $name, PDO::PARAM_STR);
            $sql->bindValue(':title', $title, PDO::PARAM_STR);
            $sql->bindValue(':text', $text, PDO::PARAM_STR);
            $sql->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
            $sql->bindValue(':delete_key', $delete_key, PDO::PARAM_STR);
            $sql->execute();
            $db->commit();
            header('Location:post.php');
            exit();
        } catch (PDOException $e) {
            echo 'DB接続エラー：' . $e->getMessage();
            $db->rollBack();
        }
    }
}
//ページネーション
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($page)) {
    $page = 1;
}

$page = max($page, 1);

try {
    $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts WHERE delete_flag=0');
    $cnt = $counts->fetch();
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}

if ($cnt === 0) {
    $page = 1;
}

$maxPage = ceil($cnt['cnt'] / 20);
$page = min($page, $maxPage);
$start = ($page - 1) * 20;

//投稿内容一覧のデータを取得
try {
    $articles = $db->prepare('SELECT * FROM posts WHERE delete_flag=0 ORDER BY created_at DESC LIMIT ?, 20');
    $articles->bindParam(1, $start, PDO::PARAM_INT);
    $articles->execute();
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <title>ウェブ掲示板</title>
</head>

<body>
  <section class="container mb-5 mt-5 pb-5" style="padding:0; border: solid 1px #ccc;">
    <h1 class="pl-5 bg-primary text-white pt-3 pb-3">掲示板</h1>
    <div class="mt-3 pl-5 pb-5 pr-5">
      <form action="" method="post">
        <label class="mt-3">投稿者名</label><input type="text" name="name" class="form-control" id="name">
        <label class="mt-3">タイトル</label><input type="text" name="title" class="form-control" id="title">
        <label class="mt-3">本文</label><textarea name="text" class="form-control"></textarea><br>
        <label>削除キー</label><input type="text" name="delete_key" class="form-control">
        <input type="submit" value="投稿する" class="btn btn-md btn-primary mt-4" id="submit" name="insert">
      </form>
    </div>

    <div class="pl-5 pr-5">
      <h2 class="mt-4 mb-4">投稿内容一覧</h2>
      <?php foreach ($articles as $article): ?>
        <?php if ((int) $article['delete_flag'] === 0): ?>
          <div class="d-flex mt-4">
            <label class="font-weight-bold">ID：</label>
            <p><?=h($article['id']);?></p>
            <label class="font-weight-bold ml-4">投稿者名：</label>
            <p><?=h($article['name']);?></p>
            <label class="font-weight-bold ml-4">投稿日時：</label>
            <p><?=h($article['created_at']);?></p>
          </div>
          <p><span class="font-weight-bold">タイトル：</span><?=h($article['title']);?></p>
          <p><span class="font-weight-bold">本文：</span><?=nl2br(h($article['text']));?></p>
          <div class="d-flex justify-content-end post-border">
            <a href="delete.php?id=<?=h($article['id']);?>" class="btn btn-sm btn-outline-dark mb-4" name="delete_btn">削除する</a>
          </div>
        <?php endif;?>
      <?php endforeach;?>

    <ul class="mt-5 d-flex justify-content-center" style="list-style:none;">
      <?php if ($page > 1): ?>
        <li><a class="page-link" href="index.php?page=<?=($page - 1);?>">前へ</a></li>
      <?php endif;?>
      <?php for ($i = 1; $i <= $maxPage; $i++): ?>
        <li class="page-item <?php if ($page == $i) {echo 'active';}?>">
          <a class="page-link" href="index.php?page=<?=$i;?>"> <?=$i;?> </a>
        </li>
      <?php endfor;?>
      <?php if ($page < $maxPage): ?>
        <li><a class="page-link" href="index.php?page=<?=($page + 1)?>">次へ</a></li>
      <?php endif;?>
    </ul>
    </div>
  </section>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
  　　$(function() {
          $("#submit").click(function() {
              if($("#name").val() == '') {
                $("<p class='text-danger'>必須項目が未記入です。</p>").insertAfter("input #name")
              }
          });
　    });
  </script>
</body>
</html>
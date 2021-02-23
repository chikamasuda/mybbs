<?php
require 'dbconnect.php';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
$text = $_POST['text'];
$delete_key = filter_input(INPUT_POST, 'delete_key', FILTER_SANITIZE_SPECIAL_CHARS);
$ip_address = $_SERVER['REMOTE_ADDR'];
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

//バリデーション
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (strlen($name) > 255) {
        $error['name'] = 'length';
    }
    if (strlen($title) > 255) {
        $error['title'] = 'length';
    }
    if (strlen($delete_key) > 255) {
        $error['delete_key'] = 'length';
    }

    //投稿データをDBに追加
    if (empty($error)) {
        $sql = $db->prepare("INSERT INTO posts SET name=:name, title=:title, text=:text, ip_address=:ip_address, delete_key=:delete_key, created_at=now()");

        $sql->bindValue(':name', $name, PDO::PARAM_STR);
        $sql->bindValue(':title', $title, PDO::PARAM_STR);
        $sql->bindValue(':text', $text, PDO::PARAM_STR);
        $sql->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
        $sql->bindValue(':delete_key', $delete_key, PDO::PARAM_STR);
        $sql->execute();

        header('Location:post.php');
        exit();
    }
}

//ページネーション
$page = $_REQUEST['page'];

if ($page == '') {
    $page = 1;
}

$page = max($page, 1);
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts WHERE delete_flag=0');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 20);
$page = min($page, $maxPage);
$start = ($page - 1) * 20;

//投稿内容一覧のデータを取得
$articles = $db->prepare('SELECT * FROM posts WHERE delete_flag=0 ORDER BY created_at DESC LIMIT ?, 20');
$articles->bindParam(1, $start, PDO::PARAM_INT);
$articles->execute();

//htmlspecialchars関数
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
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
  <section class="container mb-5 mt-5 pb-5">
    <h1 class="pl-5 bg-primary text-white pt-3 pb-3">掲示板</h1>
    <!-- 投稿フォーム -->
    <div class="mt-3 pl-5 pb-5 pr-5">
      <form action="" method="post">
        <label class="mt-3">投稿者名</label><input type="text" name="name" class="form-control" id="name">
        <!-- 投稿者名エラー表示 -->
        <?php if ($error['name'] === 'blank'): ?>
          <p class="text-danger">投稿者名が未記入です。</p>
        <?php endif;?>
        <?php if ($error['name'] === 'length'): ?>
          <p class="text-danger">投稿者名は255文字以内で入力してください。</p>
        <?php endif;?>
        <!-- 投稿者名エラー表示ここまで -->
        <label class="mt-3">タイトル</label><input type="text" name="title" class="form-control" id="title">
        <!-- タイトルエラー表示 -->
        <?php if ($error['title'] === 'blank'): ?>
          <p class="text-danger">タイトルが未記入です。</p>
        <?php endif;?>
        <?php if ($error['title'] === 'length'): ?>
          <p class="text-danger">タイトルは255文字以内で入力してください。</p>
        <?php endif;?>
        <!-- タイトルエラー表示ここまで -->
        <label class="mt-3">本文</label><textarea name="text" class="form-control"></textarea><br>
        <!-- 本文エラー表示 -->
        <?php if ($error['text'] === 'blank'): ?>
          <p class="text-danger">本文が未記入です。</p>
        <?php endif;?>
        <!-- 本文エラー表示ここまで -->
        <label>削除キー</label><input type="text" name="delete_key" class="form-control">
        <!-- 削除キーエラー表示 -->
        <?php if ($error['delete_key'] === 'blank'): ?>
          <p class="text-danger">削除キーが未記入です。</p>
        <?php endif;?>
        <?php if ($error['delete_key'] === 'length'): ?>
          <p class="text-danger">削除キーは255文字以内で入力してください。</p>
        <?php endif;?>
        <!-- 削除キーエラー表示ここまで -->
        <input type="submit" value="投稿する" class="btn btn-md btn-primary mt-4" id="submit">
      </form>
    </div>
    <!-- 投稿フォームここまで -->
    <!-- 投稿内容一覧 -->
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
            <a href="delete.php?id=<?=h($article['id']);?>" class="btn btn-sm btn-outline-dark mb-4">削除する</a>
          </div>
        <?php endif;?>
      <?php endforeach;?>
    <!-- 投稿内容一覧ここまで -->
    <!-- ページネーション -->
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
    <!-- ページネーションここまで -->
    </div>
  </section>
</body>

</html>
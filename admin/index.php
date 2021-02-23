<?php
require '../dbconnect.php';
session_start();

//ログインしていないときはログインページに戻る
if ($_SESSION['admin_login'] !== true) {
    header('Location:login.php');
    exit();
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
  <section class="container mb-5 mt-5">
    <h2 class="pl-5 bg-info text-white pt-3 pb-3">管理画面</h2>
    <div class="mt-3 pl-5 pb-3 pr-5 d-flex mt-5">
      <h3>ようこそ管理画面へ</h3>
      <form action="logout.php" method="get">
        <input type="submit" value="ログアウト" class="btn btn-info btn-md ml-5">
      </form>
    </div>
    <!-- 投稿内容一覧 -->
    <div class="pl-5 pr-5">
      <h4 class="mt-4 mb-4">投稿内容一覧</h4>
      <?php foreach ($articles as $article): ?>
        <?php if ((int) $article['delete_flag'] === 0): ?>
          <div class="d-flex mt-4">
            <label class="font-weight-bold">ID：</label>
            <p><?=h($article['id']);?></p>
            <label class="font-weight-bold ml-4">投稿者名：</label>
            <p><?=h($article['name']);?></p>
            <label class="font-weight-bold ml-4">投稿日時：</label>
            <p><?=h($article['created_at']);?></p>
            <label class="font-weight-bold ml-4">IPアドレス：</label>
            <p><?=h($article['ip_address']);?></p>
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
    <ul class="mt-5 mb-5 d-flex justify-content-center">
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
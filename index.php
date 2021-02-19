<?php
require('dbconnect.php');

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

$start = ($page -1) * 20;

//投稿内容一覧のデータを取得
$articles = $db->prepare('SELECT * FROM posts WHERE delete_flag=0 ORDER BY created_at DESC LIMIT ?, 20');
$articles->bindParam(1, $start, PDO::PARAM_INT);
$articles->execute();

//htmlspecialchars関数
if( ! function_exists('h') ) {
  function h($s) {
    echo htmlspecialchars($s, ENT_QUOTES, "UTF-8");
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
  <section class="container mb-5 mt-5 pb-5" style="padding:0; border: solid 1px #ccc;">
    <h1 class="pl-5 bg-primary text-white pt-3 pb-3">掲示板</h1>
    <!-- 投稿フォーム -->
    <div class="mt-3 pl-5 pb-5 pr-5">
      <form action="post.php" method="post">
        <label class="mt-3">投稿者名</label><input type="text" name="name" class="form-control" id="name">
        <label class="mt-3">タイトル</label><input type="text" name="title" class="form-control" id="title">
        <label class="mt-3">本文</label><textarea name="text" class="form-control"></textarea><br>
        <label>削除キー</label><input type="text" name="delete_key" class="form-control mb-4">
        <input type="submit" value="投稿する" class="btn btn-md btn-primary" id="submit">
      </form>
    </div>
    <!-- 投稿フォームここまで -->
    <!-- 投稿内容一覧 -->
    <div class="pl-5 pr-5">
      <h2 class="mt-4 mb-4">投稿内容一覧</h2>
      <?php foreach ($articles as $article): ?>
        <?php if((int)$article['delete_flag'] === 0): ?>
          <div class="d-flex mt-4">
            <label class="font-weight-bold">ID：</label>
            <p><?= h($article['id']); ?></p>
            <label class="font-weight-bold ml-4">投稿者名：</label>
            <p><?= h($article['name']); ?></p>
            <label class="font-weight-bold ml-4">投稿日時：</label>
            <p><?= h($article['created_at']); ?></p>
          </div>
          <p><span class="font-weight-bold">タイトル：</span><?= h($article['title']); ?></p>
          <p><span class="font-weight-bold">本文：</span><?= h($article['text']); ?></p>
          <div class="d-flex justify-content-end" style="border-bottom: solid 1px #ced4da; margin:0;">
            <a href="delete.php?id=<?=h($article['id']); ?>" class="btn btn-sm btn-outline-dark mb-4">削除する</a>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <!-- 投稿内容一覧ここまで -->
    <!-- ページネーション -->
    <ul class="mt-5 d-flex justify-content-center" style="list-style:none;">
      <?php if($page > 1): ?>
        <li><a class="page-link" href="index.php?page=<?= ($page-1); ?>">前へ</a></li>
      <?php endif; ?>
      <?php for($i = 1; $i <= $maxPage; $i++ ): ?>
        <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
          <a class="page-link" href="index.php?page=<?= $i; ?>"> <?= $i; ?> </a>
        </li>
      <?php endfor; ?>
      <?php if($page < $maxPage): ?>
        <li><a class="page-link" href="index.php?page=<?= ($page+1) ?>">次へ</a></li>
      <?php endif; ?>
    </ul>
    <!-- ページネーションここまで -->
    </div>
  </section>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="js/main.js"></script>
</body>

</html>
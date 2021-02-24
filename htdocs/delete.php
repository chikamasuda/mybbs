<?php
require_once '../dbconnect.php';

$id = $_REQUEST['id'];
$delete_key = filter_input(INPUT_POST, 'delete_key', FILTER_SANITIZE_SPECIAL_CHARS);

//数字以外の値がパラメータで送られてきた場合、トップ画面に戻す
if (!preg_match('/^[0-9]+$/', $id)) {
    header('Location:index.php');
    exit();
}

//index.phpでの投稿内容のデータを取得

$articles = $db->prepare('SELECT * FROM posts WHERE id=?');
$articles->execute(array($id));
$article = $articles->fetch();

//投稿の削除処理
if ($article['delete_key'] == $delete_key) {
    $del = $db->prepare('UPDATE posts SET delete_flag=1 WHERE id=?');
    $del->execute(array($id));
    header('Location:index.php');
    exit();
}

//バリデーション
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($delete_key)) {
        $error['delete_key'] = 'blank';
    }
    if (!empty($delete_key) && $article['delete_key'] !== $delete_key) {
        $error['delete_key'] = 'mismatch';
    }
    if (strlen($delete_key) > 255) {
        $error['delete_key'] = 'length';
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
    <h2 class="pl-5 bg-primary text-white pt-3 pb-3">投稿内容削除（ID:<?=$id?>)</h1>
    <div class="mt-3 pl-5 pb-5 pr-5">
      <form action="" method="post">
        <label class="mt-3">削除キー</label><input type="text" name="delete_key" class="form-control mb-4">
        <!-- エラー処理 -->
        <?php if ($error['delete_key'] === 'mismatch'): ?>
          <p class="text-danger">削除キーが違います。</p>
        <?php endif;?>
        <?php if ($error['delete_key'] === 'length'): ?>
          <p class="text-danger">削除キーは255文字以内で記入してください。</p>
        <?php endif;?>
        <?php if ($error['delete_key'] === 'blank'): ?>
          <p class="text-danger">削除キーが未記入です。</p>
        <?php endif;?>
        <!-- エラー処理おわり -->
        <input type="submit" value="削除する" class="btn btn-md btn-primary">
      </form>
    </div>
  </section>
</body>

</html>
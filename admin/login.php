<?php
require '../dbconnect.php';

$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

//ハッシュ化したパスワードをDBに保管
// $hash = password_hash($password, PASSWORD_DEFAULT);
// echo $hash;

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

//管理画面ユーザーの情報を取得
$admin_users = $db->prepare('SELECT * FROM admin WHERE id=?');
$admin_users->execute(array($id));
$admin_user = $admin_users->fetch();

//バリデーション、エラーがなかったらログイン認証
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($id)) {
        $error['id'] = 'blank';
    }
    if (!empty($id) && $admin_user['id'] !== $id) {
        $error['id'] = 'mismatch';
    }
    if (empty($password)) {
        $error['password'] = 'blank';
    }
    if (!empty($password) && !password_verify($password, $admin_user['password'])) {
        $error['password'] = 'mismatch';
    }

    if (empty($error)) {
        if ($admin_user['id'] === $id && password_verify($password, $admin_user['password'])) {
            session_start();
            $_SESSION['admin_login'] = true;
            header('Location:index.php');
            exit();
        }
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
  <title>ウェブ掲示板 管理画面</title>
</head>

<body>
  <section class="row  mb-5 mt-5 pb-5" style="padding:0;">
  　<div class="col-md-5 mx-auto">
      <h2 class="pl-5 bg-info text-white pt-3 pb-3" style="margin:0;">掲示板　管理画面</h1>
      <div class="pt-3 pl-5 pr-5" style="border: solid 1px #ccc;">
        <form action="" method="post">
          <label class="mt-3">ID</label><input type="text" name="id" class="form-control">
          <!-- IDエラー表示 -->
          <?php if ($error['id'] === 'blank'): ?>
            <p class="text-danger">IDが未記入です。</p>
          <?php endif;?>
          <?php if ($error['id'] === 'mismatch'): ?>
            <p class="text-danger">IDに誤りがあります。</p>
          <?php endif;?>
          <!-- IDエラー表示ここまで -->
          <label class="mt-3">パスワード</label><input type="password" name="password" class="form-control">
          <!-- パスワードエラー表示 -->
          <?php if ($error['password'] === 'blank'): ?>
            <p class="text-danger">パスワードが未記入です。</p>
          <?php endif;?>
          <?php if ($error['password'] === 'mismatch'): ?>
            <p class="text-danger">パスワードに誤りがあります。</p>
          <?php endif;?>
          <!-- パスワードエラー表示ここまで -->
          <input type="submit" value="送信" class="btn btn-md btn-info mt-4 mb-4 pl-4 pr-4" id="login-submit">
        </form>
      </div>
    </div>
  </section>
</body>

</html>

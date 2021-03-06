<?php
require_once '../../dbconnect.php';

$password = (string) filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

//ハッシュ化したパスワードをDBに保管
// $hash = password_hash($password, PASSWORD_DEFAULT);
// echo $hash;

$id = (int) filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$error = [];

//管理画面ユーザーの情報を取得
try {
    $admin_users = $db->prepare('SELECT * FROM admin WHERE id=:id');
    $admin_users->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $admin_users->execute();
    $admin_user = $admin_users->fetch();
    !$admin_user ? $admin_user = array("id" => "", "password" => "") : "";
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}

//バリデーション、エラーがなかったらログイン認証
if (isset($_POST['login'])) {
    if (empty($id)) {
        $error['id'] = 'blank';
    } else if ($admin_user['id'] === $id) {
        $error['id'] = 'mismatch';
    }

    if (empty($password)) {
        $error['password'] = 'blank';
    } else if (!password_verify($password, $admin_user['password'])) {
        $error['password'] = 'mismatch';
    }

    if (empty($error)) {
        session_start();
        $_SESSION['admin_login'] = true;
        header('Location:index.php');
        exit();
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
          <?php if ($error['id'] === 'blank'): ?>
            <p class="text-danger">IDが未記入です。</p>
          <?php endif;?>
          <?php if ($error['id'] === 'mismatch'): ?>
            <p class="text-danger">IDもしくはパスワードに誤りがあります。</p>
          <?php endif;?>
          <label class="mt-3">パスワード</label><input type="password" name="password" class="form-control">
          <?php if ($error['password'] === 'blank'): ?>
            <p class="text-danger">パスワードが未記入です。</p>
          <?php endif;?>
          <?php if ($error['password'] === 'mismatch'): ?>
            <p class="text-danger">IDもしくはパスワードに誤りがあります。</p>
          <?php endif;?>
          <input type="submit" value="送信" name="login" class="btn btn-md btn-info mt-4 mb-4 pl-4 pr-4" id="login-submit">
        </form>
      </div>
    </div>
  </section>
</body>

</html>
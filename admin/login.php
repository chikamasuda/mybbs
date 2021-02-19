<?php
require('../dbconnect.php');

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
        <form action="index.php" method="post">
          <label class="mt-3">ID</label><input type="text" name="id" class="form-control">
          <label class="mt-3">パスワード</label><input type="text" name="password" class="form-control">
          <input type="submit" value="送信" class="btn btn-md btn-info mt-4 mb-4 pl-4 pr-4" id="login-submit">
        </form>
      </div>
    </div>
  </section>
</body>

</html>

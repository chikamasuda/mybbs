<?php

//htmlspecialchars関数
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//掲示板の投稿を削除
function delete($db, $id, $delete_key)
{
    try {
        $db->beginTransaction();
        $del = $db->prepare('UPDATE posts SET delete_flag=1 WHERE id=? AND `delete_key`=?');
        $del->bindValue(1, $id, PDO::PARAM_INT);
        $del->bindValue(2, $delete_key);
        $del->execute();
        $db->commit();
        header('Location:index.php');
        exit();
    } catch (PDOException $e) {
        echo 'DB接続エラー：' . $e->getMessage();
        $db->rollBack();
    }
}

//管理画面上の投稿を削除
function admin_delete($db, $id)
{
    try {
        $db->beginTransaction();
        $del = $db->prepare('UPDATE posts SET delete_flag=1 WHERE id=?');
        $del->bindValue(1, $id, PDO::PARAM_INT);
        $del->execute();
        $db->commit();
        header('Location:index.php');
        exit();
    } catch (PDOException $e) {
        echo 'DB接続エラー：' . $e->getMessage();
        $db->rollBack();
    }
}

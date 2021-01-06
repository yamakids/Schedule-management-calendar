<?php

require_once('config.php');
require_once('functions.php');

session_start();

$me = $_SESSION['me'];
$myname = $me['name'];

$dbh = connectDb();
$i = $_POST['i'];

// タスク更新処理
$sql = "update {$myname} set title = :title, modified = now() where time = '{$i}' and id = :id";
$stmt = $dbh->prepare($sql);
$stmt->execute(array(
    ":id" => (int)$_POST['id'],
    ":title" => $_POST['title']
));

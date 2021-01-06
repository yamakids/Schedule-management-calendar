<?php

require_once('config.php');
require_once('functions.php');

session_start();

$me = $_SESSION['me'];
$myname = $me['name'];

$dbh = connectDb();
$i = $_POST['i'];

// タスクチェック処理
$sql = "update {$myname} set type = if(type='done', 'notyet', 'done'), modified = now() where time = '{$i}' and id = :id";
$stmt = $dbh->prepare($sql);
$stmt->execute(array("id" => (int)$_POST['id']));

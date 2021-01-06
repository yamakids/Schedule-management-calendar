<?php

require_once('config.php');
require_once('functions.php');

session_start();

$me = $_SESSION['me'];
$myname = $me['name'];

$dbh = connectDb();
$id = $_POST['id'];
parse_str($_POST['task']); // $task

// タスク並び替え
foreach ($task as $key => $val) {
    $sql = "update {$myname} set seq = :seq where time = '{$id}' and id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(
        ":seq" => $key,
        ":id" => $val
    ));
}

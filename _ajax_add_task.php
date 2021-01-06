<?php

require_once('config.php');
require_once('functions.php');

session_start();

$me = $_SESSION['me'];
$myname = $me['name'];

$id = $_POST['id'];
$dbh = connectDb();

$sql = "select count(*) from {$myname} where time = '{$id}' and type != 'deleted'";
$count = $dbh->query($sql)->fetchColumn();

// タスク追加処理
if($count==0){
  global $myname;
  global $id;
  global $dbh;
  $sql = "insert into {$myname}
          (time,seq, title, created, modified)
          values
          (:id, 1, :title, now(), now())";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(
      ":id" => $id,
      ":title" => $_POST['title']
  ));
  echo $dbh->lastInsertId();
}else{
  global $myname;
  global $id;
  global $dbh;
  $sql = "select max(seq)+1 from {$myname} where time = '{$id}' and type != 'deleted'";
  $seq = $dbh->query($sql)->fetchColumn();

  $sql = "insert into {$myname}
          (time, seq, title, created, modified)
          values
          (:id, :seq, :title, now(), now())";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(
      "id" => $id,
      ":seq" => $seq,
      ":title" => $_POST['title']
  ));
  echo $dbh->lastInsertId();
}

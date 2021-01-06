<?php

// DB接続
function connectDb() {
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

// エスケイプ
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

// トークンセット
function setToken() {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

// トークンチェック
function checkToken() {
    if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])) {
        echo "不正なPOSTが行われました！";
        exit;
    }
}

// 名前存在確認
function nameExists($name, $dbh) {
    $sql = "select * from users where name = :name limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":name" => $name));
    $user = $stmt->fetch();
    return $user ? true : false;
}

// eメールアドレス存在確認
function emailExists($email, $dbh) {
    $sql = "select * from users where email = :email limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":email" => $email));
    $user = $stmt->fetch();
    return $user ? true : false;
}

// パスワードセキュリティー対策
function getSha1Password($s) {
    return (sha1(PASSWORD_KEY.$s));
}

// マイネーム取得
function myname(){
  return $_SESSION['me']['name'];
}

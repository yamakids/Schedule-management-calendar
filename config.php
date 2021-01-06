<?php

// DB接続準備
define('DSN', 'mysql:host=us-cdbr-east-02.cleardb.com;dbname=heroku_badea93177a7881');
define('DB_USER', 'b6ae28015f843b');
define('DB_PASSWORD', '332f7257');

define('SITE_URL', 'https://stormy-temple-00803.herokuapp.com/');
define('PASSWORD_KEY', '**********');

// エラー表示
error_reporting(E_ALL & ~E_NOTICE);

// セッションを使用できるページを限定
session_set_cookie_params(0, 'https://stormy-temple-00803.herokuapp.com/');

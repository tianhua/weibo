<?php
session_start();
  include_once('DoubanOAuth.php');
  include_once('../config.php');
  $douban = new DoubanOAuth(array(
    'key' => DB_KEY,
    'secret' => DB_SECRET,
    'redirect_url' => DB_REDIRECT,
  ));

  $result = $douban->getAccessToken($_GET['code']);

 // var_dump($_GET['state'], $result);
var_dump($result);
$_SESSION['db_token'] = $result['access_token'];
var_dump($_SESSION['db_token']);
  //$result = $douban->get('user/~me');

 // var_dump($result);

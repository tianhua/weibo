<?php
header('Content-Type: text/html; charset=UTF-8');

define( "WB_AKEY" , '3721470456' );
define( "WB_SKEY" , '293a498eba25332707f0b4b35f2507b3' );
define( "WB_CALLBACK_URL" , 'http://localhost.weibo.com:8080/weibo/callback.php'  );
define('DB_KEY', '07f215538fc60df617d63ffaaf5229e2');
define('DB_SECRET', '978c31b7f8f7046b');
define('DB_REDIRECT', 'http://localhost.weibo.com:8080/weibo/douban/callback.php');

define('DB_SCOPE', 'douban_basic_common,book_basic_r,book_basic_w');
define('DB_STATE', 'Something');
//define( "WB_CALLBACK_URL" , 'https://api.weibo.com/oauth2/default.html'  );
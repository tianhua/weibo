<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
include_once('douban/DoubanOAuth.php');

$douban = new DoubanOAuth(array(
		'key' => DB_KEY,
		'secret' => DB_SECRET,
		'redirect_url' => DB_REDIRECT,
));

$db_url = $douban->getAuthorizeURL(DB_SCOPE, DB_STATE);
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="71163f0a2a86dc96" />
<title>Demo - Powered by Sina App Engine</title>
</head>

<body>

    <p><a href="<?=$code_url?>"><img src="weibo_login.png"  border="0" /></a></p>
    <p><a href="<?=$db_url?>">登陆豆瓣</a></p>
</body>
</html>

<?php
session_start ();

include_once ('config.php');
include_once ('saetv2.ex.class.php');

$o = new SaeTOAuthV2 ( WB_AKEY, WB_SKEY );

$code_url = $o->getAuthorizeURL ( WB_CALLBACK_URL );
if (isset ( $_POST ['submitBtn'] )) {
	$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $_SESSION ['token'] ['access_token'] );
	$content = htmlentities ( $_POST ['content'] );
	$ret = $c->update ( $content );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="71163f0a2a86dc96" />
<title>Demo - Powered by Sina App Engine</title>
<style>
body{max-width:1024px;margin:0 auto;}
.clear {
	clear: both;
}

.input {
	margin-top: 5px;
}

.input .lable {
	float: left;
	font-weight: bold;
	text-align: right; margin-right : 10px;
	min-width: 200px;
	min-width: 200px;
	margin-right: 10px;
}

.input .field {
	float: left;
}
</style>
</head>

<body>
	<form method="post">
		<div class='input'>
			<div class='lable'>title</div>
			<div class='field'>
				<input type='text' name='title'></input>
			</div>
			<div class='clear'></div>
		</div>
		<div class='input'>
			<div class='lable'>send to</div>
			<div class='field'>
				<label>sina<input type='checkbox' name='sina'></input></label> <label>renren
					<input type='checkbox' name='renren'></input>
				</label>
			</div>
			<div class='clear'></div>
		</div>
		<div class='input'>
			<div class='lable'>content</div>
			<div class='field'>
				<textarea name='content' rows="15" cols="70"></textarea>
			</div>
			<div class='clear'></div>
		</div>
		<div style='text-align:center;'>
			<input name='submitBtn' type='submit' value='submit'></input>
		</div>

	</form>

</body>
</html>

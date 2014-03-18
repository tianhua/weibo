<?php
session_start ();

include_once ('config.php');
include_once ('saetv2.ex.class.php');
include_once ('douban/DoubanOAuth.php');
$o = new SaeTOAuthV2 ( WB_AKEY, WB_SKEY );

if (isset ( $_POST ['submitBtn'] )) {
	var_dump ( $_POST );
	if (isset($_POST ['sina']) && $_POST ['sina'] == 'on') {
		$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $_SESSION ['token'] ['access_token'] );
		$content = htmlentities ( $_POST ['content'] );
		$ret = $c->update ( $content );
	}
	if (isset($_POST ['forum']) && $_POST ['forum'] == 'on') {
	
		$content = htmlentities ( $_POST ['content'] );
		$fid = 44;
		//$mysqli = new mysqli('198.46.147.35', 'sa', 'my_password', 'my_db');
		$mysqli = new mysqli('localhost', 'root', 'goumao', 'oc');
		/*
		 * This is the "official" OO way to do it,
		* BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
		*/
		if ($mysqli->connect_error) {
			die('Connect Error (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		/*
		 * Use this instead of $connect_error if you need to ensure
		* compatibility with PHP versions prior to 5.2.9 and 5.3.0.
		*/
		if (mysqli_connect_error()) {
			die('Connect Error (' . mysqli_connect_errno() . ') '
					. mysqli_connect_error());
		}
		
		//echo 'Success... ' . $mysqli->host_info . "\n";
		$query = "insert into oc_banner ( name, status) values ('test', 1 )";
		$result = $mysqli->query($query);
		$mysqli->close();
	 var_dump($result);
	}
}

$douban = new DoubanOAuth ( array ('key' => DB_KEY, 'secret' => DB_SECRET, 'access_token' => $_SESSION ['db_token'] ) );
/*
 * $result = $douban->get('book/search', array( 'q' => 'AA创作', ));
 */

/* $result = $douban->post('notes', array(
		'title' => 'hello',
		'can_reply' => false,
		'content' => 'hello a',
		'privacy' => 'public'
)); */

/* $result = $douban->get('note/people_notes/72318148/guesses', array(

));  */

/* $result = $douban->delete('note/329694366', array(
)); */
$db_user = $douban->get ( 'user/~me' );

/*
 * $result = $douban->get('note/user_created/' . $db_user['id'], array( //'q' =>
 * 'AA创作', ));
 */
// var_dump($douban->http_code, $result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="71163f0a2a86dc96" />
<title>Admin - Powered by 土豪金</title>
<style>
body {
	max-width: 1024px;
	margin: 0 auto;
}

.clear {
	clear: both;
}

.input {
	margin-top: 5px;
}

.input .lable {
	float: left;
	font-weight: bold;
	text-align: right;
	margin-right: 10px;
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
				</label> <label>论坛 <input type='checkbox' name='forum'></input>
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
		<div style='text-align: center;'>
			<input name='submitBtn' type='submit' value='submit'></input>
		</div>

	</form>

</body>
</html>

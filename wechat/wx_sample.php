<?php
/**
  * wechat php test
  */
include("wechat.class.php");
//define your token
define("TOKEN", "yushukeji"); //thjcj
//$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();

/* $options = array(
		'token'=>'thjcj', //填写你设定的key
		'appid'=>'wx5007aebe43f266ac', //填写高级调用功能的app id, 请在微信开发模式后台查询
		'appsecret'=>'9a1783aac69883afbe0012978fe40155', //填写高级调用功能的密钥
		//'debug'=>true,
		//'logcallback'=>'logdebug',
		// 'partnerid'=>'88888888', //财付通商户身份标识，支付权限专用，没有可不填
		// 'partnerkey'=>'', //财付通商户权限密钥Key，支付权限专用
		//'paysignkey'=>'' //商户签名密钥Key，支付权限专用
); */
$options = array(
		'token'=>'yushukeji', //填写你设定的key
		'appid'=>'wx81c05a579d2ab456', //填写高级调用功能的app id, 请在微信开发模式后台查询
		'appsecret'=>'fe2290189dc4815c4c84dcb4ecc3d41f', //填写高级调用功能的密钥

);
$weObj = new Wechat($options);
//$weObj->valid();
//$menu = $weObj->getMenu();
//var_dump($menu);
$weObj->deleteMenu();
$newmenu =  array(
    		"button"=>
    			array(
    				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
					array('type'=>'view','name'=>'查看我们','url'=>'http://www.baidu.com'),
    				)
    		);
  $result = $weObj->createMenu($newmenu);

var_dump($result);
$type = $weObj->getRev()->getRevType();
switch($type) {
	
	case Wechat::MSGTYPE_TEXT:
		$weObj->text("欢迎关注羽书科技公众账号, 你很有思想")->reply();
		exit;
		break;
	case Wechat::MSGTYPE_EVENT:
		//$weObj->text("正在响应")->reply();
		$keyArr = $weObj->getRev()->getRevEvent();
		if($keyArr && isset($keyArr['key']))
		{
			$key = $keyArr['key'];
			switch($key) {
				case 'MENU_KEY_NEWS':
					$weObj->text("羽书科技最新消息：No news is good news")->reply();
					break;
				default:break;
			}
		}
		break;
	case Wechat::MSGTYPE_IMAGE:
			$weObj->text("恭喜你 都会传图片了")->reply();
		break;
	default:
		$weObj->text("help info")->reply();
}

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
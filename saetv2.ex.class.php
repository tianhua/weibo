<?php
/**
 * PHP SDK for weibo.com (using OAuth2)
 * 
 * @author Elmer Zhang <freeboy6716@gmail.com>
 */

/**
 * @ignore
 */
class OAuthException extends Exception {
	// pass
}


/**
 * æ–°æµªå¾®å�š OAuth è®¤è¯�ç±»(OAuth2)
 *
 * æŽˆæ�ƒæœºåˆ¶è¯´æ˜Žè¯·å¤§å®¶å�‚è€ƒå¾®å�šå¼€æ”¾å¹³å�°æ–‡æ¡£ï¼š{@link http://open.weibo.com/wiki/Oauth2}
 *
 * @package sae
 * @author Elmer Zhang
 * @version 1.0
 */
class SaeTOAuthV2 {
	/**
	 * @ignore
	 */
	public $client_id;
	/**
	 * @ignore
	 */
	public $client_secret;
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * @ignore
	 */
	public $refresh_token;
	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "https://api.weibo.com/2/";
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent = 'Sae T OAuth2 v0.1';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;

	/**
	 * boundary of multipart
	 * @ignore
	 */
	public static $boundary = '';

	/**
	 * Set API URLS
	 */
	/**
	 * @ignore
	 */
	function accessTokenURL()  { return 'https://api.weibo.com/oauth2/access_token'; }
	/**
	 * @ignore
	 */
	function authorizeURL()    { return 'https://api.weibo.com/oauth2/authorize'; }

	/**
	 * construct WeiboOAuth object
	 */
	function __construct($client_id, $client_secret, $access_token = NULL, $refresh_token = NULL) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
	}

	/**
	 * authorizeæŽ¥å�£
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/Oauth2/authorize Oauth2/authorize}
	 *
	 * @param string $url æŽˆæ�ƒå�Žçš„å›žè°ƒåœ°å�€,ç«™å¤–åº”ç”¨éœ€ä¸Žå›žè°ƒåœ°å�€ä¸€è‡´,ç«™å†…åº”ç”¨éœ€è¦�å¡«å†™canvas pageçš„åœ°å�€
	 * @param string $response_type æ”¯æŒ�çš„å€¼åŒ…æ‹¬ code å’Œtoken é»˜è®¤å€¼ä¸ºcode
	 * @param string $state ç”¨äºŽä¿�æŒ�è¯·æ±‚å’Œå›žè°ƒçš„çŠ¶æ€�ã€‚åœ¨å›žè°ƒæ—¶,ä¼šåœ¨Query Parameterä¸­å›žä¼ è¯¥å�‚æ•°
	 * @param string $display æŽˆæ�ƒé¡µé�¢ç±»åž‹ å�¯é€‰èŒƒå›´: 
	 *  - default		é»˜è®¤æŽˆæ�ƒé¡µé�¢		
	 *  - mobile		æ”¯æŒ�html5çš„æ‰‹æœº		
	 *  - popup			å¼¹çª—æŽˆæ�ƒé¡µ		
	 *  - wap1.2		wap1.2é¡µé�¢		
	 *  - wap2.0		wap2.0é¡µé�¢		
	 *  - js			js-sdk ä¸“ç”¨ æŽˆæ�ƒé¡µé�¢æ˜¯å¼¹çª—ï¼Œè¿”å›žç»“æžœä¸ºjs-sdkå›žæŽ‰å‡½æ•°		
	 *  - apponweibo	ç«™å†…åº”ç”¨ä¸“ç”¨,ç«™å†…åº”ç”¨ä¸�ä¼ displayå�‚æ•°,å¹¶ä¸”response_typeä¸ºtokenæ—¶,é»˜è®¤ä½¿ç”¨æ”¹display.æŽˆæ�ƒå�Žä¸�ä¼šè¿”å›žaccess_tokenï¼Œå�ªæ˜¯è¾“å‡ºjsåˆ·æ–°ç«™å†…åº”ç”¨çˆ¶æ¡†æž¶
	 * @return array
	 */
	function getAuthorizeURL( $url, $response_type = 'code', $state = NULL, $display = NULL ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $url;
		$params['response_type'] = $response_type;
		$params['state'] = $state;
		$params['display'] = $display;
		return $this->authorizeURL() . "?" . http_build_query($params);
	}

	/**
	 * access_tokenæŽ¥å�£
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/OAuth2/access_token OAuth2/access_token}
	 *
	 * @param string $type è¯·æ±‚çš„ç±»åž‹,å�¯ä»¥ä¸º:code, password, token
	 * @param array $keys å…¶ä»–å�‚æ•°ï¼š
	 *  - å½“$typeä¸ºcodeæ—¶ï¼š array('code'=>..., 'redirect_uri'=>...)
	 *  - å½“$typeä¸ºpasswordæ—¶ï¼š array('username'=>..., 'password'=>...)
	 *  - å½“$typeä¸ºtokenæ—¶ï¼š array('refresh_token'=>...)
	 * @return array
	 */
	function getAccessToken( $type = 'code', $keys ) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		if ( $type === 'token' ) {
			$params['grant_type'] = 'refresh_token';
			$params['refresh_token'] = $keys['refresh_token'];
		} elseif ( $type === 'code' ) {
			$params['grant_type'] = 'authorization_code';
			$params['code'] = $keys['code'];
			$params['redirect_uri'] = $keys['redirect_uri'];
		} elseif ( $type === 'password' ) {
			$params['grant_type'] = 'password';
			$params['username'] = $keys['username'];
			$params['password'] = $keys['password'];
		} else {
			throw new OAuthException("wrong auth type");
		}

		$response = $this->oAuthRequest($this->accessTokenURL(), 'POST', $params);
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['error']) ) {
			$this->access_token = $token['access_token'];
			//$this->refresh_token = $token['refresh_token'];
		} else {
			throw new OAuthException("get access token failed." . $token['error']);
		}
		return $token;
	}

	/**
	 * è§£æž� signed_request
	 *
	 * @param string $signed_request åº”ç”¨æ¡†æž¶åœ¨åŠ è½½iframeæ—¶ä¼šé€šè¿‡å�‘Canvas URL postçš„å�‚æ•°signed_request
	 *
	 * @return array
	 */
	function parseSignedRequest($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$sig = self::base64decode($encoded_sig) ;
		$data = json_decode(self::base64decode($payload), true);
		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') return '-1';
		$expected_sig = hash_hmac('sha256', $payload, $this->client_secret, true);
		return ($sig !== $expected_sig)? '-2':$data;
	}

	/**
	 * @ignore
	 */
	function base64decode($str) {
		return base64_decode(strtr($str.str_repeat('=', (4 - strlen($str) % 4)), '-_', '+/'));
	}

	/**
	 * è¯»å�–jssdkæŽˆæ�ƒä¿¡æ�¯ï¼Œç”¨äºŽå’Œjssdkçš„å�Œæ­¥ç™»å½•
	 *
	 * @return array æˆ�åŠŸè¿”å›žarray('access_token'=>'value', 'refresh_token'=>'value'); å¤±è´¥è¿”å›žfalse
	 */
	function getTokenFromJSSDK() {
		$key = "weibojs_" . $this->client_id;
		if ( isset($_COOKIE[$key]) && $cookie = $_COOKIE[$key] ) {
			parse_str($cookie, $token);
			if ( isset($token['access_token']) && isset($token['refresh_token']) ) {
				$this->access_token = $token['access_token'];
				$this->refresh_token = $token['refresh_token'];
				return $token;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * ä»Žæ•°ç»„ä¸­è¯»å�–access_tokenå’Œrefresh_token
	 * å¸¸ç”¨äºŽä»ŽSessionæˆ–Cookieä¸­è¯»å�–tokenï¼Œæˆ–é€šè¿‡Session/Cookieä¸­æ˜¯å�¦å­˜æœ‰tokenåˆ¤æ–­ç™»å½•çŠ¶æ€�ã€‚
	 *
	 * @param array $arr å­˜æœ‰access_tokenå’Œsecret_tokençš„æ•°ç»„
	 * @return array æˆ�åŠŸè¿”å›žarray('access_token'=>'value', 'refresh_token'=>'value'); å¤±è´¥è¿”å›žfalse
	 */
	function getTokenFromArray( $arr ) {
		if (isset($arr['access_token']) && $arr['access_token']) {
			$token = array();
			$this->access_token = $token['access_token'] = $arr['access_token'];
			if (isset($arr['refresh_token']) && $arr['refresh_token']) {
				$this->refresh_token = $token['refresh_token'] = $arr['refresh_token'];
			}

			return $token;
		} else {
			return false;
		}
	}

	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($url, $parameters = array(), $multi = false) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multi );
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 *
	 * @return mixed
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {

		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}.{$this->format}";
	}

	switch ($method) {
		case 'GET':
			$url = $url . '?' . http_build_query($parameters);
			return $this->http($url, 'GET');
		default:
			$headers = array();
			if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
				$body = http_build_query($parameters);
			} else {
				$body = self::build_http_query_multi($parameters);
				$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
			}
			return $this->http($url, $method, $body, $headers);
	}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if ( isset($this->access_token) && $this->access_token )
			$headers[] = "Authorization: OAuth2 ".$this->access_token;

		if ( !empty($this->remote_ip) ) {
			if ( defined('SAE_ACCESSKEY') ) {
				$headers[] = "SaeRemoteIP: " . $this->remote_ip;
			} else {
				$headers[] = "API-RemoteIP: " . $this->remote_ip;
			}
		} else {
			if ( !defined('SAE_ACCESSKEY') ) {
				$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
			}
		}
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;

		if ($this->debug) {
			echo "=====post data======\r\n";
			var_dump($postfields);

			echo "=====headers======\r\n";
			print_r($headers);

			echo '=====request info====='."\r\n";
			print_r( curl_getinfo($ci) );

			echo '=====response====='."\r\n";
			print_r( $response );
		}
		curl_close ($ci);
		return $response;
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	/**
	 * @ignore
	 */
	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
}


/**
 * æ–°æµªå¾®å�šæ“�ä½œç±»V2
 *
 * ä½¿ç”¨å‰�éœ€è¦�å…ˆæ‰‹å·¥è°ƒç”¨saetv2.ex.class.php <br />
 *
 * @package sae
 * @author Easy Chen, Elmer Zhang,Lazypeople
 * @version 1.0
 */
class SaeTClientV2
{
	/**
	 * æž„é€ å‡½æ•°
	 * 
	 * @access public
	 * @param mixed $akey å¾®å�šå¼€æ”¾å¹³å�°åº”ç”¨APP KEY
	 * @param mixed $skey å¾®å�šå¼€æ”¾å¹³å�°åº”ç”¨APP SECRET
	 * @param mixed $access_token OAuthè®¤è¯�è¿”å›žçš„token
	 * @param mixed $refresh_token OAuthè®¤è¯�è¿”å›žçš„token secret
	 * @return void
	 */
	function __construct( $akey, $skey, $access_token, $refresh_token = NULL)
	{
		$this->oauth = new SaeTOAuthV2( $akey, $skey, $access_token, $refresh_token );
	}

	/**
	 * å¼€å�¯è°ƒè¯•ä¿¡æ�¯
	 *
	 * å¼€å�¯è°ƒè¯•ä¿¡æ�¯å�Žï¼ŒSDKä¼šå°†æ¯�æ¬¡è¯·æ±‚å¾®å�šAPIæ‰€å�‘é€�çš„POST Dataã€�Headersä»¥å�Šè¯·æ±‚ä¿¡æ�¯ã€�è¿”å›žå†…å®¹è¾“å‡ºå‡ºæ�¥ã€‚
	 *
	 * @access public
	 * @param bool $enable æ˜¯å�¦å¼€å�¯è°ƒè¯•ä¿¡æ�¯
	 * @return void
	 */
	function set_debug( $enable )
	{
		$this->oauth->debug = $enable;
	}

	/**
	 * è®¾ç½®ç”¨æˆ·IP
	 *
	 * SDKé»˜è®¤å°†ä¼šé€šè¿‡$_SERVER['REMOTE_ADDR']èŽ·å�–ç”¨æˆ·IPï¼Œåœ¨è¯·æ±‚å¾®å�šAPIæ—¶å°†ç”¨æˆ·IPé™„åŠ åˆ°Request Headerä¸­ã€‚ä½†æŸ�äº›æƒ…å†µä¸‹$_SERVER['REMOTE_ADDR']å�–åˆ°çš„IPå¹¶é�žç”¨æˆ·IPï¼Œè€Œæ˜¯ä¸€ä¸ªå›ºå®šçš„IPï¼ˆä¾‹å¦‚ä½¿ç”¨SAEçš„Cronæˆ–TaskQueueæœ�åŠ¡æ—¶ï¼‰ï¼Œæ­¤æ—¶å°±æœ‰å�¯èƒ½ä¼šé€ æˆ�è¯¥å›ºå®šIPè¾¾åˆ°å¾®å�šAPIè°ƒç”¨é¢‘çŽ‡é™�é¢�ï¼Œå¯¼è‡´APIè°ƒç”¨å¤±è´¥ã€‚æ­¤æ—¶å�¯ä½¿ç”¨æœ¬æ–¹æ³•è®¾ç½®ç”¨æˆ·IPï¼Œä»¥é�¿å…�æ­¤é—®é¢˜ã€‚
	 *
	 * @access public
	 * @param string $ip ç”¨æˆ·IP
	 * @return bool IPä¸ºé�žæ³•IPå­—ç¬¦ä¸²æ—¶ï¼Œè¿”å›žfalseï¼Œå�¦åˆ™è¿”å›žtrue
	 */
	function set_remote_ip( $ip )
	{
		if ( ip2long($ip) !== false ) {
			$this->oauth->remote_ip = $ip;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * èŽ·å�–æœ€æ–°çš„å…¬å…±å¾®å�šæ¶ˆæ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/public_timeline statuses/public_timeline}
	 *
	 * @access public
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function public_timeline( $page = 1, $count = 50, $base_app = 0 )
	{
		$params = array();
		$params['count'] = intval($count);
		$params['page'] = intval($page);
		$params['base_app'] = intval($base_app);
		return $this->oauth->get('statuses/public_timeline', $params);//å�¯èƒ½æ˜¯æŽ¥å�£çš„bugä¸�èƒ½è¡¥å…¨
	}

	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·å�Šå…¶æ‰€å…³æ³¨ç”¨æˆ·çš„æœ€æ–°å¾®å�šæ¶ˆæ�¯ã€‚
	 *
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·å�Šå…¶æ‰€å…³æ³¨ç”¨æˆ·çš„æœ€æ–°å¾®å�šæ¶ˆæ�¯ã€‚å’Œç”¨æˆ·ç™»å½• http://weibo.com å�Žåœ¨â€œæˆ‘çš„é¦–é¡µâ€�ä¸­çœ‹åˆ°çš„å†…å®¹ç›¸å�Œã€‚å�Œfriends_timeline()
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/home_timeline statuses/home_timeline}
	 * 
	 * @access public
	 * @param int $page æŒ‡å®šè¿”å›žç»“æžœçš„é¡µç �ã€‚æ ¹æ�®å½“å‰�ç™»å½•ç”¨æˆ·æ‰€å…³æ³¨çš„ç”¨æˆ·æ•°å�Šè¿™äº›è¢«å…³æ³¨ç”¨æˆ·å�‘è¡¨çš„å¾®å�šæ•°ï¼Œç¿»é¡µåŠŸèƒ½æœ€å¤šèƒ½æŸ¥çœ‹çš„æ€»è®°å½•æ•°ä¼šæœ‰æ‰€ä¸�å�Œï¼Œé€šå¸¸æœ€å¤šèƒ½æŸ¥çœ‹1000æ�¡å·¦å�³ã€‚é»˜è®¤å€¼1ã€‚å�¯é€‰ã€‚
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„è®°å½•æ•°ã€‚ç¼ºçœ�å€¼50ï¼Œæœ€å¤§å€¼200ã€‚å�¯é€‰ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³æ¯”since_idå�‘è¡¨æ—¶é—´æ™šçš„å¾®å�šæ¶ˆæ�¯ï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„å¾®å�šæ¶ˆæ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $feature è¿‡æ»¤ç±»åž‹IDï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šå›¾ç‰‡ã€�3ï¼šè§†é¢‘ã€�4ï¼šéŸ³ä¹�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function home_timeline( $page = 1, $count = 50, $since_id = 0, $max_id = 0, $base_app = 0, $feature = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['count'] = intval($count);
		$params['page'] = intval($page);
		$params['base_app'] = intval($base_app);
		$params['feature'] = intval($feature);

		return $this->oauth->get('statuses/home_timeline', $params);
	}

	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·å�Šå…¶æ‰€å…³æ³¨ç”¨æˆ·çš„æœ€æ–°å¾®å�šæ¶ˆæ�¯ã€‚
	 *
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·å�Šå…¶æ‰€å…³æ³¨ç”¨æˆ·çš„æœ€æ–°å¾®å�šæ¶ˆæ�¯ã€‚å’Œç”¨æˆ·ç™»å½• http://weibo.com å�Žåœ¨â€œæˆ‘çš„é¦–é¡µâ€�ä¸­çœ‹åˆ°çš„å†…å®¹ç›¸å�Œã€‚å�Œhome_timeline()
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/friends_timeline statuses/friends_timeline}
	 * 
	 * @access public
	 * @param int $page æŒ‡å®šè¿”å›žç»“æžœçš„é¡µç �ã€‚æ ¹æ�®å½“å‰�ç™»å½•ç”¨æˆ·æ‰€å…³æ³¨çš„ç”¨æˆ·æ•°å�Šè¿™äº›è¢«å…³æ³¨ç”¨æˆ·å�‘è¡¨çš„å¾®å�šæ•°ï¼Œç¿»é¡µåŠŸèƒ½æœ€å¤šèƒ½æŸ¥çœ‹çš„æ€»è®°å½•æ•°ä¼šæœ‰æ‰€ä¸�å�Œï¼Œé€šå¸¸æœ€å¤šèƒ½æŸ¥çœ‹1000æ�¡å·¦å�³ã€‚é»˜è®¤å€¼1ã€‚å�¯é€‰ã€‚
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„è®°å½•æ•°ã€‚ç¼ºçœ�å€¼50ï¼Œæœ€å¤§å€¼200ã€‚å�¯é€‰ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³æ¯”since_idå�‘è¡¨æ—¶é—´æ™šçš„å¾®å�šæ¶ˆæ�¯ï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„å¾®å�šæ¶ˆæ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1ä¸ºé™�åˆ¶æœ¬åº”ç”¨å¾®å�šï¼Œ0ä¸ºä¸�å�šé™�åˆ¶ã€‚é»˜è®¤ä¸º0ã€‚å�¯é€‰ã€‚
	 * @param int $feature å¾®å�šç±»åž‹ï¼Œ0å…¨éƒ¨ï¼Œ1åŽŸåˆ›ï¼Œ2å›¾ç‰‡ï¼Œ3è§†é¢‘ï¼Œ4éŸ³ä¹�. è¿”å›žæŒ‡å®šç±»åž‹çš„å¾®å�šä¿¡æ�¯å†…å®¹ã€‚è½¬ä¸ºä¸º0ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function friends_timeline( $page = 1, $count = 50, $since_id = 0, $max_id = 0, $base_app = 0, $feature = 0 )
	{
		return $this->home_timeline( $since_id, $max_id, $count, $page, $base_app, $feature);
	}

	/**
	 * èŽ·å�–ç”¨æˆ·å�‘å¸ƒçš„å¾®å�šä¿¡æ�¯åˆ—è¡¨
	 *
	 * è¿”å›žç”¨æˆ·çš„å�‘å¸ƒçš„æœ€è¿‘næ�¡ä¿¡æ�¯ï¼Œå’Œç”¨æˆ·å¾®å�šé¡µé�¢è¿”å›žå†…å®¹æ˜¯ä¸€è‡´çš„ã€‚æ­¤æŽ¥å�£ä¹Ÿå�¯ä»¥è¯·æ±‚å…¶ä»–ç”¨æˆ·çš„æœ€æ–°å�‘è¡¨å¾®å�šã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/user_timeline statuses/user_timeline}
	 * 
	 * @access public
	 * @param int $page é¡µç �
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚
	 * @param mixed $uid æŒ‡å®šç”¨æˆ·UIDæˆ–å¾®å�šæ˜µç§°
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³æ¯”since_idå�‘è¡¨æ—¶é—´æ™šçš„å¾®å�šæ¶ˆæ�¯ï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„æ��åˆ°å½“å‰�ç™»å½•ç”¨æˆ·å¾®å�šæ¶ˆæ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1ä¸ºé™�åˆ¶æœ¬åº”ç”¨å¾®å�šï¼Œ0ä¸ºä¸�å�šé™�åˆ¶ã€‚é»˜è®¤ä¸º0ã€‚
	 * @param int $feature è¿‡æ»¤ç±»åž‹IDï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šå›¾ç‰‡ã€�3ï¼šè§†é¢‘ã€�4ï¼šéŸ³ä¹�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $trim_user è¿”å›žå€¼ä¸­userä¿¡æ�¯å¼€å…³ï¼Œ0ï¼šè¿”å›žå®Œæ•´çš„userä¿¡æ�¯ã€�1ï¼šuserå­—æ®µä»…è¿”å›žuidï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function user_timeline_by_id( $uid = NULL , $page = 1 , $count = 50 , $since_id = 0, $max_id = 0, $feature = 0, $trim_user = 0, $base_app = 0)
	{
		$params = array();
		$params['uid']=$uid;
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['base_app'] = intval($base_app);
		$params['feature'] = intval($feature);
		$params['count'] = intval($count);
		$params['page'] = intval($page);
		$params['trim_user'] = intval($trim_user);

		return $this->oauth->get( 'statuses/user_timeline', $params );
	}
	
	
	/**
	 * èŽ·å�–ç”¨æˆ·å�‘å¸ƒçš„å¾®å�šä¿¡æ�¯åˆ—è¡¨
	 *
	 * è¿”å›žç”¨æˆ·çš„å�‘å¸ƒçš„æœ€è¿‘næ�¡ä¿¡æ�¯ï¼Œå’Œç”¨æˆ·å¾®å�šé¡µé�¢è¿”å›žå†…å®¹æ˜¯ä¸€è‡´çš„ã€‚æ­¤æŽ¥å�£ä¹Ÿå�¯ä»¥è¯·æ±‚å…¶ä»–ç”¨æˆ·çš„æœ€æ–°å�‘è¡¨å¾®å�šã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/user_timeline statuses/user_timeline}
	 * 
	 * @access public
	 * @param string $screen_name å¾®å�šæ˜µç§°ï¼Œä¸»è¦�æ˜¯ç”¨æ�¥åŒºåˆ†ç”¨æˆ·UIDè·Ÿå¾®å�šæ˜µç§°ï¼Œå½“äºŒè€…ä¸€æ ·è€Œäº§ç”Ÿæ­§ä¹‰çš„æ—¶å€™ï¼Œå»ºè®®ä½¿ç”¨è¯¥å�‚æ•° 
	 * @param int $page é¡µç �
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³æ¯”since_idå�‘è¡¨æ—¶é—´æ™šçš„å¾®å�šæ¶ˆæ�¯ï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„æ��åˆ°å½“å‰�ç™»å½•ç”¨æˆ·å¾®å�šæ¶ˆæ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $feature è¿‡æ»¤ç±»åž‹IDï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šå›¾ç‰‡ã€�3ï¼šè§†é¢‘ã€�4ï¼šéŸ³ä¹�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $trim_user è¿”å›žå€¼ä¸­userä¿¡æ�¯å¼€å…³ï¼Œ0ï¼šè¿”å›žå®Œæ•´çš„userä¿¡æ�¯ã€�1ï¼šuserå­—æ®µä»…è¿”å›žuidï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1ä¸ºé™�åˆ¶æœ¬åº”ç”¨å¾®å�šï¼Œ0ä¸ºä¸�å�šé™�åˆ¶ã€‚é»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function user_timeline_by_name( $screen_name = NULL , $page = 1 , $count = 50 , $since_id = 0, $max_id = 0, $feature = 0, $trim_user = 0, $base_app = 0 )
	{
		$params = array();
		$params['screen_name'] = $screen_name;
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['base_app'] = intval($base_app);
		$params['feature'] = intval($feature);
		$params['count'] = intval($count);
		$params['page'] = intval($page);
		$params['trim_user'] = intval($trim_user);

		return $this->oauth->get( 'statuses/user_timeline', $params );
	}
	
	
	
	/**
	 * æ‰¹é‡�èŽ·å�–æŒ‡å®šçš„ä¸€æ‰¹ç”¨æˆ·çš„timeline
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/timeline_batch statuses/timeline_batch}
	 *
	 * @param string $screen_name  éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·æ˜µç§°ï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œä¸€æ¬¡æœ€å¤š20ä¸ª
	 * @param int    $count        å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int    $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚ 
	 * @param int    $base_app  æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int    $feature   è¿‡æ»¤ç±»åž‹IDï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šå›¾ç‰‡ã€�3ï¼šè§†é¢‘ã€�4ï¼šéŸ³ä¹�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function timeline_batch_by_name( $screen_name, $page = 1, $count = 50, $feature = 0, $base_app = 0)
	{
		$params = array();
		if (is_array($screen_name) && !empty($screen_name)) {
			$params['screen_name'] = join(',', $screen_name);
		} else {
			$params['screen_name'] = $screen_name;
		}
		$params['count'] = intval($count);
		$params['page'] = intval($page); 
		$params['base_app'] = intval($base_app);
		$params['feature'] = intval($feature);
		return $this->oauth->get('statuses/timeline_batch', $params);
	}

	/**
	 * æ‰¹é‡�èŽ·å�–æŒ‡å®šçš„ä¸€æ‰¹ç”¨æˆ·çš„timeline
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/timeline_batch statuses/timeline_batch}
	 *
	 * @param string $uids  éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·IDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œä¸€æ¬¡æœ€å¤š20ä¸ªã€‚
	 * @param int    $count        å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int    $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚ 
	 * @param int    $base_app  æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int    $feature   è¿‡æ»¤ç±»åž‹IDï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šå›¾ç‰‡ã€�3ï¼šè§†é¢‘ã€�4ï¼šéŸ³ä¹�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function timeline_batch_by_id( $uids, $page = 1, $count = 50, $feature = 0, $base_app = 0)
	{
		$params = array();
		if (is_array($uids) && !empty($uids)) {
			foreach($uids as $k => $v) {
				$this->id_format($uids[$k]);
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}
		$params['count'] = intval($count);
		$params['page'] = intval($page); 
		$params['base_app'] = intval($base_app);
		$params['feature'] = intval($feature);
		return $this->oauth->get('statuses/timeline_batch', $params);
	}


	/**
	 * è¿”å›žä¸€æ�¡åŽŸåˆ›å¾®å�šæ¶ˆæ�¯çš„æœ€æ–°næ�¡è½¬å�‘å¾®å�šæ¶ˆæ�¯ã€‚æœ¬æŽ¥å�£æ— æ³•å¯¹é�žåŽŸåˆ›å¾®å�šè¿›è¡ŒæŸ¥è¯¢ã€‚ 
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/repost_timeline statuses/repost_timeline}
	 * 
	 * @access public
	 * @param int $sid è¦�èŽ·å�–è½¬å�‘å¾®å�šåˆ—è¡¨çš„åŽŸåˆ›å¾®å�šIDã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ã€‚ 
	 * @param int $count å�•é¡µè¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚å�¯é€‰ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„è®°å½•ï¼ˆæ¯”since_idå�‘è¡¨æ—¶é—´æ™šï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è®°å½•ã€‚å�¯é€‰ã€‚
	 * @param int $filter_by_author ä½œè€…ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæˆ‘å…³æ³¨çš„äººã€�2ï¼šé™Œç”Ÿäººï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function repost_timeline( $sid, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0 )
	{
		$this->id_format($sid);

		$params = array();
		$params['id'] = $sid;
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['filter_by_author'] = intval($filter_by_author);

		return $this->request_with_pager( 'statuses/repost_timeline', $page, $count, $params );
	}

	/**
	 * èŽ·å�–å½“å‰�ç”¨æˆ·æœ€æ–°è½¬å�‘çš„næ�¡å¾®å�šæ¶ˆæ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/repost_by_me statuses/repost_by_me}
	 * 
	 * @access public
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ã€‚ 
	 * @param int $count  æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚å�¯é€‰ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„è®°å½•ï¼ˆæ¯”since_idå�‘è¡¨æ—¶é—´æ™šï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id  è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è®°å½•ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function repost_by_me( $page = 1, $count = 50, $since_id = 0, $max_id = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}

		return $this->request_with_pager('statuses/repost_by_me', $page, $count, $params );
	}

	/**
	 * èŽ·å�–@å½“å‰�ç”¨æˆ·çš„å¾®å�šåˆ—è¡¨
	 *
	 * è¿”å›žæœ€æ–°næ�¡æ��åˆ°ç™»å½•ç”¨æˆ·çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³åŒ…å�«@usernameçš„å¾®å�šæ¶ˆæ�¯ï¼‰
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/mentions statuses/mentions}
	 * 
	 * @access public
	 * @param int $page è¿”å›žç»“æžœçš„é¡µåº�å�·ã€‚
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼ˆå�³é¡µé�¢å¤§å°�ï¼‰ï¼Œä¸�å¤§äºŽ200ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„å¾®å�šæ¶ˆæ�¯ï¼ˆå�³æ¯”since_idå�‘è¡¨æ—¶é—´æ™šçš„å¾®å�šæ¶ˆæ�¯ï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„æ��åˆ°å½“å‰�ç™»å½•ç”¨æˆ·å¾®å�šæ¶ˆæ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $filter_by_author ä½œè€…ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæˆ‘å…³æ³¨çš„äººã€�2ï¼šé™Œç”Ÿäººï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $filter_by_source æ�¥æº�ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæ�¥è‡ªå¾®å�šã€�2ï¼šæ�¥è‡ªå¾®ç¾¤ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $filter_by_type åŽŸåˆ›ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨å¾®å�šã€�1ï¼šåŽŸåˆ›çš„å¾®å�šï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function mentions( $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0, $filter_by_type = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['filter_by_author'] = $filter_by_author;
		$params['filter_by_source'] = $filter_by_source;
		$params['filter_by_type'] = $filter_by_type;

		return $this->request_with_pager( 'statuses/mentions', $page, $count, $params );
	}


	/**
	 * æ ¹æ�®IDèŽ·å�–å�•æ�¡å¾®å�šä¿¡æ�¯å†…å®¹
	 *
	 * èŽ·å�–å�•æ�¡IDçš„å¾®å�šä¿¡æ�¯ï¼Œä½œè€…ä¿¡æ�¯å°†å�Œæ—¶è¿”å›žã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/show statuses/show}
	 * 
	 * @access public
	 * @param int $id è¦�èŽ·å�–å·²å�‘è¡¨çš„å¾®å�šID, å¦‚IDä¸�å­˜åœ¨è¿”å›žç©º
	 * @return array
	 */
	function show_status( $id )
	{
		$this->id_format($id);
		$params = array();
		$params['id'] = $id;
		return $this->oauth->get('statuses/show', $params);
	}

	/**
	 * æ ¹æ�®å¾®å�šidå�·èŽ·å�–å¾®å�šçš„ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/show_batch statuses/show_batch}
	 *
	 * @param string $ids éœ€è¦�æŸ¥è¯¢çš„å¾®å�šIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡50ä¸ªã€‚
	 * @return array
	 */
    function show_batch( $ids )
	{
		$params=array();
		if (is_array($ids) && !empty($ids)) {
			foreach($ids as $k => $v) {
				$this->id_format($ids[$k]);
			}
			$params['ids'] = join(',', $ids);
		} else {
			$params['ids'] = $ids;
		}
		return $this->oauth->get('statuses/show_batch', $params);
	}

	/**
	 * é€šè¿‡å¾®å�šï¼ˆè¯„è®ºã€�ç§�ä¿¡ï¼‰IDèŽ·å�–å…¶MID
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/querymid statuses/querymid}
	 *
	 * @param int|string $id  éœ€è¦�æŸ¥è¯¢çš„å¾®å�šï¼ˆè¯„è®ºã€�ç§�ä¿¡ï¼‰IDï¼Œæ‰¹é‡�æ¨¡å¼�ä¸‹ï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡20ä¸ªã€‚
	 * @param int $type  èŽ·å�–ç±»åž‹ï¼Œ1ï¼šå¾®å�šã€�2ï¼šè¯„è®ºã€�3ï¼šç§�ä¿¡ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $is_batch æ˜¯å�¦ä½¿ç”¨æ‰¹é‡�æ¨¡å¼�ï¼Œ0ï¼šå�¦ã€�1ï¼šæ˜¯ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function querymid( $id, $type = 1, $is_batch = 0 )
	{
		$params = array();
		$params['id'] = $id;
		$params['type'] = intval($type);
		$params['is_batch'] = intval($is_batch);
		return $this->oauth->get( 'statuses/querymid',  $params);
	}

	/**
	 * é€šè¿‡å¾®å�šï¼ˆè¯„è®ºã€�ç§�ä¿¡ï¼‰MIDèŽ·å�–å…¶ID
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/queryid statuses/queryid}
	 *
	 * @param int|string $mid  éœ€è¦�æŸ¥è¯¢çš„å¾®å�šï¼ˆè¯„è®ºã€�ç§�ä¿¡ï¼‰MIDï¼Œæ‰¹é‡�æ¨¡å¼�ä¸‹ï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡20ä¸ªã€‚
	 * @param int $type  èŽ·å�–ç±»åž‹ï¼Œ1ï¼šå¾®å�šã€�2ï¼šè¯„è®ºã€�3ï¼šç§�ä¿¡ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $is_batch æ˜¯å�¦ä½¿ç”¨æ‰¹é‡�æ¨¡å¼�ï¼Œ0ï¼šå�¦ã€�1ï¼šæ˜¯ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $inbox  ä»…å¯¹ç§�ä¿¡æœ‰æ•ˆï¼Œå½“MIDç±»åž‹ä¸ºç§�ä¿¡æ—¶ç”¨æ­¤å�‚æ•°ï¼Œ0ï¼šå�‘ä»¶ç®±ã€�1ï¼šæ”¶ä»¶ç®±ï¼Œé»˜è®¤ä¸º0 ã€‚
	 * @param int $isBase62 MIDæ˜¯å�¦æ˜¯base62ç¼–ç �ï¼Œ0ï¼šå�¦ã€�1ï¼šæ˜¯ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function queryid( $mid, $type = 1, $is_batch = 0, $inbox = 0, $isBase62 = 0)
	{
		$params = array();
		$params['mid'] = $mid;
		$params['type'] = intval($type);
		$params['is_batch'] = intval($is_batch);
		$params['inbox'] = intval($inbox);
		$params['isBase62'] = intval($isBase62);
		return $this->oauth->get('statuses/queryid', $params);
	}

	/**
	 * æŒ‰å¤©è¿”å›žçƒ­é—¨å¾®å�šè½¬å�‘æ¦œçš„å¾®å�šåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/hot/repost_daily statuses/hot/repost_daily}
	 *
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œæœ€å¤§ä¸�è¶…è¿‡50ï¼Œé»˜è®¤ä¸º20ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function repost_daily( $count = 20, $base_app = 0)
	{
		$params = array();
		$params['count'] = intval($count);
		$params['base_app'] = intval($base_app);
		return $this->oauth->get('statuses/hot/repost_daily',  $params);
	}

	/**
	 * æŒ‰å‘¨è¿”å›žçƒ­é—¨å¾®å�šè½¬å�‘æ¦œçš„å¾®å�šåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/hot/repost_weekly statuses/hot/repost_weekly}
	 *
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œæœ€å¤§ä¸�è¶…è¿‡50ï¼Œé»˜è®¤ä¸º20ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function repost_weekly( $count = 20,  $base_app = 0)
	{
		$params = array();
		$params['count'] = intval($count);
		$params['base_app'] = intval($base_app);
		return $this->oauth->get( 'statuses/hot/repost_weekly',  $params);
	}

	/**
	 * æŒ‰å¤©è¿”å›žçƒ­é—¨å¾®å�šè¯„è®ºæ¦œçš„å¾®å�šåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/hot/comments_daily statuses/hot/comments_daily}
	 *
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œæœ€å¤§ä¸�è¶…è¿‡50ï¼Œé»˜è®¤ä¸º20ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function comments_daily( $count = 20,  $base_app = 0)
	{
		$params =  array();
		$params['count'] = intval($count);
		$params['base_app'] = intval($base_app);
		return $this->oauth->get( 'statuses/hot/comments_daily',  $params);
	}

	/**
	 * æŒ‰å‘¨è¿”å›žçƒ­é—¨å¾®å�šè¯„è®ºæ¦œçš„å¾®å�šåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/hot/comments_weekly statuses/hot/comments_weekly}
	 *
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œæœ€å¤§ä¸�è¶…è¿‡50ï¼Œé»˜è®¤ä¸º20ã€‚
	 * @param int $base_app æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function comments_weekly( $count = 20, $base_app = 0)
	{
		$params =  array();
		$params['count'] = intval($count);
		$params['base_app'] = intval($base_app);
		return $this->oauth->get( 'statuses/hot/comments_weekly', $params);
	}


	/**
	 * è½¬å�‘ä¸€æ�¡å¾®å�šä¿¡æ�¯ã€‚
	 *
	 * å�¯åŠ è¯„è®ºã€‚ä¸ºé˜²æ­¢é‡�å¤�ï¼Œå�‘å¸ƒçš„ä¿¡æ�¯ä¸Žæœ€æ–°ä¿¡æ�¯ä¸€æ ·è¯�ï¼Œå°†ä¼šè¢«å¿½ç•¥ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/repost statuses/repost}
	 * 
	 * @access public
	 * @param int $sid è½¬å�‘çš„å¾®å�šID
	 * @param string $text æ·»åŠ çš„è¯„è®ºä¿¡æ�¯ã€‚å�¯é€‰ã€‚
	 * @param int $is_comment æ˜¯å�¦åœ¨è½¬å�‘çš„å�Œæ—¶å�‘è¡¨è¯„è®ºï¼Œ0ï¼šå�¦ã€�1ï¼šè¯„è®ºç»™å½“å‰�å¾®å�šã€�2ï¼šè¯„è®ºç»™åŽŸå¾®å�šã€�3ï¼šéƒ½è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function repost( $sid, $text = NULL, $is_comment = 0 )
	{
		$this->id_format($sid);

		$params = array();
		$params['id'] = $sid;
		$params['is_comment'] = $is_comment;
		if( $text ) $params['status'] = $text;

		return $this->oauth->post( 'statuses/repost', $params  );
	}

	/**
	 * åˆ é™¤ä¸€æ�¡å¾®å�š
	 * 
	 * æ ¹æ�®IDåˆ é™¤å¾®å�šæ¶ˆæ�¯ã€‚æ³¨æ„�ï¼šå�ªèƒ½åˆ é™¤è‡ªå·±å�‘å¸ƒçš„ä¿¡æ�¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/destroy statuses/destroy}
	 * 
	 * @access public
	 * @param int $id è¦�åˆ é™¤çš„å¾®å�šID
	 * @return array
	 */
	function delete( $id )
	{
		return $this->destroy( $id );
	}

	/**
	 * åˆ é™¤ä¸€æ�¡å¾®å�š
	 *
	 * åˆ é™¤å¾®å�šã€‚æ³¨æ„�ï¼šå�ªèƒ½åˆ é™¤è‡ªå·±å�‘å¸ƒçš„ä¿¡æ�¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/destroy statuses/destroy}
	 * 
	 * @access public
	 * @param int $id è¦�åˆ é™¤çš„å¾®å�šID
	 * @return array
	 */
	function destroy( $id )
	{
		$this->id_format($id);
		$params = array();
		$params['id'] = $id;
		return $this->oauth->post( 'statuses/destroy',  $params );
	}

	
	/**
	 * å�‘è¡¨å¾®å�š
	 *
	 * å�‘å¸ƒä¸€æ�¡å¾®å�šä¿¡æ�¯ã€‚
	 * <br />æ³¨æ„�ï¼šlatå’Œlongå�‚æ•°éœ€é…�å�ˆä½¿ç”¨ï¼Œç”¨äºŽæ ‡è®°å�‘è¡¨å¾®å�šæ¶ˆæ�¯æ—¶æ‰€åœ¨çš„åœ°ç�†ä½�ç½®ï¼Œå�ªæœ‰ç”¨æˆ·è®¾ç½®ä¸­geo_enabled=trueæ—¶å€™åœ°ç�†ä½�ç½®ä¿¡æ�¯æ‰�æœ‰æ•ˆã€‚
	 * <br />æ³¨æ„�ï¼šä¸ºé˜²æ­¢é‡�å¤�æ��äº¤ï¼Œå½“ç”¨æˆ·å�‘å¸ƒçš„å¾®å�šæ¶ˆæ�¯ä¸Žä¸Šæ¬¡æˆ�åŠŸå�‘å¸ƒçš„å¾®å�šæ¶ˆæ�¯å†…å®¹ä¸€æ ·æ—¶ï¼Œå°†è¿”å›ž400é”™è¯¯ï¼Œç»™å‡ºé”™è¯¯æ��ç¤ºï¼šâ€œ40025:Error: repeated weibo text!â€œã€‚ 
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/update statuses/update}
	 * 
	 * @access public
	 * @param string $status è¦�æ›´æ–°çš„å¾®å�šä¿¡æ�¯ã€‚ä¿¡æ�¯å†…å®¹ä¸�è¶…è¿‡140ä¸ªæ±‰å­—, ä¸ºç©ºè¿”å›ž400é”™è¯¯ã€‚
	 * @param float $lat çº¬åº¦ï¼Œå�‘è¡¨å½“å‰�å¾®å�šæ‰€åœ¨çš„åœ°ç�†ä½�ç½®ï¼Œæœ‰æ•ˆèŒƒå›´ -90.0åˆ°+90.0, +è¡¨ç¤ºåŒ—çº¬ã€‚å�¯é€‰ã€‚
	 * @param float $long ç»�åº¦ã€‚æœ‰æ•ˆèŒƒå›´-180.0åˆ°+180.0, +è¡¨ç¤ºä¸œç»�ã€‚å�¯é€‰ã€‚
	 * @param mixed $annotations å�¯é€‰å�‚æ•°ã€‚å…ƒæ•°æ�®ï¼Œä¸»è¦�æ˜¯ä¸ºäº†æ–¹ä¾¿ç¬¬ä¸‰æ–¹åº”ç”¨è®°å½•ä¸€äº›é€‚å�ˆäºŽè‡ªå·±ä½¿ç”¨çš„ä¿¡æ�¯ã€‚æ¯�æ�¡å¾®å�šå�¯ä»¥åŒ…å�«ä¸€ä¸ªæˆ–è€…å¤šä¸ªå…ƒæ•°æ�®ã€‚è¯·ä»¥jsonå­—ä¸²çš„å½¢å¼�æ��äº¤ï¼Œå­—ä¸²é•¿åº¦ä¸�è¶…è¿‡512ä¸ªå­—ç¬¦ï¼Œæˆ–è€…æ•°ç»„æ–¹å¼�ï¼Œè¦�æ±‚json_encodeå�Žå­—ä¸²é•¿åº¦ä¸�è¶…è¿‡512ä¸ªå­—ç¬¦ã€‚å…·ä½“å†…å®¹å�¯ä»¥è‡ªå®šã€‚ä¾‹å¦‚ï¼š'[{"type2":123}, {"a":"b", "c":"d"}]'æˆ–array(array("type2"=>123), array("a"=>"b", "c"=>"d"))ã€‚
	 * @return array
	 */
	function update( $status, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		$params = array();
		$params['status'] = $status;
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}
		if (is_string($annotations)) {
			$params['annotations'] = $annotations;
		} elseif (is_array($annotations)) {
			$params['annotations'] = json_encode($annotations);
		}

		return $this->oauth->post( 'statuses/update', $params );
	}

	/**
	 * å�‘è¡¨å›¾ç‰‡å¾®å�š
	 *
	 * å�‘è¡¨å›¾ç‰‡å¾®å�šæ¶ˆæ�¯ã€‚ç›®å‰�ä¸Šä¼ å›¾ç‰‡å¤§å°�é™�åˆ¶ä¸º<5Mã€‚ 
	 * <br />æ³¨æ„�ï¼šlatå’Œlongå�‚æ•°éœ€é…�å�ˆä½¿ç”¨ï¼Œç”¨äºŽæ ‡è®°å�‘è¡¨å¾®å�šæ¶ˆæ�¯æ—¶æ‰€åœ¨çš„åœ°ç�†ä½�ç½®ï¼Œå�ªæœ‰ç”¨æˆ·è®¾ç½®ä¸­geo_enabled=trueæ—¶å€™åœ°ç�†ä½�ç½®ä¿¡æ�¯æ‰�æœ‰æ•ˆã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/upload statuses/upload}
	 * 
	 * @access public
	 * @param string $status è¦�æ›´æ–°çš„å¾®å�šä¿¡æ�¯ã€‚ä¿¡æ�¯å†…å®¹ä¸�è¶…è¿‡140ä¸ªæ±‰å­—, ä¸ºç©ºè¿”å›ž400é”™è¯¯ã€‚
	 * @param string $pic_path è¦�å�‘å¸ƒçš„å›¾ç‰‡è·¯å¾„, æ”¯æŒ�urlã€‚[å�ªæ”¯æŒ�png/jpg/gifä¸‰ç§�æ ¼å¼�, å¢žåŠ æ ¼å¼�è¯·ä¿®æ”¹get_image_mimeæ–¹æ³•]
	 * @param float $lat çº¬åº¦ï¼Œå�‘è¡¨å½“å‰�å¾®å�šæ‰€åœ¨çš„åœ°ç�†ä½�ç½®ï¼Œæœ‰æ•ˆèŒƒå›´ -90.0åˆ°+90.0, +è¡¨ç¤ºåŒ—çº¬ã€‚å�¯é€‰ã€‚
	 * @param float $long å�¯é€‰å�‚æ•°ï¼Œç»�åº¦ã€‚æœ‰æ•ˆèŒƒå›´-180.0åˆ°+180.0, +è¡¨ç¤ºä¸œç»�ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function upload( $status, $pic_path, $lat = NULL, $long = NULL )
	{
		$params = array();
		$params['status'] = $status;
		$params['pic'] = '@'.$pic_path;
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}

		return $this->oauth->post( 'statuses/upload', $params, true );
	}


	/**
	 * æŒ‡å®šä¸€ä¸ªå›¾ç‰‡URLåœ°å�€æŠ“å�–å�Žä¸Šä¼ å¹¶å�Œæ—¶å�‘å¸ƒä¸€æ�¡æ–°å¾®å�š
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/upload_url_text statuses/upload_url_text}
	 *
	 * @param string $status  è¦�å�‘å¸ƒçš„å¾®å�šæ–‡æœ¬å†…å®¹ï¼Œå†…å®¹ä¸�è¶…è¿‡140ä¸ªæ±‰å­—ã€‚
	 * @param string $url    å›¾ç‰‡çš„URLåœ°å�€ï¼Œå¿…é¡»ä»¥httpå¼€å¤´ã€‚
	 * @return array
	 */
	function upload_url_text( $status,  $url )
	{
		$params = array();
		$params['status'] = $status;
		$params['url'] = $url;
		return $this->oauth->post( 'statuses/upload', $params, true );
	}


	/**
	 * èŽ·å�–è¡¨æƒ…åˆ—è¡¨
	 *
	 * è¿”å›žæ–°æµªå¾®å�šå®˜æ–¹æ‰€æœ‰è¡¨æƒ…ã€�é­”æ³•è¡¨æƒ…çš„ç›¸å…³ä¿¡æ�¯ã€‚åŒ…æ‹¬çŸ­è¯­ã€�è¡¨æƒ…ç±»åž‹ã€�è¡¨æƒ…åˆ†ç±»ï¼Œæ˜¯å�¦çƒ­é—¨ç­‰ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/emotions emotions}
	 * 
	 * @access public
	 * @param string $type è¡¨æƒ…ç±»åˆ«ã€‚"face":æ™®é€šè¡¨æƒ…ï¼Œ"ani"ï¼šé­”æ³•è¡¨æƒ…ï¼Œ"cartoon"ï¼šåŠ¨æ¼«è¡¨æƒ…ã€‚é»˜è®¤ä¸º"face"ã€‚å�¯é€‰ã€‚
	 * @param string $language è¯­è¨€ç±»åˆ«ï¼Œ"cnname"ç®€ä½“ï¼Œ"twname"ç¹�ä½“ã€‚é»˜è®¤ä¸º"cnname"ã€‚å�¯é€‰
	 * @return array
	 */
	function emotions( $type = "face", $language = "cnname" )
	{
		$params = array();
		$params['type'] = $type;
		$params['language'] = $language;
		return $this->oauth->get( 'emotions', $params );
	}


	/**
	 * æ ¹æ�®å¾®å�šIDè¿”å›žæŸ�æ�¡å¾®å�šçš„è¯„è®ºåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/show comments/show}
	 *
	 * @param int $sid éœ€è¦�æŸ¥è¯¢çš„å¾®å�šIDã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDæ¯”since_idå¤§çš„è¯„è®ºï¼ˆå�³æ¯”since_idæ—¶é—´æ™šçš„è¯„è®ºï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $max_id  è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $filter_by_author ä½œè€…ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæˆ‘å…³æ³¨çš„äººã€�2ï¼šé™Œç”Ÿäººï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function get_comments_by_sid( $sid, $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0 )
	{
		$params = array();
		$this->id_format($sid);
		$params['id'] = $sid;
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['count'] = $count;
		$params['page'] = $page;
		$params['filter_by_author'] = $filter_by_author;
		return $this->oauth->get( 'comments/show',  $params );
	}


	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·æ‰€å�‘å‡ºçš„è¯„è®ºåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/by_me comments/by_me}
	 *
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDæ¯”since_idå¤§çš„è¯„è®ºï¼ˆå�³æ¯”since_idæ—¶é—´æ™šçš„è¯„è®ºï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count  å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $filter_by_source æ�¥æº�ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæ�¥è‡ªå¾®å�šçš„è¯„è®ºã€�2ï¼šæ�¥è‡ªå¾®ç¾¤çš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function comments_by_me( $page = 1 , $count = 50, $since_id = 0, $max_id = 0,  $filter_by_source = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['count'] = $count;
		$params['page'] = $page;
		$params['filter_by_source'] = $filter_by_source;
		return $this->oauth->get( 'comments/by_me', $params );
	}

	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·æ‰€æŽ¥æ”¶åˆ°çš„è¯„è®ºåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/to_me comments/to_me}
	 *
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDæ¯”since_idå¤§çš„è¯„è®ºï¼ˆå�³æ¯”since_idæ—¶é—´æ™šçš„è¯„è®ºï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $max_id  è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $filter_by_author ä½œè€…ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæˆ‘å…³æ³¨çš„äººã€�2ï¼šé™Œç”Ÿäººï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $filter_by_source æ�¥æº�ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæ�¥è‡ªå¾®å�šçš„è¯„è®ºã€�2ï¼šæ�¥è‡ªå¾®ç¾¤çš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */ 
	function comments_to_me( $page = 1 , $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0)
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['count'] = $count;
		$params['page'] = $page;
		$params['filter_by_author'] = $filter_by_author;
		$params['filter_by_source'] = $filter_by_source;
		return $this->oauth->get( 'comments/to_me', $params );
	}

	/**
	 * æœ€æ–°è¯„è®º(æŒ‰æ—¶é—´)
	 *
	 * è¿”å›žæœ€æ–°næ�¡å�‘é€�å�Šæ”¶åˆ°çš„è¯„è®ºã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/timeline comments/timeline}
	 * 
	 * @access public
	 * @param int $page é¡µç �
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™å�ªè¿”å›žIDæ¯”since_idå¤§çš„è¯„è®ºï¼ˆæ¯”since_idå�‘è¡¨æ—¶é—´æ™šï¼‰ã€‚å�¯é€‰ã€‚
	 * @param int $max_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è¯„è®ºã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function comments_timeline( $page = 1, $count = 50, $since_id = 0, $max_id = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}

		return $this->request_with_pager( 'comments/timeline', $page, $count, $params );
	}


	/**
	 * èŽ·å�–æœ€æ–°çš„æ��åˆ°å½“å‰�ç™»å½•ç”¨æˆ·çš„è¯„è®ºï¼Œå�³@æˆ‘çš„è¯„è®º
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/mentions comments/mentions}
	 *
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDæ¯”since_idå¤§çš„è¯„è®ºï¼ˆå�³æ¯”since_idæ—¶é—´æ™šçš„è¯„è®ºï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $max_id  è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $filter_by_author  ä½œè€…ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæˆ‘å…³æ³¨çš„äººã€�2ï¼šé™Œç”Ÿäººï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $filter_by_source æ�¥æº�ç­›é€‰ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šæ�¥è‡ªå¾®å�šçš„è¯„è®ºã€�2ï¼šæ�¥è‡ªå¾®ç¾¤çš„è¯„è®ºï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */ 
	function comments_mentions( $page = 1, $count = 50, $since_id = 0, $max_id = 0, $filter_by_author = 0, $filter_by_source = 0)
	{
		$params = array();
		$params['since_id'] = $since_id;
		$params['max_id'] = $max_id;
		$params['count'] = $count;
		$params['page'] = $page;
		$params['filter_by_author'] = $filter_by_author;
		$params['filter_by_source'] = $filter_by_source;
		return $this->oauth->get( 'comments/mentions', $params );
	}


	/**
	 * æ ¹æ�®è¯„è®ºIDæ‰¹é‡�è¿”å›žè¯„è®ºä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/show_batch comments/show_batch}
	 *
	 * @param string $cids éœ€è¦�æŸ¥è¯¢çš„æ‰¹é‡�è¯„è®ºIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤§50
	 * @return array
	 */
	function comments_show_batch( $cids )
	{
		$params = array();
		if (is_array( $cids) && !empty( $cids)) {
			foreach($cids as $k => $v) {
				$this->id_format($cids[$k]);
			}
			$params['cids'] = join(',', $cids);
		} else {
			$params['cids'] = $cids;
		}
		return $this->oauth->get( 'comments/show_batch', $params );
	}


	/**
	 * å¯¹ä¸€æ�¡å¾®å�šè¿›è¡Œè¯„è®º
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/create comments/create}
	 *
	 * @param string $comment è¯„è®ºå†…å®¹ï¼Œå†…å®¹ä¸�è¶…è¿‡140ä¸ªæ±‰å­—ã€‚
	 * @param int $id éœ€è¦�è¯„è®ºçš„å¾®å�šIDã€‚
	 * @param int $comment_ori å½“è¯„è®ºè½¬å�‘å¾®å�šæ—¶ï¼Œæ˜¯å�¦è¯„è®ºç»™åŽŸå¾®å�šï¼Œ0ï¼šå�¦ã€�1ï¼šæ˜¯ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function send_comment( $id , $comment , $comment_ori = 0)
	{
		$params = array();
		$params['comment'] = $comment;
		$this->id_format($id);
		$params['id'] = $id;
		$params['comment_ori'] = $comment_ori;
		return $this->oauth->post( 'comments/create', $params );
	}

	/**
	 * åˆ é™¤å½“å‰�ç”¨æˆ·çš„å¾®å�šè¯„è®ºä¿¡æ�¯ã€‚
	 *
	 * æ³¨æ„�ï¼šå�ªèƒ½åˆ é™¤è‡ªå·±å�‘å¸ƒçš„è¯„è®ºï¼Œå�‘éƒ¨å¾®å�šçš„ç”¨æˆ·ä¸�å�¯ä»¥åˆ é™¤å…¶ä»–äººçš„è¯„è®ºã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/statuses/comment_destroy statuses/comment_destroy}
	 * 
	 * @access public
	 * @param int $cid è¦�åˆ é™¤çš„è¯„è®ºid
	 * @return array
	 */
	function comment_destroy( $cid )
	{
		$params = array();
		$params['cid'] = $cid;
		return $this->oauth->post( 'comments/destroy', $params);
	}


	/**
	 * æ ¹æ�®è¯„è®ºIDæ‰¹é‡�åˆ é™¤è¯„è®º
	 *
	 * æ³¨æ„�ï¼šå�ªèƒ½åˆ é™¤è‡ªå·±å�‘å¸ƒçš„è¯„è®ºï¼Œå�‘éƒ¨å¾®å�šçš„ç”¨æˆ·ä¸�å�¯ä»¥åˆ é™¤å…¶ä»–äººçš„è¯„è®ºã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/destroy_batch comments/destroy_batch}
	 *
	 * @access public
	 * @param string $ids éœ€è¦�åˆ é™¤çš„è¯„è®ºIDï¼Œç”¨å�Šè§’é€—å�·éš”å¼€ï¼Œæœ€å¤š20ä¸ªã€‚
	 * @return array
	 */
	function comment_destroy_batch( $ids )
	{
		$params = array();
		if (is_array($ids) && !empty($ids)) {
			foreach($ids as $k => $v) {
				$this->id_format($ids[$k]);
			}
			$params['cids'] = join(',', $ids);
		} else {
			$params['cids'] = $ids;
		}
		return $this->oauth->post( 'comments/destroy_batch', $params);
	}


	/**
	 * å›žå¤�ä¸€æ�¡è¯„è®º
	 *
	 * ä¸ºé˜²æ­¢é‡�å¤�ï¼Œå�‘å¸ƒçš„ä¿¡æ�¯ä¸Žæœ€å�Žä¸€æ�¡è¯„è®º/å›žå¤�ä¿¡æ�¯ä¸€æ ·è¯�ï¼Œå°†ä¼šè¢«å¿½ç•¥ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/comments/reply comments/reply}
	 * 
	 * @access public
	 * @param int $sid å¾®å�šid
	 * @param string $text è¯„è®ºå†…å®¹ã€‚
	 * @param int $cid è¯„è®ºid
	 * @param int $without_mention 1ï¼šå›žå¤�ä¸­ä¸�è‡ªåŠ¨åŠ å…¥â€œå›žå¤�@ç”¨æˆ·å��â€�ï¼Œ0ï¼šå›žå¤�ä¸­è‡ªåŠ¨åŠ å…¥â€œå›žå¤�@ç”¨æˆ·å��â€�.é»˜è®¤ä¸º0.
     * @param int $comment_ori	  å½“è¯„è®ºè½¬å�‘å¾®å�šæ—¶ï¼Œæ˜¯å�¦è¯„è®ºç»™åŽŸå¾®å�šï¼Œ0ï¼šå�¦ã€�1ï¼šæ˜¯ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function reply( $sid, $text, $cid, $without_mention = 0, $comment_ori = 0 )
	{
		$this->id_format( $sid );
		$this->id_format( $cid );
		$params = array();
		$params['id'] = $sid;
		$params['comment'] = $text;
		$params['cid'] = $cid;
		$params['without_mention'] = $without_mention;
		$params['comment_ori'] = $comment_ori;

		return $this->oauth->post( 'comments/reply', $params );

	}

	/**
	 * æ ¹æ�®ç”¨æˆ·UIDæˆ–æ˜µç§°èŽ·å�–ç”¨æˆ·èµ„æ–™
	 *
	 * æŒ‰ç”¨æˆ·UIDæˆ–æ˜µç§°è¿”å›žç”¨æˆ·èµ„æ–™ï¼Œå�Œæ—¶ä¹Ÿå°†è¿”å›žç”¨æˆ·çš„æœ€æ–°å�‘å¸ƒçš„å¾®å�šã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/users/show users/show}
	 * 
	 * @access public
	 * @param int  $uid ç”¨æˆ·UIDã€‚
	 * @return array
	 */
	function show_user_by_id( $uid )
	{
		$params=array();
		if ( $uid !== NULL ) {
			$this->id_format($uid);
			$params['uid'] = $uid;
		}

		return $this->oauth->get('users/show', $params );
	}
	
	/**
	 * æ ¹æ�®ç”¨æˆ·UIDæˆ–æ˜µç§°èŽ·å�–ç”¨æˆ·èµ„æ–™
	 *
	 * æŒ‰ç”¨æˆ·UIDæˆ–æ˜µç§°è¿”å›žç”¨æˆ·èµ„æ–™ï¼Œå�Œæ—¶ä¹Ÿå°†è¿”å›žç”¨æˆ·çš„æœ€æ–°å�‘å¸ƒçš„å¾®å�šã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/users/show users/show}
	 * 
	 * @access public
	 * @param string  $screen_name ç”¨æˆ·UIDã€‚
	 * @return array
	 */
	function show_user_by_name( $screen_name )
	{
		$params = array();
		$params['screen_name'] = $screen_name;

		return $this->oauth->get( 'users/show', $params );
	}

	/**
	 * é€šè¿‡ä¸ªæ€§åŒ–åŸŸå��èŽ·å�–ç”¨æˆ·èµ„æ–™ä»¥å�Šç”¨æˆ·æœ€æ–°çš„ä¸€æ�¡å¾®å�š
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/users/domain_show users/domain_show}
	 * 
	 * @access public
	 * @param mixed $domain ç”¨æˆ·ä¸ªæ€§åŸŸå��ã€‚ä¾‹å¦‚ï¼šlazypeopleï¼Œè€Œä¸�æ˜¯http://weibo.com/lazypeople
	 * @return array
	 */
	function domain_show( $domain )
	{
		$params = array();
		$params['domain'] = $domain;
		return $this->oauth->get( 'users/domain_show', $params );
	}

	 /**
	 * æ‰¹é‡�èŽ·å�–ç”¨æˆ·ä¿¡æ�¯æŒ‰uids
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/users/show_batch users/show_batch}
	 *
	 * @param string $uids éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·IDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œä¸€æ¬¡æœ€å¤š20ä¸ªã€‚
	 * @return array
	 */
	function users_show_batch_by_id( $uids )
	{
		$params = array();
		if (is_array( $uids ) && !empty( $uids )) {
			foreach( $uids as $k => $v ) {
				$this->id_format( $uids[$k] );
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}
		return $this->oauth->get( 'users/show_batch', $params );
	}
	
	/**
	 * æ‰¹é‡�èŽ·å�–ç”¨æˆ·ä¿¡æ�¯æŒ‰screen_name
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/users/show_batch users/show_batch}
	 *
	 * @param string  $screen_name éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·æ˜µç§°ï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œä¸€æ¬¡æœ€å¤š20ä¸ªã€‚
	 * @return array
	 */
	function users_show_batch_by_name( $screen_name )
	{
		$params = array();
		if (is_array( $screen_name ) && !empty( $screen_name )) {
			$params['screen_name'] = join(',', $screen_name);
		} else {
			$params['screen_name'] = $screen_name;
		}
		return $this->oauth->get( 'users/show_batch', $params );
	}


	/**
	 * èŽ·å�–ç”¨æˆ·çš„å…³æ³¨åˆ—è¡¨
	 *
	 * å¦‚æžœæ²¡æœ‰æ��ä¾›cursorå�‚æ•°ï¼Œå°†å�ªè¿”å›žæœ€å‰�é�¢çš„5000ä¸ªå…³æ³¨id
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
	 * 
	 * @access public
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param int $uid  è¦�èŽ·å�–çš„ç”¨æˆ·çš„IDã€‚
	 * @return array
	 */
	function friends_by_id( $uid, $cursor = 0, $count = 50 )
	{
		$params = array();
		$params['cursor'] = $cursor;
		$params['count'] = $count;
		$params['uid'] = $uid;

		return $this->oauth->get( 'friendships/friends', $params );
	}
	
	
	/**
	 * èŽ·å�–ç”¨æˆ·çš„å…³æ³¨åˆ—è¡¨
	 *
	 * å¦‚æžœæ²¡æœ‰æ��ä¾›cursorå�‚æ•°ï¼Œå°†å�ªè¿”å›žæœ€å‰�é�¢çš„5000ä¸ªå…³æ³¨id
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends friendships/friends}
	 * 
	 * @access public
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param string $screen_name  è¦�èŽ·å�–çš„ç”¨æˆ·çš„ screen_name
	 * @return array
	 */
	function friends_by_name( $screen_name, $cursor = 0, $count = 50 )
	{
		$params = array();
		$params['cursor'] = $cursor;
		$params['count'] = $count;
		$params['screen_name'] = $screen_name;
		return $this->oauth->get( 'friendships/friends', $params );
	}


	/**
	 * èŽ·å�–ä¸¤ä¸ªç”¨æˆ·ä¹‹é—´çš„å…±å�Œå…³æ³¨äººåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/in_common friendships/friends/in_common}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–å…±å�Œå…³æ³¨å…³ç³»çš„ç”¨æˆ·UID
	 * @param int $suid  éœ€è¦�èŽ·å�–å…±å�Œå…³æ³¨å…³ç³»çš„ç”¨æˆ·UIDï¼Œé»˜è®¤ä¸ºå½“å‰�ç™»å½•ç”¨æˆ·ã€‚
	 * @param int $count  å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @return array
	 */
	function friends_in_common( $uid, $suid = NULL, $page = 1, $count = 50 )
	{
		$params = array();
		$params['uid'] = $uid;
		$params['suid'] = $suid;
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'friendships/friends/in_common', $params  );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„å�Œå�‘å…³æ³¨åˆ—è¡¨ï¼Œå�³äº’ç²‰åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/bilateral friendships/friends/bilateral}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–å�Œå�‘å…³æ³¨åˆ—è¡¨çš„ç”¨æˆ·UIDã€‚
	 * @param int $count  å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $sort  æŽ’åº�ç±»åž‹ï¼Œ0ï¼šæŒ‰å…³æ³¨æ—¶é—´æœ€è¿‘æŽ’åº�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function bilateral( $uid, $page = 1, $count = 50, $sort = 0 )
	{
		$params = array();
		$params['uid'] = $uid;
		$params['count'] = $count;
		$params['page'] = $page;
		$params['sort'] = $sort;
		return $this->oauth->get( 'friendships/friends/bilateral', $params  );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„å�Œå�‘å…³æ³¨uidåˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/bilateral/ids friendships/friends/bilateral/ids}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–å�Œå�‘å…³æ³¨åˆ—è¡¨çš„ç”¨æˆ·UIDã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $sort  æŽ’åº�ç±»åž‹ï¼Œ0ï¼šæŒ‰å…³æ³¨æ—¶é—´æœ€è¿‘æŽ’åº�ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function bilateral_ids( $uid, $page = 1, $count = 50, $sort = 0)
	{
		$params = array();
		$params['uid'] = $uid;
		$params['count'] = $count;
		$params['page'] = $page;
		$params['sort'] = $sort;
		return $this->oauth->get( 'friendships/friends/bilateral/ids',  $params  );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„å…³æ³¨åˆ—è¡¨uid
	 *
	 * å¦‚æžœæ²¡æœ‰æ��ä¾›cursorå�‚æ•°ï¼Œå°†å�ªè¿”å›žæœ€å‰�é�¢çš„5000ä¸ªå…³æ³¨id
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/ids friendships/friends/ids}
	 * 
	 * @access public
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼ˆå�³é¡µé�¢å¤§å°�ï¼‰ï¼Œä¸�å¤§äºŽ5000, é»˜è®¤è¿”å›ž500ã€‚
	 * @param int $uid è¦�èŽ·å�–çš„ç”¨æˆ· UIDï¼Œé»˜è®¤ä¸ºå½“å‰�ç”¨æˆ·
	 * @return array
	 */
	function friends_ids_by_id( $uid, $cursor = 0, $count = 500 )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		$params['cursor'] = $cursor;
		$params['count'] = $count;
		return $this->oauth->get( 'friendships/friends/ids', $params );
	}
	
	/**
	 * èŽ·å�–ç”¨æˆ·çš„å…³æ³¨åˆ—è¡¨uid
	 *
	 * å¦‚æžœæ²¡æœ‰æ��ä¾›cursorå�‚æ•°ï¼Œå°†å�ªè¿”å›žæœ€å‰�é�¢çš„5000ä¸ªå…³æ³¨id
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/ids friendships/friends/ids}
	 * 
	 * @access public
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼ˆå�³é¡µé�¢å¤§å°�ï¼‰ï¼Œä¸�å¤§äºŽ5000, é»˜è®¤è¿”å›ž500ã€‚
	 * @param string $screen_name è¦�èŽ·å�–çš„ç”¨æˆ·çš„ screen_nameï¼Œé»˜è®¤ä¸ºå½“å‰�ç”¨æˆ·
	 * @return array
	 */
	function friends_ids_by_name( $screen_name, $cursor = 0, $count = 500 )
	{
		$params = array();
		$params['cursor'] = $cursor;
		$params['count'] = $count;
		$params['screen_name'] = $screen_name;
		return $this->oauth->get( 'friendships/friends/ids', $params );
	}


	/**
	 * æ‰¹é‡�èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·çš„å…³æ³¨äººçš„å¤‡æ³¨ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends/remark_batch friendships/friends/remark_batch}
	 *
	 * @param string $uids  éœ€è¦�èŽ·å�–å¤‡æ³¨çš„ç”¨æˆ·UIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡50ä¸ªã€‚
	 * @return array
	 **/
	function friends_remark_batch( $uids )
	{
		$params = array();
		if (is_array( $uids ) && !empty( $uids )) {
			foreach( $uids as $k => $v) {
				$this->id_format( $uids[$k] );
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}
		return $this->oauth->get( 'friendships/friends/remark_batch', $params  );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„ç²‰ä¸�åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
	 *
	 * @param int $uid  éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·UID
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param int $cursor false è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function followers_by_id( $uid , $cursor = 0 , $count = 50)
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		$params['count'] = $count;
		$params['cursor'] = $cursor;
		return $this->oauth->get( 'friendships/followers', $params  );
	}
	
	/**
	 * èŽ·å�–ç”¨æˆ·çš„ç²‰ä¸�åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
	 *
	 * @param string $screen_name  éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·çš„æ˜µç§°
	 * @param int  $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param int  $cursor false è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function followers_by_name( $screen_name, $cursor = 0 , $count = 50 )
	{
		$params = array();
		$params['screen_name'] = $screen_name;
		$params['count'] = $count;
		$params['cursor'] = $cursor;
		return $this->oauth->get( 'friendships/followers', $params  );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„ç²‰ä¸�åˆ—è¡¨uid
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
	 *
	 * @param int $uid éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·UID
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function followers_ids_by_id( $uid, $cursor = 0 , $count = 50 )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		$params['count'] = $count;
		$params['cursor'] = $cursor;
		return $this->oauth->get( 'friendships/followers/ids', $params  );
	}
	
	/**
	 * èŽ·å�–ç”¨æˆ·çš„ç²‰ä¸�åˆ—è¡¨uid
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/followers friendships/followers}
	 *
	 * @param string $screen_name éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·screen_name
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 **/
	function followers_ids_by_name( $screen_name, $cursor = 0 , $count = 50 )
	{
		$params = array();
		$params['screen_name'] = $screen_name;
		$params['count'] = $count;
		$params['cursor'] = $cursor;
		return $this->oauth->get( 'friendships/followers/ids', $params  );
	}

	/**
	 * èŽ·å�–ä¼˜è´¨ç²‰ä¸�
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/followers/active friendships/followers/active}
	 *
	 * @param int $uid éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·UIDã€‚
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º20ï¼Œæœ€å¤§ä¸�è¶…è¿‡200ã€‚
     * @return array
	 **/
	function followers_active( $uid,  $count = 20)
	{
		$param = array();
		$this->id_format($uid);
		$param['uid'] = $uid;
		$param['count'] = $count;
		return $this->oauth->get( 'friendships/followers/active', $param);
	}


	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·çš„å…³æ³¨äººä¸­å�ˆå…³æ³¨äº†æŒ‡å®šç”¨æˆ·çš„ç”¨æˆ·åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/friends_chain/followers friendships/friends_chain/followers}
	 *
	 * @param int $uid æŒ‡å®šçš„å…³æ³¨ç›®æ ‡ç”¨æˆ·UIDã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @return array
	 **/
	function friends_chain_followers( $uid, $page = 1, $count = 50 )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'friendships/friends_chain/followers',  $params );
	}

	/**
	 * è¿”å›žä¸¤ä¸ªç”¨æˆ·å…³ç³»çš„è¯¦ç»†æƒ…å†µ
	 *
	 * å¦‚æžœæº�ç”¨æˆ·æˆ–ç›®çš„ç”¨æˆ·ä¸�å­˜åœ¨ï¼Œå°†è¿”å›žhttpçš„400é”™è¯¯
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/show friendships/show}
	 * 
	 * @access public
	 * @param mixed $target_id ç›®æ ‡ç”¨æˆ·UID
	 * @param mixed $source_id æº�ç”¨æˆ·UIDï¼Œå�¯é€‰ï¼Œé»˜è®¤ä¸ºå½“å‰�çš„ç”¨æˆ·
	 * @return array
	 */
	function is_followed_by_id( $target_id, $source_id = NULL )
	{
		$params = array();
		$this->id_format($target_id);
		$params['target_id'] = $target_id;

		if ( $source_id != NULL ) {
			$this->id_format($source_id);
			$params['source_id'] = $source_id;
		}

		return $this->oauth->get( 'friendships/show', $params );
	}

	/**
	 * è¿”å›žä¸¤ä¸ªç”¨æˆ·å…³ç³»çš„è¯¦ç»†æƒ…å†µ
	 *
	 * å¦‚æžœæº�ç”¨æˆ·æˆ–ç›®çš„ç”¨æˆ·ä¸�å­˜åœ¨ï¼Œå°†è¿”å›žhttpçš„400é”™è¯¯
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/show friendships/show}
	 * 
	 * @access public
	 * @param mixed $target_name ç›®æ ‡ç”¨æˆ·çš„å¾®å�šæ˜µç§°
	 * @param mixed $source_name æº�ç”¨æˆ·çš„å¾®å�šæ˜µç§°ï¼Œå�¯é€‰ï¼Œé»˜è®¤ä¸ºå½“å‰�çš„ç”¨æˆ·
	 * @return array
	 */
	function is_followed_by_name( $target_name, $source_name = NULL )
	{
		$params = array();
		$params['target_screen_name'] = $target_name;

		if ( $source_name != NULL ) {
			$params['source_screen_name'] = $source_name;
		}

		return $this->oauth->get( 'friendships/show', $params );
	}

	/**
	 * å…³æ³¨ä¸€ä¸ªç”¨æˆ·ã€‚
	 *
	 * æˆ�åŠŸåˆ™è¿”å›žå…³æ³¨äººçš„èµ„æ–™ï¼Œç›®å‰�æœ€å¤šå…³æ³¨2000äººï¼Œå¤±è´¥åˆ™è¿”å›žä¸€æ�¡å­—ç¬¦ä¸²çš„è¯´æ˜Žã€‚å¦‚æžœå·²ç»�å…³æ³¨äº†æ­¤äººï¼Œåˆ™è¿”å›žhttp 403çš„çŠ¶æ€�ã€‚å…³æ³¨ä¸�å­˜åœ¨çš„IDå°†è¿”å›ž400ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/create friendships/create}
	 * 
	 * @access public
	 * @param int $uid è¦�å…³æ³¨çš„ç”¨æˆ·UID
	 * @return array
	 */
	function follow_by_id( $uid )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		return $this->oauth->post( 'friendships/create', $params );
	}
	
	/**
	 * å…³æ³¨ä¸€ä¸ªç”¨æˆ·ã€‚
	 *
	 * æˆ�åŠŸåˆ™è¿”å›žå…³æ³¨äººçš„èµ„æ–™ï¼Œç›®å‰�çš„æœ€å¤šå…³æ³¨2000äººï¼Œå¤±è´¥åˆ™è¿”å›žä¸€æ�¡å­—ç¬¦ä¸²çš„è¯´æ˜Žã€‚å¦‚æžœå·²ç»�å…³æ³¨äº†æ­¤äººï¼Œåˆ™è¿”å›žhttp 403çš„çŠ¶æ€�ã€‚å…³æ³¨ä¸�å­˜åœ¨çš„IDå°†è¿”å›ž400ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/create friendships/create}
	 * 
	 * @access public
	 * @param string $screen_name è¦�å…³æ³¨çš„ç”¨æˆ·æ˜µç§°
	 * @return array
	 */
	function follow_by_name( $screen_name )
	{
		$params = array();
		$params['screen_name'] = $screen_name;
		return $this->oauth->post( 'friendships/create', $params);
	}


	/**
	 * æ ¹æ�®ç”¨æˆ·UIDæ‰¹é‡�å…³æ³¨ç”¨æˆ·
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/create_batch friendships/create_batch}
	 *
	 * @param string $uids è¦�å…³æ³¨çš„ç”¨æˆ·UIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡20ä¸ªã€‚
	 * @return array
	 */
	function follow_create_batch( $uids )
	{
		$params = array();
		if (is_array($uids) && !empty($uids)) {
			foreach($uids as $k => $v) {
				$this->id_format($uids[$k]);
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}
		return $this->oauth->post( 'friendships/create_batch', $params);
	}

	/**
	 * å�–æ¶ˆå…³æ³¨æŸ�ç”¨æˆ·
	 *
	 * å�–æ¶ˆå…³æ³¨æŸ�ç”¨æˆ·ã€‚æˆ�åŠŸåˆ™è¿”å›žè¢«å�–æ¶ˆå…³æ³¨äººçš„èµ„æ–™ï¼Œå¤±è´¥åˆ™è¿”å›žä¸€æ�¡å­—ç¬¦ä¸²çš„è¯´æ˜Žã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/destroy friendships/destroy}
	 * 
	 * @access public
	 * @param int $uid è¦�å�–æ¶ˆå…³æ³¨çš„ç”¨æˆ·UID
	 * @return array
	 */
	function unfollow_by_id( $uid )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		return $this->oauth->post( 'friendships/destroy', $params);
	}
	
	/**
	 * å�–æ¶ˆå…³æ³¨æŸ�ç”¨æˆ·
	 *
	 * å�–æ¶ˆå…³æ³¨æŸ�ç”¨æˆ·ã€‚æˆ�åŠŸåˆ™è¿”å›žè¢«å�–æ¶ˆå…³æ³¨äººçš„èµ„æ–™ï¼Œå¤±è´¥åˆ™è¿”å›žä¸€æ�¡å­—ç¬¦ä¸²çš„è¯´æ˜Žã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/destroy friendships/destroy}
	 * 
	 * @access public
	 * @param string $screen_name è¦�å�–æ¶ˆå…³æ³¨çš„ç”¨æˆ·æ˜µç§°
	 * @return array
	 */
	function unfollow_by_name( $screen_name )
	{
		$params = array();
		$params['screen_name'] = $screen_name;
		return $this->oauth->post( 'friendships/destroy', $params);
	}

	/**
	 * æ›´æ–°å½“å‰�ç™»å½•ç”¨æˆ·æ‰€å…³æ³¨çš„æŸ�ä¸ªå¥½å�‹çš„å¤‡æ³¨ä¿¡æ�¯
	 *
	 * å�ªèƒ½ä¿®æ”¹å½“å‰�ç™»å½•ç”¨æˆ·æ‰€å…³æ³¨çš„ç”¨æˆ·çš„å¤‡æ³¨ä¿¡æ�¯ã€‚å�¦åˆ™å°†ç»™å‡º400é”™è¯¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/friendships/remark/update friendships/remark/update}
	 * 
	 * @access public
	 * @param int $uid éœ€è¦�ä¿®æ”¹å¤‡æ³¨ä¿¡æ�¯çš„ç”¨æˆ·IDã€‚
	 * @param string $remark å¤‡æ³¨ä¿¡æ�¯ã€‚
	 * @return array
	 */
	function update_remark( $uid, $remark )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		$params['remark'] = $remark;
		return $this->oauth->post( 'friendships/remark/update', $params);
	}

	/**
	 * èŽ·å�–å½“å‰�ç”¨æˆ·æœ€æ–°ç§�ä¿¡åˆ—è¡¨
	 *
	 * è¿”å›žç”¨æˆ·çš„æœ€æ–°næ�¡ç§�ä¿¡ï¼Œå¹¶åŒ…å�«å�‘é€�è€…å’ŒæŽ¥å�—è€…çš„è¯¦ç»†èµ„æ–™ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages direct_messages}
	 * 
	 * @access public
	 * @param int $page é¡µç �
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚
	 * @param int64 $since_id è¿”å›žIDæ¯”æ•°å€¼since_idå¤§ï¼ˆæ¯”since_idæ—¶é—´æ™šçš„ï¼‰çš„ç§�ä¿¡ã€‚å�¯é€‰ã€‚
	 * @param int64 $max_id è¿”å›žIDä¸�å¤§äºŽmax_id(æ—¶é—´ä¸�æ™šäºŽmax_id)çš„ç§�ä¿¡ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function list_dm( $page = 1, $count = 50, $since_id = 0, $max_id = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}

		return $this->request_with_pager( 'direct_messages', $page, $count, $params );
	}

	/**
	 * èŽ·å�–å½“å‰�ç”¨æˆ·å�‘é€�çš„æœ€æ–°ç§�ä¿¡åˆ—è¡¨
	 *
	 * è¿”å›žç™»å½•ç”¨æˆ·å·²å�‘é€�æœ€æ–°50æ�¡ç§�ä¿¡ã€‚åŒ…æ‹¬å�‘é€�è€…å’ŒæŽ¥å�—è€…çš„è¯¦ç»†èµ„æ–™ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/sent direct_messages/sent}
	 * 
	 * @access public
	 * @param int $page é¡µç �
	 * @param int $count æ¯�æ¬¡è¿”å›žçš„æœ€å¤§è®°å½•æ•°ï¼Œæœ€å¤šè¿”å›ž200æ�¡ï¼Œé»˜è®¤50ã€‚
	 * @param int64 $since_id è¿”å›žIDæ¯”æ•°å€¼since_idå¤§ï¼ˆæ¯”since_idæ—¶é—´æ™šçš„ï¼‰çš„ç§�ä¿¡ã€‚å�¯é€‰ã€‚
	 * @param int64 $max_id è¿”å›žIDä¸�å¤§äºŽmax_id(æ—¶é—´ä¸�æ™šäºŽmax_id)çš„ç§�ä¿¡ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function list_dm_sent( $page = 1, $count = 50, $since_id = 0, $max_id = 0 )
	{
		$params = array();
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}

		return $this->request_with_pager( 'direct_messages/sent', $page, $count, $params );
	}


	/**
	 * èŽ·å�–ä¸Žå½“å‰�ç™»å½•ç”¨æˆ·æœ‰ç§�ä¿¡å¾€æ�¥çš„ç”¨æˆ·åˆ—è¡¨ï¼Œä¸Žè¯¥ç”¨æˆ·å¾€æ�¥çš„æœ€æ–°ç§�ä¿¡
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/user_list direct_messages/user_list}
	 *
	 * @param int $count  å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º20ã€‚
	 * @param int $cursor è¿”å›žç»“æžœçš„æ¸¸æ ‡ï¼Œä¸‹ä¸€é¡µç”¨è¿”å›žå€¼é‡Œçš„next_cursorï¼Œä¸Šä¸€é¡µç”¨previous_cursorï¼Œé»˜è®¤ä¸º0ã€‚
	 * @return array
	 */
	function dm_user_list( $count = 20, $cursor = 0)
	{
		$params = array();
		$params['count'] = $count;
		$params['cursor'] = $cursor;
		return $this->oauth->get( 'direct_messages/user_list', $params );
	} 

	/**
	 * èŽ·å�–ä¸ŽæŒ‡å®šç”¨æˆ·çš„å¾€æ�¥ç§�ä¿¡åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/conversation direct_messages/conversation}
	 *
	 * @param int $uid éœ€è¦�æŸ¥è¯¢çš„ç”¨æˆ·çš„UIDã€‚
	 * @param int $since_id è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDæ¯”since_idå¤§çš„ç§�ä¿¡ï¼ˆå�³æ¯”since_idæ—¶é—´æ™šçš„ç§�ä¿¡ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $max_id  è‹¥æŒ‡å®šæ­¤å�‚æ•°ï¼Œåˆ™è¿”å›žIDå°�äºŽæˆ–ç­‰äºŽmax_idçš„ç§�ä¿¡ï¼Œé»˜è®¤ä¸º0ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page  è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @return array
	 */
	function dm_conversation( $uid, $page = 1, $count = 50, $since_id = 0, $max_id = 0)
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		if ($since_id) {
			$this->id_format($since_id);
			$params['since_id'] = $since_id;
		}
		if ($max_id) {
			$this->id_format($max_id);
			$params['max_id'] = $max_id;
		}
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'direct_messages/conversation', $params );
	}

	/**
	 * æ ¹æ�®ç§�ä¿¡IDæ‰¹é‡�èŽ·å�–ç§�ä¿¡å†…å®¹
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/show_batch direct_messages/show_batch}
	 *
	 * @param string  $dmids éœ€è¦�æŸ¥è¯¢çš„ç§�ä¿¡IDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œä¸€æ¬¡æœ€å¤š50ä¸ª
	 * @return array
	 */
	function dm_show_batch( $dmids )
	{
		$params = array();
		if (is_array($dmids) && !empty($dmids)) {
			foreach($dmids as $k => $v) {
				$this->id_format($dmids[$k]);
			}
			$params['dmids'] = join(',', $dmids);
		} else {
			$params['dmids'] = $dmids;
		}
		return $this->oauth->get( 'direct_messages/show_batch',  $params );
	}

	/**
	 * å�‘é€�ç§�ä¿¡
	 *
	 * å�‘é€�ä¸€æ�¡ç§�ä¿¡ã€‚æˆ�åŠŸå°†è¿”å›žå®Œæ•´çš„å�‘é€�æ¶ˆæ�¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
	 * 
	 * @access public
	 * @param int $uid ç”¨æˆ·UID
	 * @param string $text è¦�å�‘ç”Ÿçš„æ¶ˆæ�¯å†…å®¹ï¼Œæ–‡æœ¬å¤§å°�å¿…é¡»å°�äºŽ300ä¸ªæ±‰å­—ã€‚
	 * @param int $id éœ€è¦�å�‘é€�çš„å¾®å�šIDã€‚
	 * @return array
	 */
	function send_dm_by_id( $uid, $text, $id = NULL )
	{
		$params = array();
		$this->id_format( $uid );
		$params['text'] = $text;
		$params['uid'] = $uid;
		if ($id) {
			$this->id_format( $id );
			$params['id'] = $id;
		}
		return $this->oauth->post( 'direct_messages/new', $params );
	}
	
	/**
	 * å�‘é€�ç§�ä¿¡
	 *
	 * å�‘é€�ä¸€æ�¡ç§�ä¿¡ã€‚æˆ�åŠŸå°†è¿”å›žå®Œæ•´çš„å�‘é€�æ¶ˆæ�¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/new direct_messages/new}
	 * 
	 * @access public
	 * @param string $screen_name ç”¨æˆ·æ˜µç§°
	 * @param string $text è¦�å�‘ç”Ÿçš„æ¶ˆæ�¯å†…å®¹ï¼Œæ–‡æœ¬å¤§å°�å¿…é¡»å°�äºŽ300ä¸ªæ±‰å­—ã€‚
	 * @param int $id éœ€è¦�å�‘é€�çš„å¾®å�šIDã€‚
	 * @return array
	 */
	function send_dm_by_name( $screen_name, $text, $id = NULL )
	{
		$params = array();
		$params['text'] = $text;
		$params['screen_name'] = $screen_name;
		if ($id) {
			$this->id_format( $id );
			$params['id'] = $id;
		}
		return $this->oauth->post( 'direct_messages/new', $params);
	}

	/**
	 * åˆ é™¤ä¸€æ�¡ç§�ä¿¡
	 *
	 * æŒ‰IDåˆ é™¤ç§�ä¿¡ã€‚æ“�ä½œç”¨æˆ·å¿…é¡»ä¸ºç§�ä¿¡çš„æŽ¥æ”¶äººã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/destroy direct_messages/destroy}
	 * 
	 * @access public
	 * @param int $did è¦�åˆ é™¤çš„ç§�ä¿¡ä¸»é”®ID
	 * @return array
	 */
	function delete_dm( $did )
	{
		$this->id_format($did);
		$params = array();
		$params['id'] = $did;
		return $this->oauth->post('direct_messages/destroy', $params);
	}

	/**
	 * æ‰¹é‡�åˆ é™¤ç§�ä¿¡
	 *
	 * æ‰¹é‡�åˆ é™¤å½“å‰�ç™»å½•ç”¨æˆ·çš„ç§�ä¿¡ã€‚å‡ºçŽ°å¼‚å¸¸æ—¶ï¼Œè¿”å›ž400é”™è¯¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/direct_messages/destroy_batch direct_messages/destroy_batch}
	 * 
	 * @access public
	 * @param mixed $dids æ¬²åˆ é™¤çš„ä¸€ç»„ç§�ä¿¡IDï¼Œç”¨å�Šè§’é€—å�·éš”å¼€ï¼Œæˆ–è€…ç”±ä¸€ç»„è¯„è®ºIDç»„æˆ�çš„æ•°ç»„ã€‚æœ€å¤š20ä¸ªã€‚ä¾‹å¦‚ï¼š"4976494627, 4976262053"æˆ–array(4976494627,4976262053);
	 * @return array
	 */
	function delete_dms( $dids )
	{
		$params = array();
		if (is_array($dids) && !empty($dids)) {
			foreach($dids as $k => $v) {
				$this->id_format($dids[$k]);
			}
			$params['ids'] = join(',', $dids);
		} else {
			$params['ids'] = $dids;
		}

		return $this->oauth->post( 'direct_messages/destroy_batch', $params);
	}
	


	/**
	 * èŽ·å�–ç”¨æˆ·åŸºæœ¬ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/basic account/profile/basic}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–åŸºæœ¬ä¿¡æ�¯çš„ç”¨æˆ·UIDï¼Œé»˜è®¤ä¸ºå½“å‰�ç™»å½•ç”¨æˆ·ã€‚
	 * @return array
	 */
	function account_profile_basic( $uid = NULL  )
	{
		$params = array();
		if ($uid) {
			$this->id_format($uid);
			$params['uid'] = $uid;
		}
		return $this->oauth->get( 'account/profile/basic', $params );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·çš„æ•™è‚²ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/education account/profile/education}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–æ•™è‚²ä¿¡æ�¯çš„ç”¨æˆ·UIDï¼Œé»˜è®¤ä¸ºå½“å‰�ç™»å½•ç”¨æˆ·ã€‚
	 * @return array
	 */
	function account_education( $uid = NULL )
	{
		$params = array();
		if ($uid) {
			$this->id_format($uid);
			$params['uid'] = $uid;
		}
		return $this->oauth->get( 'account/profile/education', $params );
	}

	/**
	 * æ‰¹é‡�èŽ·å�–ç”¨æˆ·çš„æ•™è‚²ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/education_batch account/profile/education_batch}
	 *
	 * @param string $uids éœ€è¦�èŽ·å�–æ•™è‚²ä¿¡æ�¯çš„ç”¨æˆ·UIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡20ã€‚
	 * @return array
	 */
	function account_education_batch( $uids  )
	{
		$params = array();
		if (is_array($uids) && !empty($uids)) {
			foreach($uids as $k => $v) {
				$this->id_format($uids[$k]);
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}

		return $this->oauth->get( 'account/profile/education_batch', $params );
	}


	/**
	 * èŽ·å�–ç”¨æˆ·çš„è�Œä¸šä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/career account/profile/career}
	 *
	 * @param int $uid  éœ€è¦�èŽ·å�–æ•™è‚²ä¿¡æ�¯çš„ç”¨æˆ·UIDï¼Œé»˜è®¤ä¸ºå½“å‰�ç™»å½•ç”¨æˆ·ã€‚
	 * @return array
	 */
	function account_career( $uid = NULL )
	{
		$params = array();
		if ($uid) {
			$this->id_format($uid);
			$params['uid'] = $uid;
		}
		return $this->oauth->get( 'account/profile/career', $params );
	}

	/**
	 * æ‰¹é‡�èŽ·å�–ç”¨æˆ·çš„è�Œä¸šä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/career_batch account/profile/career_batch}
	 *
	 * @param string $uids éœ€è¦�èŽ·å�–æ•™è‚²ä¿¡æ�¯çš„ç”¨æˆ·UIDï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡20ã€‚
	 * @return array
	 */
	function account_career_batch( $uids )
	{
		$params = array();
		if (is_array($uids) && !empty($uids)) {
			foreach($uids as $k => $v) {
				$this->id_format($uids[$k]);
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}

		return $this->oauth->get( 'account/profile/career_batch', $params );
	}

	/**
	 * èŽ·å�–éš�ç§�ä¿¡æ�¯è®¾ç½®æƒ…å†µ
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/get_privacy account/get_privacy}
	 * 
	 * @access public
	 * @return array
	 */
	function get_privacy()
	{
		return $this->oauth->get('account/get_privacy');
	}

	/**
	 * èŽ·å�–æ‰€æœ‰çš„å­¦æ ¡åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/school_list account/profile/school_list}
	 *
	 * @param array $query æ�œç´¢é€‰é¡¹ã€‚æ ¼å¼�ï¼šarray('key0'=>'value0', 'key1'=>'value1', ....)ã€‚æ”¯æŒ�çš„key:
	 *  - province	int		çœ�ä»½èŒƒå›´ï¼Œçœ�ä»½IDã€‚
	 *  - city		int		åŸŽå¸‚èŒƒå›´ï¼ŒåŸŽå¸‚IDã€‚
	 *  - area		int		åŒºåŸŸèŒƒå›´ï¼ŒåŒºIDã€‚
	 *  - type		int		å­¦æ ¡ç±»åž‹ï¼Œ1ï¼šå¤§å­¦ã€�2ï¼šé«˜ä¸­ã€�3ï¼šä¸­ä¸“æŠ€æ ¡ã€�4ï¼šåˆ�ä¸­ã€�5ï¼šå°�å­¦ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - capital	string	å­¦æ ¡é¦–å­—æ¯�ï¼Œé»˜è®¤ä¸ºAã€‚
	 *  - keyword	string	å­¦æ ¡å��ç§°å…³é”®å­—ã€‚
	 *  - count		int		è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * å�‚æ•°keywordä¸ŽcapitaläºŒè€…å¿…é€‰å…¶ä¸€ï¼Œä¸”å�ªèƒ½é€‰å…¶ä¸€ã€‚æŒ‰é¦–å­—æ¯�capitalæŸ¥è¯¢æ—¶ï¼Œå¿…é¡»æ��ä¾›provinceå�‚æ•°ã€‚
	 * @access public
	 * @return array
	 */
	function school_list( $query )
	{
		$params = $query;

		return $this->oauth->get( 'account/profile/school_list', $params );
	}

	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·çš„APIè®¿é—®é¢‘çŽ‡é™�åˆ¶æƒ…å†µ
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/rate_limit_status account/rate_limit_status}
	 * 
	 * @access public
	 * @return array
	 */
	function rate_limit_status()
	{
		return $this->oauth->get( 'account/rate_limit_status' );
	}

	/**
	 * OAuthæŽˆæ�ƒä¹‹å�Žï¼ŒèŽ·å�–æŽˆæ�ƒç”¨æˆ·çš„UID
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/get_uid account/get_uid}
	 * 
	 * @access public
	 * @return array
	 */
	function get_uid()
	{
		return $this->oauth->get( 'account/get_uid' );
	}


	/**
	 * æ›´æ”¹ç”¨æˆ·èµ„æ–™
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/basic_update account/profile/basic_update}
	 * 
	 * @access public
	 * @param array $profile è¦�ä¿®æ”¹çš„èµ„æ–™ã€‚æ ¼å¼�ï¼šarray('key1'=>'value1', 'key2'=>'value2', .....)ã€‚
	 * æ”¯æŒ�ä¿®æ”¹çš„é¡¹ï¼š
	 *  - screen_name		string	ç”¨æˆ·æ˜µç§°ï¼Œä¸�å�¯ä¸ºç©ºã€‚
	 *  - gender	i		string	ç”¨æˆ·æ€§åˆ«ï¼Œmï¼šç”·ã€�fï¼šå¥³ï¼Œä¸�å�¯ä¸ºç©ºã€‚
	 *  - real_name			string	ç”¨æˆ·çœŸå®žå§“å��ã€‚
	 *  - real_name_visible	int		çœŸå®žå§“å��å�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - province	true	int		çœ�ä»½ä»£ç �IDï¼Œä¸�å�¯ä¸ºç©ºã€‚
	 *  - city	true		int		åŸŽå¸‚ä»£ç �IDï¼Œä¸�å�¯ä¸ºç©ºã€‚
	 *  - birthday			string	ç”¨æˆ·ç”Ÿæ—¥ï¼Œæ ¼å¼�ï¼šyyyy-mm-ddã€‚
	 *  - birthday_visible	int		ç”Ÿæ—¥å�¯è§�èŒƒå›´ï¼Œ0ï¼šä¿�å¯†ã€�1ï¼šå�ªæ˜¾ç¤ºæœˆæ—¥ã€�2ï¼šå�ªæ˜¾ç¤ºæ˜Ÿåº§ã€�3ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - qq				string	ç”¨æˆ·QQå�·ç �ã€‚
	 *  - qq_visible		int		ç”¨æˆ·QQå�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - msn				string	ç”¨æˆ·MSNã€‚
	 *  - msn_visible		int		ç”¨æˆ·MSNå�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - url				string	ç”¨æˆ·å�šå®¢åœ°å�€ã€‚
	 *  - url_visible		int		ç”¨æˆ·å�šå®¢åœ°å�€å�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - credentials_type	int		è¯�ä»¶ç±»åž‹ï¼Œ1ï¼šèº«ä»½è¯�ã€�2ï¼šå­¦ç”Ÿè¯�ã€�3ï¼šå†›å®˜è¯�ã€�4ï¼šæŠ¤ç…§ã€‚
	 *  - credentials_num	string	è¯�ä»¶å�·ç �ã€‚
	 *  - email				string	ç”¨æˆ·å¸¸ç”¨é‚®ç®±åœ°å�€ã€‚
	 *  - email_visible		int		ç”¨æˆ·å¸¸ç”¨é‚®ç®±åœ°å�€å�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - lang				string	è¯­è¨€ç‰ˆæœ¬ï¼Œzh_cnï¼šç®€ä½“ä¸­æ–‡ã€�zh_twï¼šç¹�ä½“ä¸­æ–‡ã€‚
	 *  - description		string	ç”¨æˆ·æ��è¿°ï¼Œæœ€é•¿ä¸�è¶…è¿‡70ä¸ªæ±‰å­—ã€‚
	 * å¡«å†™birthdayå�‚æ•°æ—¶ï¼Œå�šå¦‚ä¸‹çº¦å®šï¼š
	 *  - å�ªå¡«å¹´ä»½æ—¶ï¼Œé‡‡ç”¨1986-00-00æ ¼å¼�ï¼›
	 *  - å�ªå¡«æœˆä»½æ—¶ï¼Œé‡‡ç”¨0000-08-00æ ¼å¼�ï¼›
	 *  - å�ªå¡«æŸ�æ—¥æ—¶ï¼Œé‡‡ç”¨0000-00-28æ ¼å¼�ã€‚
	 * @return array
	 */
	function update_profile( $profile )
	{
		return $this->oauth->post( 'account/profile/basic_update',  $profile);
	}


	/**
	 * è®¾ç½®æ•™è‚²ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/edu_update account/profile/edu_update}
	 * 
	 * @access public
	 * @param array $edu_update è¦�ä¿®æ”¹çš„å­¦æ ¡ä¿¡æ�¯ã€‚æ ¼å¼�ï¼šarray('key1'=>'value1', 'key2'=>'value2', .....)ã€‚
	 * æ”¯æŒ�è®¾ç½®çš„é¡¹ï¼š
	 *  - type			int		å­¦æ ¡ç±»åž‹ï¼Œ1ï¼šå¤§å­¦ã€�2ï¼šé«˜ä¸­ã€�3ï¼šä¸­ä¸“æŠ€æ ¡ã€�4ï¼šåˆ�ä¸­ã€�5ï¼šå°�å­¦ï¼Œé»˜è®¤ä¸º1ã€‚å¿…å¡«å�‚æ•°
	 *  - school_id	`	int		å­¦æ ¡ä»£ç �ï¼Œå¿…å¡«å�‚æ•°
	 *  - id			string	éœ€è¦�ä¿®æ”¹çš„æ•™è‚²ä¿¡æ�¯IDï¼Œä¸�ä¼ åˆ™ä¸ºæ–°å»ºï¼Œä¼ åˆ™ä¸ºæ›´æ–°ã€‚
	 *  - year			int		å…¥å­¦å¹´ä»½ï¼Œæœ€å°�ä¸º1900ï¼Œæœ€å¤§ä¸�è¶…è¿‡å½“å‰�å¹´ä»½
	 *  - department	string	é™¢ç³»æˆ–è€…ç�­åˆ«ã€‚
	 *  - visible		int		å¼€æ”¾ç­‰çº§ï¼Œ0ï¼šä»…è‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨çš„äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 * @return array
	 */
	function edu_update( $edu_update )
	{
		return $this->oauth->post( 'account/profile/edu_update',  $edu_update);
	}

	/**
	 * æ ¹æ�®å­¦æ ¡IDåˆ é™¤ç”¨æˆ·çš„æ•™è‚²ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/edu_destroy account/profile/edu_destroy}
	 * 
	 * @param int $id æ•™è‚²ä¿¡æ�¯é‡Œçš„å­¦æ ¡IDã€‚
	 * @return array
	 */
	function edu_destroy( $id )
	{
		$this->id_format( $id );
		$params = array();
		$params['id'] = $id;
		return $this->oauth->post( 'account/profile/edu_destroy', $params);
	}

	/**
	 * è®¾ç½®è�Œä¸šä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/car_update account/profile/car_update}
	 * 
	 * @param array $car_update è¦�ä¿®æ”¹çš„è�Œä¸šä¿¡æ�¯ã€‚æ ¼å¼�ï¼šarray('key1'=>'value1', 'key2'=>'value2', .....)ã€‚
	 * æ”¯æŒ�è®¾ç½®çš„é¡¹ï¼š
	 *  - id			string	éœ€è¦�æ›´æ–°çš„è�Œä¸šä¿¡æ�¯IDã€‚
	 *  - start			int		è¿›å…¥å…¬å�¸å¹´ä»½ï¼Œæœ€å°�ä¸º1900ï¼Œæœ€å¤§ä¸ºå½“å¹´å¹´ä»½ã€‚
	 *  - end			int		ç¦»å¼€å…¬å�¸å¹´ä»½ï¼Œè‡³ä»Šå¡«0ã€‚
	 *  - department	string	å·¥ä½œéƒ¨é—¨ã€‚
	 *  - visible		int		å�¯è§�èŒƒå›´ï¼Œ0ï¼šè‡ªå·±å�¯è§�ã€�1ï¼šå…³æ³¨äººå�¯è§�ã€�2ï¼šæ‰€æœ‰äººå�¯è§�ã€‚
	 *  - province		int		çœ�ä»½ä»£ç �IDï¼Œä¸�å�¯ä¸ºç©ºå€¼ã€‚
	 *  - city			int		åŸŽå¸‚ä»£ç �IDï¼Œä¸�å�¯ä¸ºç©ºå€¼ã€‚
	 *  - company		string	å…¬å�¸å��ç§°ï¼Œä¸�å�¯ä¸ºç©ºå€¼ã€‚
	 * å�‚æ•°provinceä¸ŽcityäºŒè€…å¿…é€‰å…¶ä¸€<br />
	 * å�‚æ•°idä¸ºç©ºï¼Œåˆ™ä¸ºæ–°å»ºè�Œä¸šä¿¡æ�¯ï¼Œå�‚æ•°companyå�˜ä¸ºå¿…å¡«é¡¹ï¼Œå�‚æ•°idé�žç©ºï¼Œåˆ™ä¸ºæ›´æ–°ï¼Œå�‚æ•°companyå�¯é€‰
	 * @return array
	 */
	function car_update( $car_update )
	{
		return $this->oauth->post( 'account/profile/car_update', $car_update);
	}

	/**
	 * æ ¹æ�®å…¬å�¸IDåˆ é™¤ç”¨æˆ·çš„è�Œä¸šä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/profile/car_destroy account/profile/car_destroy}
	 * 
	 * @access public
	 * @param int $id  è�Œä¸šä¿¡æ�¯é‡Œçš„å…¬å�¸ID
	 * @return array
	 */
	function car_destroy( $id )
	{
		$this->id_format($id);
		$params = array();
		$params['id'] = $id;
		return $this->oauth->post( 'account/profile/car_destroy', $params);
	}

	/**
	 * æ›´æ”¹å¤´åƒ�
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/avatar/upload account/avatar/upload}
	 *
	 * @param string $image_path è¦�ä¸Šä¼ çš„å¤´åƒ�è·¯å¾„, æ”¯æŒ�urlã€‚[å�ªæ”¯æŒ�png/jpg/gifä¸‰ç§�æ ¼å¼�, å¢žåŠ æ ¼å¼�è¯·ä¿®æ”¹get_image_mimeæ–¹æ³•] å¿…é¡»ä¸ºå°�äºŽ700Kçš„æœ‰æ•ˆçš„GIF, JPGå›¾ç‰‡. å¦‚æžœå›¾ç‰‡å¤§äºŽ500åƒ�ç´ å°†æŒ‰æ¯”ä¾‹ç¼©æ”¾ã€‚
	 * @return array
	 */
	function update_profile_image( $image_path )
	{
		$params = array();
		$params['image'] = "@{$image_path}";

		return $this->oauth->post('account/avatar/upload', $params);
	}

	/**
	 * è®¾ç½®éš�ç§�ä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/account/update_privacy account/update_privacy}
	 * 
	 * @param array $privacy_settings è¦�ä¿®æ”¹çš„éš�ç§�è®¾ç½®ã€‚æ ¼å¼�ï¼šarray('key1'=>'value1', 'key2'=>'value2', .....)ã€‚
	 * æ”¯æŒ�è®¾ç½®çš„é¡¹ï¼š
	 *  - comment	int	æ˜¯å�¦å�¯ä»¥è¯„è®ºæˆ‘çš„å¾®å�šï¼Œ0ï¼šæ‰€æœ‰äººã€�1ï¼šå…³æ³¨çš„äººï¼Œé»˜è®¤ä¸º0ã€‚
	 *  - geo		int	æ˜¯å�¦å¼€å�¯åœ°ç�†ä¿¡æ�¯ï¼Œ0ï¼šä¸�å¼€å�¯ã€�1ï¼šå¼€å�¯ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - message	int	æ˜¯å�¦å�¯ä»¥ç»™æˆ‘å�‘ç§�ä¿¡ï¼Œ0ï¼šæ‰€æœ‰äººã€�1ï¼šå…³æ³¨çš„äººï¼Œé»˜è®¤ä¸º0ã€‚
	 *  - realname	int	æ˜¯å�¦å�¯ä»¥é€šè¿‡çœŸå��æ�œç´¢åˆ°æˆ‘ï¼Œ0ï¼šä¸�å�¯ä»¥ã€�1ï¼šå�¯ä»¥ï¼Œé»˜è®¤ä¸º0ã€‚
	 *  - badge		int	å‹‹ç« æ˜¯å�¦å�¯è§�ï¼Œ0ï¼šä¸�å�¯è§�ã€�1ï¼šå�¯è§�ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - mobile	int	æ˜¯å�¦å�¯ä»¥é€šè¿‡æ‰‹æœºå�·ç �æ�œç´¢åˆ°æˆ‘ï¼Œ0ï¼šä¸�å�¯ä»¥ã€�1ï¼šå�¯ä»¥ï¼Œé»˜è®¤ä¸º0ã€‚
	 * ä»¥ä¸Šå�‚æ•°å…¨éƒ¨é€‰å¡«
	 * @return array
	 */
	function update_privacy( $privacy_settings )
	{
		return $this->oauth->post( 'account/update_privacy', $privacy_settings);
	}


	/**
	 * èŽ·å�–å½“å‰�ç”¨æˆ·çš„æ”¶è—�åˆ—è¡¨
	 *
	 * è¿”å›žç”¨æˆ·çš„å�‘å¸ƒçš„æœ€è¿‘20æ�¡æ”¶è—�ä¿¡æ�¯ï¼Œå’Œç”¨æˆ·æ”¶è—�é¡µé�¢è¿”å›žå†…å®¹æ˜¯ä¸€è‡´çš„ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites favorites}
	 * 
	 * @access public
	 * @param  int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param  int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @return array
	 */
	function get_favorites( $page = 1, $count = 50 )
	{
		$params = array();
		$params['page'] = intval($page);
		$params['count'] = intval($count);

		return $this->oauth->get( 'favorites', $params );
	}


	/**
	 * æ ¹æ�®æ”¶è—�IDèŽ·å�–æŒ‡å®šçš„æ”¶è—�ä¿¡æ�¯
	 *
	 * æ ¹æ�®æ”¶è—�IDèŽ·å�–æŒ‡å®šçš„æ”¶è—�ä¿¡æ�¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/show favorites/show}
	 * 
	 * @access public
	 * @param int $id éœ€è¦�æŸ¥è¯¢çš„æ”¶è—�IDã€‚
	 * @return array
	 */
	function favorites_show( $id )
	{
		$params = array();
		$this->id_format($id);
		$params['id'] = $id;
		return $this->oauth->get( 'favorites/show', $params );
	}


	/**
	 * æ ¹æ�®æ ‡ç­¾èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·è¯¥æ ‡ç­¾ä¸‹çš„æ”¶è—�åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/by_tags favorites/by_tags}
	 *
	 * 
	 * @param int $tid  éœ€è¦�æŸ¥è¯¢çš„æ ‡ç­¾IDã€‚'
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @return array
	 */
	function favorites_by_tags( $tid, $page = 1, $count = 50)
	{
		$params = array();
		$params['tid'] = $tid;
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'favorites/by_tags', $params );
	}


	/**
	 * èŽ·å�–å½“å‰�ç™»å½•ç”¨æˆ·çš„æ”¶è—�æ ‡ç­¾åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/tags favorites/tags}
	 * 
	 * @access public
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º50ã€‚
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @return array
	 */
	function favorites_tags( $page = 1, $count = 50)
	{
		$params = array();
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'favorites/tags', $params );
	}


	/**
	 * æ”¶è—�ä¸€æ�¡å¾®å�šä¿¡æ�¯
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/create favorites/create}
	 * 
	 * @access public
	 * @param int $sid æ”¶è—�çš„å¾®å�šid
	 * @return array
	 */
	function add_to_favorites( $sid )
	{
		$this->id_format($sid);
		$params = array();
		$params['id'] = $sid;

		return $this->oauth->post( 'favorites/create', $params );
	}

	/**
	 * åˆ é™¤å¾®å�šæ”¶è—�ã€‚
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/destroy favorites/destroy}
	 * 
	 * @access public
	 * @param int $id è¦�åˆ é™¤çš„æ”¶è—�å¾®å�šä¿¡æ�¯ID.
	 * @return array
	 */
	function remove_from_favorites( $id )
	{
		$this->id_format($id);
		$params = array();
		$params['id'] = $id;
		return $this->oauth->post( 'favorites/destroy', $params);
	}


	/**
	 * æ‰¹é‡�åˆ é™¤å¾®å�šæ”¶è—�ã€‚
	 *
	 * æ‰¹é‡�åˆ é™¤å½“å‰�ç™»å½•ç”¨æˆ·çš„æ”¶è—�ã€‚å‡ºçŽ°å¼‚å¸¸æ—¶ï¼Œè¿”å›žHTTP400é”™è¯¯ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/destroy_batch favorites/destroy_batch}
	 * 
	 * @access public
	 * @param mixed $fids æ¬²åˆ é™¤çš„ä¸€ç»„ç§�ä¿¡IDï¼Œç”¨å�Šè§’é€—å�·éš”å¼€ï¼Œæˆ–è€…ç”±ä¸€ç»„è¯„è®ºIDç»„æˆ�çš„æ•°ç»„ã€‚æœ€å¤š20ä¸ªã€‚ä¾‹å¦‚ï¼š"231101027525486630,201100826122315375"æˆ–array(231101027525486630,201100826122315375);
	 * @return array
	 */
	function remove_from_favorites_batch( $fids )
	{
		$params = array();
		if (is_array($fids) && !empty($fids)) {
			foreach ($fids as $k => $v) {
				$this->id_format($fids[$k]);
			}
			$params['ids'] = join(',', $fids);
		} else {
			$params['ids'] = $fids;
		}

		return $this->oauth->post( 'favorites/destroy_batch', $params);
	}


	/**
	 * æ›´æ–°ä¸€æ�¡æ”¶è—�çš„æ”¶è—�æ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/tags/update favorites/tags/update}
	 * 
	 * @access public
	 * @param int $id éœ€è¦�æ›´æ–°çš„æ”¶è—�IDã€‚
	 * @param string $tags éœ€è¦�æ›´æ–°çš„æ ‡ç­¾å†…å®¹ï¼Œç”¨å�Šè§’é€—å�·åˆ†éš”ï¼Œæœ€å¤šä¸�è¶…è¿‡2æ�¡ã€‚
	 * @return array
	 */
	function favorites_tags_update( $id,  $tags )
	{
		$params = array();
		$params['id'] = $id;
		if (is_array($tags) && !empty($tags)) {
			foreach ($tags as $k => $v) {
				$this->id_format($tags[$k]);
			}
			$params['tags'] = join(',', $tags);
		} else {
			$params['tags'] = $tags;
		}
		return $this->oauth->post( 'favorites/tags/update', $params );
	}

	/**
	 * æ›´æ–°å½“å‰�ç™»å½•ç”¨æˆ·æ‰€æœ‰æ”¶è—�ä¸‹çš„æŒ‡å®šæ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/tags/update_batch favorites/tags/update_batch}
	 *
	 * @param int $tid  éœ€è¦�æ›´æ–°çš„æ ‡ç­¾IDã€‚å¿…å¡«
	 * @param string $tag  éœ€è¦�æ›´æ–°çš„æ ‡ç­¾å†…å®¹ã€‚å¿…å¡«
	 * @return array
	 */
	function favorites_update_batch( $tid, $tag )
	{
		$params = array();
		$params['tid'] = $tid;
		$params['tag'] = $tag;
		return $this->oauth->post( 'favorites/tags/update_batch', $params);
	}

	/**
	 * åˆ é™¤å½“å‰�ç™»å½•ç”¨æˆ·æ‰€æœ‰æ”¶è—�ä¸‹çš„æŒ‡å®šæ ‡ç­¾
	 *
	 * åˆ é™¤æ ‡ç­¾å�Žï¼Œè¯¥ç”¨æˆ·æ‰€æœ‰æ”¶è—�ä¸­ï¼Œæ·»åŠ äº†è¯¥æ ‡ç­¾çš„æ”¶è—�å�‡è§£é™¤ä¸Žè¯¥æ ‡ç­¾çš„å…³è�”å…³ç³»
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/favorites/tags/destroy_batch favorites/tags/destroy_batch}
	 *
	 * @param int $tid  éœ€è¦�æ›´æ–°çš„æ ‡ç­¾IDã€‚å¿…å¡«
	 * @return array
	 */
	function favorites_tags_destroy_batch( $tid )
	{
		$params = array();
		$params['tid'] = $tid;
		return $this->oauth->post( 'favorites/tags/destroy_batch', $params);
	}

	/**
	 * èŽ·å�–æŸ�ç”¨æˆ·çš„è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends trends}
	 * 
	 * @param int $uid æŸ¥è¯¢ç”¨æˆ·çš„IDã€‚é»˜è®¤ä¸ºå½“å‰�ç”¨æˆ·ã€‚å�¯é€‰ã€‚
	 * @param int $page æŒ‡å®šè¿”å›žç»“æžœçš„é¡µç �ã€‚å�¯é€‰ã€‚
	 * @param int $count å�•é¡µå¤§å°�ã€‚ç¼ºçœ�å€¼10ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function get_trends( $uid = NULL, $page = 1, $count = 10 )
	{
		$params = array();
		if ($uid) {
			$params['uid'] = $uid;
		} else {
			$user_info = $this->get_uid();
			$params['uid'] = $user_info['uid'];
		}
		$this->id_format( $params['uid'] );
		$params['page'] = $page;
		$params['count'] = $count;
		return $this->oauth->get( 'trends', $params );
	}


	/**
	 * åˆ¤æ–­å½“å‰�ç”¨æˆ·æ˜¯å�¦å…³æ³¨æŸ�è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/is_follow trends/is_follow}
	 * 
	 * @access public
	 * @param string $trend_name è¯�é¢˜å…³é”®å­—ã€‚
	 * @return array
	 */
	function trends_is_follow( $trend_name )
	{
		$params = array();
		$params['trend_name'] = $trend_name;
		return $this->oauth->get( 'trends/is_follow', $params );
	}

	/**
	 * è¿”å›žæœ€è¿‘ä¸€å°�æ—¶å†…çš„çƒ­é—¨è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/hourly trends/hourly}
	 * 
	 * @param  int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1è¡¨ç¤ºåŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ï¼Œé»˜è®¤ä¸º0ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function hourly_trends( $base_app = 0 )
	{
		$params = array();
		$params['base_app'] = $base_app;

		return $this->oauth->get( 'trends/hourly', $params );
	}

	/**
	 * è¿”å›žæœ€è¿‘ä¸€å¤©å†…çš„çƒ­é—¨è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/daily trends/daily}
	 * 
	 * @param int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1è¡¨ç¤ºåŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ï¼Œé»˜è®¤ä¸º0ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function daily_trends( $base_app = 0 )
	{
		$params = array();
		$params['base_app'] = $base_app;

		return $this->oauth->get( 'trends/daily', $params );
	}

	/**
	 * è¿”å›žæœ€è¿‘ä¸€å‘¨å†…çš„çƒ­é—¨è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/weekly trends/weekly}
	 * 
	 * @access public
	 * @param int $base_app æ˜¯å�¦åŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ã€‚1è¡¨ç¤ºåŸºäºŽå½“å‰�åº”ç”¨æ�¥èŽ·å�–æ•°æ�®ï¼Œé»˜è®¤ä¸º0ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function weekly_trends( $base_app = 0 )
	{
		$params = array();
		$params['base_app'] = $base_app;

		return $this->oauth->get( 'trends/weekly', $params );
	}

	/**
	 * å…³æ³¨æŸ�è¯�é¢˜
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/follow trends/follow}
	 * 
	 * @access public
	 * @param string $trend_name è¦�å…³æ³¨çš„è¯�é¢˜å…³é”®è¯�ã€‚
	 * @return array
	 */
	function follow_trends( $trend_name )
	{
		$params = array();
		$params['trend_name'] = $trend_name;
		return $this->oauth->post( 'trends/follow', $params );
	}

	/**
	 * å�–æ¶ˆå¯¹æŸ�è¯�é¢˜çš„å…³æ³¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/trends/destroy trends/destroy}
	 * 
	 * @access public
	 * @param int $tid è¦�å�–æ¶ˆå…³æ³¨çš„è¯�é¢˜IDã€‚
	 * @return array
	 */
	function unfollow_trends( $tid )
	{
		$this->id_format($tid);

		$params = array();
		$params['trend_id'] = $tid;

		return $this->oauth->post( 'trends/destroy', $params );
	}

	/**
	 * è¿”å›žæŒ‡å®šç”¨æˆ·çš„æ ‡ç­¾åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags tags}
	 * 
	 * @param int $uid æŸ¥è¯¢ç”¨æˆ·çš„IDã€‚é»˜è®¤ä¸ºå½“å‰�ç”¨æˆ·ã€‚å�¯é€‰ã€‚
	 * @param int $page æŒ‡å®šè¿”å›žç»“æžœçš„é¡µç �ã€‚å�¯é€‰ã€‚
	 * @param int $count å�•é¡µå¤§å°�ã€‚ç¼ºçœ�å€¼20ï¼Œæœ€å¤§å€¼200ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function get_tags( $uid = NULL, $page = 1, $count = 20 )
	{
		$params = array();
		if ( $uid ) {
			$params['uid'] = $uid;
		} else {
			$user_info = $this->get_uid();
			$params['uid'] = $user_info['uid'];
		}
		$this->id_format( $params['uid'] );
		$params['page'] = $page;
		$params['count'] = $count;
		return $this->oauth->get( 'tags', $params );
	}

	/**
	 * æ‰¹é‡�èŽ·å�–ç”¨æˆ·çš„æ ‡ç­¾åˆ—è¡¨
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags/tags_batch tags/tags_batch}
	 * 
	 * @param  string $uids è¦�èŽ·å�–æ ‡ç­¾çš„ç”¨æˆ·IDã€‚æœ€å¤§20ï¼Œé€—å�·åˆ†éš”ã€‚å¿…å¡«
	 * @return array
	 */
	function get_tags_batch( $uids )
	{
		$params = array();
		if (is_array( $uids ) && !empty( $uids )) {
			foreach ($uids as $k => $v) {
				$this->id_format( $uids[$k] );
			}
			$params['uids'] = join(',', $uids);
		} else {
			$params['uids'] = $uids;
		}
		return $this->oauth->get( 'tags/tags_batch', $params );
	}

	/**
	 * è¿”å›žç”¨æˆ·æ„Ÿå…´è¶£çš„æ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags/suggestions tags/suggestions}
	 * 
	 * @access public
	 * @param int $count å�•é¡µå¤§å°�ã€‚ç¼ºçœ�å€¼10ï¼Œæœ€å¤§å€¼10ã€‚å�¯é€‰ã€‚
	 * @return array
	 */
	function get_suggest_tags( $count = 10)
	{
		$params = array();
		$params['count'] = intval($count);
		return $this->oauth->get( 'tags/suggestions', $params );
	}

	/**
	 * ä¸ºå½“å‰�ç™»å½•ç”¨æˆ·æ·»åŠ æ–°çš„ç”¨æˆ·æ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags/create tags/create}
	 * 
	 * @access public
	 * @param mixed $tags è¦�åˆ›å»ºçš„ä¸€ç»„æ ‡ç­¾ï¼Œæ¯�ä¸ªæ ‡ç­¾çš„é•¿åº¦ä¸�å�¯è¶…è¿‡7ä¸ªæ±‰å­—ï¼Œ14ä¸ªå�Šè§’å­—ç¬¦ã€‚å¤šä¸ªæ ‡ç­¾ä¹‹é—´ç”¨é€—å�·é—´éš”ï¼Œæˆ–ç”±å¤šä¸ªæ ‡ç­¾æž„æˆ�çš„æ•°ç»„ã€‚å¦‚ï¼š"abc,drf,efgh,tt"æˆ–array("abc", "drf", "efgh", "tt")
	 * @return array
	 */
	function add_tags( $tags )
	{
		$params = array();
		if (is_array($tags) && !empty($tags)) {
			$params['tags'] = join(',', $tags);
		} else {
			$params['tags'] = $tags;
		}
		return $this->oauth->post( 'tags/create', $params);
	}

	/**
	 * åˆ é™¤æ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags/destroy tags/destroy}
	 * 
	 * @access public
	 * @param int $tag_id æ ‡ç­¾IDï¼Œå¿…å¡«å�‚æ•°
	 * @return array
	 */
	function delete_tag( $tag_id )
	{
		$params = array();
		$params['tag_id'] = $tag_id;
		return $this->oauth->post( 'tags/destroy', $params );
	}

	/**
	 * æ‰¹é‡�åˆ é™¤æ ‡ç­¾
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/tags/destroy_batch tags/destroy_batch}
	 * 
	 * @access public
	 * @param mixed $ids å¿…é€‰å�‚æ•°ï¼Œè¦�åˆ é™¤çš„tag idï¼Œå¤šä¸ªidç”¨å�Šè§’é€—å�·åˆ†å‰²ï¼Œæœ€å¤š10ä¸ªã€‚æˆ–ç”±å¤šä¸ªtag idæž„æˆ�çš„æ•°ç»„ã€‚å¦‚ï¼šâ€œ553,554,555"æˆ–array(553, 554, 555)
	 * @return array
	 */
	function delete_tags( $ids )
	{
		$params = array();
		if (is_array($ids) && !empty($ids)) {
			$params['ids'] = join(',', $ids);
		} else {
			$params['ids'] = $ids;
		}
		return $this->oauth->post( 'tags/destroy_batch', $params );
	}


	/**
	 * éªŒè¯�æ˜µç§°æ˜¯å�¦å�¯ç”¨ï¼Œå¹¶ç»™äºˆå»ºè®®æ˜µç§°
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/register/verify_nickname register/verify_nickname}
	 *
	 * @param string $nickname éœ€è¦�éªŒè¯�çš„æ˜µç§°ã€‚4-20ä¸ªå­—ç¬¦ï¼Œæ”¯æŒ�ä¸­è‹±æ–‡ã€�æ•°å­—ã€�"_"æˆ–å‡�å�·ã€‚å¿…å¡«
	 * @return array
	 */
	function verify_nickname( $nickname )
	{
		$params = array();
		$params['nickname'] = $nickname;
		return $this->oauth->get( 'register/verify_nickname', $params );
	}



	/**
	 * æ�œç´¢ç”¨æˆ·æ—¶çš„è�”æƒ³æ�œç´¢å»ºè®®
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/suggestions/users search/suggestions/users}
	 *
	 * @param string $q æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»å�šURLencodingã€‚å¿…å¡«,ä¸­é—´æœ€å¥½ä¸�è¦�å‡ºçŽ°ç©ºæ ¼
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @return array
	 */
	function search_users( $q,  $count = 10 )
	{
		$params = array();
		$params['q'] = $q;
		$params['count'] = $count;
		return $this->oauth->get( 'search/suggestions/users',  $params );
	}


	/**
	 * æ�œç´¢å¾®å�šæ—¶çš„è�”æƒ³æ�œç´¢å»ºè®®
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/suggestions/statuses search/suggestions/statuses}
	 *
	 * @param string $q æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»å�šURLencodingã€‚å¿…å¡«
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @return array
	 */
	function search_statuses( $q,  $count = 10)
	{
		$params = array();
		$params['q'] = $q;
		$params['count'] = $count;
		return $this->oauth->get( 'search/suggestions/statuses', $params );
	}


	/**
	 * æ�œç´¢å­¦æ ¡æ—¶çš„è�”æƒ³æ�œç´¢å»ºè®®
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/suggestions/schools search/suggestions/schools}
	 *
	 * @param string $q æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»å�šURLencodingã€‚å¿…å¡«
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @param int type å­¦æ ¡ç±»åž‹ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šå¤§å­¦ã€�2ï¼šé«˜ä¸­ã€�3ï¼šä¸­ä¸“æŠ€æ ¡ã€�4ï¼šåˆ�ä¸­ã€�5ï¼šå°�å­¦ï¼Œé»˜è®¤ä¸º0ã€‚é€‰å¡«
	 * @return array
	 */
	function search_schools( $q,  $count = 10,  $type = 1)
	{
		$params = array();
		$params['q'] = $q;
		$params['count'] = $count;
		$params['type'] = $type;
		return $this->oauth->get( 'search/suggestions/schools', $params );
	}

	/**
	 * æ�œç´¢å…¬å�¸æ—¶çš„è�”æƒ³æ�œç´¢å»ºè®®
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/suggestions/companies search/suggestions/companies}
	 *
	 * @param string $q æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»å�šURLencodingã€‚å¿…å¡«
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @return array
	 */
	function search_companies( $q, $count = 10)
	{
		$params = array();
		$params['q'] = $q;
		$params['count'] = $count;
		return $this->oauth->get( 'search/suggestions/companies', $params );
	}


	/**
	 * ï¼ ç”¨æˆ·æ—¶çš„è�”æƒ³å»ºè®®
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/suggestions/at_users search/suggestions/at_users}
	 *
	 * @param string $q æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»å�šURLencodingã€‚å¿…å¡«
	 * @param int $count è¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @param int $type è�”æƒ³ç±»åž‹ï¼Œ0ï¼šå…³æ³¨ã€�1ï¼šç²‰ä¸�ã€‚å¿…å¡«
	 * @param int $range è�”æƒ³èŒƒå›´ï¼Œ0ï¼šå�ªè�”æƒ³å…³æ³¨äººã€�1ï¼šå�ªè�”æƒ³å…³æ³¨äººçš„å¤‡æ³¨ã€�2ï¼šå…¨éƒ¨ï¼Œé»˜è®¤ä¸º2ã€‚é€‰å¡«
	 * @return array
	 */
	function search_at_users( $q, $count = 10, $type=0, $range = 2)
	{
		$params = array();
		$params['q'] = $q;
		$params['count'] = $count;
		$params['type'] = $type;
		$params['range'] = $range;
		return $this->oauth->get( 'search/suggestions/at_users', $params );
	}


	


	/**
	 * æ�œç´¢ä¸ŽæŒ‡å®šçš„ä¸€ä¸ªæˆ–å¤šä¸ªæ�¡ä»¶ç›¸åŒ¹é…�çš„å¾®å�š
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/statuses search/statuses}
	 *
	 * @param array $query æ�œç´¢é€‰é¡¹ã€‚æ ¼å¼�ï¼šarray('key0'=>'value0', 'key1'=>'value1', ....)ã€‚æ”¯æŒ�çš„key:
	 *  - q				string	æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»è¿›è¡ŒURLencodeã€‚
	 *  - filter_ori	int		è¿‡æ»¤å™¨ï¼Œæ˜¯å�¦ä¸ºåŽŸåˆ›ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŽŸåˆ›ã€�2ï¼šè½¬å�‘ï¼Œé»˜è®¤ä¸º0ã€‚
	 *  - filter_pic	int		è¿‡æ»¤å™¨ã€‚æ˜¯å�¦åŒ…å�«å›¾ç‰‡ï¼Œ0ï¼šå…¨éƒ¨ã€�1ï¼šåŒ…å�«ã€�2ï¼šä¸�åŒ…å�«ï¼Œé»˜è®¤ä¸º0ã€‚
	 *  - fuid			int		æ�œç´¢çš„å¾®å�šä½œè€…çš„ç”¨æˆ·UIDã€‚
	 *  - province		int		æ�œç´¢çš„çœ�ä»½èŒƒå›´ï¼Œçœ�ä»½IDã€‚
	 *  - city			int		æ�œç´¢çš„åŸŽå¸‚èŒƒå›´ï¼ŒåŸŽå¸‚IDã€‚
	 *  - starttime		int		å¼€å§‹æ—¶é—´ï¼ŒUnixæ—¶é—´æˆ³ã€‚
	 *  - endtime		int		ç»“æ�Ÿæ—¶é—´ï¼ŒUnixæ—¶é—´æˆ³ã€‚
	 *  - count			int		å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 *  - page			int		è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - needcount		boolean	è¿”å›žç»“æžœä¸­æ˜¯å�¦åŒ…å�«è¿”å›žè®°å½•æ•°ï¼Œtrueï¼šè¿”å›žã€�falseï¼šä¸�è¿”å›žï¼Œé»˜è®¤ä¸ºfalseã€‚
	 *  - base_app		int		æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * needcountå�‚æ•°ä¸�å�Œï¼Œä¼šå¯¼è‡´ç›¸åº”çš„è¿”å›žå€¼ç»“æž„ä¸�å�Œ
	 * ä»¥ä¸Šå�‚æ•°å…¨éƒ¨é€‰å¡«
	 * @return array
	 */
	function search_statuses_high( $query )
	{
		return $this->oauth->get( 'search/statuses', $query );
	}



	/**
	 * é€šè¿‡å…³é”®è¯�æ�œç´¢ç”¨æˆ·
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/search/users search/users}
	 *
	 * @param array $query æ�œç´¢é€‰é¡¹ã€‚æ ¼å¼�ï¼šarray('key0'=>'value0', 'key1'=>'value1', ....)ã€‚æ”¯æŒ�çš„key:
	 *  - q			string	æ�œç´¢çš„å…³é”®å­—ï¼Œå¿…é¡»è¿›è¡ŒURLencodeã€‚
	 *  - snick		int		æ�œç´¢èŒƒå›´æ˜¯å�¦åŒ…å�«æ˜µç§°ï¼Œ0ï¼šä¸�åŒ…å�«ã€�1ï¼šåŒ…å�«ã€‚
	 *  - sdomain	int		æ�œç´¢èŒƒå›´æ˜¯å�¦åŒ…å�«ä¸ªæ€§åŸŸå��ï¼Œ0ï¼šä¸�åŒ…å�«ã€�1ï¼šåŒ…å�«ã€‚
	 *  - sintro	int		æ�œç´¢èŒƒå›´æ˜¯å�¦åŒ…å�«ç®€ä»‹ï¼Œ0ï¼šä¸�åŒ…å�«ã€�1ï¼šåŒ…å�«ã€‚
	 *  - stag		int		æ�œç´¢èŒƒå›´æ˜¯å�¦åŒ…å�«æ ‡ç­¾ï¼Œ0ï¼šä¸�åŒ…å�«ã€�1ï¼šåŒ…å�«ã€‚
	 *  - province	int		æ�œç´¢çš„çœ�ä»½èŒƒå›´ï¼Œçœ�ä»½IDã€‚
	 *  - city		int		æ�œç´¢çš„åŸŽå¸‚èŒƒå›´ï¼ŒåŸŽå¸‚IDã€‚
	 *  - gender	string	æ�œç´¢çš„æ€§åˆ«èŒƒå›´ï¼Œmï¼šç”·ã€�fï¼šå¥³ã€‚
	 *  - comorsch	string	æ�œç´¢çš„å…¬å�¸å­¦æ ¡å��ç§°ã€‚
	 *  - sort		int		æŽ’åº�æ–¹å¼�ï¼Œ1ï¼šæŒ‰æ›´æ–°æ—¶é—´ã€�2ï¼šæŒ‰ç²‰ä¸�æ•°ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - count		int		å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 *  - page		int		è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 *  - base_app	int		æ˜¯å�¦å�ªèŽ·å�–å½“å‰�åº”ç”¨çš„æ•°æ�®ã€‚0ä¸ºå�¦ï¼ˆæ‰€æœ‰æ•°æ�®ï¼‰ï¼Œ1ä¸ºæ˜¯ï¼ˆä»…å½“å‰�åº”ç”¨ï¼‰ï¼Œé»˜è®¤ä¸º0ã€‚
	 * ä»¥ä¸Šæ‰€æœ‰å�‚æ•°å…¨éƒ¨é€‰å¡«
	 * @return array
	 */
	function search_users_keywords( $query )
	{
		return $this->oauth->get( 'search/users', $query );
	}



	/**
	 * èŽ·å�–ç³»ç»ŸæŽ¨è��ç”¨æˆ·
	 *
	 * è¿”å›žç³»ç»ŸæŽ¨è��çš„ç”¨æˆ·åˆ—è¡¨ã€‚
	 * <br />å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/suggestions/users/hot suggestions/users/hot}
	 * 
	 * @access public
	 * @param string $category åˆ†ç±»ï¼Œå�¯é€‰å�‚æ•°ï¼Œè¿”å›žæŸ�ä¸€ç±»åˆ«çš„æŽ¨è��ç”¨æˆ·ï¼Œé»˜è®¤ä¸º defaultã€‚å¦‚æžœä¸�åœ¨ä»¥ä¸‹åˆ†ç±»ä¸­ï¼Œè¿”å›žç©ºåˆ—è¡¨ï¼š<br />
	 *  - default:äººæ°”å…³æ³¨
	 *  - ent:å½±è§†å��æ˜Ÿ
	 *  - hk_famous:æ¸¯å�°å��äºº
	 *  - model:æ¨¡ç‰¹
	 *  - cooking:ç¾Žé£Ÿ&å�¥åº·
	 *  - sport:ä½“è‚²å��äºº
	 *  - finance:å•†ç•Œå��äºº
	 *  - tech:ITäº’è�”ç½‘
	 *  - singer:æ­Œæ‰‹
	 *  - writerï¼šä½œå®¶
	 *  - moderator:ä¸»æŒ�äºº
	 *  - medium:åª’ä½“æ€»ç¼–
	 *  - stockplayer:ç‚’è‚¡é«˜æ‰‹
	 * @return array
	 */
	function hot_users( $category = "default" )
	{
		$params = array();
		$params['category'] = $category;

		return $this->oauth->get( 'suggestions/users/hot', $params );
	}

	/**
	 * èŽ·å�–ç”¨æˆ·å�¯èƒ½æ„Ÿå…´è¶£çš„äºº
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/suggestions/users/may_interested suggestions/users/may_interested}
	 * 
	 * @access public
	 * @param int $page è¿”å›žç»“æžœçš„é¡µç �ï¼Œé»˜è®¤ä¸º1ã€‚
	 * @param int $count å�•é¡µè¿”å›žçš„è®°å½•æ�¡æ•°ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @return array
	 * @ignore
	 */
	function suggestions_may_interested( $page = 1, $count = 10 )
	{   
		$params = array();
		$params['page'] = $page;
		$params['count'] = $count;
		return $this->oauth->get( 'suggestions/users/may_interested', $params);
	}

	/**
	 * æ ¹æ�®ä¸€æ®µå¾®å�šæ­£æ–‡æŽ¨è��ç›¸å…³å¾®å�šç”¨æˆ·ã€‚ 
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/suggestions/users/by_status suggestions/users/by_status}
	 * 
	 * @access public
	 * @param string $content å¾®å�šæ­£æ–‡å†…å®¹ã€‚
	 * @param int $num è¿”å›žç»“æžœæ•°ç›®ï¼Œé»˜è®¤ä¸º10ã€‚
	 * @return array
	 */
	function suggestions_users_by_status( $content, $num = 10 )
	{
		$params = array();
		$params['content'] = $content;
		$params['num'] = $num;
		return $this->oauth->get( 'suggestions/users/by_status', $params);
	}

	/**
	 * çƒ­é—¨æ”¶è—�
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/suggestions/favorites/hot suggestions/favorites/hot}
	 *
	 * @param int $count æ¯�é¡µè¿”å›žç»“æžœæ•°ï¼Œé»˜è®¤20ã€‚é€‰å¡«
	 * @param int $page è¿”å›žé¡µç �ï¼Œé»˜è®¤1ã€‚é€‰å¡«
	 * @return array
	 */
	function hot_favorites( $page = 1, $count = 20 )
	{
		$params = array();
		$params['count'] = $count;
		$params['page'] = $page;
		return $this->oauth->get( 'suggestions/favorites/hot', $params);
	}

	/**
	 * æŠŠæŸ�äººæ ‡è¯†ä¸ºä¸�æ„Ÿå…´è¶£çš„äºº
	 *
	 * å¯¹åº”APIï¼š{@link http://open.weibo.com/wiki/2/suggestions/users/not_interested suggestions/users/not_interested}
	 *
	 * @param int $uid ä¸�æ„Ÿå…´è¶£çš„ç”¨æˆ·çš„UIDã€‚
	 * @return array
	 */
	function put_users_not_interested( $uid )
	{
		$params = array();
		$params['uid'] = $uid;
		return $this->oauth->post( 'suggestions/users/not_interested', $params);
	}



	// =========================================

	/**
	 * @ignore
	 */
	protected function request_with_pager( $url, $page = false, $count = false, $params = array() )
	{
		if( $page ) $params['page'] = $page;
		if( $count ) $params['count'] = $count;

		return $this->oauth->get($url, $params );
	}

	/**
	 * @ignore
	 */
	protected function request_with_uid( $url, $uid_or_name, $page = false, $count = false, $cursor = false, $post = false, $params = array())
	{
		if( $page ) $params['page'] = $page;
		if( $count ) $params['count'] = $count;
		if( $cursor )$params['cursor'] =  $cursor;

		if( $post ) $method = 'post';
		else $method = 'get';

		if ( $uid_or_name !== NULL ) {
			$this->id_format($uid_or_name);
			$params['id'] = $uid_or_name;
		}

		return $this->oauth->$method($url, $params );

	}

	/**
	 * @ignore
	 */
	protected function id_format(&$id) {
		if ( is_float($id) ) {
			$id = number_format($id, 0, '', '');
		} elseif ( is_string($id) ) {
			$id = trim($id);
		}
	}

}

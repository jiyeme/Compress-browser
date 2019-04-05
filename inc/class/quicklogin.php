<?php
/*
 *
 *	快捷登陆
 *
 *	2011/7/10 @ jiuwap.cn
 *
 */


function quickLogin($type){
	static $classes = array();
	if ( isset($classes[$type]) ){
		return $classes[$type];
	}
	$type = 'quickLogin_' . $type;
	if ( !class_exists($type) ){
		$class = false;
	}else{
		$class = new $type();
		if ( !$class instanceof IquickLogin ){
			$class = false;
		}
	}
	return $classes[$type] = $class;
}

interface IquickLogin{
	//跳转到登陆界面
	function login();

	//回调处理
	function callback();
}

class quickLogin_qq implements IquickLogin{
	private $url = '';
	private $appid = '';
	private $appkey = '';
	private $scope = '';
	private $callback_url = '';
	function __construct(){
		global $b_set;
		$this->appid = $b_set['quicklogin']['qq']['appid'];
		$this->appkey = $b_set['quicklogin']['qq']['appkey'];
		$this->scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo';
		$this->callback_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php?type=qq&callback=true';

		if ( IsWap() ){
			$this->url = 'https://graph.z.qq.com/moc2/';
		}else{
			$this->url = 'https://graph.qq.com/oauth2.0/';
		}
	}

	function login(){
		$code_url = $this->url . 'authorize?response_type=code&client_id='. $this->appid . '&redirect_uri='. urlencode($this->callback_url). '&state=test&scope='.$this->scope;
		header('LOCATION: ' . $code_url);
		exit;
	}

	function callback(){
		if ( !isset($_REQUEST['code']) ){
			return false;
		}
		$token_url = $this->url . "token?grant_type=authorization_code&"
            . "client_id=" . $this->appid. "&redirect_uri=" . urlencode($this->callback_url)
            . "&client_secret=" . $this->appkey. "&code=" . $_REQUEST['code'];

		$response = self::get_url_contents($token_url);

		if ($response === false ){
			return false;
		}else if (strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                return false;
            }
        }

        $params = array();
        parse_str($response, $params);

		$token = $params['access_token'];

/*
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $token;

		$str  = file_get_contents($graph_url);
		if (strpos($str, "callback") !== false) {
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}

		$user = json_decode($str);
		if (isset($user->error)){
			echo "<h3>error:</h3>" . $user->error;
			echo "<h3>msg  :</h3>" . $user->error_description;
			exit;
		}

		//debug
		//echo("Hello " . $user->openid);

		//set openid to session
		$openid = $user->openid;
*/
		return $token;
	}

	static function get_url_contents($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result =  curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}

Class quickLogin_sina implements IquickLogin{
	private $dispaly = 'default';
	private $akey = '';
	private $skey = '';
	private $callback_url = '';
	function __construct(){
		global $b_set;
		$this->akey = $b_set['quicklogin']['sina']['akey'];
		$this->skey = $b_set['quicklogin']['sina']['skey'];
		$this->callback_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php?type=sina&callback=true';
		if ( IsWap() ){
			if ( IsWap2() ){
				$this->dispaly = 'wap2.0';
			}else{
				$this->dispaly = 'wap1.2';
			}
		}else{
			$this->dispaly = 'default';
		}
	}

	function login(){
		require_once 'saetv2.ex.class.php';
		$o = new SaeTOAuthV2( $this->akey , $this->skey );
		$code_url = $o->getAuthorizeURL( $this->callback_url ,'code',null,$this->dispaly);
		header('LOCATION: ' . $code_url);
		exit;
	}


	function callback(){
		require_once 'saetv2.ex.class.php';
		$o = new SaeTOAuthV2( $this->akey , $this->skey );

		$token = '';
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->callback_url;
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}

		if ($token) {
			return $token;
		}else{
			return false;
		}
	}
}



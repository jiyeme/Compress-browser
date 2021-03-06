<?php
/*
 *
 *	浏览器->HTTP通讯核心
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */


require_once 'class.http.php';
/*
//关于如何自己重写该类：

class http_myself Extends http_base implements Ihttp_api{
	public function open($url){
		//创建新连接
	}

	public function send(){
		//向连接发送post/cookie等
		//返回header内容(含cookie)
	}

	public function response($max_length=false){
		//获取完整的内容(或者可直接从send()里获取)
	}
}
*/

class http_fsockopen Extends http_base implements Ihttp_api{
	private $static_response = false;

	private $fp = null;
	private $tmp_body = false;

	public function __construct(){
		if ( !function_exists('fsockopen') ){
			throw new Exception('Call to undefined function fsockopen(),Don\'t support the FSOCKOPEN ! ');
		}
	}

	public function __destruct(){
		$this->close();
	}

	public function close(){
		$this->fp && @fclose($this->fp);
		$this->fp = false;
	}

	public function open($url=false){
		$url !==false && parent::put_url($url);
		//exit($this->_proxy['1']);
		if ( $this->urls['ip'] === false ){
			return false;
		}
		if ( $this->_proxy ){
			$this->urls['path2'] = $this->urls['scheme'].'://'.$this->urls['host'].$this->urls['path'].$this->urls['query'];
			$this->urls['ip2'] = $this->_proxy['0'];
			$this->urls['port2'] = $this->_proxy['1'];
		}else{
		    $this->urls['host2'] = $this->urls['host'];
			$this->urls['path2'] = $this->urls['path'].$this->urls['query'];
			$this->urls['ip2'] = $this->urls['ip'];
			$this->urls['port2'] = $this->urls['port'];
            $this->urls['protocol2'] = $this->urls['protocol'];
		}
		//exit($this->urls['protocol2'].'&'.$this->urls['port2']);
		$this->fp = @fsockopen($this->urls['protocol2'].$this->urls['host2'], $this->urls['port2'], $errno, $errstr, $this->timeout);

		if ( $this->fp === false ){
			//异常,
			$this->close();
			$this->show_debug_exit($errno . $errstr);
			$this->http_error($this->url,$errno,$errstr);
			return false;
		}
		$this->show_debug($this->urls);
		@stream_set_timeout($this->fp, $this->timeout);
		$this->callback_read_cookie();
		return true;
	}


	private function http_error($url,$num,$str,$lite=false){
		if ( $num == '-3' ) {
			$error_str = 'Socket连接创建失败';
		}elseif ( $num == '-4' ) {
			$error_str = 'DNS定位失败';
		}elseif ( $num == '-5' ) {
			$error_str = '连接超时或被拒绝';
		}elseif ( $str == 'php_network_getaddresses: getaddrinfo failed: 不知道这样的主机。'){
			$error_str = '网址不可用';
		}else{
			$error_str = '访问失败';
		}
		if ( $lite ){
			if ( $error_str == '访问失败'){
				return $str;
			}else{
				return $error_str;
			}
		}
		header('Content-Type: text/html; charset=utf-8');
		$con = '提示：'.$error_str.'('.$num.')<br/>';
		$con .= '网址：'.htmlspecialchars($url).'<br/>';
		$con .= '详细：'.$str.'<br/>';
		echo '访问失败<br/>'.$con ;
		exit;
	}

	public function send(){
		if ( !$this->fp ){
			throw new Exception('Please [$this->open] !');
		}
		$SendStr  = "{$this->method} {$this->urls['path2']} HTTP/1.1\r\n";
		/*if($this->urls['shame'] == 'https'){
		    $SendStr .= "Host: {$this->urls['host']}:443\r\n";
		}else if ( $this->urls['port']==80 ){*/
			$SendStr .= "Host: {$this->urls['host']}\r\n";
		/*}else{
			$SendStr .= "Host: {$this->urls['host']}:{$this->urls['port']}\r\n";
		}*/
		
		
		if ( $this->_cookies ){
			$this->_headers['Cookie'] = '';
			foreach ($this->_cookies as $key => $value) {
				$this->_headers['Cookie'] .= "{$key}={$value}; ";
			}
		}

		foreach($this->_headers as $param => $value){
			$SendStr.= "{$param}: {$value}\r\n";
		}
		if ( $this->_files ) {
			srand((double)microtime()*1000000);
			$boundary = '---------------------------'.substr(md5(rand(0,32000)),0,10);
			$QueryStr = '';
			foreach ($this->_posts as $param => $value) {
					$QueryStr .= "--$boundary\r\n";
					$QueryStr .= "Content-Disposition: form-data; name=\"$param\"\r\n";
					$QueryStr .= "\r\n{$value}\r\n";
					$QueryStr .= "--$boundary\r\n";
			}
			foreach ( $this->_files as $val_k=>$val){
				$QueryStr .= "--$boundary\r\n";
				$QueryStr .= "Content-Disposition: form-data; name=\"{$val_k}\"; filename=\"{$val[0]}\"\r\n";
				$QueryStr .= "Content-Type: {$val[2]}\r\n";
				$QueryStr .= "\r\n{$val[1]}\r\n";
				$QueryStr .= "--$boundary--\r\n";
			}
			$this->_files = array();//尽早释放不必要的变量
			$length   = strlen($QueryStr);
			$SendStr .= "Content-Type: multipart/form-data; boundary=$boundary\r\n";
			$SendStr .= "Content-Length: {$length}\r\n";

		}elseif ( $this->_posts ) {
			$QueryStr = http_build_query($this->_posts);
			$length   = strlen($QueryStr);
			$SendStr .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$SendStr .= "Content-Length: {$length}\r\n";
		}else{
			$QueryStr = null;
		}
		$this->_posts = array();//尽早释放不必要的变量
		$this->show_debug($SendStr."\r\n".$QueryStr);

		if ( !@fputs($this->fp,$SendStr."\r\n".$QueryStr) ){
			//异常
			$this->close();
			$this->show_debug_exit($errno . $errstr);
			$this->http_error($this->url,'-1','发送数据时出现异常');
			//die($errno . $errstr);
			return false;
		}
		$header = null;
		while ( ($line = @fgets($this->fp)) !== false) {
			$header .= $line;
			if ( substr($header,-4) == "\r\n\r\n" || substr($header,-2) == "\n\n" ){
				break;
			}
		}
		$this->show_debug($header);
		$this->headers = self::parse_header($header);
		$this->cookies = $this->headers['COOKIE'];
		$this->show_debug($this->headers);
		$this->show_debug($this->cookies);
		$this->tmp_body = '';
		$this->callback_save_cookie();
		$this->_checkLocation();
		return true;
	}

	//检测并跳转
	private function _checkLocation(){
		if ( $this->get_header('LOCATION') !== null && ( $this->location > 0 || $this->location === true || $this->location == -1 )){
			foreach($this->get_header('COOKIE') as $key=>$val){
				if ( !$val['domain'] ){
					if ( $val['expires'] && $val['expires'] <= time()){
						unset($this->_cookies[$key]);
					}else{
						$this->_cookies[$key] = $val['value'];
					}
				}elseif ( strtolower($val['domain']) == strtolower(substr($this->urls['host'],strlen($this->urls['host'])-strlen($val['domain']))) ){
					if ( $val['expires'] && $val['expires'] <= time() ){
						unset($this->_cookies[$key]);
					}else{
						$this->_cookies[$key] = $val['value'];
					}
				}
			}
			$target_url = self::urlfix($this->get_header('LOCATION'),$this->urls);
			if ($this->location !== true && $this->location != -1 && $this->location != 0 ){
				$this->location--;
			}
			$this->set_referer($this->url);
			$this->clean_posts();

			@fclose($this->fp);$this->fp = false;
			$this->open($target_url);
			$this->send();

		}
	}

	//其中的参数用于指定最大的下载大小
	public function response($max_length=false){
		if ( !$this->fp ){
			throw new Exception('Please [$this->open] !#1');
		}else if ( $this->tmp_body === false  ){
			throw new Exception('Please [$this->send] !#2');
		}
		//static $response = false;
		//static $response = false;
		if ( $this->static_response !== false  ){
			return $this->static_response;
		}
		if ( !$this->fp ){
			$this->show_debug_exit('response -> fp was closed');
			return '';
		}
		$this->static_response = $this->tmp_body;
		unset($this->tmp_body);
		if ( $this->get_header('CONTENT-LENGTH') !== null ){
			$this->show_debug('response -> CONTENT-LENGTH');
			//直接按照指定的网页大小读取
			$limit = $this->get_header('CONTENT-LENGTH') - strlen($this->static_response);
			if ( $limit > 0 ){
				while( true ) {
					if (!$_response = @fread($this->fp, (!$limit || $limit>$this->blocksize )? $this->blocksize : $limit)){
						break;
					}
					$this->static_response .= $_response;
					$limit = $limit - strlen($_response);
					if ( $max_length && strlen($this->static_response) > $max_length ){
						$max_length = false;
						break;
					}
					if ( $limit <= 0){
						break;
					}
				}
			}
			unset($_response,$limit);
		}elseif( $this->get_header('TRANSFER-ENCODING') !== null && strtolower($this->get_header('TRANSFER-ENCODING')) == 'chunked'){
			$this->show_debug('response -> TRANSFER-ENCODING -> chunked');
			while ($chunk_length = hexdec(fgets($this->fp))){
				$this->static_responseContentChunk = '';
				$read_length = 0;
				while ($read_length < $chunk_length){
					$this->static_responseContentChunk .= fread($this->fp, $chunk_length - $read_length);
					$read_length = strlen($this->static_responseContentChunk);
					if ( $max_length && $read_length > $max_length ){
						$max_length = false;
						break;
					}
				}
				$this->static_response.= $this->static_responseContentChunk;
				unset($this->static_responseContentChunk,$read_length,$chunk_length);
				fgets($this->fp);
			}
		}else{
			$this->show_debug('response -> fread');
			while( !feof($this->fp) ) {
				$this->static_response .= @fread($this->fp,$this->blocksize);
				if ( $max_length && strlen($this->static_response) > $max_length ){
					$max_length = false;
					break;
				}
			}
		}

		if ( $this->get_header('CONTENT-ENCODING') !== null && stripos($this->get_header('CONTENT-ENCODING'),'gzip' )!==false ){
			$this->show_debug('response -> gzencode');
			$this->static_response = self::gzdecode($this->static_response);
		}
		return $this->static_response;
	}
}

/*
//调试
$http = new http_fsockopen();

//$http->set_debug();

//$http->set_dns('[*].baidu.com','127.0.0.1');


//$http->set_proxy('www3300ue.sakura.ne.jp','8080');

$http->open('https://www.jysafe.cn/microsoft/os-optimize');
//$http->put_cookie('aa','bb');

$http->put_header_default();
$http->send();

//var_dump($http->get_headers());

echo $http->get_body();

//var_dump($http->get_cookies());

*/
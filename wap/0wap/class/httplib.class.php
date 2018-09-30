<?php
/*****
 *
 *	HTTP通讯类(fsockopen)
 *
 *	2011-1-15 @ jiuwap.cn
 *
 *      补充：老虎会游泳
 *
//例子
$http = new httplib;

//设置代理
//$http->proxy(IP地址,端口);


//$http->open('http://jiuwap.cn',超时时间,限制跳转次数);
$http->open('http://jiuwap.cn',30,5);

//header
$http->header('name','value');

//post
$http->post('name','value');

//cookie
$http->cookie('name','value');

//上传文件
#content是文件内容

#type就是content-type,为空则application/octet-stream

//$http->file('input_title','filename','content','type');

//防盗链的,或者可以直接用$http->header
//$http->referer(地址)

//设置是否等待服务器返回。设为false并多次send()可实现并发连接
//默认为true
$http->blocking(false);

$http->send();

echo $http->url();//返回最终的URL地址(因为可能跳转)

var_dump($http->parse_url()); //解析出url

$header = $http->header();
var_dump($header);
//返回的cookie $header['COOKIE']
//返回的所有header强制大写了。。
//哈，可以快速返回header信息,获取header时不会获取内容
echo $http->response();
//把内容写到文件
$http->tofile('0.txt');
//检测错误:response/header/send/open如果返回false则出错了，获取错误信息用$http->error()
*****/
Class httplib{
   public $returnerr=true;
	private $is_cute_header	= true;
	private $blocking	= true;
	private $jump		= 5;
	private $outtime	= 30;
	private $errormsg	= null;
	private $proxy_host	= null;		//代理ip地址
	private $proxy_port	= null;		//代理ip端口
	//防盗链
	private $set_referer= null;
	//设置的东西
	private $set_header	= array();
	private $set_cookie	= array();
	private $set_post	= array();
	private $set_file	= array();
	//得到的东西
	private $get_header	= array();
	private $get__body	= null;
	//fsockopen句柄
	private $fp;
	private $offset=0;
	function getfp()
	{
		return $this->fp;
	}
	function __destruct(){
		if ( $this->fp ){
			fclose($this->fp);
		}
	}
	function file($name,$value,$content,$type='application/octet-stream'){
		$this->set_file[] = array(
            'form'=>$name,
            'name'=>$value,
            'type'=>$type,
            'content'=>$content,
        );
	}
	function proxy($host,$part=80){
		$this->proxy_host = $host;
		$this->proxy_port = $part;
	}
	function referer($set_referer=false){
		if ( $set_referer === false){
			return $this->set_referer;
		}else{
			$this->set_referer = $set_referer;
		}
	}
	function header($name=false,$value=false){
if(is_array($name))
 {
foreach($name as $n=>$v)
{$this->header($n,$v);}
return true;
 }
		if ( $name === false ){
			$this->cute_header();
			return $this->get_header;
		}elseif ( $value === false ) {
			$this->cute_header();
			if ( isset($this->get_header[$name] ) ){
				return $this->get_header[$name];
			}else{
				return null;
			}
		}else{
			$this->set_header[$name] = $value;
		}
	}
	function cookie($name=false,$value=false){
if(is_array($name))
 {
foreach($name as $n=>$v)
{$this->cookie($n,$v);}
return true;
 }
		if ( $name === false ){
			$this->cute_header();
			return $this->get_header['COOKIE'];
		}elseif ( $value === false ) {
			$this->cute_header();
			if ( isset($this->get_header['COOKIE'][$name] ) ){
				return $this->get_header['COOKIE'][$name];
			}else{
				return null;
			}
		}else{
if(is_array($value))
{
 if($value['expires'] && $value['expires']<=time())
  return false;
 else
  $value=$value['value'];
}
			$this->set_cookie[$name] = $value;
		}
	}
	function blocking($blocking)
	{
		$this->blocking=$blocking;
	}
	function post($name,$value){
if(is_array($name))
 {
foreach($name as $n=>$v)
{$this->post($n,$v);}
return true;
 }
		$this->set_post[$name] = $value;
	}
	function url(){
		$this->cute_header();
		return $this->_url;
	}
	function parse_url(){
		return array('scheme' => $this->_scheme,'host' => $this->_host,'port' => $this->_port,'path' => $this->_path,'query' => $this->_query);
	}
	function error(){
		return $this->errormsg;
	}
	function open($url,$outtime=30,$jump=5,$returnerr=true){
  if($this->fp)
    fclose($this->fp);
		//set_time_limit($outtime+10);
		$this->jump = $jump;
		$this->outtime = $outtime;
		$this->returnerr = $returnerr;
		return $this->_parseURL($url);
	}
	function send(){
		if ( $this->proxy_host && $this->proxy_port){
			$path = $this->_scheme.'://'.$this->_host.($this->defport ? '' : ':'.$this->_port).$this->_path.$this->_query;
			$host = $this->proxy_host;
			$port = $this->proxy_port;
 $http='tcp://';
		}else{
			$path = $this->_path.$this->_query;
			$host = $this->_host;
			$port = $this->_port;
if($this->_scheme=='http')
 $http='tcp://';
else
 $http='ssl://';
		}
    ($this->fp = stream_socket_client($http.$host.':'.$port, $errno, $errstr, $this->outtime)) or ($this->fp=fsockopen($http.$host,$port,$errno,$errstr,$this->outtime));
    if(!$this->fp) {
            $errstr = trim(mb_convert_encoding($errstr, 'utf-8', 'gbk'));
            if ( $this->returnerr ){
                $this->errormsg = self::http_error($this->_url,$errno,$errstr,true);
                return false;
            }else{
                self::http_error($this->_url,$errno,$errstr);
            }
		}
		stream_set_timeout($this->fp, $this->outtime);
		stream_set_blocking($this->fp, $this->blocking);
		$method = $this->set_post ? 'POST' : 'GET';
		if ( $this->set_cookie ){
			$this->set_header['Cookie'] = '';
			foreach ($this->set_cookie as $key => $value) {
				$this->set_header['Cookie'] .= "$key=$value; ";
			}
		}
		$SendStr  = "{$method} {$path} HTTP/1.0\r\n";
		$_port   = $this->defport ? '' : ':'.$this->_port;
		$SendStr .= "Host: {$this->_host}{$_port}\r\n";
		if ( $this->set_referer ){
			$SendStr .= "Referer: {$this->set_referer}\r\n";
		}
		//'Authorization: BASIC '.base64_encode($this->username.':'.$this->password)
		$SendStr .= "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8\r\n";
		//$SendStr .= "Accept-Encoding: gzip, deflate, compress;q=0.9\r\n";
		$SendStr .= "Accept-Charset: utf-8, gb2312;q=0.7,*;q=0.7\r\n";
		$SendStr .= "Accept: text/html, application/xml;q=0.9, application/xhtml+xml, text/vnd.wap.wml, image/png, image/jpeg, image/gif, */*;q=0.1\r\n";
		foreach($this->set_header as $name => $value){
			$SendStr.= "{$name}: {$value}\r\n";
		}
		$SendStr.= "Pragma: no-cache\r\n";
		$SendStr.= "Cache-Control: no-cache\r\n";
		if ( $this->set_file ) {
            srand((double)microtime()*1000000);
            $boundary = '---------------------------'.substr(md5(rand(0,32000)),0,10);
			$QueryStr = '';
			foreach ($this->set_post as $param => $value) {
				$QueryStr .= "--$boundary\r\n";
				$QueryStr .= "Content-Disposition: form-data; name=\"$param\"\r\n\r\n";
				$QueryStr .= "$value\r\n";
			}
			foreach ( $this->set_file as $val){
				$QueryStr .= "--$boundary\r\n";
				$QueryStr .= "Content-Disposition: form-data; name=\"{$val['form']}\"; filename=\"{$val['name']}\"\r\n";
				$QueryStr .= "Content-Type: {$val['type']}\r\n\r\n";
				$QueryStr .= "{$val['content']}\r\n";
				$QueryStr .= "--$boundary--\r\n";
			}
			$length   = strlen($QueryStr);
			$SendStr .= "Content-Type: multipart/form-data; boundary=$boundary\r\n";
			$SendStr .= "Content-Length: {$length}\r\n";
		}elseif ( $this->set_post ) {
			$QueryStr = $this->buildQueryString($this->set_post);
			$length   = strlen($QueryStr);
			$SendStr .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$SendStr .= "Content-Length: {$length}\r\n";
		}else{
			$QueryStr = null;
		}
		//$SendStr .= "Keep-Alive: 300\r\n";
    		//$SendStr .= "Connection: Keep-Alive\r\n\r\n";
		//echo $SendStr;exit;
		$SendStr .= "Connection: Close\r\n\r\n";
	if ( !fputs($this->fp,$SendStr) ){
            if ( $this->returnerr ){
                $this->errormsg = self::http_error($this->_url,0,'发送数据(header)失败',true);
                return false;
            }else{
                self::http_error($this->_url,0,'发送数据(header)失败');
            }
		}
		if ( $QueryStr ) {
            if ( !fputs($this->fp,$QueryStr."\r\n\r\n") ){
                if ( $this->returnerr ){
                    $this->errormsg = self::http_error($this->_url,0,'发送数据(post)失败',true);
                    return false;
                }else{
                    self::http_error($this->_url,0,'发送数据(post)失败');
                }
            }
		}
		$this->is_cute_header=false;
		$this->offset=0;
		return true;
	}

	function cute_header()
	{

	if($this->is_cute_header)
	  return false;
		$header = null;//$i=0;
		do{
			if ( !$header .= fread($this->fp,1024) ){
                if ( $this->returnerr ){
                    $this->errormsg = self::http_error($this->_url,0,'获取数据(header)失败',true);
                    return false;
                }else{
                    self::http_error($this->_url,0,'获取数据(header)失败');
                }
			}
            $a = strpos($header,"\r\n\r\n");

			if ( feof($this->fp) ){
				break;
			}
			/*$i++;
			if ( $i == 1 ){
				echo $header;
				exit;
			}*/
		}while( !$a );
	//exit;
        $this->get__body = substr($header,$a+4);
        $header = substr($header,0,$a);
		$this->get_header = $this->_parseHEADER($header);
		$this->_checkJUMP();
		$this->is_cute_header=true;
		return true;
	}

	function response($length=0){
		if ( !$this->fp || feof($this->fp) ){
			return null;
		}
	$this->cute_header();
	if(isset($this->get__body))
	{
	        $response = $this->get__body;
		$this->offset=strlen($response);
        	unset($this->get__body);
	}

		if ( isset($this->get_header['CONTENT-LENGTH']) ){
			$limit = $this->get_header['CONTENT-LENGTH'] - $this->offset;
			if ( $limit > 0 ){
				for($leni=0;(!$length||$leni<=$length)&&!feof($this->fp); $leni+=8192,$this->offset+=8192) {
					$_response = fread($this->fp, ($limit == 0 || $limit > 8192 )? 8192 : $limit);
					$response .= $_response;
					$limit -= strlen($_response);
					if ( $limit <= 0){
						break;
					}
				}
			}
			unset($_response,$limit);
		}else{
			for($leni=0;(!$length||$leni<=$length)&&!feof($this->fp); $leni+=1024,$this->offset+=1024) {
				$response .= fread($this->fp,1024);
			}
		}
		//fclose($this->fp);
        if ( isset($this->get_header['CONTENT-ENCODING']) && stripos($this->get_header['CONTENT-ENCODING'],'gzip' )!==false ){
            $response = self::gzdecode($response);
		}
		return $response;
    }

	function tofile($fname,$length=0,$mode='w')
	{
		if(!$fp=fopen($fname,$mode))
		  return false;
	fwrite($fp,$this->response($length));
  fclose($fp);
	}
	function _checkJUMP(){
		if ( isset($this->get_header['LOCATION']) && $this->jump > 0 ){
			foreach($this->get_header['COOKIE'] as $key=>$val){
				if ( !$val['domain'] ){
					if ( $val['expires'] && $val['expires'] <= time()){
						unset($this->set_cookie[$key]);
					}else{
						$this->set_cookie[$key] = $val['value'];
					}
				}elseif ( strtolower($val['domain']) == strtolower(substr($this->_host,strlen($this->_host)-strlen($val['domain']))) ){
					if ( $val['expires'] && $val['expires'] <= time() ){
						unset($this->set_cookie[$key]);
					}else{
						$this->set_cookie[$key] = $val['value'];
					}
				}
			}

			$url = $this->_fixURL($this->get_header['LOCATION']);

			$this->jump--;
			$this->_parseURL($url);
			$this->set_referer = $url;
			$this->set_post = array();
			$this->send();
			$this->cute_header();
		}
	}

	function _fixURL($url){
		if(in_array(strtolower(substr($url,0,7)),array('http://','https:/'))){
			return $url;
		}elseif ( substr($url,0,1) == '?' ){
			$url = $this->_scheme.'://'.$this->_host.$this->_path.$url;
		}elseif ( substr($url,0,1) == '/'){
			$url = $this->_scheme.'://'.$this->_host.$url;
		}else{
			if ( $this->_path <> '/' ){
				$url = $this->_scheme.'://'.$this->_host.substr($this->_path,0,strrpos(substr($this->_path,0,strrpos($this->_path.'?','?')),'/')+1).$url;
			}else{
				$url = $this->_scheme.'://'.$this->_host.'/'.$url;
			}
		}
		return $url;
	}
	function _parseHEADER($array){
 		$header['COOKIE'] = array() ;
		$header['STATUS'] = '0' ;
		$array = explode("\r\n", $array) ;
		foreach ($array as $value) {
			$pos = strpos($value, ':') ;
			if ($pos) {
				$key = trim(strtoupper(substr($value, 0, $pos))) ;
				$value = trim(substr($value, $pos + 1)) ;
				if ($key == 'SET-COOKIE') {
					$temp = $this->_parseCOOKIE($value) ;
					$key = $temp['name'];unset($temp['name']);
					$header['COOKIE'][$key] = $temp;
				}else{
					$header[$key] = $value ;
				}
			}elseif (preg_match("'HTTP/(.*?) (.*?) '", $value, $matches)) {
				$header['STATUS'] = trim($matches[2]) ;
			}
		}
		return $header;
	}

	function _parseCOOKIE($array){
		$name = '';
		$value = '';
		$path = '';
		$expires = '';
		$domain = '';
		$array = explode('; ', $array) ;
		if ( !$array ) {
			$name = trim(substr($array,0,strpos($array,'=')));
			$value =trim(substr($array,strpos($array,'=')+1));
		} else {
			$name = trim(substr($array[0],0,strpos($array[0],'=')));
			$value = trim(substr($array[0],strpos($array[0],'=')+1));
			unset($array[0]);
			foreach($array as $temp){
				if ( strpos($temp,'=') ){
					$nam = trim(substr($temp,0,strpos($temp,'=')));
					$valu = trim(substr($temp,strpos($temp,'=')+1));
					if ( $nam == 'expires'){
						$expires = strtotime($valu);
					}elseif ( $nam == 'path'){
						$path = $valu;
					}elseif ( $nam == 'domain'){
						$domain = $valu;
					}
				}
			}
		}
		return array('value'=>$value,'path'=>$path,'expires'=>$expires,'name'=>$name,'domain'=>$domain);
	}

	function _parseURL($url){
		$this->_url = $url;
		if ( !$aUrl = parse_url($url) ){
            if ( $this->returnerr ){
                self::http_error($this->_url,0,'URL地址解析失败',true);
                return false;
            }else{
                self::http_error($this->_url,0,'URL地址解析失败');
            }
		}
		$aUrl['query'] = isset($aUrl['query']) ? $aUrl['query'] : null;
		$this->_scheme = (!isset($aUrl['scheme']) || $aUrl['scheme'] == 'http'  ) ? 'http' : 'https';
		$this->_host  = isset($aUrl['host']) ? $aUrl['host'] : null;
		$this->_port  = empty($aUrl['port']) ? null : (int)$aUrl['port'];
if($this->_port===null)
{
 if($this->_scheme=='http')
  $this->_port=80;
 else
  $this->_port=443;
$this->defport=true;
}
else
 $this->defport=false;
		$this->_path  = empty($aUrl['path']) ? '/' : (string)$aUrl['path'];
		$this->_query = strlen($aUrl['query']) > 0 ? '?'.$aUrl['query'] : null;
 		return true;
   }
	function buildQueryString($data) {
		$querystring = '';
		if (is_array($data)) {
			foreach ($data as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $val2) {
						$querystring .= urlencode($key).'='.urlencode($val2).'&';
					}
				} else {
					$querystring .= urlencode($key).'='.urlencode($val).'&';
				}
			}
			$querystring = substr($querystring, 0, -1);
    	} else {
			$querystring = $data;
    	}
		return $querystring;
	}
static function http_ishtml($contenttype){
	if ( stripos($contenttype,'text') ||
		 stripos($contenttype,'xhtml') ||
		 stripos($contenttype,'html') ||
		 stripos($contenttype,'wml') ||
		 stripos($contenttype,'plain') ||
		 stripos($contenttype,'xml')
										){
		return TRUE;
	}else{
		return FALSE;
	}
}

static function http_fixhtml($content){
	$content = trim($content);
	$m = strpos($content,'<');
	if ( $m!==false ){
		$content = substr($content,$m);
	}
	$m = strrpos($content,'>');
	if ( $m!==false ){
		$content = substr($content,0,$m+1);
	}
	return $content;

}
static function gzdecode($data) {
  $len = strlen($data);
  if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
   return null;  // Not GZIP format (See RFC 1952)
  }
  $method = ord(substr($data,2,1));  // Compression method
  $flags  = ord(substr($data,3,1));  // Flags
  if ($flags & 31 != $flags) {
   // Reserved bits are set -- NOT ALLOWED by RFC 1952
   return null;
  }
  // NOTE: $mtime may be negative (PHP integer limitations)
  $mtime = unpack("V", substr($data,4,4));
  $mtime = $mtime[1];
  $xfl  = substr($data,8,1);
  $os    = substr($data,8,1);
  $headerlen = 10;
  $extralen  = 0;
  $extra    = "";
  if ($flags & 4) {
   // 2-byte length prefixed EXTRA data in header
   if ($len - $headerlen - 2 < 8) {
     return false;    // Invalid format
   }
   $extralen = unpack("v",substr($data,8,2));
   $extralen = $extralen[1];
   if ($len - $headerlen - 2 - $extralen < 8) {
     return false;    // Invalid format
   }
   $extra = substr($data,10,$extralen);
   $headerlen += 2 + $extralen;
  }

  $filenamelen = 0;
  $filename = "";
  if ($flags & 8) {
   // C-style string file NAME data in header
   if ($len - $headerlen - 1 < 8) {
     return false;    // Invalid format
   }
   $filenamelen = strpos(substr($data,8+$extralen),chr(0));
   if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
     return false;    // Invalid format
   }
   $filename = substr($data,$headerlen,$filenamelen);
   $headerlen += $filenamelen + 1;
  }

  $commentlen = 0;
  $comment = "";
  if ($flags & 16) {
   // C-style string COMMENT data in header
   if ($len - $headerlen - 1 < 8) {
     return false;    // Invalid format
   }
   $commentlen = strpos(substr($data,8+$extralen+$filenamelen),chr(0));
   if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
     return false;    // Invalid header format
   }
   $comment = substr($data,$headerlen,$commentlen);
   $headerlen += $commentlen + 1;
  }

  $headercrc = "";
  if ($flags & 1) {
   // 2-bytes (lowest order) of CRC32 on header present
   if ($len - $headerlen - 2 < 8) {
     return false;    // Invalid format
   }
   $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
   $headercrc = unpack("v", substr($data,$headerlen,2));
   $headercrc = $headercrc[1];
   if ($headercrc != $calccrc) {
     return false;    // Bad header CRC
   }
   $headerlen += 2;
  }

  // GZIP FOOTER - These be negative due to PHP's limitations
  $datacrc = unpack("V",substr($data,-8,4));
  $datacrc = $datacrc[1];
  $isize = unpack("V",substr($data,-4));
  $isize = $isize[1];

  // Perform the decompression:
  $bodylen = $len-$headerlen-8;
  if ($bodylen < 1) {
   // This should never happen - IMPLEMENTATION BUG!
   return null;
  }
  $body = substr($data,$headerlen,$bodylen);
  $data = "";
  if ($bodylen > 0) {
   switch ($method) {
     case 8:
       // Currently the only supported compression method:
       $data = gzinflate($body);
       break;
     default:
       // Unknown compression method
       return false;
   }
  } else {
   // I'm not sure if zero-byte body content is allowed.
   // Allow it for now...  Do nothing...
  }

  // Verifiy decompressed size and CRC32:
  // NOTE: This may fail with large data sizes depending on how
  //      PHP's integer limitations affect strlen() since $isize
  //      may be negative for large sizes.
  if ($isize != strlen($data) || crc32($data) != $datacrc) {
   // Bad format!  Length or CRC doesn't match!
   return false;
  }
  return $data;
}


static function unGzip($content){
    if(substr($content,0,10) == "\x1F\x8B\x08\x00\x00\x00\x00\x00\x00\x03"){
        $content = substr($content,10);
        $content = gzinflate($content);
    }
    return $content;
}

static function unChunked($content){
    $pos = strpos($content,"\x0d\x0a");
    if($pos > 0 && $pos < 20){
        $content = substr($content,$pos+2);
    }
    $content = preg_replace("/\x0d\x0a[0-9a-f]+?\x0d\x0a/is",'',$content);
    if(substr($content,-2) == "\r\n") $content = substr($content,0,strlen($content)-2);
    $content = str_replace("\r\n2000\r\n",'',$content);
    return $content;
}

static function http_error($url,$num,$str,$lite=false){
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
    $con = '提示：'.$error_str.'('.$num.')<br/>';
    $con .= '网址：'.htmlspecialchars($url).'<br/>';
    $con .= '详细：'.$str.'<br/>';
    echo $con;
    exit;
}
#httplib类结束
}
?>
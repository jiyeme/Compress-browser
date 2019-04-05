<?php
/**
 * HTTP协议类(本类只处理HTTP协议，无HTTP通讯部分，HTTP通讯部分需要其他的类使用此接口)
 *
 * @package tianyiw
 * @author tianyiw
 * @version 1.0
 */



/**
 * HTTP协议类(抽象 通用静态方法)
 *
 * @package tianyiw
 * @author tianyiw
 * @version 1.0
 */
abstract class http_static{

	/**
	 * 根据指定的URL数组补全URL地址
	 *
	 * @param string $url 待补全的URL
	 * @param array/string $urls 上级URL(可以是parse_url数组或完整URL)
	 * @return string 返回补全后的URL地址
	 */
	static function urlfix($url,$urls){
		if ( !is_array($urls) ){
			$urls = @parse_url($urls);
		}
		if(in_array(strtolower(substr($url,0,7)),array('http://','https:/'))){
			return $url;
		}elseif ( substr($url,0,1) == '?' ){
			$url = $urls['scheme'].'://'.$urls['host'].$urls['path'].$url;
		}elseif ( substr($url,0,1) == '/'){
			$url = $urls['scheme'].'://'.$urls['host'].$url;
		}else{
			if ( $urls['path'] != '/' ){
				$url = $urls['scheme'].'://'.$urls['host'].substr($urls['path'],0,strrpos(substr($urls['path'],0,strrpos($urls['path'].'?','?')),'/')+1).$url;
			}else{
				$url = $urls['scheme'].'://'.$urls['host'].'/'.$url;
			}
		}
		return $url;
	}

	/**
	 * 解压GZIP压缩后的内容
	 *
	 * @param string $data 压缩后的内容
	 * @return string 解压后的内容,失败返回false
	 */
	static function gzdecode($data) {
		$len = strlen($data);
		if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
			return null;
		}
		$method = ord(substr($data,2,1));
		$flags  = ord(substr($data,3,1));
		if ($flags & 31 != $flags) {
			return null;
		}
		$mtime = unpack("V", substr($data,4,4));
		$mtime = $mtime[1];
		$headerlen = 10;
		$extralen  = 0;
		if ( $flags & 4 ) {
			if ($len - $headerlen - 2 < 8) {
				return false;
			}
			$extralen = unpack("v",substr($data,8,2));
			$extralen = $extralen[1];
			if ($len - $headerlen - 2 - $extralen < 8) {
				return false;
			}
			$headerlen += 2 + $extralen;
		}

		$filenamelen = 0;
		if ($flags & 8) {
			if ($len - $headerlen - 1 < 8) {
				return false;
			}
			$filenamelen = strpos(substr($data,8+$extralen),chr(0));
			if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
				return false;
			}
			$headerlen += $filenamelen + 1;
		}

		$commentlen = 0;
		if ($flags & 16) {
			if ($len - $headerlen - 1 < 8) {
				return false;
			}
			$commentlen = strpos(substr($data,8+$extralen+$filenamelen),chr(0));
			if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
				return false;
			}
			$headerlen += $commentlen + 1;
		}

		if ($flags & 1) {
			if ($len - $headerlen - 2 < 8) {
				return false;
			}
			$calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
			$headercrc = unpack("v", substr($data,$headerlen,2));
			$headercrc = $headercrc[1];
			if ($headercrc != $calccrc) {
				return false;
			}
			$headerlen += 2;
		}

		$datacrc = unpack("V",substr($data,-8,4));
		$datacrc = $datacrc[1];
		$isize = unpack("V",substr($data,-4));
		$isize = $isize[1];

		$bodylen = $len-$headerlen-8;
		if ($bodylen < 1) {
			return null;
		}
		$body = substr($data,$headerlen,$bodylen);
		$data = '';
		if ($bodylen > 0) {
			if  ($method == 8) {
				$data = @gzinflate($body);
			}else{
				return false;
			}
		}

		if ($isize != strlen($data) || crc32($data) != $datacrc) {
			return false;
		}
		return $data;
	}

	/**
	 * 将字符串日期 转换 为Unix时间戳,该方法用于解析COOKIE过期时间(strtotime增强版)
	 *
	 * @param string $str 待转换的日期字符串
	 * @param string $date 此参数无需使用!(用于方法内部调用)
	 * @return int 解析后的Unix时间戳
	 */
	static function strtotime($str,$date=false){
		if ( $str === false && $date !== false ){
			if ( !is_numeric($date) ) {
				return $date;
			}elseif( $date > 2030 ){
				return 2030;
			}elseif( $date < 1969 ){
				return 1969;
			}else{
				return $date;
			}
		}
		if ( preg_match('/([A-Z][a-z][a-z]), ([0-9]{2})-([A-Z][a-z][a-z])-([0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)/',$str, $matches) == 1){
			if ( $matches[4] > 30 ){
				$matches[4] = 30;
			}
			$str = "{$matches[6]} {$matches[1]} {$matches[3]} {$matches[2]} 20{$matches[4]}";
			return strtotime($str) + strtotime($matches[5]) - strtotime('Today');
		}else if ( preg_match('/([A-Z][a-z][a-z]), ([0-9]{2})-([A-Z][a-z][a-z])-([0-9]{4}) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)/',$str, $matches) == 1){
			if ( $matches[4] > 2030 ){
				$matches[4] = 2030;
			}elseif( $matches[4] < 1969 ){
				$matches[4] = 1969;
			}
			$str = "{$matches[6]} {$matches[1]} {$matches[3]} {$matches[2]} 20{$matches[4]}";
			return strtotime($str)+strtotime($matches[5]) - strtotime('Today');
		}

		$time = strtotime($str);
		if ( $date === false && preg_match('/(.+) ([0-9]{2}:[0-9]{2}:[0-9]{2}) (.+)/',$str, $matches) == 1 ){
			//Sun, 03 Jun 2012 07:12:50 GMT
			$matches[2] = str_replace(array(', ','-'),'',$matches[2]);
			$startOfDay = strtotime("Today");
			$timeThroughDay = strtotime($matches[2]) - $startOfDay ;
			$time = self::strtotime("$matches[3] Mon Feb 20 2012",true)+$timeThroughDay;
			//var_dump( strtotime($matches[1]));
		}else if ( empty($time) ){
			$str = preg_replace('@([1-2][0-9][0-9][0-9])@ies', "self::strtotime(false,'\\1')", $str);
			$time = strtotime($str);
			if ( empty($time) ){
				$str = preg_replace('@([1-3][0-9]\-[a-zA-Z][a-zA-Z][a-zA-Z]\-)([1-9][0-9])@ies', "'\\1' . self::strtotime(false,'20\\2')", $str);
				$time = strtotime($str);
			}
		}
		return $time;
	}


	/**
	 * 解析域名的IP地址,可指定DNS数据(gethostbyname增强版)
	 *
	 * @param string $str 待转换的日期字符串
	 * @param array $dns DNS数据数组,domain=>ip
	 * @return string 解析出的IP地址,失败返回FALSE
	 */
	static function getIpByDomian($domain,$dns=array()){
		if ( empty($domain) ){
			return false;
		}
		static $dns_cache = array();
		if ( isset($dns_cache[$domain]) ){
			return $dns_cache[$domain];
		}
		if ( self::is_ip($domain) ){
			return $dns_cache[$domain] = $domain;
		}
		$ip = false;
		foreach($dns as $a=>$b){
			if ( substr($a,0,3) === '[*]' ){//例如 [*].jiuwap.cn 则 xx.jiuwap.cn
				if ( substr($domain,3-strlen($a)) != substr($a,3) ){
					continue;
				}
				if ( strpos(substr($domain,0,strlen($domain) - strlen($a) + 3 ),'.') === false  ){
					$ip = $b;
					break;
				}
			}else if ( substr($a,0,1) === '*' ){//例如 *.jiuwap.cn 则 xx.xx.jiuwap.cn
				if ( substr($domain,1-strlen($a)) == substr($a,1) ){
					$ip = $b;
					break;
				}
			}else if ( $a == $domain ) {
				$ip = $b;
				break;
			}
		}
		if ( $ip === false ){
			$ip = @gethostbyname($domain);
		}else if ( !self::is_ip($ip) ){
			$ip = @gethostbyname($ip);
		}
		if ( $ip === false || $ip == '' || $ip == $domain ){
			$ip = false;
		}
		return $dns_cache[$domain] = $ip;
	}

	/**
	 * 判断字符串是否为IP地址，可含端口号
	 *
	 * @param string $str 待判断的IP地址
	 * @param boolean $havePort 是否含有端口号
	 * @return string 成功返回IP地址，失败返回false
	 */
	static function is_ip($str,$havePort=false){
		if ( !$str ){
			return false;
		}

		if ( strpos($str,':') === false ){
			$port = 80;
		}else{
			$ip_agents = explode(':',$str);
			if ( count($ip_agents)!=2 || !is_numeric($ip_agents[1]) || $ip_agents[1]<1 || $ip_agents[1]>65535 ){
				return false;
			}
			$port = $ip_agents[1];
			$str = $ip_agents[0];
		}

		$ip_array = explode('.', $str);

		if (
			count($ip_array) != 4 ||
			!is_numeric($ip_array[0]) || $ip_array[0]<1 ||
			!is_numeric($ip_array[1]) || $ip_array[1]<0 || $ip_array[1]>255 ||
			!is_numeric($ip_array[2]) || $ip_array[2]<0 || $ip_array[2]>255 ||
			!is_numeric($ip_array[3]) || $ip_array[3]<0 || $ip_array[3]>255
			) {
			return false;
		}else{
			return $str.':'.$port;
		}
	}

	/**
	 * 解析HTTP的header为数组
	 *
	 * @param string $array 含有换行符的原始header数据
	 * @return array headers数组
	 */
	static function parse_header($array){
 		//$header['COOKIE'] = $this->_cookie;
 		$header['COOKIE'] = array();
		$header['STATUS'] = '0' ;
		if ( !is_array($array) ){
			$array = str_replace("\r","",$array);
			$array = explode("\n", $array) ;
		}
		foreach ($array as $value) {
			$pos = strpos($value, ':') ;
			if ( $pos ) {
				$key = trim(strtoupper(substr($value, 0, $pos))) ;
				$value = trim(substr($value, $pos + 1)) ;
				if ($key == 'SET-COOKIE') {
					$temp = self::parse_cookie($value) ;
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

	/**
	 * 解析HTTP的cookies为数组
	 *
	 * @param string $array 原始的cookies(不含SET-COOKIE: )
	 * @return array cookies数组
	 */
	static function parse_cookie($array){
		//http_parse_cookie($array);
		$name = $value = $path = $expires = $domain = '';
		$expires = time() + 86400;
		$array = explode(';', $array) ;
		if ( !$array ) {
			$a = strpos($array,'=');
			$name = trim(substr($array,0,$a));
			$value =trim(substr($array,$a+1));
		} else {
			$a = strpos($array[0],'=');
			$name = trim(substr($array[0],0,$a));
			$value = trim(substr($array[0],$a+1));
			unset($array[0]);
			foreach($array as $temp){
				$a = strpos($temp,'=');
				if ( $a ){
					$nam = trim(substr($temp,0,$a));
					$valu = trim(substr($temp,$a+1));
					if ( $nam == 'expires' || $nam == 'max-age' ){
						$expires = self::strtotime($valu);
					}elseif ( $nam == 'path'){
						$path = $valu;
					}elseif ( $nam == 'domain'){
						$domain = $valu;
					}elseif ( $nam == 'secure'){
						//$secure = $valu;
					}elseif ( $nam == 'httponly'){
						//$httponly = $valu;
					}else{
						//null
					}
				}
			}
		}
		return array('value'=>$value,'path'=>$path,'expires'=>$expires,'name'=>$name,'domain'=>$domain);
	}


}


/**
 * HTTP协议类(抽象 通用静态方法2)
 *
 * @package tianyiw
 * @author tianyiw
 * @version 1.0
 */
abstract class http_base extends http_static{
	/*待发送的*/
	public $_headers = array();
	public $_posts = array();
	public $_cookies = array();
	public $_files = array();
	public $_proxy = false;

	//DNS数据库
	public $_dns = array();

	//获取后的HTTP - body
	private $_response = false;

	//获取body的执行时间限制
	private $_response_timeused = 0;

	//分块下载时的缓冲大小
	protected $blocksize = 6000;

	/*返回的新值*/
	public $headers = array();
	public $cookies = array();

	//HTTP通讯超时时间
	public $timeout = 30;

	//HTTP通讯方法
	public $method = 'GET';

	//URL地址
	public $url = null;

	//URL解析后的数组
	public $urls = array();

	//是否进入调试模式
	public $debug = false;

	//HTTP最大location跳转次数
	public $location = 3;//跳转次数

	//回调COOKIE处理函数
	protected $_callback_read_cookie = false;
	protected $_callback_save_cookie = false;


	/**
	 * 设置HTTP联网时发送COOKIE的调用函数(例如多次跳转时的cookie发送)
	 *
	 * @param string $func 函数名
	 */
	public function callback_read_cookie($func=false){
		if ( $func!==false ){
			$this->_callback_read_cookie = $func;
		}else{
			if ( $this->_callback_read_cookie === false ){
				return;
			}
			$func = $this->_callback_read_cookie;
			$func($this);
		}
	}

	/**
	 * 设置HTTP联网时获取COOKIE的调用函数(例如多次跳转时的cookie保存)
	 *
	 * @param string $func 函数名
	 */
	public function callback_save_cookie($func=false){
		if ( $func!==false ){
			$this->_callback_save_cookie = $func;
		}else{
			if ( $this->_callback_save_cookie === false ){
				return;
			}
			$func = $this->_callback_save_cookie;
			$func($this);
		}
	}

	/**
	 * 将类序列号为字符串(HTTP获取的body内容)
	 *
	 * @return string body内容
	 */
	public function __toString(){
		return (String)$this->get_body();
	}

	/**
	 * 设置HTTP联网的跳转次数
	 *
	 * @param string $times 最大跳转次数,为-1或true则无限制跳转，为false则禁止跳转
	 */
	public function set_location($times){
		$this->location = $times;
	}

	/**
	 * 设置自定义DNS数据库
	 *
	 * @param string $domain 域名
	 * @param string $ip 域名对应的IP地址(如果为false,$domain可以为数组domain=>$ip)
	 */
	public function set_dns($domain,$ip=false){
		if ( $ip !== false ){
			$this->_dns[$domain] = $ip;
		}else if ( is_array($domain) ){
			foreach($domain as $a=>$b){
				$this->_dns[$a] = $b;
			}
		}else{
			throw new Exception('Format Error!');
		}
	}

	/**
	 * 设置HTTP缓冲池大小
	 *
	 * @param string $value 单位B
	 */
	public function set_blocksize($value){
		$this->blocksize = $value;
	}

	/**
	 * 设置HTTP超时时间
	 *
	 * @param string $value 单位秒
	 */
	public function set_timeout($value){
		$this->timeout = $value;
	}

	/**
	 * 通过文件内容(数组) 上传文件
	 *
	 * @param array $aData 文件数组(array(array(表单POST名,文件名,文件内容[,mime类型])))
	 */
	public function put_files_bystring($aData){
		foreach($aData as $data){
			if ( !is_array($data) || count($data)< 3 ){
				throw new Exception('Format Error , Only One Parameter , Is a ARRAY ($form_name,$file_name,$file_content[,$type])');
			}
			if ( !isset($data[3]) ){
				$data[3] = 'application/octet-stream';
			}
			$this->put_file_bystring($data[0],$data[1],$data[2],$data[3]);
		}
	}

	/**
	 * 通过文件路径(数组) 上传文件
	 *
	 * @param array $aData 文件数组(array(array(表单POST名,文件名,文件路径(建议用绝对路径)[,mime类型])))
	 */
	public function put_files_byfile($aData){
		foreach($aData as $data){
			if ( !is_array($data) || count($data)< 3 ){
				throw new Exception('Format Error , Only One Parameter , Is a ARRAY ($form_name,$file_name,$file_content[,$type])');
			}
			if ( !isset($data[3]) ){
				$data[3] = 'application/octet-stream';
			}
			$this->put_file_byfile($data[0],$data[1],$data[2],$data[3]);
		}
	}

	/**
	 * 通过文件内容 上传文件
	 *
	 * @param string $form_name 表单POST名
	 * @param string $file_name 文件名
	 * @param string $file_url 内容
	 * @param string $type 文件mime类型,可选
	 */
	public function put_file_bystring($form_name,$file_name,$file_content,$type='application/octet-stream'){
		$this->_files[$form_name] = array($file_name,$file_content,$type);
		$this->method = 'POST';
	}

	/**
	 * 通过文件路径 上传文件
	 *
	 * @param string $form_name 表单POST名
	 * @param string $file_name 文件名
	 * @param string $file_url 文件路径(推荐绝对路径)
	 * @param string $type 文件mime类型,可选
	 */
	public function put_file_byfile($form_name,$file_name,$file_url,$type='application/octet-stream'){
		$file_content = @file_get_contents($file_url);
		if ( $file_content === false ){
			throw new Exception('the File not Existence !');
		}
		$this->_files[$form_name] = array($file_name,$file_content,$type);
		$this->method = 'POST';
	}


	/**
	 * 发送指定header
	 *
	 * @param string $key key
	 * @param string $value value
	 */
	public function put_header($key,$value){
		$this->_headers[$key] = $value;
	}

	/**
	 * 发送指定headers(数组)
	 *
	 * @param array $aData array(array(key=>value))
	 */
	public function put_headers($aData){
		foreach($aData as $k=>$v){
			$this->put_header($k,$v);
		}
	}

	/**
	 * 发送指定cookie
	 *
	 * @param string $key key
	 * @param string $value value
	 */
	public function put_cookie($key,$value){
		$this->_cookies[$key] = $value;
	}

	/**
	 * 发送指定cookies(数组)
	 *
	 * @param array $aData array(array(key=>value))
	 */
	public function put_cookies($aData){
		foreach($aData as $k=>$v){
			$this->put_cookie($k,$v);
		}
	}

	/**
	 * 发送指定post
	 *
	 * @param string $key key
	 * @param string $value value
	 */
	public function put_post($key,$value){
		$this->_posts[$key] = $value;
		$this->method = 'POST';
	}

	/**
	 * 发送指定posts(数组)
	 *
	 * @param array $aData array(array(key=>value))
	 */
	public function put_posts($aData){
		foreach($aData as $k=>$v){
			$this->put_post($k,$v);
		}
	}

	/**
	 * 清空POST信息，转为GET，一般用于子类跳出POST时清空POST数据
	 *
	 */
	public function clean_posts(){
		$this->_posts = array();
		$this->method = 'GET';
	}

	/**
	 * 设置http代理
	 *
	 * @param string $ip 代理地址
	 * @param string $port 代理端口号,可选
	 */
	public function set_proxy($ip,$port){
		if ( !is_numeric($port) || $port < 0 || $port > 65535){
			throw new Exception('HTTP代理格式错误！');
			return false;
		}
		$ip = self::getIpByDomian($ip);
		if ( $ip === false ){
			throw new Exception('HTTP代理格式(端口)错误！');
			return false;
		}
		$this->_proxy = array($ip,$port);
		return true;
	}

	/**
	 * 设置HTTP_REFERER(也可用put_header('Referer',$value)来设置)
	 *
	 * @param string $value Refere内容
	 */
	public function set_referer($value){
		$this->put_header('Referer',$value);
	}

	/**
	 * 返回指定的header
	 *
	 * @param string $key header名
	 * @return string 返回指定header的值,不存在则返回null
	 */
	public function get_header($key){
		if ( isset($this->headers[$key]) ){
			return $this->headers[$key];
		}else{
			return null;
		}
	}

	/**
	 * 返回全部的headers(数组)
	 *
	 * @return array 返回全部的headers数组
	 */
	public function get_headers(){
		return $this->headers;
	}

	/**
	 * 返回指定的cookie
	 *
	 * @param string $key cookie名
	 * @return string 返回指定cookie的值,不存在则返回null
	 */
	public function get_cookie($key){
		if ( isset($this->cookies[$key]) ){
			return $this->cookies[$key];
		}else{
			return null;
		}
	}

	/**
	 * 返回全部的cookies(数组)
	 *
	 * @return array 返回全部的cookies数组
	 */
	public function get_cookies(){
		return $this->cookies;
	}

	/**
	 * 返回当前的URL
	 *
	 * @return string (目标或跳转后的)URL地址
	 */
	public function get_url(){
		return $this->url;
	}

	/**
	 * 返回当前的URL(数组)
	 *
	 * @return array (目标或跳转后的)URL地址(数组)
	 */
	public function get_urls($key=false){
		if ( $key === false ){
			return $this->urls;
		}else{
			return $this->urls[$key];
		}
	}

	/*
	public function get_error(){

	} */

	/**
	 * 设置URL，一般无需再外部调用，直接open即可！
	 *
	 * @param string $url 完整URL路径
	 * @return array URL地址(数组)
	 */
	public function put_url($url){
		$this->url = $url;
		$urls = @parse_url($url);
		if ( $urls === false ){
			//异常,解析URL错误
		}
		$urls['query'] = isset($urls['query']) ? $urls['query'] : null;
		$this->urls['scheme'] = (!isset($urls['scheme']) || $urls['scheme'] == 'http'  ) ? 'http' : 'https';
		$this->urls['host']  = isset($urls['host']) ? $urls['host'] : null;
		$this->urls['port']  = empty($urls['port']) ? 80 : (int)$urls['port'];
		$this->urls['path']  = empty($urls['path']) ? '/' : $urls['path'];
		$this->urls['query'] = strlen($urls['query']) > 0 ? '?'.$urls['query'] : null;
		$this->urls['ip']  = self::getIpByDomian($urls['host'],$this->_dns);
		return $this->urls;
	}

	/**
	 * 返回body
	 *
	 * @param string $max_length 最大值,可选
	 * @return string Body内容
	 */
	public function get_body($max_length=false){
		$this->_response === false && $this->_response = $this->response($max_length);
		return $this->_response;
	}

	/**
	 * 发送默认的header数据(指定语言和可接受的内容)
	 *
	 */
	public function put_header_default(){
		$this->put_header('Accept-Language','zh-CN,zh;q=0.9,en;q=0.8');
		$this->put_header('Accept','text/html, application/xml;q=0.9, application/xhtml+xml, application/vnd.wap.xhtml+xml, application/vnd.wap.wmlc;q=0.9, application/vnd.wap.wmlscriptc;q=0.7, text/vnd.wap.wml;q=0.7, */*;q=0.1');
	}

	/**
	 * 输出调试信息
	 *
	 */
	protected function show_debug(){
		if ( !$this->debug ){
			return;
		}
		$args = func_get_args();
		foreach($args as $opt=>$val){
			echo '<hr/>';
			if ( is_array($val) ){
				var_dump($val);
			}else{
				echo htmlspecialchars($val);
			}
			echo '<hr/>';
		}
	}

	/**
	 * 输出调试信息并中断脚本执行
	 *
	 */
	protected function show_debug_exit(){
		if ( !$this->debug ){
			return;
		}
		$args = func_get_args();
		foreach($args as $opt=>$val){
			echo '<hr/>';
			if ( is_array($val) ){
				var_dump($val);
			}else{
				echo htmlspecialchars($val);
			}
			echo '<hr/>';
		}
		exit();

	}

	/**
	 * 设置调试模式
	 *
	 * @param boolean $debug 是否为调试模式
	 */
	public function set_debug($debug=true){
		$this->debug = $debug;
		if ( $this->debug ){
			echo '[ Tips: Current in Debug Mode. ]<hr/>';
		}
	}



}


/**
 * HTTP通讯接口(含三个方法，分别是建立连接、发送、返回)
 *
 * @package tianyiw
 * @author tianyiw
 * @version 1.0
 */
interface Ihttp_api{

	/**
	 * 创建连接
	 *
	 * @param string $url 目标URL(如果为false则需在此之前调用put_url($url);)
	 */
	public function open($url=false);

	/**
	 * 发送数据(将会获取header部分，body部分可有可无)
	 * 获取header请使用get_header或get_headers
	 *  请注意在这里将要发送全部header/cookies/post/文件等内容，并获取完整header内容
	 *  如需改动或二次开发，请参见class.http.fsockopen.php文件
	 *
	 */
	public function send();

	/**
	 * 获取数据(body)
	 * 也可使用get_body获取body或直接使用类名打印
	 *
	 * @param int $max_length 最大获取的大小,默认为false则不限制大小
	 *
	 */
	public function response($max_length=false);
}


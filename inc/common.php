<?php
/*
 *
 *	浏览器核心类
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */

//error_reporting( E_ALL | E_STRICT );

//@ini_set('zlib.output_compression', 0);
//@ini_set('implicit_flush', 1);
function ob_gzip($content){
	if ( !defined('no_ob_gzip') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && !headers_sent() && extension_loaded('zlib') && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')!==false ){
		$content = gzencode($content,9);
		header('Content-Encoding: gzip');
		header('Vary: Accept-Encoding');
		header('Content-Length: '.strlen($content));
	}
	return $content;
}
ob_start('ob_gzip');//必须首先就执行否则出错？

define('ROOT_DIR',substr(__FILE__,0,-strlen('inc/common.php')));

require ROOT_DIR . 'inc/class/error.exception.php';

if ( isset($_SERVER['HTTP_JIUWAPB']) ){
	global $version;
	header('Content-Type: text/html; charset=utf-8');
	echo '错误：禁止访问，您当前使用玖玩浏览器访问了玖玩浏览器，系统禁止嵌套访问！您使用的浏览器版本['.$_SERVER['HTTP_JIUWAPB'].']，访问的浏览器版本['.$version.']';
	exit;
}

if ( get_magic_quotes_gpc() ){
	function stripslashes_deep($value){
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
	//$_SERVER && $_SERVER = stripslashes_deep($_SERVER);
	//$_REQUEST && $_REQUEST = stripslashes_deep($_REQUEST);
	$_COOKIE && $_COOKIE = stripslashes_deep($_COOKIE);
	$_POST && $_POST = stripslashes_deep($_POST);
	$_GET && $_GET = stripslashes_deep($_GET);
}

if(function_exists('ini_get')) {
	function return_bytes($val) {
		$val = trim(intval($val));
		$last = strtolower($val{strlen($val)-1});
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}
	$memorylimit = @ini_get('memory_limit');
	if( $memorylimit && return_bytes($memorylimit) < 33554432 ) {
		@ini_set('memory_limit', '32m');
	}
}


require_once ROOT_DIR .'set_config/set_config.php';
require_once ROOT_DIR .'inc/class/cloude.memcache.php';
require_once ROOT_DIR .'inc/class/cloude.storage.php';

require_once ROOT_DIR .'set_config/ad/init.php';
require_once ROOT_DIR .'inc/class/time.php';

require_once ROOT_DIR .'inc/function.php';
require_once ROOT_DIR .'inc/class/class.http.init.php';
require_once ROOT_DIR .'inc/class/class.db.php';

require_once ROOT_DIR .'inc/template.php';

include_once ROOT_DIR .'inc/class/captcha/Box.php';
include_once ROOT_DIR .'inc/class/captcha/Color.php';
include_once ROOT_DIR .'inc/class/captcha.class.php';

Class browser{
	public $login_key = array();
	public $db = null;

	public $uid = 0;
	public $rand = 0;
	public $uname = '';		//昵称
	public $template = -1;	//0:wap2  1:wap1  -1自动检测

	public $ipagent = 0;		//http代理
	public $ipagent_open = 0;	//http代理-开关
	public $wap2wml = 0;		//0关闭、1web转wap2、2wap转web、3webwap2转wml

	public $useragent = 0;	//浏览器UA
	public $pic = 0;		//图片显示
	public $pic_wap = 0;	//压缩图片是否对WAP可用,0对wap不压缩

	public $url_key = false;		//链接- 数字
	public $pic_key = false;		//图片- 数字
	public $_cachepic_have = array();//图片网址缓存(已经存在)
	public $_cacheurl_have = array();//链接网址缓存(已经存在)

	public $_cachepic = array();//
	public $_cacheurl = array();//

	public $num_time = 0;		//更新时间
	public $num_size_html = 0;	//html累计压缩
	public $num_size_pic = 0;	//图片累计压缩
	public $num_look = 0;		//浏览页面数

	public $template_foot = 0;		//底部模板

	function __construct(){
		//ob_start('ob_gzip');
		qqagent_init();//捕捉QQ浏览器UA
		global $b_set;
		$this->db = db::connect($b_set['db']['server'],$b_set['db']['user'],$b_set['db']['pass'],$b_set['db']['table']);
		$this->rand = rand(1,9999);
		$this->login_key = $this->_cookie_cut();
		if (isset($this->login_key[0])){
			$this->template = $this->login_key[0];
		}
		if ( $this->template!=0 && $this->template!=1 ){
			if ( IsWap2() ){
				$this->template = 0;
			}else{
				$this->template = 1;
			}
		}
		if ( $this->template == 1 ){
			define('hr','<br/>------------<br/>');
		}else{
			define('hr','<hr/>');
		}

	}

	function template_top($title,$refreshurl='',$return=false,$code='utf-8',$time=1){
		if ( $this->template == 0 ){
			return top_wap2($title,$refreshurl,$return,$code,$time);
		}else{
			return top_wap1($title,$refreshurl,$return,$code,$time);
		}
	}

	function template_foot($exit=true,$return=false,$code='utf-8'){
		if ( $this->template == 0 ){
			return foot_wap2($exit,$return,$code);
		}else{
			return foot_wap1($exit,$return,$code);
		}
	}

	function template_set($type){
		$this->template = $type;
		if ( isset($this->login_key[1]) && isset($this->login_key[2]) ){
			$name = $this->login_key[1];
			$pass = $this->login_key[2];
		}else{
			$name = '';
			$pass = '';
		}
		Set_cookie('FREE', $this->template.';'.$name.';'.$pass,time_()+2592000);
	}

	function user_news($num=5){
		$query = $this->db->query('SELECT name FROM `browser_users` ORDER BY id DESC LIMIT 0,'.(float)$num);
		$array = array();
		while ( $var = $this->db->fetch_array($query) ){
			$array[] = $var['name'];
		}
		return $array;
	}

	function cookie_del(){
		if ( $this->uid ){
			$this->db->query('DELETE FROM browser_cookies WHERE user_id='.$this->uid);
		}
		return true;
	}

	function cacheurl_del($type = 'url'){
	    require ROOT_DIR .'set_config/set_config.php';
		if ( !$this->uid ){
			return ;
		}
		if($b_set['server_php_mamcache_server']){
    		cloud_memcache::set($this->uid.'_url_key',0);
    		cloud_memcache::set($this->uid.'_pic_key',0);
    		}

		return ;

		if ( $type == 'url' ){
			$this->db->delete('browser_caches','type=0 AND uid='.$this->uid);
		}else{
			/* !!! todo 删除缓存文件 !!! */
			deldir($b_set['utemp'].'pics/'.$this->uid,false);
			$this->db->delete('browser_caches','type=1 AND uid='.$this->uid);
		}
	}


	function copy_num(){
		$arr = $this->db->fetch_first('SELECT COUNT(id) AS nums FROM `browser_copys` WHERE uid='.$this->uid);
		if ( isset($arr['nums']) ){
			return $arr['nums'];
		}else{
			return 0;
		}
	}

	function copy_change($id,$content){
		return $this->db->query('UPDATE `browser_copys` SET content="'.addslashes(trim($content)).'" WHERE id='.$id.' AND uid='.$this->uid);
	}

	function copy_add($content){
		$data = array(
			'uid'		=>	$this->uid,
			'content'	=>	addslashes(trim($content))
		);
		return $this->db->insert('browser_copys', $data,true);
	}
	function copy_look($id){
		return $this->db->fetch_first('SELECT id,content FROM `browser_copys` WHERE id='.$id.' AND uid='.$this->uid);
	}

	function copy_del($id = false){
		if ( $id === false ){
			$this->db->query('DELETE FROM `browser_copys` WHERE uid='.$this->uid);
		}else{
			return $this->db->delete('browser_copys','id='.$id.' AND uid='.$this->uid,1);
		}
	}

	function copy_lists(){
		$query = $this->db->query('SELECT id,content FROM `browser_copys` WHERE uid='.$this->uid.' ORDER BY id DESC');
		$array = array();
		$num = 0;
		while ( $var = $this->db->fetch_array($query) ){
			$num++;
			$array[] = $var;
		}
		return array($num,$array);
	}

	function copy_get($html,$start='',$end='',$nnn=0){
		$html = str_replace('@','&at;at;',$html);
		$html = str_replace('&copy;','©',$html);
		$html = str_replace('&nbsp;',' ',$html);
		$html = str_ireplace(array('<br/>','<br>','</p>','</table>','<br />'),'[/br/]', $html);
		$html = preg_replace('@<!--(.*?)-->@','', $html);
		$html = preg_replace('@<title(.*?)</title>@i','', $html);
		$html = preg_replace('@<noscript(.*?)</noscript>@i','', $html);
		$html = preg_replace('@<script(.*?)</script>@i','', $html);
		$html = preg_replace('@<embed(.*?)</embed>@i','', $html);
		$html = preg_replace('@<link(.*?)>@i','', $html);
		$html = preg_replace('@<iframe(.*?)</iframe>@i','', $html);
		$html = preg_replace('@<style(.*?)style>@i','', $html);
		$html = preg_replace('@<(strong|th|em|base|area|font|label|p|ul|div|!doctype|td|tr|span|sup|li|table|tbody|map)(.*?)>@i','', $html);
		$html = preg_replace('@<(h1|h2|h3|dt|dl|tr|td|li|dd|tbody)(/|)@i','', $html);
		$html = preg_replace('@(marginwidth|marginheight|leftmargin|topmargin|bgcolor|target|hidefocus|autocomplete|onmouseover|onclick|onload|id|style|usemap|rel|onchange|onmouseout|class|onMouseOver|onblur|onFocus|onkeydown|onkeypress|onmousedown)="(.*?)"@i','', $html);
		$html = preg_replace('@</(h1|h2|h3|p|dt|dl|div|ul|td|tr|li|table|label|tbody)>@i','[/br/]', $html);
		$html = preg_replace('@</(th|em|base|span|map|font|sup|dd|strong)>@i','', $html);
		$html = preg_replace('@<(.*?)>@i','', $html);
		$html = str_replace('&at;at;','@',$html);
		$html = htmlspecialchars_decode($html);
		while( strpos($html,'  ')){
			$html = str_replace('  ',' ', $html);
		}
		while( strpos($html,'[/br/][/br/]')){
			$html = str_replace('[/br/][/br/]','[/br/]', $html);
		}
		if ( strpos($html,'[/br/]') === 0 ){
			$html = substr($html,6);
		}

		$i = strrpos($html,'[/br/]');
		if ( $i != false && strlen($html) - $i === 6 ){
			$html = substr($html,0,$i);
		}

		$nn = array('','[br/]','[br]','(br)','///','//','\\\\','<br>','<br/>',"\r\n",' ');
		$html = str_replace('[/br/]',$nn[$nnn], $html);
		$end = trim($end);
		$start = trim($start);
		if ( $end == '' && $start == '' ){
			//复制全部内容
			return $html;
		}elseif ( $end == '' && $start != '' ) {
			//指定开头到结束
			return substr($html,strpos($html,$start));
		}elseif ( $end != '' && $start == '' ) {
			//从开头到指定结束
			return substr($html,0,strpos($html,$end)+strlen($end));
		}elseif ( !empty($_POST['end']) && !empty($_POST['start']) ) {
			//指定开头和结束
			return substr($html,strpos($html,$start),strpos(substr($html,strpos($html,$start)),$end)+strlen($end));
		}else{
			return '';
		}
	}

	function cacheurl_set(){
	    require ROOT_DIR .'set_config/set_config.php';
		/*if ( $this->uid == 0 ){
			write_log(__FILE__,__line__,'UID丢失:`_set',false);
		}*/
		if($b_set['server_php_mamcache_server']){
    		cloud_memcache::set($this->uid.'_url_key',$this->url_key);
    		cloud_memcache::set($this->uid.'_pic_key',$this->pic_key);
		    
		}
    		/*if ( $this->PHPLock ){
			$this->PHPLock->unlock();
			$this->PHPLock->endLock();
		}*/
	}

	function fixlower($str){
		$zimu = array(  'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$shuzi = array('_0','_1','_2','_3','_4','_5','_6','_7','_8','_9','_a','_b','_c','_d','_e','_f','_g','_h','_i','_j','_k','_l','_m','_n','_o','_p');
		return str_replace($zimu,$shuzi,$str);
	}

	function cache_add($type,$url,$referer=false,$mime=false){
	    require ROOT_DIR .'set_config/set_config.php';
		static $first = true;
		if ( $first ){
			if ( !$this->uid ){
				return null;
			}
			//$this->PHPLock = new PHPLock($b_set['utemp'].$this->uid.'/','bblock'.$this->uid);
			//$this->PHPLock->startLock ();
			//while( !$this->PHPLock->Lock() ){
			//	sleep(0.5);
			//}
			
			$key = false;
			
			if($b_set['server_php_mamcache_server'])
			    $key = cloud_memcache::get($this->uid.'_url_key');
			    
			if ($key){
				$this->url_key = $key;
			}else{
				$this->url_key = -1;
			}
			
			$key = false;
			
			if($b_set['server_php_mamcache_server'])
			    $key = cloud_memcache::get($this->uid.'_pic_key');
			    
			if ($key){
				$this->pic_key = $key;
			}else{
				$this->pic_key = -1;
			}

			$first = false;
		}

		if ( $type == 'url' ){
			$md5_url = md5($url);
			if ( isset($this->_cacheurl_have[$md5_url]) ){
				return $this->_cacheurl_have[$md5_url];
			}
			$this->url_key++;
			$key_new = num2short($this->url_key);
			if ( $this->url_key >= 3500 ){
				$this->url_key = -1;
			}
			$this->db->query('REPLACE INTO `browser_caches` SET keyid="'.$this->uid.'u'.$this->fixlower($key_new).'",content="'.addslashes(trim($url)).'",uid='.$this->uid.',type=0');
			$this->_cacheurl_have = array( $md5_url => $key_new ) + $this->_cacheurl_have;
		}elseif ( $type == 'pic' ){
			$md5_url = md5($url);
			if ( isset($this->_cachepic_have[$md5_url]) ){
				return $this->_cachepic_have[$md5_url];
			}
			$this->pic_key++;
			$key_new = num2short($this->pic_key);
			if ( $this->pic_key >= 10000 ){
				$this->pic_key = -1;
			}
			$arr = array(
				'url' => ($url),
				'referer' => ($referer),
				'mime' => $mime
				);
			$arr = addslashes(serialize($arr));
			$this->db->query('REPLACE INTO `browser_caches` SET keyid="'.$this->uid.'p'.$this->fixlower($key_new).'",content="'.$arr.'",uid='.$this->uid.',type=1');
			$this->_cachepic_have = array( $md5_url => $key_new ) + $this->_cachepic_have;
		}
		return $key_new;
	}

	function cache_get($type,$key){
		if ( !$this->uid ){
			return Null;
		}
		if ( $type == 'url' ){
			$var = $this->db->fetch_first('SELECT content FROM `browser_caches` WHERE keyid="'.$this->uid.'u'.$this->fixlower($key).'" AND type=0 AND uid='.$this->uid);
			if ( $var ){
				return $var['content'];
			}else{
				return Null;
			}
		}elseif ( $type == 'pic' ){
			$var = $this->db->fetch_first('SELECT content FROM `browser_caches` WHERE keyid="'.$this->uid.'p'.$this->fixlower($key).'" AND type=1 AND uid='.$this->uid);
			//loginfo('SELECT content FROM `browser_caches` WHERE keyid="'.$this->uid.'p'.$this->fixlower($key).'" AND type=1 AND uid='.$this->uid);
			if ( $var['content'] ){
				if ( !$var['content'] = @unserialize($var['content']) ){
					return array();
				}else{
					return $var['content'];
				}
			}else{
				return Null;
			}
		}else{
			exit('err');
		}
	}

	function num_del(){
		$this->db->query('UPDATE `browser_users` SET num_time='.time_().',num_size_html=0,num_size_pic=0,num_look=0 WHERE id='.$this->uid);
	}

	function site_get($id){
		$site = $this->site_lists();
		if ( isset($site[$id]) ){
			return $site[$id];
		}else{
			return false;
		}
	}

	function site_lists($all=true){
		$site = array();
		$site[] = array('title' => 'Traum','url' => 'http://www.jysafe.cn');
		$site[] = array('title' => '预留','url' => '');
		@include ROOT_DIR.'set_config/sites.php';
		return $site;
	}

	function history_del(){
	    require ROOT_DIR .'set_config/set_config.php';
		if ( !$this->uid ){
			return;
		}
        if($b_set['server_php_mamcache_server']){
        		cloud_memcache::set($this->uid.'_history_cache',serialize(array()));
        		cloud_memcache::set($this->uid.'_history_key',0);
        }
	}

	function history_add($title,$url,$content,$mime,$code,$html_size = 0,$pic_size = 0){
	    require ROOT_DIR .'set_config/set_config.php';
		if ( !$this->uid ){
			return null;
		}
		
		$history_key = false;
		
        if($b_set['server_php_mamcache_server'])
		    $history_key = cloud_memcache::get($this->uid.'_history_key');
		    
		if ( $history_key === false || $history_key >= 1000 ){
			$history_key = -1;
		}
		$history_key++;
		
        if($b_set['server_php_mamcache_server'])
		    cloud_memcache::set($this->uid.'_history_key',$history_key);

		$history = $this->history_get();
		$key_new = num2short($history_key);

		if ( $title=='' ){
			$title = $url;
		}
		$title = htmlspecialchars(htmlspecialchars_decode($title));

		if ( count($history) > 10 ){
			foreach( $history as $k => $t){
				unset($history[$k]);
				break;
			}
		}

		$history[$key_new] = array(
								'mime'		=>	$mime,
								'code'		=>	$code,
								'title'		=>	$title,
								'url'		=>	$url,
								'content'	=>	$content
							);
		
		if($b_set['server_php_mamcache_server'])
		    cloud_memcache::set($this->uid.'_history_cache',serialize($history));
		    
		$sql = '';
		if ( $html_size > 0 ){
			$sql .= ',num_size_html=num_size_html+'.$html_size;
		}
		if ( $pic_size > 0 ){
			$sql .= ',num_size_pic=num_size_pic+'.$num_size_pic;
		}
		$this->db->query('UPDATE `browser_users` SET num_look=num_look+1'.$sql.' WHERE id='.$this->uid);
		return $key_new;
	}

	function history_get($key=false){
	    require ROOT_DIR .'set_config/set_config.php';
		if ( !$this->uid ){
			return array();
		}
		
		$history = false;
		
        if($b_set['server_php_mamcache_server'])
		    $history = cloud_memcache::get($this->uid.'_history_cache');
		    
		if ( !$history || !$history = unserialize($history)){
			$history = array();
		}

		if ( $key !== false && isset($history[$key])){
			$arr = $history[$key];
			$arr['key'] = $key;
			return $arr;
		}elseif ( $key !== false ){
			return false;
		}else{
			return $history;
		}
	}

	function num_add($html_size = 0,$pic_size = 0){
		if ( $html_size > 0 && $pic_size > 0 ){
			$sql = 'num_size_html=num_size_html+'.$html_size.',num_size_pic=num_size_pic+'.$pic_size;
		}elseif ( $html_size > 0 ){
			$sql = 'num_size_html=num_size_html+'.$html_size;
		}elseif ( $pic_size > 0 ){
			$sql = 'num_size_pic=num_size_pic+'.$pic_size;
		}else{
			return;
		}
		$this->db->query('UPDATE `browser_users` SET '.$sql.' WHERE id='.$this->uid);
	}


	function book_change($id,$title,$url){
		$data = array(
			'title'	=>	trim($title),
			'url'	=>	trim($url)
		);
		$this->db->update('browser_books',$data,'id='.$id.' AND uid='.$this->uid);
	}

	function book_get($id,$addnum = false){
		return $this->db->fetch_first('SELECT title,url FROM `browser_books` WHERE id='.$id.' AND uid='.$this->uid);
	}

	function book_check(){
		if ( !isset($_POST['title']) || empty($_POST['title']) ){
			return '书签标题不能为空。';
		}elseif ( !isset($_POST['url']) || empty($_POST['url']) ){
			return '书签网址不能为空。';
		}elseif ( strlen($_POST['url']) > 250 ){
			return '书签网址太长。';
		}elseif ( strlen($_POST['title']) > 90 ){
			return '书签标题太长。';
		}else{
			return null;
		}
	}

	function book_del($id){
		return $this->db->delete('browser_books','id='.$id.' AND uid='.$this->uid,1);
	}

	function book_add($title,$url,$change = false){
		$data = array(
			'uid'	=>	$this->uid,
			'nums'	=>	0,
			'title'	=>	addslashes(trim($title)),
			'url'	=>	addslashes(trim($url))
		);
		if ( $change ){
			$this->db->delete('browser_books','(`title`="'.$data['title'].'" OR `url`="'.$data['url'].'") AND uid='.$this->uid,1);
		}
		$this->db->insert('browser_books', $data);
	}

	function book_order($id,$order){
		$arr = $this->book_lists();
		$all = count($arr);
		if ( $order == 'up'){
			if ( $id == 1 ){
				return;
			}
		}else{
			if ( $id == $all ){
				return;
			}
		}
		if ( !isset($arr[$id-1]['id']) ){
			return;
		}
		$temp_id = $arr[$id-1]['id'];

		$i = 0;
		foreach ( $arr as $val){
			$old[$i] = $val['id'];
		}
		unset($arr[$id-1]);

		$i = 0;
		foreach ( $arr as $val){
			$i++;
			if ( $order == 'up'){
				if ( $i == $id -1 ){
					$new[$i] = $temp_id;
					$i++;
					$new[$i] = $val['id'];
				}else{
					$new[$i] = $val['id'];
				}
			}else{
				$new[$i] = $val['id'];
				if ( $i == $id ){
					$i++;
					$new[$i] = $temp_id;
				}
			}

		}
		if ( $old == $new){
			return;
		}
		foreach ( $new as $nums=>$id){
			if ( !isset($old[$nums]) || $old[$nums] != $id){
				$this->db->query('UPDATE `browser_books` SET nums='.$nums.' WHERE id='.$id);
			}
		}
	}

	function book_lists(){
		$query = $this->db->query('SELECT id,title FROM `browser_books` WHERE uid='.$this->uid.' ORDER BY nums,id ASC');
		$array = array();
		while ( $var = $this->db->fetch_array($query) ){
			$array[] = $var;
		}
		return $array;

	}

	private function _cookie_cut(){
		if ( isset($_COOKIE[',_FREE']) && !isset($_COOKIE['FREE']) ){
			$_COOKIE['FREE'] = $_COOKIE[',_FREE'];
		}
		if ( isset($_COOKIE['FREE']) ){
			$login_key = trim($_COOKIE['FREE']);
		}else{
			return false;
		}
		$login_key = explode(';',$login_key);
		return $login_key;
	}

	function user_logout(){
		Set_cookie('FREE', $this->template.';;',time_()+2592000);
	}

	function user_login_check(){
		global $b_set;
		if ( $this->user_login() === false ){
			header('location: /login.php?r='.$this->rand);
			exit;
		}else{

			if ( !IsWap2() && $this->template == 1){
				$browser->wap2wml = 3;
			}
		}
	}

	function set_ipagent_check($ip,$connecting = false){


		if( !preg_match('/^[0-9a-zA-Z\_\-\:\.]*$/i',$ip ) ){
			return false;
		}
		//$preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5])):[0-9]{2,5}\Z/";
		//if(!preg_match($preg,$ip)){
		//	return false;
		//}
		if ( $connecting ){
			$ip = explode(':',$ip);
			$http = new httplib();
			$http->set_timeout(20);
			$http->open('http://home.baidu.com/');
			if ( !@$http->set_proxy(trim($ip[0]),trim($ip[1])) ){
				return false;
			}
			$http->send();
			$header = $http->get_headers();
			if ( !isset($header['STATUS']) || $header['STATUS'] !=200 ){
				return false;
			}
			$response = getUTFString($http->get_body());
			if ( empty($response) || stripos($response,'<title>关于百度</title>') === false){
				return false;
			}
		}
		return true;
	}


	function set_default(){
		$this->db->update('browser_users',$this->_set_default(),'id='.$this->uid);
	}

	function set_config($array){
		$this->db->update('browser_users',$array,'id='.$this->uid);
	}

	function _set_default(){
		$var = array(
				'config_pic'		=>	'4',
				'config_useragent'	=>	'0',
				'config_wap2wml'	=>	'0',
				'config_ipagent_open'=>'0',
				'config_pic_wap'	=>	'0',
				'template_foot'		=>	'[book]|[menu][br][size]',
		);
		return $var;
	}

	function user_login($name = false, $pass = false ,$template = 0){
		$type = 0;	//登录方式(表单)
		if ( $name === false || $pass === false ){
			if ( isset($this->login_key[1]) && isset($this->login_key[2]) ){
				$name = trim($this->login_key[1]);
				$pass = trim($this->login_key[2]);
				if ( $this->_user_name_check($name,$pass) !== false ){
					return false;
				}
			}else{
				return false;
			}
			$type = 1;	//cookie登录
		}
		unset($login_key);//config_cutpage,
		$var = $this->db->fetch_first('SELECT config_ipagent_open,config_ipagent,config_wap2wml,config_useragent,config_pic,config_pic_wap,id,num_time,num_size_html,num_size_pic,num_look,template_foot FROM `browser_users` WHERE name="'.$name.'" AND pass="'.$pass.'"');
		//exit(json_encode($var));
		if ( $var === null ){
			return false;
		}else{
			$this->ipagent = $var['config_ipagent'];
			$this->wap2wml = $var['config_wap2wml'];
			$this->useragent = $var['config_useragent'];
			$this->pic = $var['config_pic'];
			$this->pic_wap = $var['config_pic_wap'];
			$this->uid = $var['id'];
			$this->uname = $name;
			$this->num_time = $var['num_time'];
			$this->ipagent_open = $var['config_ipagent_open'];
			$this->num_size_html = $var['num_size_html'];
			$this->num_size_pic = $var['num_size_pic'];
			$this->num_look = $var['num_look'];
			$this->template_foot = $var['template_foot'];

			if ( $type === 0 ){
				Set_cookie('FREE', $this->template.';'.$name.';'.$pass,time_()+2592000);
			}
			return true;
		}
	}

	function user_repass($name, $pass){
		$str = $this->_user_name_check($name,$pass);
		if ( !$str ){
			return false;
		}
		$var = $browser->db->fetch_first('SELECT pass,id FROM `browser_users` WHERE name="'.$name.'"');
		if ( $var){
			if ( $var['pass']!=$pass ){
				$browser->db->query('UPDATE `browser_users` SET pass="'.$pass.'" WHERE id='. $var['id']);
				return true;
			}
		}
		return false;
	}

	function user_reg($name, $pass, $pass1=false, $sendcookie=true,$vip=false){
	    if($vip){
	        //exit("VIP");
	        Traum_Captcha_question_validate();
	        
	    }
	    
		if ( $pass1 === false ){
			$pass = $pass1;
		}
		$error = false;
		if ( $pass!=$pass1 ){
			$error = '两次密码不一样。';
		}else{
			$error = $this->_user_name_check($name,$pass);
		}
		if ( $error === false ){
			if ( $this->db->fetch_first('SELECT id FROM `browser_users` WHERE name="'.$name.'"') !== null ){
				$error = '该账号['.$name.']已存在';
			}
		}
		if ( $error === false ){
			$time = time_();
			$var = array(
					'name'			=>	$name,
					'pass'			=>	$pass,
					'num_time'		=>	$time,
					'num_look'		=>	'0',
					'num_size_html'	=>	'0',
					'num_size_pic'	=>	'0',
				);
			$var += $this->_set_default();
			$this->db->insert('browser_users',$var);
			if ( $sendcookie ){
				Set_cookie('FREE', $this->template.';'.$name.';'.$pass,time_()+2592000);
			}
		}
		return $error;
	}
//ereg('^[0-9a-zA-Z\_]*$',$pass )
//preg_match('/^[0-9a-zA-Z\_]*$/i',$name )
	Function _user_name_check($name,$pass){
		if( !preg_match('/^[0-9a-zA-Z\_]*$/i',$name ) ){
			return '账号必须为数字或者英文字符。';
		}elseif( !preg_match('/^[0-9a-zA-Z\_]*$/i',$name ) ){
			return '密码必须为数字或者英文字符。';
		}elseif( strlen($name)<5 || strlen($name)>15 ){
			return '账号长度必须在5到15位之间。';
		}elseif( strlen($pass)<5 || strlen($pass)>15 ){
			return '密码长度必须在5到15位之间。';
		}else{
			return false;
		}
	}

	Function GetHost($h){
		$h = strtolower('.'.$h);
		$arr = array(
				'7'	=>	array('.org.cn','.gov.cn','.net.cn','.com.cn','.com.hk'),
				'4'	=>	array('.com','.net','.org','.tel'),
				'3'	=>	array('.la','.co','.cn','.me','.cc','.hk','.tk','.in','.gp','.us','.lc'),
				'5'	=>	array('.mobi','.info','.name','.asia'),
				);
		foreach($arr as $nn => $houzhui){
			$h_len = strlen($h)-$nn;
			foreach( $houzhui as $val){
				if ( substr($h,$h_len,$nn) == $val ){
					$temp = substr($h,0,$h_len);
					return substr($temp,strrpos($temp,'.')+1,$h_len).$val;
				}
			}
		}
		return $h;
	}


	//提取COOKIE
	function cookieGet($domain,$path,$getAll=false){
		static $time = 0;
		if ( !$time ) {
			$time = time_();
		}
		if ( $domain == '' ){
			$query = $this->db->query('SELECT * FROM browser_cookies WHERE user_id='.$this->uid);

			$待选 = array();
			while ( $data = $this->db->fetch_array($query) ){
				if ( $data['expires'] < $time ){
					$this->db->delete('browser_cookies','id='.$data['id']);
				}else{
					if ( $getAll ){
						$data['value'] = urldecode($data['value']);
						$待选[] = $data;
					}else{
						$待选[$data['key']] = urldecode($data['value']);
					}
				}
			}
			return $待选;
		}
		$domain = strtolower($domain);
		$domains = explode('.',$domain);
		$path = strtolower($path);
		$domain_root = $this->GetHost($domain);

		$待选 = array();
		if ( $domain_root == $domain || /*无点域名*/strpos($domain,'.') === false ){
			$待选[] = '.'.$domain;
			$待选[] = $domain;

		}else if ( is_numeric(end($domains)) ){//IP
			$待选[] = $domain;

		}else{
			$domain_root_num = substr_count($domain_root, '.');
			$domains = substr($domain,0,strlen($domain) - strlen($domain_root) - 1);
			$domains = explode('.',$domains);
			$domains = array_reverse($domains);
			$num = count($domains)-1;
			$had = '.'.$domain_root;
			$待选[] = $had;
			foreach($domains as $k=>$tmp){
				$had = $tmp . $had;
				if ( $num != $k){
					$had = '.'.$had;
				}
				$待选[] = $had;
			}
		}
		foreach($待选 as &$tmp){
			$tmp = 'domain="'.$tmp.'"';
		}
		$待选 = implode(' OR ',$待选);
		//echo $待选;exit;
		$query = $this->db->query('SELECT id,`key`,value,path,expires FROM browser_cookies WHERE user_id='.$this->uid.' AND ( '.$待选.' )');

		$待选 = array();
		while ( $data = $this->db->fetch_array($query) ){
			if ( $data['expires'] < $time ){
				$this->db->delete('browser_cookies','id='.$data['id']);
			}else{
				if ( $data['path']=='/' || $data['path']==''  || substr($path,0,strlen($data['path'])) == $data['path'] ){
					if ( $getAll ){
						$data['value'] = urldecode($data['value']);
						$待选[] = $data;
					}else{
						$待选[$data['key']] = urldecode($data['value']);
					}
				}
			}
		}
		return $待选;
	}


	//保存更新COOKIE
	function cookieSave($host,$domain,$key,$value,$path,$expires){
		static $time = 0;
		if ( !$time){
			$time = time_();
		}
		$host = strtolower($host);
		if ( $domain == '' ){
			$domain = $host;
		}else{
			//非法设置有效域名(跨域)
			$domain = strtolower($domain);
			if ( $domain != $host ){
				$host1 = '.'.$host;
				$domain1 = $domain;
				if ( substr($domain,0,1) != '.'  ){
					$domain1 = '.'.$domain1;
				}
				if ( substr($domain1,-strlen($host1))!=$host1 && substr($host1,-strlen($domain1))!=$domain1 ) {
					return ;
				}
			}
		}
		if ( $expires <= $time ){
			//echo $expires.'|'.$time;
			//都过期了,还设置什么
			$this->db->delete('browser_cookies','user_id='.$this->uid.' AND `key`="'.$key.'" AND path="'.$path.'" AND domain="'.$domain.'"');
			return;
		}else if ( $key === '' || $value === '' ){
			//坑爹，什么都木有
			return ;
		}
		$path = strtolower($path);
		$data = array(
			'user_id' => $this->uid,
			'domain' => $this->db->escape_string($domain),
			'path' => $this->db->escape_string($path),
			'key' => $this->db->escape_string($key),
			'value' => urlencode($value),
			'expires' => $expires,
		);
		$this->db->replace('browser_cookies', $data ,'user_id='.$this->uid.' AND `key`="'.$key.'" AND path="'.$path.'" AND domain="'.$domain.'"');
	}

	public function __destory(){
		//测试得出此析构函数无法被执行。
		//echo 'aa';
		/*if ( $this->PHPLock ){
			$this->PHPLock->unlock();
			$this->PHPLock->endLock();
		}*/
	}

	function selectBrowserUA(){
		global $http,$HTTP_Q_UA,$HTTP_Q_AUTH,$HTTP_Q_GUID,$url_A,$version;
		switch ($this->useragent){
		case 0://QQ浏览器
			$http->put_header('User-Agent', 'TTMobile/09.03.18/symbianOS9.1 Series60/3.0 Nokia6120cAP3.03') ;
			if ( substr(strtolower($url_A['host']),strlen($url_A['host'])-6)=='qq.com' ){
				$HTTP_Q_UA && $http->put_header('Q-UA', $HTTP_Q_UA) ;
				$HTTP_Q_AUTH && $http->put_header('Q-AUTH', $HTTP_Q_AUTH) ;
				$HTTP_Q_GUID && $http->put_header('Q-GUID', $HTTP_Q_GUID) ;
			}
			$http->put_header('via','WTP/1.1 BJBJ-P-GW13-WAP.bj.monternet.com (Nokia WAP Gateway 4.1 CD1/ECD13_D/4.1.04)');
			break;
		case 1://UC浏览器
			$http->put_header('User-Agent', 'Nokia5230/UCWEB7.4.0.57/50/999') ;
			$http->put_header('via','WTP/1.1 BJBJ-P-GW13-WAP.bj.monternet.com (Nokia WAP Gateway 4.1 CD1/ECD13_D/4.1.04)');
			break;
		case 2://IE浏览器
			$http->put_header('User-Agent', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)') ;
			break;
		case 3://FF浏览器
			$http->put_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.4) Gecko/20100413 Firefox/3.6.4') ;
			break;
		case 4://OP浏览器
			$http->put_header('User-Agent', 'Opera/9.80 (Windows NT 5.1; U; zh-cn) Presto/2.6.30 Version/10.63') ;
			break;
		case 6://移动模拟
			$http->put_header('User-Agent', 'Mozilla/5.0 (Nokia5800 XpressMusic)UC ApplieWebkit(Gecko) Safari/530') ;
			$http->put_header('x-wap-profile','http://nds1.nds.nokia.com/uaprof/N6670r100.xml');
			$http->put_header('x-network-info','GPRS,8615006538888,218.201.170.205,cmwap,unsecured');
			$http->put_header('x-nokia-gateway-id','NWG/4.1/Build4.1.04');
			$http->put_header('x-up-calling-line-id','8615006538888');
			$http->put_header('x-up-subno','8615006538888');
			$http->put_header('x-nokia-msisdn','8615006538888');
			$http->put_header('x-up-bearer-type','GPRS/EDGE');
			$http->put_header('x-nokia-connection-mode','TCP');
			$http->put_header('x-source-id','BJGGSN06BMT-CSK');
			$http->put_header('x-forwarded-for','218.201.170.205');
			$http->put_header('client-ip','218.201.170.205');
			$http->put_header('via','WTP/1.1 +-P-GW13-WAP.bj.monternet.com (Nokia WAP Gateway 4.1 CD1/ECD13_D/4.1.04)');
			break;
		case 7://iphone
			$http->put_header('User-Agent', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7') ;
			break;
		case 8://S60V5 QQ浏览器
			$http->put_header('User-Agent', 'MQQBrowser/2.0 (Nokia5230;SymbianOS/9.1 Series60/3.0)') ;
			$http->put_header('Q-UA', 'SQB22_GA/220441&SMTT_3/020100&SYM5&224014&Nokia5230&0&5775&V3') ;
			global $b_set;
			if ( $b_set['switch']['qqua'] ){
				$HTTP_Q_AUTH && $http->put_header('Q-AUTH', $HTTP_Q_AUTH) ;
				$HTTP_Q_GUID && $http->put_header('Q-GUID', $HTTP_Q_GUID) ;
			}
			break;
		case 9://JIUWAP浏览器特权
			$http->put_header('User-Agent', 'JIUWAP/'.$version.' (zh-cn; java; wap; php; tianyiw;)') ;
			$http->put_header('via','WTP/1.1 mm.jiuwap.cn('.$version.')');
			$http->put_header('Q-UA', 'SQB22_GA/220441&SMTT_3/020100&SYM5&224014&JIUWAP&0&5775&'.$version) ;
			if ( substr(strtolower($url_A['host']),strlen($url_A['host'])-6)=='qq.com' ){
				$HTTP_Q_AUTH && $http->put_header('Q-AUTH', $HTTP_Q_AUTH) ;
				$HTTP_Q_GUID && $http->put_header('Q-GUID', $HTTP_Q_GUID) ;
			}
			break;
		case 10://Chrome浏览器(Phone)
			$http->put_header('User-Agent', 'Mozilla/5.0 (Linux; Android 7.1.2; Lenovo K3 Note) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.90 Mobile Safari/537.36') ;
			break;
		case 11:
		    $http->put_header('User-Agent',$_SERVER['HTTP_USER_AGENT']);
		    break;
		default:
			//JIUWAP浏览器
			$http->put_header('User-Agent', 'JIUWAP/'.$version.' (zh-cn; symbianOS9.1; Series60/3.0; Nokia6120cAP3.03)') ;
			$http->put_header('via','WTP/1.1 BJBJ-P-GW13-WAP.bj.monternet.com (Nokia WAP Gateway 4.1 CD1/ECD13_D/4.1.04)');
			break;
		}
	}


	function tempfile_write($file,$content){
		cloud_storage::write('tmp2_' .$file,$content);
		$this->db->replace('browser_temps_file', array('uid'=>$this->uid,'file'=>$file,'time'=>time_()) ,'uid='.$this->uid.' AND `file`="'.$file.'"');
	}

	function tempfile_read($file){
		$var = $this->db->fetch_first('SELECT id,`file`,`time` FROM `browser_temps_file` WHERE uid='.$this->uid.' AND `file`="'.$file.'"');

		if ( $var === null ){
			return false;
		}else if ( $var['time'] < time_() - 1 * 24 * 60 * 60 ){
			$this->db->delete('browser_temps_file', 'id='.$var['id'] );
			return false;
		}else{
			return cloud_storage::read('tmp2_' .$file);
		}
	}

	function tempfile_exists($file){
	    //exit($file);
	    $sql = 'SELECT id,`file`,`time` FROM `browser_temps_file` WHERE uid='.$this->uid.' AND `file`="'.$file.'"';
		$var = $this->db->fetch_first($sql);
		//exit($var);

		if ( $var === null ){
			return false;
		}else if ( $var['time'] < time_() - 1 * 24 * 60 * 60 ){
		    //exit($var['id']);
			$this->db->delete('browser_temps_file', 'id='.$var['id'] );
			return false;
		}else{
			return cloud_storage::exists('tmp2_' .$file);
		}
	}

	function tempfile_delete($file = false){
		if ( $file === false || $file === true ){
			$query = $this->db->query('SELECT id,`file` FROM `browser_temps_file` WHERE `time`<' .( time_() - 7 * 24 * 60 * 60)  .' ORDER BY id');
			while ( $var = $this->db->fetch_array($query) ){
				cloud_storage::delete('tmp2_' .$var['file']);
				$this->db->delete('browser_temps_file', 'id='.$var['id'] );
			}

		}else{
			$var = $this->db->fetch_first('SELECT id FROM `browser_temps_file` WHERE uid='.$this->uid.' AND `file`="'.$file.'"');
			if ( $var ){
				cloud_storage::delete('tmp2_' .$file);
				$this->db->delete('browser_temps_file', 'id='.$var['id'] );
			}
		}
	}



	function temp_write($type,$key,$value){
		$value = $this->db->escape_string($value);
		$this->db->replace('browser_temps', array('uid'=>$this->uid,'type'=>$type,'key'=>$key,'value'=>$value,'time'=>time_()) ,'uid='.$this->uid.' AND `key`="'.$key.'" AND `type`="'.$type.'"');
	}

	function temp_read($type,$key,$value=false){
		$value = $this->db->escape_string($value);
		$var = $this->db->fetch_first('SELECT `value`,`time` FROM `browser_temps` WHERE uid='.$this->uid.' AND `key`="'.$key.'" AND `type`="'.$type.'"');
		if ( $var === false ){
			$var = $value;
		}else if ( $var['time'] < time_() - 7 * 24 * 60 * 60 ){
			$this->db->delete('browser_temps', 'uid='.$this->uid.' AND `key`="'.$key.'" AND `type`="'.$type.'"');
			$var = $value;
		}else{
			$var = $var['value'];
		}

		return $var;
	}

	function temp_clean($type=false,$uid=0){
		if ( $type === true ){
			$this->db->delete('browser_temps');
		}else if ( $type ){
			if ( $uid == 0 ){
				$this->db->delete('browser_temps', '`type`="'.$type.'" OR `time`<' .( time_() - 7 * 24 * 60 * 60) );
			}else{
				$this->db->delete('browser_temps', '`type`="'.$type.'" AND uid='.$uid.' OR `time`<' .( time_() - 7 * 24 * 60 * 60) );
			}
		}else{
			$this->db->delete('browser_temps', '`time`<' .( time_() - 7 * 24 * 60 * 60) );
		}
	}
	function quickLogin_set(){
	    require ROOT_DIR .'set_config/set_config.php';
	    
	    if($b_set['server_php_mamcache_server'])
		    $token = cloud_memcache::get('tmp_quicklogin_' . $this->uid);
		    
		echo $token;exit;
	}

	function quickLogin_login($type,$token){
		//$type = $type=='qq'?0:1;
		$var = $this->db->fetch_first('SELECT `uid`,`time` FROM `browser_users_quicklogin` WHERE `key`="'.$token.'" AND `type`="'.$type.'"');
		if ( $var ){
			//if ( $var['time'] < time_()-3*30*24*60*60 ){
				//token过期
			//	return false;
			//}

			$var2 = $this->db->fetch_first('SELECT `name`,`pass` FROM `browser_users` WHERE `id`='.$var['uid']);
			if ( $var2 ){
				//直接进行登陆罢了
				$this->user_login($var2['name'],$var2['pass']);
				return true;
			}else{
				//用户不存在
				return false;
			}

		}else{
			//不存在或过期
			return false;
		}
	}

	//得到全部的DNS，包含系统内设的。
	function dns_getAll(){
		global $b_set;
		$dns = array();
		@include(ROOT_DIR . 'set_config/set_dns.php');
		if ( !$b_set['switch']['dns'] ){
			return $dns;
		}
		$query = $this->db->query('SELECT `domain`,`target` FROM browser_dns WHERE uid='.$this->uid .' ORDER BY id ASC' );
		while ( $data = $this->db->fetch_array($query) ){
			$dns[$data['domain']] = $data['target'];
		}
		return $dns;
	}

	function dns_getMy(){
		$dns = array();
		$query = $this->db->query('SELECT `id`,`domain`,`target` FROM browser_dns WHERE uid='.$this->uid .' ORDER BY id ASC' );
		while ( $data = $this->db->fetch_array($query) ){
			$dns[] = $data;
		}
		return $dns;
	}
}

$browser = new browser;

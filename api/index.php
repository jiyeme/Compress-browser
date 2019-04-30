<?php
/*
 *
 *	浏览器->API接口
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *

获取版本号
http://test/api/?cmd=version

获取书签
http://test/api/?cmd=book&username=用户名&password=密码

清理垃圾
http://test/api/?cmd=clean

 */

define('no_ob_gzip','true');
set_time_limit(7200);

$version = '0';
include '../set_config/version.php';
if ( $version == 0 ){
	header('LOCATION: /install/index.php');
    exit;
}

$cmd = isset($_GET['cmd']) ? strtolower(trim($_GET['cmd'])) : null;
if ( $cmd == 'synch' ){
	$_COOKIE['FREE'] = isset($_GET['key']) ? trim($_GET['key']) : '';
}

require '../inc/common.php';


if ( $cmd == 'version' ){
	echo $version;

}elseif ( $cmd == 'synch' ){
	ignore_user_abort(true);

	$browser->user_login_check();
	$name = isset($_GET['name']) ? trim($_GET['name']) : '';
	$pass = isset($_GET['pass']) ? trim($_GET['pass']) : '';
	$host = isset($_GET['host']) ? trim($_GET['host']) : '127.0.0.1';
	$cover = isset($_GET['cover']) ? trim($_GET['cover']) : 1;
	if( !preg_match('/^[0-9a-zA-Z\_\-\:\.]*$/i',$host ) ){
		exit('error#host');
	}
	$book ='http://'.$host.'/api/?cmd=book&username='.urlencode($name).'&password='.urlencode($pass);
	$book = file_get_content($book);
	if ( $book ){
		$book = getJson($book);
		if ( $book[0] ){
			foreach ($book[1] as $arr){
				$browser->book_add($arr['title'],$arr['url'],$cover);
			}
		}
	}
	unset($book);

	$cookie ='http://'.$host.'/api/?cmd=cookie&username='.urlencode($name).'&password='.urlencode($pass);
	$cookie = file_get_content($cookie);
	if ( $cookie ){
		$cookie = getJson($cookie);
		if ( $cookie[0] ){
			foreach ($cookie[1] as $arr){
				$browser->cookieSave($arr['domain'],$arr['domain'],$arr['key'],$arr['value'],$arr['path'],$arr['expires']);
			}
		}
	}
	unset($cookie);
	echo 'ok';

}elseif ( $cmd == 'cookie' ){
	@ignore_user_abort(true);

	$name = isset($_GET['username']) ? trim($_GET['username']) : null;
	$pass = isset($_GET['password']) ? trim($_GET['password']) : null;

	if ( $browser->_user_name_check($name,$pass) ){
		echo returnJson(array(0,array()));exit;
	}
	$var = $browser->db->fetch_first('SELECT id FROM `browser_users` WHERE name="'.$name.'" AND pass="'.$pass.'"');
	if ( !$var ){
		echo returnJson(array(0,array()));exit;
	}else{
		$id = $var['id'];
	}
	$query = $browser->db->query('SELECT `value`,domain,path,expires,`key` FROM `browser_cookies` WHERE user_id='.$id);
	$arr = array();
	while ( $var = $browser->db->fetch_array($query) ){
		$arr[] = $var;
	}
	echo returnJson(array(1,$arr));exit;

}elseif ( $cmd == 'book' ){
	@ignore_user_abort(true);

	$name = isset($_GET['username']) ? trim($_GET['username']) : null;
	$pass = isset($_GET['password']) ? trim($_GET['password']) : null;

	if ( $browser->_user_name_check($name,$pass) ){
		echo returnJson(array(0,array()));exit;
	}
	$var = $browser->db->fetch_first('SELECT id FROM `browser_users` WHERE name="'.$name.'" AND pass="'.$pass.'"');
	if ( !$var ){
		echo returnJson(array(0,array()));exit;
	}else{
		$id = $var['id'];
	}
	$query = $browser->db->query('SELECT title,url FROM `browser_books` WHERE uid='.$id.' ORDER BY nums,id ASC');
	$arr=array();
	while ( $var = $browser->db->fetch_array($query) ){
		$arr[] = $var;
	}
	echo returnJson(array(1,$arr));exit;

}elseif ( $cmd == 'clean' ){
	if ( !isset($_REQUEST['psw']) || !isset($b_set['rootpassword']) || $_REQUEST['psw']!=$b_set['rootpassword'] ){
		exit('sorry,need psw');
	}

	@ignore_user_abort(true);

	$browser->db->query('TRUNCATE TABLE `browser_caches`');
	$browser->db->query('DELETE FROM `browser_caches`');

	$browser->temp_clean('return_down');
	$browser->tempfile_delete();

	deldir($b_set['dftemp'],false);
	echo 'clean end';
}elseif ( $cmd == 'clean_all' ){
	if ( !isset($_REQUEST['psw']) || !isset($b_set['rootpassword']) || $_REQUEST['psw']!=$b_set['rootpassword'] ){
		exit('sorry,need psw');
	}

	@ignore_user_abort(true);
	//deldir(DIR.'temp/',false);
	//@mkdir(DIR.'temp/');
	//@chmod(DIR.'temp/','0777');
	echo 'clean end';

}elseif ( $cmd == 'usersnum' ){
	$var = $browser->db->fetch_first('SELECT count(id) as nums FROM `browser_users`');
	echo $var['nums'];

}elseif ( $cmd == 'getadlist' ){
	@ignore_user_abort(true);
	init_ad_api();
	echo 'Get AD list END.OK';
}else{
	echo 'Welcome';
}

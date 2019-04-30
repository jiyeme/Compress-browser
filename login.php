<?php
/*
 *
 *	浏览器->登陆
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */

$version = '0';
@include 'set_config/version.php';
if ( $version == 0 ){
	header('LOCATION: /install/index.php');
    exit;
}

require 'inc/common.php';
require ROOT_DIR.'inc/class/quicklogin.php';
if ( isset($_GET['type']) && isset($_GET['key']) && isset($_POST['name']) && isset($_POST['pass']) && $_GET['type']=='bangding' ) {
	$token = str_decrypt($_GET['key'],'token');
	$token = explode(';',$token);
	if ( count($token) != 2 ){
		$browser->template_top('绑定失败');
		echo '系统繁忙<br/><a href="/login.php">返回</a>';
		$browser->template_foot();
	}
	$_GET['type'] = $token[0];
	$token = $token[1];

	$result = $browser->_user_name_check($_POST['name'],$_POST['pass']);
	if ( $result !== false ){
		$browser->template_top('快捷登录失败');
		echo '账号或密码格式输入不正确！<br/><a href="login.php?callback=true&amp;type='.$_GET['type'].'&amp;key='.str_encrypt($_GET['type'] .';' .$token,'token').'">返回</a>';
		$browser->template_foot();
	}
	if ( $var = $browser->db->fetch_first('SELECT `id`,`pass` FROM `browser_users` WHERE name="'.$_POST['name'].'"') ){
		if ( $var['pass'] != $_POST['pass'] ){
			$browser->template_top('快捷登录失败');
			echo '密码错误！<br/><a href="login.php?callback=true&amp;type='.$_GET['type'].'&amp;key='.str_encrypt($_GET['type'] .';' .$token,'token').'">返回</a>';
			$browser->template_foot();
		}else{
			//绑定旧账号
			$browser->db->replace('browser_users_quicklogin', array('key'=>$token,'uid'=>$var['id'],'type'=>$_GET['type'],'time'=>time_()) ,'`key`="'.$token.'" AND `type`="'.$_GET['type'].'"');

			set_Cookie('FREE', $browser->template.';'.$_POST['name'].';'.$_POST['pass'],time_()+2592000);
			load_template('login_success',false,'index.php?r='.$browser->rand,false,'utf-8',5);
		}
	}else{
		//注册新的
		$var = array(
				'name'			=>	$_POST['name'],
				'pass'			=>	$_POST['pass'],
				'num_time'		=>	time_(),
				'num_look'		=>	'0',
				'num_size_html'	=>	'0',
				'num_size_pic'	=>	'0',
			);
		$var += $browser->_set_default();
		$browser->db->insert('browser_users',$var);

		$uid = $browser->db->insert_id();
		if ( !$uid ){
			$browser->template_top('绑定失败');
			echo '系统繁忙<br/><a href="/login.php">返回</a>';
			$browser->template_foot();
		}
		$browser->db->insert('browser_users_quicklogin',array('key'=>$token,'uid'=>$uid,'type'=>$_GET['type'],'time'=>time_()));

		set_Cookie('FREE', $browser->template.';'.$_POST['name'].';'.$_POST['pass'],time_()+2592000);

		//header();

		load_template('login_success',false,'index.php?r='.$browser->rand,false,'utf-8',5);
	}



}else if ( isset($_GET['type']) && quickLogin($_GET['type']) ){
	if ( !$b_set['switch']['quicklogin'] ){
		error_show('快捷登陆功能已经被关闭。');
	}
	if ( isset($_GET['callback']) ){
		//登陆成功，开始回调

		if ( isset($_GET['key']) ){
			$token = str_decrypt($_GET['key'],'token');
			$token = explode(';',$token);
			if ( count($token) != 2 ){
				$browser->template_top('绑定失败');
				echo '系统繁忙<br/><a href="/login.php">返回</a>';
				$browser->template_foot();
			}
			$token = $token[1];
		}else{
			$token = quickLogin($_GET['type'])->callback();
		}


		if ( !$token ){
			$browser->template_top('快捷登录失败');
			echo '授权失败。请返回重新登陆！<br/><a href="/login.php">返回</a>';
			$browser->template_foot();
		}else{
			//授权成功,检测是否绑定账号，已绑定则直接登陆，否则提示绑定
			if ( $browser->quickLogin_login($_GET['type'],$token) ){
				load_template('login_success',false,'index.php?r='.$browser->rand,false,'utf-8',5);
			}else{
				$browser->template_top('快捷登录');
				echo '
				快捷登陆成功！<hr/>
				您的快捷登陆账号还没有绑定浏览器账号。<br/>现在登陆或注册浏览器账号将自动与您的快捷账号绑定！<br/>';
				echo template_quicklogin_form($_GET['type'] . ';' . $token);
				echo '(新用户将自动注册并绑定)';
				echo '<hr/><a href="/login.php">返回</a>';
				$browser->template_foot();
			}

		}

	}else{
		quickLogin($_GET['type'])->login();
	}

}else if ( isset($_GET['yes']) ){
    //开始登录
    if ( $browser->user_login($_GET['name'],$_GET['pass']) ){
		load_template('login_success',false,'index.php?r='.$browser->rand,false,'utf-8',5);
		
    }else{
		load_template('login_fail',false);
    }
}else{
	load_template('login_form');
}
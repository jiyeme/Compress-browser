<?php
/*
 *
 *	浏览器->设置 - COOKIE
 *
 *	2012/7/28 星期六 @ jiuwap.cn
 *
 */

require 'inc/common.php';

if ( !$b_set['switch']['cookiemanage'] ){
	error_show('COOKIES管理功能已经被关闭。');
}


$h = isset($_GET['h']) ? $_GET['h'] : '';
if ( $h != ''){
	$au = '&amp;h='.$h;
}else{
	$au = '';
}

$browser->user_login_check();

$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';
$domain = isset($_GET['domain']) ? $_GET['domain'] : '';
$path = isset($_GET['path']) ? $_GET['path'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : '';

if ( !isset($_GET['domain']) && !isset($_GET['path']) && isset($_GET['h']) ){
	$url = $browser->history_get($_GET['h']);
	$url = $url['url'];
	$url_A = parse_url($url);
	if ( !isset($url_A['path']) ){
		$url_A['path'] = '/';
	}
	$domain = $url_A['host'];
	$path = $url_A['path'];
	unset($http);
}

$browser->template_top('COOKIES管理');

echo '返回:';
	echo '<a href="set.php?h='.$h.'">设置</a>.';
if ( $h!='' ){
	echo '<a href="/?h='.$h.'">网页</a>.';
}
echo '<a href="/?m='.$h.'">菜单</a>.<a href="/">首页</a>';
echo hr;

echo '<a href="set_cookie.php?h='.$h.'&amp;domain=&amp;path=">全部域名</a>,';
echo '<a href="set_cookie.php?cmd=new&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">新建COOKIE</a>,';
echo '<a href="set_cookie.php?cmd=clean&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">清空全部</a>';
echo hr;
if ( $domain == '' ){
	echo '全部域名：';
}else{
	echo '['.$domain.']COOKIES：<br/>';
	echo ($path!=''?'目录：'.$path.'<br/>':'');
}
echo hr;

if ($cmd == 'clean' ){
	$list = $browser->cookieGet($domain,$path,true);
	if ( !isset($_GET['yes']) ){
		echo '共有'. count($list) .'条COOKIES，确认要删除吗？<br/>';
		echo '<a href="set_cookie.php?cmd=clean&amp;yes=yes&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">确认</a>,';
	}else{
		$ids = array();
		foreach($list as $c){
			$ids[] = '`id`='.$c['id'];
		}
		if ( $ids ){
			$browser->db->delete('browser_cookies',implode(' OR ',$ids));
			echo '删除成功！';
		}
	}
	echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">返回</a>';



}else if ($cmd == 'new2' ){
		$save_domain = isset($_POST['save_domain']) ? trim($_POST['save_domain']) : '';
		$save_key = isset($_POST['save_key']) ? trim($_POST['save_key']) : '';
		$save_value = isset($_POST['save_value']) ? trim($_POST['save_value']) : '';
		$save_expires = isset($_POST['save_expires']) ? (int)$_POST['save_expires'] : 0;
		$save_path = isset($_POST['save_path']) ? trim($_POST['save_path']) : '';
		echo '<a href="set_cookie.php?cmd=new&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'">返回</a><br/>';
		if ( $save_expires == 0 ){
			$save_expires = 365.5*24*60*60;
		}
		if ( $save_expires < 0 ){
			echo '过期时间不能为负数！';
		}else if ( $save_expires > 365.5*24*60*60 * 10 ){
			echo '过期时间最长不能大于10年！';
		}else if ( strlen($save_value) < 1 ){
			echo '内容不能为空！';
		}else if ( strlen($save_value) > 254 ){
			echo '内容不能多于254个字符！';
		}else if ( strlen($save_key) < 1 ){
			echo '名称不能为空！';
		}else if ( strlen($save_key) > 254 ){
			echo '名称不能多于254个字符！';
		}else if ( strlen($save_domain) < 1 ){
			echo '域名不能为空！';
		}else if ( strlen($save_domain) > 254 ){
			echo '域名不能多于254个字符！';
		}else if ( strlen($save_path) > 254 ){
			echo '路径不能多于254个字符！';
		}else{
			$cover = false;
			$list = $browser->cookieGet($save_domain,$path,true);
			foreach($list as $c){
				if ( $c['key'] == $save_key ){
					$cover = $c['id'];
				}
			}
			$save_expires += time_();
			if ( $cover ){
				$browser->db->update('browser_cookies',
											array(
												'key'=>$browser->db->escape_string($save_key),
												'value'=>$browser->db->escape_string($save_value),
												'path'=>$browser->db->escape_string($save_path),
												'expires'=>$save_expires,
											),'`id`="'.$cover.'"');
			}else{
				$browser->db->insert('browser_cookies',
											array(
												'domain'=>$browser->db->escape_string($save_domain),
												'key'=>$browser->db->escape_string($save_key),
												'value'=>$browser->db->escape_string($save_value),
												'path'=>$browser->db->escape_string($save_path),
												'expires'=>$save_expires,
												'user_id'=>$browser->uid,
											));
			}
			echo '提交成功！<br/>';
			echo '<a href="set_cookie.php?domain='.urlencode($save_domain).'&amp;path='.urlencode($save_path).$au.'">查看列表</a><br/>';

		}
}else if ($cmd == 'new' ){
	//新建
	echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">返回</a><br/>';
	if ( $browser->template == 0 ){
		echo '<form action="set_cookie.php?cmd=new2&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'" method="post">';
		echo '域名：<input type="text" name="save_domain" value="'.$domain.'" /><br/>';
		echo '名称：<input type="text" name="save_key" value="" /><br/>';
		echo '内容：<input type="text" name="save_value" value="" /><br/>';
		echo '过期(分钟)：<input type="text" name="save_expires" value="0" /><br/>';
		echo '路径：<input type="text" name="save_path" value="'.$path.'" /><br/>';
		echo '<input type="submit" value="提交" /><br/>';
		echo '</form>';
	}else{
		echo '域名：<input type="text" name="save_domain'.$browser->rand.'" value="'.$domain.'" /><br/>';
		echo '名称：<input type="text" name="save_key'.$browser->rand.'" value="" /><br/>';
		echo '内容：<input type="text" name="save_value'.$browser->rand.'" value="" /><br/>';
		echo '过期(分钟)：<input type="text" name="save_expires'.$browser->rand.'" value="0" /><br/>';
		echo '路径：<input type="text" name="save_path'.$browser->rand.'" value="'.$path.'" /><br/>';
		echo '<anchor>
		<go href="set_cookie.php?cmd=new2&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'" method="post">
		<postfield name="save_domain" value="$(save_domain'.$browser->rand.')" />
		<postfield name="save_key" value="$(save_key'.$browser->rand.')" />
		<postfield name="save_value" value="$(save_value'.$browser->rand.')" />
		<postfield name="save_expires" value="$(save_expires'.$browser->rand.')" />
		<postfield name="save_path" value="$(save_path'.$browser->rand.')" />
		</go>提交</anchor>';
	}
	echo '提示：过期时间为0，则为1年过期时间。路径可为空。如果域名和键值存在重复的，则会覆盖原数据！<br/>';
}else if ($id == '' ){

	$list = $browser->cookieGet($domain,$path,true);


	if ( !$list ){
		echo '无';

	}else{
		if ( $domain == '' ){
			$domains = array();
			foreach($list as $c){
				if (!in_array($c['domain'],$domains)){
					$domains[] = $c['domain'];
				}
			}
			foreach($domains as $d){
				echo '<a href="set_cookie.php?domain='.urlencode($d).'&amp;path='.urlencode($path).$au.'">'.$d.'</a><br/>';
			}
		}else{
			foreach($list as $c){
				echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($c['id']).$au.'">'.$c['key'].'</a><br/>';
			}
		}
	}
}else{
	$var = $browser->db->fetch_first('SELECT * FROM browser_cookies WHERE user_id='.$browser->uid.' AND `id`="'.$id.'"');

	if ($var ){
		if ( $cmd== 'delete' ){
			$browser->db->delete('browser_cookies','user_id='.$browser->uid.' AND `id`="'.$id.'"');
			echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">返回</a><br/>';
			echo '删除[' .$var['key'].']成功！';
		}else if ( $cmd== 'edit2' ) {
			$save_value = isset($_POST['save_value']) ? trim($_POST['save_value']) : '';
			$save_expires = isset($_POST['save_expires']) ? (int)$_POST['save_expires'] : 0;
			echo '<a href="set_cookie.php?cmd=edit&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'">返回</a><br/>';
			if ( $save_expires == 0 ){
				$save_expires = 365.5*24*60*60;
			}
			if ( $save_expires < 0 ){
				echo '过期时间不能为负数！';
			}else if ( $save_expires > 365.5*24*60*60 * 10 ){
				echo '过期时间最长不能大于10年！';
			}else if ( strlen($save_value) < 1 ){
				echo '内容不能为空，如需为空请删除该COOKIE！';
			}else if ( strlen($save_value) > 254 ){
				echo '内容不能多于254个字符！';
			}else{
				$save_expires += time_();
				$browser->db->update('browser_cookies',array('value'=>$browser->db->escape_string($save_value),'expires'=>$save_expires),'`id`="'.$id.'"');
				echo '修改成功！';
			}

		}else if ( $cmd== 'edit' ) {
			echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'">返回</a><br/>';
			if ( $browser->template == 0 ){
				echo '<form action="set_cookie.php?cmd=edit2&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'" method="post">';
				echo '名称：'.$var['key'].'<br/>';
				echo '内容：<input type="text" name="save_value" value="'.$var['value'].'" /><br/>';
				echo '过期(分钟)：<input type="text" name="save_expires" value="'.(int)(($var['expires']-time_())/60).'" /><br/>';
				echo '提示：过期时间为0，则为1年过期时间。<br/>';
				echo '<input type="submit" value="保存" /><br/>';
				echo '</form>';
			}else{
				echo '名称：'.$var['key'].'<br/>';
				echo '内容：<input type="text" name="save_value'.$browser->rand.'" value="'.$var['value'].'" /><br/>';
				echo '过期(分钟)：<input type="text" name="save_expires'.$browser->rand.'" value="'.(int)(($var['expires']-time_())/60).'" /><br/>';
				echo '提示：过期时间为0，则为1年过期时间。<br/>';
				echo '<anchor>
				<go href="set_cookie.php?cmd=edit2&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'" method="post">
				<postfield name="save_value" value="$(save_value'.$browser->rand.')" />
				<postfield name="save_expires" value="$(save_expires'.$browser->rand.')" />
				</go>保存</anchor>';
			}
		}else{
			echo '<a href="set_cookie.php?domain='.urlencode($domain).'&amp;path='.urlencode($path).$au.'">返回</a>,';
			echo '<a href="set_cookie.php?cmd=edit&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'">编辑</a>,';
			echo '<a href="set_cookie.php?cmd=delete&amp;domain='.urlencode($domain).'&amp;path='.urlencode($path).'&amp;id='.($id).$au.'">删除</a><br/>';
			echo '名称：' .$var['key'].'<br/>';
			echo '内容：' .$var['value'].'<br/>';
			echo '路径：' .$var['path'].'<br/>';
			echo '过期：' .date('Y-n-j H:i:s',$var['expires']).'<br/>';
		}
	}
}

$browser->template_foot();
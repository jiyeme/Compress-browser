<?php
/*
 *
 *	浏览器->设置 - COOKIE
 *
 *	2012/7/28 星期六 @ jiuwap.cn
 *
 */

require 'inc/common.php';

if ( !$b_set['switch']['dns'] ){
	error_show('DNS功能已经被关闭。');
}


$h = isset($_GET['h']) ? $_GET['h'] : '';
if ( $h != ''){
	$au = '&amp;h='.$h;
}else{
	$au = '';
}

$browser->user_login_check();

$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';

$browser->template_top('DNS管理');

echo '返回:';
echo '<a href="set.php?h='.$h.'">设置</a>.';
if ( $h!='' ){
	echo '<a href="/?h='.$h.'">网页</a>.';
}
echo '<a href="/?m='.$h.'">菜单</a>.<a href="/">首页</a>';
echo hr;

echo '<a href="set_dns.php?cmd=new&amp;h='.$h.'">添加DNS</a>,<a href="set_dns.php?h='.$h.'">全部DNS</a>,<a href="set_dns.php?cmd=ping&amp;h='.$h.'">查看DNS</a>';

echo hr;

if ( $cmd == 'new2' ){
	$save_domain = isset($_POST['save_domain']) ? trim($_POST['save_domain']) : '';
	$save_target = isset($_POST['save_target']) ? trim($_POST['save_target']) : '';

	if ( strlen($save_domain) < 2 || strlen($save_domain) > 254 || preg_match("/[',:;?~`!^<>]|\]|\[|\/|\\\|\"|\|/", $save_domain) ){
		echo '域名不合法！';
	}else if ( httplib::is_ip($save_domain) ) {
		echo '域名不得为IP地址！';
	}else if ( strlen($save_target) < 2 || strlen($save_target) > 254 || preg_match("/[',:;?~`!^<>]|\]|\[|\/|\\\|\"|\|/", $save_target)){
		echo '目标不合法！';
	}else if ( $browser->db->fetch_first('SELECT id FROM `browser_dns` WHERE `uid`='.$browser->uid . ' AND `domain` = "'.$save_domain.'"' )  ) {
		echo '该域名已经存在！不得重复添加！';
	}else{
		$browser->db->insert('browser_dns',
									array(
										'domain'=>$browser->db->escape_string($save_domain),
										'target'=>$browser->db->escape_string($save_target),
										'uid'=>$browser->uid,
									));
		echo '添加成功！';
	}
}else if ( $cmd == 'new' ){
	if ( $browser->template == 0 ){
		echo '<form action="set_dns.php?cmd=new2'.$au.'" method="post">';
		echo '域名：<input type="text" name="save_domain" value="" /><br/>';
		echo '目标：<input type="text" name="save_target" value="" /><br/>';
		echo '<input type="submit" value="提交" /><br/>';
		echo '</form>';
	}else{
		echo '域名：<input type="text" name="save_domain'.$browser->rand.'" value="" /><br/>';
		echo '目标：<input type="text" name="save_target'.$browser->rand.'" value="" /><br/>';
		echo '<anchor>
		<go href="set_dns.php?cmd=new2'.$au.'" method="post">
		<postfield name="save_domain" value="$(save_domain'.$browser->rand.')" />
		<postfield name="save_target" value="$(save_target'.$browser->rand.')" />
		</go>提交</anchor>';
	}
	echo hr;
	echo '提示：目标可以不为IP，允许为域名。（别名绑定）<br/>该域名DNS作用于浏览器全局，HTTP代理也同样有效。';
	echo hr;
	echo '下面是一些简单的例子：<br/>';
	echo '1.将cctv.com域名解析到IP:192.168.0.1<br/>域名：cctv.com<br/>目标：192.168.0.1'.hr;
	echo '2.将cctv.com域名解析到别名cctv.cn<br/>域名：cctv.com<br/>目标：cctv.cn'.hr;
	echo '3.将yy.cctv.com、xx.bbs.cctv.com等多级泛域名解析到IP:192.168.0.1<br/>域名：*.cctv.com<br/>目标：192.168.0.1'.hr;
	echo '4.将a.cctv.com、b.cctv.com等同级泛域名解析到IP:192.168.0.1<br/>域名：[*].cctv.com<br/>目标：192.168.0.1';
}else if ( $cmd == 'edit' ){
	$id = isset($_GET['id']) ? (int)($_GET['id']) : 0;
	$dns = $browser->db->fetch_first('SELECT * FROM `browser_dns` WHERE `id` = '.$id .' AND `uid`='.$browser->uid);
	if ( !$dns ){
		echo '您要修改的DNS不存在。';
	}else if ( isset($_GET['yes']) ){
		$save_domain = isset($_POST['save_domain']) ? trim($_POST['save_domain']) : '';
		$save_target = isset($_POST['save_target']) ? trim($_POST['save_target']) : '';

		if ( strlen($save_domain) < 2 || strlen($save_domain) > 254 || preg_match("/[',:;?~`!^<>]|\]|\[|\/|\\\|\"|\|/", $save_domain) ){
			echo '域名不合法！';
		}else if ( httplib::is_ip($save_domain) ) {
			echo '域名不得为IP地址！';
		}else if ( strlen($save_target) < 2 || strlen($save_target) > 254 || preg_match("/[',:;?~`!^<>]|\]|\[|\/|\\\|\"|\|/", $save_target)){
			echo '目标不合法！';
		}else{
			$browser->db->update('browser_dns',
										array(
											'domain'=>$browser->db->escape_string($save_domain),
											'target'=>$browser->db->escape_string($save_target),
										),'id='.$id);
			echo '保存成功！';
		}
	}else if ( $browser->template == 0 ){
		echo '<form action="set_dns.php?cmd=edit&amp;id='.$id.'&amp;yes=yes'.$au.'" method="post">';
		echo '域名：<input type="text" name="save_domain" value="'.$dns['domain'].'" /><br/>';
		echo '目标：<input type="text" name="save_target" value="'.$dns['target'].'" /><br/>';
		echo '<input type="submit" value="保存" /><br/>';
		echo '</form>';
	}else{
		echo '域名：<input type="text" name="save_domain'.$browser->rand.'" value="'.$dns['domain'].'" /><br/>';
		echo '目标：<input type="text" name="save_target'.$browser->rand.'" value="'.$dns['target'].'" /><br/>';
		echo '<anchor>
		<go href="set_dns.php?cmd=edit&amp;id='.$id.'&amp;yes=yes'.$au.'" method="post">
		<postfield name="save_domain" value="$(save_domain'.$browser->rand.')" />
		<postfield name="save_target" value="$(save_target'.$browser->rand.')" />
		</go>保存</anchor>';
	}
}else if ( $cmd == 'ping' ){
	$save_domain = isset($_POST['save_domain']) ? $_POST['save_domain'] : '';
	if ( $browser->template == 0 ){
		echo '<form action="set_dns.php?cmd=ping'.$au.'" method="post">';
		echo '域名：<input type="text" name="save_domain" value="'.$save_domain.'" />';
		echo '<input type="submit" value="提交" /><br/>';
		echo '</form>';
	}else{
		echo '域名：<input type="text" name="save_domain'.$browser->rand.'" value="'.$save_domain.'" />';
		echo '<anchor>
		<go href="set_dns.php?cmd=ping'.$au.'" method="post">
		<postfield name="save_domain" value="$(save_domain'.$browser->rand.')" />
		</go>查看</anchor>';
	}
	echo hr;
	echo '此功能可获取域名绑定的IP地址，能解析到IP地址不代表此IP地址是可访问的。';
	if ( $save_domain ){
		echo hr;
		echo '域名：' . $save_domain.'<br/>';
		if ( strlen($save_domain) < 2 || strlen($save_domain) > 254 || preg_match("/[',:;?~`!^<>]|\]|\[|\/|\\\|\"|\|/", $save_domain) ){
			echo '地址：域名不合法';
		}else{
			$dns = httplib::getIpByDomian($save_domain,$browser->dns_getAll());
			if ( $dns ){
				echo '地址：' . $dns;
			}else{
				echo '地址：解析失败';
			}
		}


	}
}else if ( $cmd == 'delete' ){
	$id = isset($_GET['id']) ? (int)($_GET['id']) : false;
	$id && $browser->db->delete('browser_dns','id='.$id.' AND uid='.$browser->uid);
	echo '删除成功！';
}else{
	$mydns = $browser->dns_getMy();
	if ( !$mydns ){
		echo '无自定义DNS。';
	}else{
		foreach($mydns as $i=>$dns){
			echo ($i+1) .'. ' . htmlspecialchars($dns['domain']).'=&gt;'.htmlspecialchars($dns['target']);
			echo '(<a href="set_dns.php?cmd=edit&amp;id='.$dns['id'].'&amp;h='.$h.'">编辑</a>';
			echo ',<a href="set_dns.php?cmd=delete&amp;id='.$dns['id'].'&amp;h='.$h.'">删除</a>)<br/>';
		}
	}
}

$browser->template_foot();
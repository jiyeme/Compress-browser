<?php
/*
 *
 *
 *
 *	2011-4-3 @ jiuwap.cn
 *
 */

!defined('m') && header('location: /?r='.rand(0,999));
require_once ROOT_DIR.'tools/disk/inc.php';

$_GET['q']= isset($_GET['q']) ? trim($_GET['q']) : null;
!isset($disk) && init_disk();

$arr = $browser->cache_get('pic',$_GET['q']);
if ( !isset($arr['url']) || empty($arr['url']) ){
	error_show('文件信息丢失(1),请重新下载。('.$_GET['q'].')');
}

if ( false !== ($content = $browser->temp_read('return_down',sha1($arr['url'])) ) ){
	if ( !$content = @unserialize($content) ){
		error_show('文件信息损坏(1),请重新下载。('.$_GET['q'].')');
	}
}else{
	error_show('文件信息丢失(2),请重新下载。('.$_GET['q'].')');
}
if ( $content['size'] > $b_set['thttp'] ){
	error_show('当前系统不允许存储大于'.bitsize($b_set['thttp']).'的文件。('.$_GET['q'].')');
}

$arr = $browser->cache_get('pic',$_GET['q']);
if ( !isset($arr['url']) || empty($arr['url']) ){
	error_show('文件信息丢失(1),请重新下载。('.$_GET['q'].')');
}


if ( false !== ($content = $browser->temp_read('return_down',sha1($arr['url'])) ) ){
	if ( !$content = @unserialize($content) ){
		error_show('文件信息损坏(1),请重新下载。('.$_GET['q'].')');
	}
}else{
	error_show('文件信息丢失(2),请重新下载。('.$_GET['q'].')');
}
if ( $content['size'] > $b_set['thttp'] ){
	error_show('当前系统不允许中转下载大于'.bitsize($b_set['thttp']).'的文件。('.$_GET['q'].')');
}elseif ( $disk['space_all']-$disk['space_use']-$content['size'] <= 0){
	error_show('网盘空间已满');
}

if ( isset($_GET['yes'])){
	$up_id = isset($_POST['oid']) ? (float)$_POST['oid'] : 0;

	$the_title = urltree($up_id);
	$basename = $content['name'];
	$dir = $browser->db->fetch_first('SELECT id FROM `disk_dir` WHERE id='.$up_id.' AND uid='.$disk['id']);
	if ( $up_id!=0 && !$dir ){
		error_show('网盘目录不存在');
	}
	$dir = $browser->db->fetch_first('SELECT id FROM `disk_dir` WHERE oid='.$up_id.' AND title="'.$basename.'" AND uid='.$disk['id']);
	$file = $browser->db->fetch_first('SELECT id FROM `disk_file` WHERE oid='.$up_id.' AND title="'.$basename.'" AND uid='.$disk['id']);
	if ( $dir || $file || $basename==''){
		$basename = rand(1,999999).'_'.$basename;
	}
	$mime = get_short_file_mime($basename);
	$the_save_file = time_().'_'.rand(10000,99999);
	if ( $mime != ''){
		$the_save_file .= '_'.$mime;
	}
	if ( $browser->tempfile_exists(sha1($arr['url'])) ){
		$filecontent = $browser->tempfile_read(sha1($arr['url']));
		@cloud_storage::write('disk_' . $the_save_file,$filecontent);
	}else{
		$http = new httplib();
		$http->set_dns($browser->dns_getAll());
		//$http->set_referer($arr['referer']);
		/*if( !empty($browser->ipagent) ){
			$ip = explode(':',$http->ipagent);
	        $http->proxy(trim($ip[0]),trim($ip[1]));
			unset($ip);
		}*/
		$http->set_timeout(30);
		$http->set_location(3);
		$http->open(fix_r_n_t($arr['url']));

		foreach($content['cookie'] as $cookie_key=>$cookie_value){
			$http->put_cookie($cookie_key,$cookie_value);
		}
		unset($cookie_key,$cookie_value);

		foreach($content['header'] as $header_key=>$header_value){
			$http->put_header($header_key,$header_value);
		}
		unset($header_key,$header_value);

		$http->send();
		$header = $http->get_headers();
		@set_time_limit(7200);

		if ( !isset($header['STATUS']) || $header['STATUS'] != '200' ){
			error_show('连接目标文件失败,请重新下载。');
		}
		if ( !isset($header['CONTENT-LENGTH']) || $header['CONTENT-LENGTH'] - $content['size'] >200 || $content['size'] - $header['CONTENT-LENGTH'] >200 ){
			error_show('文件信息损坏(2),请重新下载。');
		}
		if ( isset($header['CONTENT-LENGTH']) ){
			$content['size'] = $header['CONTENT-LENGTH'];
		}
		@cloud_storage::write('disk_' . $the_save_file,$http->get_body());
	}

	$arr = array(
			'oid'			=>	$up_id,
			'uid'			=>	$disk['id'],
			'mime'			=>	$mime,
			'title'			=>	$basename,
			'size'			=>	$content['size'],
			'file'			=>	$the_save_file,
			);
	$id = $browser->db->insert('disk_file',$arr,true);

    $browser->template_top('上传文件');
	echo '<a href="disk.php?id=0'.$h.'">root</a>:\\'.$the_title.hr;
	if ( $id ){
		//var_dump($arr);exit;
		disk_space_update(+$arr['size']);
		echo '上传文件成功！<br/>查看<a href="disk.php?cmd=info&amp;id='.$id.'&amp;uid='.$up_id.$h.'">['.$basename.']</a><br/>';
		$browser->template_foot();
	}else{
		$error = '未知错误';
	}

}else{
    $browser->template_top('上传文件');
	echo $b_set['disktitle'].'-文件上传<br/>';
	echo '文件：'.$content['name'].'<br />';
	echo '大小：'.bitsize($content['size']).'<br />';
	echo '类型：'.$content['type'].'<br />';
	echo '地址：'.htmlspecialchars($arr['url']).'<br />';
	if ( $browser->template == 1 ){
		echo '
		目录：<select name="oid'.$browser->rand.'">
			<option value="0" selected="selected">根目录(root)</option>
			'.tree_select().'
		</select><br />
		<anchor>
		<go href="index.php?yes=yes&amp;q='.$_GET['q'].'" method="post">
		<postfield name="oid" value="$(oid'.$browser->rand.')" />
		</go>上传</anchor><br/>
		';
	}else{
		echo '<form action="index.php?yes=yes&amp;q='.$_GET['q'].'" method="post">
		目录：<select name="oid">
			<option value="0" selected="selected">根目录(root)</option>
			'.tree_select().'
		</select><br />
		<input type="submit" value="上传"/><br />
		</form>';
	}
}
$browser->template_foot();

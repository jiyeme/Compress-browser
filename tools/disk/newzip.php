<?php
!defined('m') && header('location: /?r='.rand(0,999));

$a_mime = array('zip','jar','gz','tar.gz','mrp');

$up_title = '根目录';
$up_id = isset($_GET['uid']) ? (float)$_GET['uid'] : 0;
$title = $_POST['title'] = isset($_POST['title']) ? ubb_copy(strtolower(trim(fix_r_n_t($_POST['title'])))) : '';
$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;
$browser->template_top('新建压缩包文件');
$the_title = '';
if ( $up_id<>0 ){
	$dir = $browser->db->fetch_first('SELECT title,oid FROM `disk_dir` WHERE uid='.$disk['id'].' AND id='.$up_id);
	if ( !$dir ){
		echo '错误：文件夹不存在！';
		echo '<a href="disk.php?id=0'.$h.'">返回根目录</a>';
		$browser->template_foot();
	}else{
		$up_title = $dir['title'];
		$the_title = urltree($dir['oid']).'<a href="disk.php?id='.$up_id.$h.'">'.$dir['title'].'</a>\\';
	}
}

echo '<a href="disk.php?id=0'.$h.'">root</a>:\\'.$the_title.'<br/>';

if ( isset($_GET['yes']) ){
	if ( isset($a_mime[$type])) {
		$mime = str_replace('.','_',$a_mime[$type]);
		$title .= '.'.$a_mime[$type];
	}
	if ( $title == '' ){
		echo '错误：文件名不得为空！';
	}elseif ( strlen($title) > 50 ){
		echo '错误：文件名不得多于50个字符！';
	}elseif( checktruestr($title) == false ){
		echo '错误：文件名不得使用标点符号！';
	}elseif( !isset($a_mime[$type]) ){
		echo '错误：没有正确选择压缩包类型！';
	}else{
		$dir = $browser->db->fetch_first('SELECT id FROM `disk_dir` WHERE oid='.$up_id.' AND title="'.$title.'" AND uid='.$disk['id']);
		$file = $browser->db->fetch_first('SELECT id FROM `disk_file` WHERE oid='.$up_id.' AND title="'.$title.'" AND uid='.$disk['id']);
		if ( $dir || $file ){
			echo '错误：文件(夹)已经存在！';
		}else{
			$the_save_file = time_().'_'.rand(10000,99999).'_'.$mime;
			if ( $mime == 'gz' || $mime == 'tar.gz' ){
				$fsize = @(float)strlen(gzencode(''));
				cloud_storage::write('disk_' . $the_save_file,gzencode(''));

			}elseif( $mime == 'mrp' ){
				$tmpdir = cloud_storage::localname('tmp_' . rand(0,999) . time() . '/') ;
				$tmpfile = cloud_storage::localname('temp_' . $the_save_file);
				require_once ROOT_DIR.'inc/class/mrp.lib.php';
				@mkdir($tmpdir);
				@file_put_contents($tmpdir . 'created_by_jiuwap_cn','created_by_jiuwap_cn');
				mrp::pack($tmpfile,array($tmpdir .'created_by_jiuwap_cn'),3,false,$tmpdir);
				deldir($tmpdir);

				$fsize = @(float)filesize($tmpfile);
				cloud_storage::upload_temp($tmpfile,'disk_' . $the_save_file);

			}elseif( $mime == 'jar' || $mime == 'zip' ){
				$tmpzip = cloud_storage::localname('temp_' . $the_save_file);
				$tmpfile = cloud_storage::localname('created_by_jiuwap_cn');
				require_once ROOT_DIR.'inc/class/pclzip.lib.php';
				@file_put_contents($tmpfile,'created_by_jiuwap_cn');
				$zip = new PclZip($tmpzip);
				$zip->create($tmpfile);
				@unlink($tmpfile);

				$fsize = (float)filesize($tmpzip);
				cloud_storage::upload_temp($tmpzip,'disk_' . $the_save_file);
			}else{
				$fsize = 0;
			}

			$arr = array(
				'oid'			=>	$up_id,
				'uid'			=>	$disk['id'],
				'mime'			=>	$mime,
				'title'			=>	$title,
				'size'			=>	$fsize,
				'file'			=>	$the_save_file,
			);
			if ( $disk['space_all']-$disk['space_use']-$arr['size'] <= 0){
				echo '错误：空间已满！';
			}elseif ( $arr['size'] > $b_set['dlocal']) {
				echo '错误：文件不得大于'.bitsize($b_set['dlocal']).'！';
			}else{
				$id = $browser->db->insert('disk_file',$arr,true);
				if ( $id ){
					disk_space_update(+$arr['size']);
					echo '新建压缩档案['.$mime.']成功！<br/>查看<a href="disk.php?cmd=info&amp;id='.$id.'&amp;uid='.$up_id.$h.'">['.$title.']</a><br/>';
					$browser->template_foot();
				}else{
					echo '错误：失败，未知错误。';
				}
			}
		}
	}

	echo hr;
}else{
	echo '新建压缩包：<br/>';
}

if ( $_POST['title'] == ''){
	$_POST['title'] = 'NewPackedFile';
}

if ( $browser->template == 1 ){
	echo '
	名称：<input name="title'.$browser->rand.'" type="text" value="'.$_POST['title'].'"/><br/>
	类型：<select name="type'.$browser->rand.'">';
	foreach($a_mime as $k=>$n){
		echo '<option value="'.$k.'">.'.$n.'</option>';
	}
	echo '</select><br />
	<anchor>
	<go href="disk.php?cmd=newzip&amp;yes=yes&amp;uid='.$up_id.$h.'" method="post">
	<postfield name="title" value="$(title'.$browser->rand.')" />
	<postfield name="type" value="$(type'.$browser->rand.')" />
	</go>新建</anchor><br/>
	';
}else{
	echo '<form action="disk.php?cmd=newzip&amp;yes=yes&amp;uid='.$up_id.$h.'" method="post">
	名称：<input name="title" type="text" value="'.$_POST['title'].'"/><br/>
	类型：<select name="type">';
	foreach($a_mime as $k=>$n){
		echo '<option value="'.$k.'">.'.$n.'</option>';
	}
	echo '</select><br />
	<input type="submit" value="新建"/><br />
	</form>';
}
echo '提示:名称无需填写扩展名只需选择类型。';
$browser->template_foot();

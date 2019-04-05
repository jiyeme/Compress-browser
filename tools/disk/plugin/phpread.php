<?php
!defined('m') && header('location: /?r='.rand(0,999));
echo '代码高亮'.hr;
if ( mime_istxt($dir['mime']) ){
	require ROOT_DIR.'inc/class/hightlight/class.stx.php';
	require ROOT_DIR.'inc/class/runtime.lib.php';
	$runtime = new runtime();
	$runtime->start();
	$content = @cloud_storage::read('disk_' . $dir['file']);
	echo highlight_stx($content,$dir['mime'],true,false);
	$runtime->stop();
	echo hr.'解析耗时: '.$runtime->spent().'ms<br/><a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';
}else{
	echo '不支持此文件'.hr;
	echo '(高亮技术由Editplus提供)<br/><a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';
}

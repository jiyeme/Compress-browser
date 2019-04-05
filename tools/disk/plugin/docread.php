<?php
!defined('m') && header('location: /?r='.rand(0,999));
require ROOT_DIR.'inc/class/msdoc.lib.php';
echo '在线阅读doc文档[文字]'.hr;
if ( $dir['mime'] == 'doc'){
	$tmpfile = cloud_storage::localname('tmp_' . rand(0,999) . time());
	$tmpfile = cloud_storage::download_tmp('disk_' . $dir['file'],$tmpfile);
	echo new doc_read($tmpfile);
}else{
	echo '你要阅读的文档非DOC文档，系统无法读取。';
}
echo hr.'<a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';

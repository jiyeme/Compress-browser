<?php
!defined('m') && header('location: /?r='.rand(0,999));
	if ( mime_istxt($dir['mime']) ){
		$content = @cloud_storage::read('disk_' . $dir['file']);
		$content = getUTFString($content,$code);
		$content = trim(nl2br(htmlspecialchars($content)));
		echo '在线查看['.$code.']'.hr;
        $page = isset($_GET['ipage']) ? (int)$_GET['ipage']:0;
        if ( strlen($content) <= 10*1024 || $page == '-1'){
            echo $content;
        }else{
            if ( $page == 0 ){
                echo '提示：内容长度大于 10 KB<br/>';
            	echo '<br/><a href="disk.php?cmd=info&amp;do=txtread&amp;ipage=1&amp;id='.$id.$h.'">分页阅读</a>';
            	echo '<br/><a href="disk.php?cmd=info&amp;do=txtread&amp;ipage=-1&amp;id='.$id.$h.'">全文阅读</a>';
            }else{
                require_once ROOT_DIR. 'inc/class/txt.cutpage.lib.php';
                $CP = new cutpage($content,10*1024);
                echo  $CP->get_str() . hr . $CP->get_page();
            }
        }
	}else{
		echo '在线查看.'.hr.'不支持此文件';
	}
	echo hr.'<a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';

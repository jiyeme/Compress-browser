<?php
/*
 *
 *	浏览器->历史
 *
 *	2011-1-14 @ jiuwap.cn
 *
 */


!defined('m') && header('location: /?r='.rand(0,999));
require ROOT_DIR.'parse/function.php';

$the_history_key = $url['key'];
$mime = $url['mime'];
$code = $url['code'];
$html = $url['content'];
$url = $url['url'];
$html_size_old = 0;


//最终网页大小
$html_size_new = strlen($html);

require ROOT_DIR.'parse/parse_foot.php';


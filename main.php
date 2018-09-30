<?php
!defined('m') && header('location: /?r='.rand(0,999));
$browser->template_top($b_set['webtitle']);
include DIR.'parse/function.php';

$size_date = time()-$browser->num_time;
if ( $size_date >= 60 ){
	$size_date = $size_date / 60;
	if ( $size_date >= 60 ){
		$size_date = $size_date / 60;
		if ( $size_date >= 60 ){
			$size_date = (int)($size_date/24) .'天';
		}else{
			$size_date = (int)$size_date . '小时';
		}
	}else{
		$size_date = (int)$size_date . '分钟';
	}
}else{
	$size_date = (int)$size_date . '秒';
}


echo $b_set['webtitle'].'(ver.'.$version.')';
echo hr;
if ( $browser->template == 0 ){
	echo '<form action="index.php" method="get">
	网址：<input type="text" name="url" value="" />
	<input type="submit" value="进入"/><br /></form>';
}else{
	echo '网址：<input name="url'.$browser->rand.'" type="text" value=""/>
	<anchor>
	<go href="index.php" method="get">
	<postfield name="url" value="$(url'.$browser->rand.')" />
	</go>进入</anchor>';
}
echo hr;
echo '<a href="book.php">书签</a>|<a href="disk.php">网盘</a>|<a href="copy.php">剪切板</a><br/>
<a href="set.php">设置</a>|<a href="clear.php">清理</a>|<a href="help.php">帮助</a><br/>
<a href="http://e.jiuwap.cn/tools/suggestion.php">反馈</a>|<a href="self/new.php">日志</a>|<a href="http://e.jiuwap.cn/self/ccode.php">源码</a>';
echo hr;

$site = $browser->site_lists();
if ( $site == array() ){
	echo '无导航站';
}else{
	foreach($site as $id=>$val){
 if(!isset($val['go']))
		echo '<a href="/?s=',$id,'">',$val['title'],'</a>.';
 elseif($val['go']=='url')
  echo '<a href="',$val['url'],'">',$val['title'],'</a>.';
 else
  echo $val['title'];
	}
}
echo hr;
echo '历史浏览：<br/>';
$history = $browser->history_get();
if ( $history == array() ){
	echo '无<br/>';
}else{
	$echo = '';$i = count($history) - 5;
	foreach($history as $id=>$val){
		if (--$i < 0){
			$echo = '<a href="/?h='.$id.'">'.urldecode($val['title']).'</a><br/>'.$echo ;
		}
	}
	echo $echo;
}
echo hr;
echo '累计节流量(<a href="help.php">?</a>)：<br/>
图片：'.bitsize($browser->num_size_pic).'<br/>
网页：'.bitsize($browser->num_size_html).'<br/>
浏览：'.$browser->num_look.'次<br/>
时间：'.$size_date;

echo hr;

echo $browser->uname.',<a href="logout.php?r='.$browser->rand.'">退出</a>';

echo hr;

echo 'Powered By <a href="http://jiuwap.cn/">Jiuwap.cn</a><br/>';
echo $b_set['icp'];
$browser->template_foot();
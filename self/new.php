<?php
require 'inc.php';
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
top_wap('我真的在努力..');

echo '我们真的在努力，更新永不止步！<br/>';

$str = '
2012/7/28 星期六
1.增加COOKIES管理，可以像WEB版OPERA浏览器那样轻松管理COOKIES了，同时增加了操作COOKIE的底部定制代码[cookie];
---------
2012/7/26 星期四
1.集成QQ、新浪微博快捷登陆;
---------
2012/7/22 星期日
1.修正COOKIE漏洞;
2.重构HTTP协议类;
3.大规模修正BUG;
---------
2012/7/1 星期日
1.玖玩浏览器上线;
';

$str = explode('---------',$str);
$num = count($str);
$apage = 5;
$pa= ceil($num/$apage);


if ( $pa < 1){
    $pa = 1;
}

if ( $p<=0 || $p > $pa ){
    $p = 1;
}

$max = $p * $apage;
if ( $max > $num){
    $max = $num;
}

//echo '<br/><a href="http://mm.jiuwap.cn/self/new.php">查看浏览器官方更新日志</a><br/>';

for($i = $max - $apage; $i<$max; $i++){
	if ( isset($str[$i]) ){
		echo str_replace("\n",'<br/>',$str[$i]);
		echo hr;
	}
}

if ( $pa > 1){
    if ( $p < $pa){
        echo '<a href="new.php?p='.($p+1).'">下页</a>';
    }
    if ( $p > 1){
        echo '<a href="new.php?p='.($p-1).'">上页</a>';
    }

}
echo '<br/>'.$apage.'条/页,共'.$num.'条,共'.$pa.'页,第'.$p.'页';
echo hr.'上述更新日志仅供参考,可能有漏掉的。'.hr;
echo '<a href="/">返回</a>';

foot_wap();
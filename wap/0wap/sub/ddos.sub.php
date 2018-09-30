<?php
#exit(' Please Stop CC ! '); #停止工作只要去掉exit前的#号
#return; #关闭该功能只要去掉return;前的#号
#阻止过快访问，防止被IP攻击
$limit=array(
 10, #n秒内
 30 #最多访问n次
 );
$teshu=array(
#设置特定IP n秒最多能访问的次数
 '127.0.0.1'=>50,
 '117.135.129.59'=>50,
 '122.0.68.248'=>50,
 '173.252.244.14'=>50,
 '218.76.89.70'=>50,
 '61.155.206.186'=>50,
 );
if(isset($teshu[$_SERVER['REMOTE_ADDR']]))
 $limit[1]=$teshu[$_SERVER['REMOTE_ADDR']];
unset($teshu);
$tm=unpack('v',pack('v',$_SERVER['REQUEST_TIME']));
$tm=$tm[1];
$ip=explode('.',$_SERVER['REMOTE_ADDR']);
$ip=($ip[1]*256+$ip[2])*4;
$fp=DB_DIR.'/ddos.dat';
if(!is_file($fp))
 file_put_contents($fp,str_repeat(chr(0),256*256*4));
$fp=fopen($fp,'r+');
fseek($fp,$ip);
$jc=unpack('v2',fread($fp,4));
$tm2=$jc[1];
$jc=$jc[2];
if(($tm2=$tm-$tm2)<$limit[0] && $tm2>=0)
{
if($jc>$limit[1] && $jc<$limit[1]*2)
 {
header('Content-Type: text/html; charset=UTF-8');
?>
<html><head><title>很抱歉，您超速了- -</title></head><body>虎绿林低速网络限速<?php echo $limit[1]; ?>次/<?php echo $limit[0]; ?>秒，您超速了。<br />您的罚单上面写着：作为惩罚，吊销您的虎绿林通行证<?php echo $limit[0]-$tm2; ?>秒钟，在这段时间内您将不能访问虎绿林。<br />您的IP地址为<?php echo $_SERVER['REMOTE_ADDR']; ?>，违章记录已存档。</body></html>
<?php
/*$tm2=fopen(DB_DIR.'/ddos.log.txt','a+');
fwrite($tm2,'<超速> '.$_SERVER['REMOTE_ADDR'].' <'.date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']).'> /'.'*'.$_SERVER['REQUEST_URI'].'*'."/\n");

fclose($tm2);*/
exit;
 }
else
 $jc=$jc+1;
}
else
 $jc=1;
fseek($fp,$ip);
fwrite($fp,pack('v2',$tm,$jc));
fclose($fp);
/*$tm2=fopen(DB_DIR.'/log.txt','a+');
fwrite($tm2,'<正常> '.$_SERVER['REMOTE_ADDR'].' <'.date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']).'> /'.'*'.$_SERVER['REQUEST_URI'].'*'."/\n");
fclose($tm2);*/
unset($ip,$tm,$tm2,$jc,$fp,$limit);
?>
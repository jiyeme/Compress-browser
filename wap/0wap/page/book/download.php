[html=章节下载器]
<?php
$db=db::conn('book');
$d=str::word($_REQUEST['d']);
$qi=floor($_REQUEST['qi']) or $qi=0;
$zhi=floor($_REQUEST['zhi']) or $zhi=999999;
$rs=$db->prepare('select zip,tit,tnr,uvm from '.DB_A.'book where zbm=? and zip>=? and zip<=? order by zip asc, uvm desc');
$rs->execute(array($d,$qi,$zhi));
$fp=fopen($fn='../fctmp/'.$d./*time().*/'.txt','w+');
for($i=1;$nr=$rs->fetch(db::ass);$i++)
{
fwrite($fp,mb_convert_encoding("($nr[zip]版本$nr[uvm])$nr[tit]\n$nr[tnr]\n",'gbk','utf-8'));
}
fclose($fp);
echo "共下载了 $i 章,",round(filesize($fn)/1024,3),'KB[br][url=',$fn,']点击下载[/url]';
?>
[hr][time][foot]
[/html]
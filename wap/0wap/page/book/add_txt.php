[html=txt单章上传]
<?php
form::start('post','read.php?cid=book&pid=add_txt',true);
echo '小说代码:';form::input('zbm',null,$_POST['zbm']);
echo '<br/>章号:';form::input('zip',3,$_POST['zip']);
echo '（留空自增）<br/>标题:';form::input('tit',null,$_POST['tit']);
echo '<br/>txt:';form::file('f',null,'wml不支持上传');
echo '<br/>编码:';form::select('code',array('gbk','utf-8'));
echo '<br/>';form::submit('上传','go');
form::end();
if($_POST['go']){
$db=db::conn('book');
$rs=$db->prepare('select book as name,zjs from title where zbm=?');
$rs->execute(array($_POST['zbm']));
if(!$data=$rs->fetch()) echo '小说代码不存在';
else{
$tit=$_POST['tit'];
if(!$tit) echo '标题不能为空';
else{
$zip=floor($_POST['zip']);
if($zip<1) $zip=$data['zjs']+1;
$txt=mb_convert_encoding(file_get_contents($_FILES['f']['tmp_name']),'utf-8',$_POST['code']);
if(!$txt) echo '文件内容为空或编码选择不正确';
else {
$rs=$db->prepare('update title set zjs=? where zbm=?');
$rs->execute(array($zip,$_POST['zbm']));
$rs=$db->prepare('insert into book(zbm,zip,tit,tnr) values(?,?,?,?)');
if($rs->execute(array($_POST['zbm'],$zip,$tit,$txt))) echo $data['name'].' '.$zip.' 添加成功';
else echo $data['name'].' '.$zip.' 添加失败';
echo '<a href="/wap/?id=lktxt&amp;d='.$_POST['zbm'].'">查看</a>';
}}}}
?>[foot][/html]
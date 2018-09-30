[html=象限角查询]
[form=post,read.php?bid=xhtml&amp;cid=math&amp;pid=xiangxianjiao]
请输入角度（只支持度数，不支持度分秒符号，多个用逗号分隔）[br]
[input=jiao]<%=$_REQUEST<('jiao')>%>[/input][br]
[submit]查询[/submit]
[/form][hr]
<?php
$jiao=$_REQUEST['jiao'];
if($jiao!='')
{
$jiao=explode(',',$jiao);
foreach($jiao as $i)
{
#echo "角度= $i[br] (度)[br]";
math::jiao2hu($i);
math::xiangxianjiao($i);
echo '[hr]';
}
}
?>
By: 绿林虎穴工作室
[/html]
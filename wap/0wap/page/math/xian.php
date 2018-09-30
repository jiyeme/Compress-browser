[html=线性回归方程]
[form=post,read.php?cid=math&pid=xian]
多个值用逗号分隔[br]
x=[input=x]<%=$_POST['x']%>[/input][br]
y=[input=y]<%=$_POST['y']%>[/input][br]
[submit=go]计算[/submit]
[/form]
<?php
if($_POST['go'])
{
$x=explode(',',$_POST['x']);
$y=explode(',',$_POST['y']);
$n=count($x);
$sx=$sy=0;
for($i=0;$i<$n;$i++)
{$sx+=$x[$i];$sy+=$y[$i];}
echo "和：x=$sx,y=$sy[br]";
$xp=$sx/$n;$yp=$sy/$n;
echo "平均数：x=$xp,y=$yp[br]";
$a=$b=0;
for($i=0;$i<$n;$i++)
{
$a+=$x[$i]*$y[$i];
$b+=$x[$i]*$x[$i];
}
echo "求和：x1y1=$a,x1&#94;2=$b[br]";
$bp=($a-$n*$xp*$yp)/($b-$n*$xp*$xp);
$ap=$yp-$bp*$xp;
echo "公式：b=$bp,a=$ap[hr]y=({$bp})*x+($ap)";
}
?>[foot]
[/html]
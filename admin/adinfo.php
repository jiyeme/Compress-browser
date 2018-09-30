<?php
if($_SESSION['statu']!=$pass or $_SESSION['statu']==null){
header("location:login.php");
}else{
echo "<title>基本设置</title><div>基本设置</div>";
$_user=isset($_POST['user'])?$_POST['user']:null;
$_pass=isset($_POST['pass'])?$_POST['pass']:null;
$_reg_num=isset($_POST['reg_num'])?$_POST['reg_num']:null;
$_reg_ol=isset($_POST['reg'])?$_POST['reg']:null;
$_mianliu=isset($_POST['mianliu'])?$_POST['mianliu']:null;
$submit=isset($_POST['submit'])?$_POST['submit']:null;
if($_pass!=null&&$_user!=null&&$_reg_num!=null&&$_mianliu!=null) {
$into='<?php
$user="'.$_user.'";
$pass="'.$_pass.'";
$reg_ol="'.$_reg_ol.'";
$reg_num="'.$_reg_num.'";
$mianliu="'.$_mianliu.'";
?>';
file_put_contents("user.php",$into);
echo "管理修改成功<hr>";
}elseif($submit==修改){
if($_user==null){
echo "帐号不能为空<hr/>";
}
if($_pass==null){
echo "密码不能为空<hr/>";
}
}
echo '<form action="'.$_SERVER['PHP_SELF'].'?do=adinfo&m=admin" method="post">帐号:<input type="text" name="user" value="'.$user.'"/><br/>密码:<input type="password" name="pass" value="'.$pass.'" /><br/>是否开放注册<select name="reg"><option value="1">是</option><option value="2">否</option></select><br/>注册人数上限:<br/><input type="text" name="reg_num" value='.$reg_num.' /><br/>免流链接:<input type="text" name="mianliu" value='.$mianliu.' /><br/><input type="submit" name="submit" value="修改" /><hr>';
echo "sunshine提醒:修改密码将会掉线，如果注册上限为零将停止注册";
}
?>
<?php
/*
 *
 *   注册和修改密码
 *
 *   2011-4-16 @ jiuwap.cn
 *
 */

include "/home/jysafec1/public_html/yl/".'/inc/init.class.php';
//不要忘记要先上面一句哦





/*



//修改密码(只提供强制密码修改,无密码确认)
   if ( $browser->user_repass(用户名,密码) ){
      echo '修改成功';
   }else{
        echo '修改失败';
   }



//注册
$browser->user_reg(用户名,密码,二次确认密码,是否立即登陆)
//返回true则注册成功,否则返回错误信息

   //例:
   $var = $browser->user_reg($name,$pass,false,false);
   if ( $var === false ){
        echo '注册成功';
   }else{
        echo '注册失败:'.$var;
   }




//下面是显示最新注册的账号
   $var = $browser->user_news();
   if ( $var ){
      foreach($var as $val){
         echo substr($val,0,5).'***<br/>';
      }
   }else{
      echo '暂无会员<br/>';
   }


*/
<?php
!defined('m') && header('location: /?r='.rand(0,999));
if ( isset($_GET['yes']) ){
	$mail_url = isset($_POST['mail']) ? trim($_POST['mail']) : '';
	if ( $dir['size'] > $b_set['tmail']) {
		$error = '错误：暂时无法发送大于'.bitsize($b_set['tmail']).'的文件！';
	}else{
		set_cache_forever('email'.$disk['id'],$mail_url);
		set_time_limit(600);
		require_once DIR.'PHPMailer/class.phpmailer.php';
		//include DIR. 'set_config/set_mail.php';
/*
		$mail=new smtp_class;
		$mail->CharSet = 'utf-8';
		$mail->Host	= $b_set['mail']['smtp'];
		//$mail->SMTPSecure = "ssl";
		$mail->Port	= 25;
		$mail->Timeout	= 10;
		$mail->Timezone	= "+0800";
		$mail->Priority	= 3;
		$mail->CharSet	= "UTF-8";
		$mail->WordWrap	= 0;
		$mail->IsHTML	= false;
		$mail->MailerDebug	= true;
		$mail->AuthLogin	= true;
		$mail->AuthUser	= $b_set['mail']['user'];
		$mail->AuthPass	= $b_set['mail']['pass'];
		$mail->From($b_set['mail']['from'], $b_set['disktitle']);
		$mail->AddTo($mail_url, '亲爱的朋友');
		$mail->Subject=$dir['title'];
		$mail->Body='欢迎使用'.$b_set['disktitle'].'-邮箱投递服务，本服务完全免费。'."\r\n".'我们的网站：http://'.$b_set['host'].'，感谢大家的支持。'."\r\n".'(本邮件由'.$b_set['disktitle'].'系统发送，请勿回复。)';
		$filedir = $b_set['dfforever'].$dir['file'];
		//$mail->AddAttachment($filedir,$dir['title'],get_file_mime(get_short_file_mime($dir['title'])));
*/

$mail = new PHPMailer(); //创建实例
$mail -> CharSet='utf-8'; //设置字符集
$mail -> SetLanguage('ch',DIR.'PHPMailer/language/');  //设置语言类型和语言文件所在目录          
$mail -> IsSMTP(); //使用SMTP方式发送
$mail -> SMTPAuth = true; //设置服务器是否需要SMTP身份验证  
$mail -> Host = $b_set['mail']['smtp']; //SMTP 主机地址  
$mail -> Port = '25'; //SMTP 主机端口
$mail -> From = $b_set['mail']['user']; //发件人EMAIL地址
$mail -> FromName = $b_set['disktitle']; //发件人在SMTP主机中的用户名  
$mail -> Username = $b_set['mail']['user']; //发件人的姓名  
$mail -> Password = $b_set['mail']['pass']; //发件人在SMTP主机中的密码  
$mail -> Subject = $dir['title']; //邮件主题  
$mail -> AltBody = 'text/html'; //设置在邮件正文不支持HTML时的备用显示
$mail -> Body = '欢迎使用'.$b_set['disktitle'].'-邮箱投递服务，本服务完全免费。'."\r\n".'我们的网站：http://'.$b_set['host'].'，感谢大家的支持。'."\r\n".'(本邮件由'.$b_set['disktitle'].'系统发送，请勿回复。)';//邮件内容做成
$mail -> IsHTML(true);  //是否是HTML邮件
$mail -> AddAddress($mail_url,'亲爱的朋友'); //收件人的地址和姓名  
$mail -> AddReplyTo($b_set['mail']['user'],$b_set['disktitle']); //收件人回复时回复给的地址和姓名
$mail -> AddAttachment($b_set['dfforever'].$dir['file'],$dir['title']);//附件的路径和附件名称
/*if(!$mail -> Send()) //发送邮件  
var_dump($mail -> ErrorInfo);  //查看发送的错误信息 
*/


		try{
			if($mail->Send()){
				echo '邮件发送成功，请注意查收['.$mail_url.']';
			}else{
				echo '邮件发送失败。';
			}
		}catch(Exception $e){
			echo '邮件发送失败：'.$e->getMessage();
		}

	}

	echo '<br/><a href="disk.php?cmd=info&amp;do=toemail&amp;id='.$id.$h.'">返回发送</a><br/>
	<a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';

}else{
	if ( $browser->template == 1 ){
		echo '
		邮件地址：<input name="mail'.$browser->rand.'" type="text" value="'.get_cache_forever('email'.$disk['id']).'"/><br/>
		(如果文件过大,手机浏览器可能会出现页面超时现象,无视即可,请随时到您的邮箱看看有没有收到邮件.)<br/>
		<anchor><go href="disk.php?cmd=info&amp;yes=yes&amp;do=toemail&amp;id='.$id.$h.'" method="post">
		<postfield name="mail" value="$(mail'.$browser->rand.')" />
		</go>发送</anchor><br/><a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>
		';
	}else{
		echo '<form action="disk.php?cmd=info&amp;yes=yes&amp;do=toemail&amp;id='.$id.$h.'" method="post">
		邮件地址：<input name="mail" type="text" value="'.get_cache_forever('email'.$disk['id']).'"/><br/>
		(如果文件过大,手机浏览器可能会出现页面超时现象,无视即可,请随时到您的邮箱看看有没有收到邮件.)<br/>
		<input type="submit" value="发送"/><br /><a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>
		</form>';
	}
}

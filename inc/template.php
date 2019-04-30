<?php
/*
 *
 *	浏览器->简易模板
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */

$version = '0';
@include 'set_config/version.php';

Function top_wap2($title,$refreshurl='',$return=false,$code='utf-8',$time=1){
	$refreshurl && $refreshurl = '<meta http-equiv="refresh" content="'.$time.';url='.$refreshurl.'"/>';
    $str='<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml; charset='.$code.'"/>
<meta http-equiv="Cache-Control" content="must-revalidate,no-cache"/>'.$refreshurl.'
<title>'.$title.'</title><style>'.@file_get_contents('template/wap2.css').'</style>
</head><body>';
    if ( $code !='utf-8'){
        @$str = iconv('utf-8',$code.'//TRANSLIT', $str);
    }
    if ( $return ){
        return $str;
    }else{
        echo $str;
        return null;
    }
}

Function foot_wap2($exit=true,$return=false,$code='utf-8'){
    $str = '</body></html>';
    if ( $code !='utf-8'){
        @$str = iconv('utf-8',$code.'//TRANSLIT', $str);
    }
    if ( $return ){
        return $str;
    }else{
    	echo $str;
    }
	exit_fix_html($exit);
    return null;
}


////////////////////////////////////////////////////////


Function top_wap1($title,$refreshurl='',$return=false,$code='utf-8',$time=1){
	header('Content-Type: text/vnd.wap.wml; charset='.$code);
	if ($refreshurl){
		$refreshurl = '<card id="main" title="'.$title.'" ontimer="'.$refreshurl.'"><timer value="'.$time.'"/>';
	}else{
		$refreshurl = '<card id="main" title="'.$title.'">';
	}
$str='<?xml version="1.0" encoding="'.$code.'"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml><head><meta http-equiv="Cache-Control" content="max-age=0"/>
<meta http-equiv="Cache-Control" content="no-cache"/></head>'.$refreshurl.'<p>';
    if ( $code !='utf-8'){
        @$str = iconv('utf-8',$code.'//TRANSLIT', $str);
    }
    if ( $return ){
        return $str;
    }else{
    	echo $str;
        return null;
    }
}

Function foot_wap1($exit=true,$return=false,$code='utf-8'){
    $str = '</p></card></wml>';
    if ( $code !='utf-8'){
        @$str = iconv('utf-8',$code.'//TRANSLIT', $str);
    }
    if ( $return ){
        return $str;
    }else{
    	echo $str;
    }
	exit_fix_html($exit);
    return null;
}

function exit_fix_html($exit=true){
	$str = ob_get_contents();;
	@ob_clean();
	$str = str_replace(array("\n","\r","\t"),'',$str);
	$str = str_replace(' />','/>',$str);
	echo $str;
	if ( $exit){
		@ob_end_flush();
		exit();
	}
}

function load_template($file,$Powered=true,$jump=false,$jump_wait=false){
	global $b_set,$browser,$version,$is_run_temp_top,$get_string;
	$is_run_temp_top = false;
	$jiuwap_template = @file_get_contents('template/'.$file.'.html');
	if ( $jiuwap_template === false ){
		$jiuwap_template = '错误：模板['.$file.'.html]丢失<hr/>';
	}

	$jiuwap_template = str_replace(array('<hr>','<hr/>','<hr />'),hr,$jiuwap_template);

	$jiuwap_template = str_replace('[title]',$b_set['webtitle'],$jiuwap_template);
	$jiuwap_template = str_replace('[version]',$version,$jiuwap_template);

	strpos($jiuwap_template,'[main_form_url]')!==false && $jiuwap_template = str_replace('[main_form_url]',template_main_form_url(),$jiuwap_template);
	$jiuwap_template = preg_replace('/\[main_menu_exit_true=(.*?)\]/i', "<a href=\"logout.php?yes=yes&r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_exit=(.*?)\]/i', "<a href=\"logout.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_book=(.*?)\]/i', "<a href=\"book.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_disk=(.*?)\]/i', "<a href=\"disk.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;


	$jiuwap_template = preg_replace('/\[main_menu_login=(.*?)\]/i', "<a href=\"login.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_copy=(.*?)\]/i', "<a href=\"copy.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_set=(.*?)\]/i', "<a href=\"set.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_clear=(.*?)\]/i', "<a href=\"clear.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_help=(.*?)\]/i', "<a href=\"help.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_synch=(.*?)\]/i', "<a href=\"synch.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_index=(.*?)\]/i', "<a href=\"index.php?r=".$browser->rand."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_jiuwap=(.*?)\]/i', "<a href=\"http://jiuwap.cn\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_updatelogs=(.*?)\]/i', "<a href=\"self/new.php\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[main_menu_update=(.*?)\]/i', "<a href=\"install\">$1</a>",$jiuwap_template) ;


	//Traum
	//strpos($jiuwap_template,'[main_map_sites=')!==false && $jiuwap_template = preg_replace('/\[main_map_sites=(.*?)\]/ies', "template_main_map_sites('\\1')", $jiuwap_template);
	strpos($jiuwap_template,'[main_map_sites=')!==false && $jiuwap_template = preg_replace_callback('/\[main_map_sites=(.*?)\]/i', function($i){return template_main_map_sites($i[1]);}, $jiuwap_template);
	//strpos($jiuwap_template,'[main_wap_select=')!==false && $jiuwap_template = preg_replace('/\[main_wap_select=(.*?)\|(.*?)\]/ies', "template_wap_select('\\1','\\2')", $jiuwap_template);
	strpos($jiuwap_template,'[main_wap_select=')!==false && $jiuwap_template = preg_replace_callback('/\[main_wap_select=(.*?)\|(.*?)\|(.*?)\]/i', function($i){return template_wap_select($i[1],$i[2],$i[3]);}, $jiuwap_template);


	$jiuwap_template = str_replace('[username]',$browser->uname,$jiuwap_template);

	strpos($jiuwap_template,'[main_historys]')!==false && $jiuwap_template = str_replace('[main_historys]',template_main_historys(),$jiuwap_template);
	strpos($jiuwap_template,'[main_use_image]')!==false && $jiuwap_template = str_replace('[main_use_image]',bitsize($browser->num_size_pic),$jiuwap_template);
	strpos($jiuwap_template,'[main_use_html]')!==false && $jiuwap_template = str_replace('[main_use_html]',bitsize($browser->num_size_html),$jiuwap_template);
	$jiuwap_template = str_replace('[main_use_look]',$browser->num_look,$jiuwap_template);
	strpos($jiuwap_template,'[main_use_time]')!==false && $jiuwap_template = str_replace('[main_use_time]',template_main_use_time(),$jiuwap_template);

	strpos($jiuwap_template,'[login_form]')!==false && $jiuwap_template = str_replace('[login_form]',template_login_form(),$jiuwap_template);
	$jiuwap_template = preg_replace('/\[login_reg=(.*?)\]/i', "<a href=\"reg.php\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[login_repass=(.*?)\]/i', "<a href=\"reg_repass.php\">$1</a>",$jiuwap_template) ;

	$jiuwap_template = str_replace('[icp]',$b_set['icp'],$jiuwap_template);

	strpos($jiuwap_template,'[reg_form]')!==false && $jiuwap_template = str_replace('[reg_form]',template_reg_form(),$jiuwap_template);

strpos($jiuwap_template,'[reg_vip_form]')!==false && $jiuwap_template = str_replace('[reg_vip_form]',template_reg_vip_form(),$jiuwap_template);

	if ( $get_string ){
		$jiuwap_template = str_replace('[get_string]',$get_string,$jiuwap_template);
	}

	global $h,$au;
	if ( !isset($h) || $h === null ){
		$h = '';
	}
	if ( !isset($au) || $au === null ){
		$au = '';
	}
	$jiuwap_template = preg_replace('/\[menu_back=(.*?)\]/i', "<a href=\"/?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_jump_out=(.*?)\]/i', "<a href=\"/?o=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_copy=(.*?)\]/i', "<a href=\"copy.php?cmd=copy".$au."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_copy_board=(.*?)\]/i', "<a href=\"copy.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_book_add=(.*?)\]/i', "<a href=\"book.php?cmd=new".$au."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_book=(.*?)\]/i', "<a href=\"/?n=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_history=(.*?)\]/i', "<a href=\"history.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_disk=(.*?)\]/i', "<a href=\"disk.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_set=(.*?)\]/i', "<a href=\"set.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_clear=(.*?)\]/i', "<a href=\"clear.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = preg_replace('/\[menu_help=(.*?)\]/i', "<a href=\"help.php?h=".$h."\">$1</a>",$jiuwap_template) ;
	$jiuwap_template = str_replace('[menu_form_url]',template_menu_form_url(),$jiuwap_template);

    //truam
	//$jiuwap_template = preg_replace('/\[title=(.*?)\]/ies', "template___top('\\1')", $jiuwap_template);
	$jiuwap_template = preg_replace_callback('/\[title=(.*?)\]/i', function ($i){return template___top($i[1]);}, $jiuwap_template);

	if ( !$is_run_temp_top ){
		$browser->template_top('',$jump,$jump_wait);
	}
	echo $jiuwap_template;
	if ( $Powered ){
		echo 'Powered By <a href="http://jiuwap.cn/">Jiuwap.cn</a> Modified by <a href="http://www.jysafe.cn/">Traum</a>';
	}
	$browser->template_foot();
}

function template___top($fun_title){
	global $browser;
	global $is_run_temp_top,$jump,$jump_wait;
	$is_run_temp_top = true;
	$browser->template_top($fun_title,$jump,$jump_wait);
	return'';
}

function template_main_form_url(){
	global $browser;
	if ( $browser->template == 0 ){
		return '<form action="index.php" method="get">
		网址：<input type="text" name="url" value="" />
		<input type="submit" value="进入"/><br /></form>';
	}else{
		return '网址：<input name="url'.$browser->rand.'" type="text" value=""/>
		<anchor>
		<go href="index.php" method="get">
		<postfield name="url" value="$(url'.$browser->rand.')" />
		</go>进入</anchor>';
	}
}

function template_main_map_sites($a){
	global $browser;
	$site = $browser->site_lists();
	if ( $site == array() ){
		return '';
	}else{
		$sites = array();
		foreach($site as $id=>$val){
			$sites[] =  '<a href="/?s='.$id.'">'.$val['title'].'</a>';
		}
		return implode($a,$sites);
	}
}

function template_main_historys(){
	global $browser;
	$history = $browser->history_get();
	if ( $history == array() ){
		return '无<br/>';
	}else{
		$echo = '';
		$i = count($history) - 5;
		foreach($history as $id=>$val){
			if (--$i < 0){
				$echo = '<a href="/?h='.$id.'">'.urldecode($val['title']).'</a><br/>'.$echo ;
			}
		}
		return $echo;
	}
}


function template_main_use_time(){
	global $browser;
	$size_date = time_()-$browser->num_time;
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
	return $size_date;
}



function template_quicklogin_form($K){
	global $browser;
	if ( $browser->template == 0 ){
		return '<form action="login.php?key='.str_encrypt($K,'token').'&type=bangding&r='.$browser->rand.'" method="post">
		账号：<input type="text" name="name" value="" /><br />
		密码：<input type="password" name="pass" value="" /><br />
		<input type="submit" value="快速绑定" />
		</form>';
	}else{
		return '账号：<input name="name" type="text" value=""/><br/>
		密码：<input name="pass" type="password" value=""/><br/>
		<anchor>
			<go href="login.php?key='.str_encrypt($K,'token').'&amp;type=bangding&amp;r='.$browser->rand.'" method="post">
			<postfield name="name" value="$name" />
			<postfield name="pass" value="$pass" />
			</go>快速绑定</anchor><br/>';
	}
}



function template_reg_form(){
	global $browser;
	if ( $browser->template == 0 ){
		return '<form action="reg.php?yes=yes&r='.$browser->rand.'" method="post">
		账号：<input type="text" name="name" value="" /><br />
		密码：<input type="password" name="pass" value="" /><br />
		<input type="submit" value="注册" />
		</form>';
	}else{
		return '账号：<input name="name" type="text" value=""/><br/>
		密码：<input name="pass" type="password" value=""/><br/>
		<anchor>
			<go href="reg.php?yes=yes&amp;r='.$browser->rand.'" method="post">
			<postfield name="name" value="$name" />
			<postfield name="pass" value="$pass" />
			</go>注册</anchor><br/>';
	}
}

function template_reg_vip_form(){
	global $browser;
	if ( $browser->template == 0 ){
		return reg_vip_form_captcha();
	}else{
		return '账号：<input name="name" type="text" value=""/><br/>
		密码：<input name="pass" type="password" value=""/><br/>
		<anchor>
			<go href="reg.php?yes=yes&amp;r='.$browser->rand.'" method="post">
			<postfield name="name" value="$name" />
			<postfield name="pass" value="$pass" />
			</go>注册</anchor><br/>';
	}
}

function reg_vip_form_captcha(){
    global $browser;
    ?><form action="reg.vip.php?yes=yes&r=<?php echo $browser->rand; ?>" method="post">
		账号：<input type="text" name="name" value="" /><br />
		密码：<input type="password" name="pass" value="" /><br />
		<p>
        <label>验证码(点击图像更换)</label><br><br>
        <input type="text" name="ctype" id="ts" style="display: none" value="vcode">
        <div id="fzs">
            <div align="center">
                <img style="background-color: white;max-width:100%" id="v" onclick="change()" src="/captcha.php?traum_captcha_type=Chemical">
            </div>
            <div class="form-group label-floating is-empty">
                <label class="control-label" for="focusedInput1">请输入上图物质的分子式</label>
                <input class="form-control" name="vcode2" id="vcode" type="text">
            </div>
        </div>
        <div id="ev" style="display:none;">
            <div align="center">
                <img style="background-color: white;max-width:100%" id="ve" onclick="change()">
            </div>
            <div class="form-group label-floating is-empty">
                <label class="control-label" for="focusedInput1">请输入上图事件发生日期</label>
                <input class="form-control" name="vcode1" id="vcode1" type="text">
                <label>格式例如：19890604，公元前请加“-”表示</label>
            </div>
        </div>
        <div id="jz" style="display:none;">
            <div align="center">
                <img style="background-color: white;max-width:100%" id="jzi" onclick="change()">
            </div>
            <div class="form-group label-floating is-empty">
                <label class="control-label" for="focusedInput1">请输入上图结果的方阵的行列式的值</label>
                <input class="form-control" name="vcode3" id="vcode3" type="text">
                <label>请使用整数表示</label>
            </div>
        </div>
    </p>
    <br />
<script crossorigin="anonymous" integrity="sha384-3APoxqwb1eaELGgfoVqjalcD/aSCaFDCzFEgxv+Tplm36hWMIjtbOWPX6vm7Ha7m" src="https://lib.baomitu.com/jquery/1.2.3/jquery.min.js"></script>

    <script>
        var jq=jQuery.noConflict();
        function change() {
            r = Math.random();
            if (r > 0.66) {
                jq("#fzs").hide();
                jq("#jz").hide();
                jq("#ev").show();
                jq("#ve").attr('src', '/captcha.php?traum_captcha_type=History&t=' + Math.random());
                ts = "vcode1";
            } else if (r < 0.66 && r > 0.33) {
                jq("#ev").hide();
                jq("#jz").hide();
                jq("#fzs").show();
                jq("#v").attr('src', '/captcha.php?traum_captcha_type=Chemical&t=' + Math.random());
                ts = "vcode2";
            } else {
                jq("#jz").show();
                jq("#fzs").hide();
                jq("#ev").hide();
                jq("#jzi").attr('src', '/captcha.php?traum_captcha_type=Matrix&t=' + Math.random());
                ts = "vcode3";
            }
            jq("#ts").val(ts);
        }
    </script>
    <br />
<input type="submit" value="注册" />
		</form>
    <?php
}

function template_login_form(){
	global $browser;
    if ( $browser->template == 0 ){
		return '<form action="login.php" method="get">
        <input type="hidden" name="r" value="'.$browser->rand.'" /><br />
        <input type="hidden" name="yes" value="yes" /><br />
        账号：<input type="text" name="name" value="" /><br />
        密码：<input type="password" name="pass" value="" /><br />
        <input type="submit" value="登录"/>
        </form>';
    }else{
		return '
        账号：<input name="name_login" type="text" value=""/><br/>
        密码：<input name="pass_login" type="password" value=""/><br/>
        <anchor>
        <go href="login.php" method="get">
        <postfield name="yes" value="yes" />
        <postfield name="r" value="'.$browser->rand.'" />
        <postfield name="name" value="$(name_login)" />
        <postfield name="pass" value="$(pass_login)" />
        </go>登录</anchor>';
   }
}


function template_wap_select($a,$b,$c){
	global $browser;
	if ( $browser->template == 1 ){
	    //wap1.1
		return $a.'|<a href="wap.php?back=login&amp;wap=0">'.$b.'</a>'.$c;
	}else if($browser->template == 0){//wap2.0
		return '<a href="wap.php?back=login&amp;wap=1">'.$a.'</a>|'.$b.'|<a href="wap.php?back=login&amp;wap=2">'.$c.'</a>';
	}else{
	    
	}
}


function template_menu_form_url(){
	global $browser,$url;
	if ( $browser->template == 0 ){
		return '<form action="index.php" method="get">
		网址：<input type="text" name="url" value="'.htmlspecialchars($url).'" />
		<input type="submit" value="进入"/>
		</form>';

	}else{
		return '网址：<input name="url'.$browser->rand.'" type="text" value="'.htmlspecialchars($url).'"/>
		<anchor>
		<go href="index.php" method="get">
		<postfield name="url" value="$(url'.$browser->rand.')" />
		</go>进入</anchor>';
	}
}

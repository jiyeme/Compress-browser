<?php
/*

http://f.10086.cn/p/p/i2iaya/?0%3Btianyiw%3B342114966:%3Fp%3D3@cx.jiuwap.cn

*/

if ( defined('ML') ){
    require 'index2.php';
    return;
}
if ( !defined('fxDomian') ){
    define('fxDomian',$_SERVER['SERVER_NAME']);
}

function fxURL0($url,$qian='href'){
    $url = str_replace('/','%2F',$url);
    if (defined('ML_noreal')){
        return $qian.'="http://f.10086.cn/p/p/i2iaya/?'.$_COOKIE['FREE'].':'.$url.'@'.fxDomian.'"';
    }else{
        return $qian.'="?'.$_COOKIE['FREE'].':'.$url.'@'.fxDomian.'"';
    }
}

function fxURL_gohref($url){
    $url = str_replace('/','%2F',$url);
    $url = str_replace('?','?gg=',$url);
    $url = str_replace('gg=po','po',$url);
    return '<go_h_ref="http://'.fxDomian.'/'.$url.'&amp;sid='.$_COOKIE['FREE'].'&amp;fxml=post"';
}
function fxURL_goaction($url){
    $url = str_replace('/','%2F',$url);
    if( strpos($url,'synch.php?yes=yes') !==false ){
        $url = str_replace('synch.php?yes=yes','index.php',$url);
        $ppp = '/get/synch=yes&amp;yes=yes&amp;';//转换为get呗
    }elseif( strpos($url,'set.php?yes=yes') !==false ){
        $url = str_replace('set.php?yes=yes','index.php',$url);
        $ppp = '/get/set=yes&amp;yes=yes&amp;';//转换为get呗
    }elseif ( strpos($url,'?') === false  ){
        $url = str_replace('?','?gg=',$url);
        $ppp = '/get/';//转换为get呗
    }else{
        $ppp = '&amp;';
    }

    return 'action="http://'.fxDomian.'/'.$url.$ppp.'sid='.$_COOKIE['FREE'].'&amp;fxml=post"';
}


function fxURL($html){
    $html = str_replace('@','&at;at;',$html);
    //$html = preg_replace('@href="?(.*?)"@ies','href="?'.$_COOKIE['FREE'].':$1@'.fxDomian.'"', $html);

    //wap 下表单
    $html = str_replace('<go href="','<go_h_ref="',$html);

    $html = preg_replace('@<go_h_ref="(.*?)"@ies',"fxURL_gohref('\\1')", $html);
    $html = preg_replace('@action="?(.*?)"@ies',"fxURL_goaction('\\1')", $html);


    $html = preg_replace('@href="?(.*?)"@ies',"fxURL0('\\1')", $html);
    $html = preg_replace('@ontimer="?(.*?)"@ies',"fxURL0('\\1','ontimer')", $html);

    //不支持图片,暂时没想出解决方法。只能显示原图咯
    global $browser;
    if ( isset($browser) &&  $browser->pic == 2 || $browser->pic == 3 || $browser->pic == 5 || $browser->pic == 6 || $browser->pic == 7 || $browser->pic == 8 ){
        $html = preg_replace('@src="(.*?)"@i','src="http://'.fxDomian.'/$1&amp;sid='.$_COOKIE['FREE'].'&amp;fxml=y"', $html);
    }

    $html = preg_replace('@get="(.*?)"@i','src="http://'.fxDomian.'/$1&amp;sid='.$_COOKIE['FREE'].'&amp;fxml=gif"', $html);
    $html = str_replace('&at;at;','@',$html);
    $html = str_replace('<go_h_ref="','<go href="',$html);

    return $html;
    //return 'http://f.10086.cn/p/p/i2iaya/?cookie:url@cx.jiuwap.cn';
}

function fxURL2($return=false){
	$html = ob_get_contents();;
	ob_clean();
    $html = fxURL($html);
    if ( $return ){
        return $html ;
    }else{
        echo $html;
    }
}


if ( isset($_GET['fxml']) && $_GET['fxml'] =='gif' && isset($_GET['sid'])){
    $_COOKIE['FREE'] = $_GET['sid'];
    include $_SERVER['DOCUMENT_ROOT'].'/index2.php';
    return null;
}
if ( (isset($_GET['gg']) || isset($_GET['po'])) && isset($_GET['fxml']) && $_GET['fxml'] =='post' && isset($_GET['sid'])){
    $_COOKIE['FREE'] = $_GET['sid'];
    if ( isset($_GET['gg']) ){
        $_SERVER['QUERY_STRING'] = $_GET['gg'];
    }else{
        $_SERVER['QUERY_STRING'] = 'po='.$_GET['po'];
    }
    unset($_GET['fxml'],$_GET['sid']);
    define('ML',true);
    define('ML_noreal',true);
    include $_SERVER['DOCUMENT_ROOT'].'/index2.php';
    return null;
}

if ( isset($_SERVER['PHP_SELF']) ){
    $PPPF = $_SERVER['PHP_SELF'];
}elseif( isset($_SERVER['PATH_INFO']) ){
    $PPPF = $_SERVER['PATH_INFO'];
}elseif( isset($_SERVER['REQUEST_URI']) ){
    $PPPF = $_SERVER['REQUEST_URI'];
}else{
    $PPPF = false;
}
if ( $PPPF !== false ){
    $aaa = strpos($PPPF,'/get/');
    if ( $aaa !== false ){
        $fxgets = substr($PPPF,$aaa+5);
        $fxgets = explode('&',$fxgets);
        foreach($fxgets as $fxget){
            $pp = strpos($fxget,'=');
            if ( $pp !== false ){
                $fxgKey = substr($fxget,0,$pp);
                $fxgValue = substr($fxget,$pp+1);
                if ( $fxgKey == 'yes' ){
                    $_GET['yes'] = $fxgValue;
                } elseif ( $fxgKey == 'sid' ){
                    $_COOKIE['FREE'] = $fxgValue;
                } elseif ( $fxgKey == 'synch' || $fxgKey == 'set' ){
                    $gotophpa = $fxgKey;
                }

            }
        }
        define('ML',true);
        define('ML_noreal',true);
        if ( isset($gotophpa) ){
            include $_SERVER['DOCUMENT_ROOT'].'/'.$gotophpa.'.php';
        }else{
            include $_SERVER['DOCUMENT_ROOT'].'/index2.php';
        }
        return null;
    }
}

//  模板;账号;密码;URL

if ( !isset($_SERVER['HTTP_VIA']) || stripos($_SERVER['HTTP_VIA'],'QZ-Game') === false  ){
    define('ML',false);
    require 'index2.php';
    return;
}else{
    define('ML',true);
    $_COOKIE['FREE'] = urldecode(substr($_SERVER['PHP_AUTH_USER'],8));

    $fxtmp = trim(urldecode(trim($_SERVER['PHP_AUTH_PW'])));

    $pos = strpos($fxtmp,'?');
    if ( $pos === false ){
        //echo $fxtmp;exit;
        if ( substr($fxtmp,0,7) == 'http://' ){
            $_GET['url'] = $fxtmp;
            $_SERVER['QUERY_STRING'] = 'url='.$fxtmp;
            $fxtmp = 'index2.php';
        }elseif ( $fxtmp == '' || $fxtmp == '/' || $fxtmp == '\\' || $fxtmp=='/index.php' || $fxtmp=='index.php' ){
            $fxtmp = 'index2.php';
        }
        include $_SERVER['DOCUMENT_ROOT'].'/'.$fxtmp;
        exit;
    }else{
        //处理get
        $fxget = substr($fxtmp,$pos+1);
        $_SERVER['QUERY_STRING'] = $fxget;
        $fxgets = explode('&',$fxget);
        foreach($fxgets as $fxget){
            $pp = strpos($fxget,'=');
            if ( $pp !== false ){
                $fxgKey = substr($fxget,0,$pp);
                $fxgValue = substr($fxget,$pp+1);
                $_GET[$fxgKey] = $fxgValue;
                $_REQUEST[$fxgKey] = $fxgValue;
            }
        }

        //var_dump($_GET);exit;

        //处理文件
        $fxfile = substr($fxtmp,0,$pos);
        if( $fxfile == '' || $fxfile == '/' || $fxfile =='index.php' || $fxfile =='/index.php' ){
            $fxfile = 'index2.php';
        }elseif (substr($fxfile,-1) == '/' ){
            $fxfile .= 'index2.php';
        }
        include $_SERVER['DOCUMENT_ROOT'].'/'.$fxfile;
    }


}






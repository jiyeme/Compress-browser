<?php
$html = file_get_contents('a.txt');


$html = preg_replace_callback('/<([!a-zA-Z]{1,9}[1-5]{0,1}) (.*?)>/i', function($i){return check_xml($i[1],$i[2]);}, $html);

        //$html = preg_replace_callback('/<([\/a-zA-Z1-5]{1,9}[1-5]{0,1})>/i', function($i){return check_xml($i[1],$i[2]);}, $html);//php7
        $html = preg_replace('@<([/a-zA-Z1-5]{1,9}[1-5]{0,1})>@ies', "check_xml('\\1','\\2')", $html);//php5.4
		$html = htmlspecialchars_decode($html);
		
		
		echo $html;



function check_xml($xml,$str){
    $str = str_replace('\"','"',$str);
    $xml = strtolower($xml);
    if ( $xml == '!doctype' ){
        $xml = '!DOCTYPE';
    }
    global $browser,$mime;
    if ( in_array($xml,array('/iframe','/img','/input','/meta','/link','/footer','footer','/section','section')) ){
        return '';
    }
    if ( $browser->wap2wml==0 && $mime <> 'text/vnd.wap.wml' && $mime <> 'application/vnd.wap.xhtml+xml'){
        if ( strpos($str,'class="')){
            $str = trim(preg_replace('@class="(.*?)"@i','', $str));
        }
        if ( strpos($str,'id="')){
            $str = trim(preg_replace('@id="(.*?)"@i','', $str));
        }
        if ( in_array($xml,array('tbody','/tbody','noframes','/noframes','embed','/embed','object','/object','param','/param','frameset','/frameset','p','bod','/bod','fieldset','/fieldset','/legend','legend','nobr','/nobr','s','/s','b','/b','map','/map','area','/area','wbr','/wbr','tr','td','em','/em','font','/font','dl','/dl','dd','/dd','dt','/dt','script','/script','link','h1','h2','h3','h4','h5','/h1','/h2','/h3','/h4','/h5','center','/center','small','/small','strong','/strong','li','ul','table','div','span','/span')) ){
            return '';
        }elseif ( in_array($xml,array('br','br/','/table','/div','/li','/ul','/tr','/p','/td')) ){
            return '<br/>';
        }
    }elseif ( $browser->wap2wml==3 && $mime <> 'text/vnd.wap.wml' ){
        if ( $xml == '!doctype' ){
            return '<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">';
        }elseif ( $xml == 'html' ){
            return '<wml>';
        }elseif ( $xml == '/html' ){
            return '</card></wml>';
        }elseif( $xml =='select'){
            return parse_xml_wml_select($str);
        }elseif( $xml =='textarea'){
            return parse_xml_textarea2input($str);
        }elseif( $xml =='hr'){
            return '<br/>----------<br/>';
        }else{
            if ( in_array($xml,array('label','/label','tbody','/tbody','noframes','embed','/embed','object','/object','param','/param','/noframes','frameset','/frameset','bod','/bod','fieldset','/fieldset','/legend','legend','nobr','/nobr','s','/s','b','/b','map','/map','area','/area','wbr','/wbr','body','/body','tr','/tr','td','em','/em','font','/font','dl','/dl','dd','/dd','dt','/dt','script','/script','link','h1','h2','h3','h4','h5','/h1','/h2','/h3','/h4','/h5','center','/center','small','/small','strong','/strong','li','ul','table','div','span','/span','/form','/textarea')) ){
                return '';
            }elseif ( in_array($xml,array('br','br/','/td','/table','/div','/li','/ul')) ){
                return '<br/>';
            }
        }
    }elseif ( $browser->wap2wml==1 && $mime <> 'text/vnd.wap.wml' ){
        if ( $xml == '!doctype' ){
            return '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';
        }else{
            if ( in_array($xml,array('label','/label','tbody','/tbody','noframes','/noframes','frameset','/frameset','bod','/bod','fieldset','/fieldset','/legend','legend','nobr','/nobr','map','/map','area','/area','wbr','/wbr','tr','/tr','td','em','/em','font','/font','dl','/dl','dd','/dd','dt','/dt','script','/script','link','center','/center','small','/small','li','ul','h1','h2','h3','h4','h5','/h1','/h2','/h3','/h4','/h5','table')) ){
                return '';
            }elseif ( in_array($xml,array('br','br/','/td','/table','/li','/ul')) ){
                return '<br/>';
            }
        }
    }elseif ( $browser->wap2wml==2 && $mime == 'text/vnd.wap.wml' ){
        if ( $xml == '!doctype' ){
            return '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';
        }elseif ( $xml == 'wml' ){
            return '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml;charset=utf-8"/>';
        }elseif ( $xml == '/wml' ){
            return '</html>';
        }elseif ( $xml == '/card' ){
            return '</body>';
        }elseif ( $xml == 'card' ){
            return parse_xml_wml2web_card($str).'<style>body{font-size:15px;color:#000;font-family:Arial,Helvetica,sans-serif;}a{color:#039;text-decoration:none;}</style></head><body>';
        }elseif ( $xml == 'timer' ){
            $value = get_xml($str,'value');
            if ( $value <> ''){
                global $wml2web_card_ontimer;
                $wml2web_card_ontimer = (int)$value;
            }
            return '';
        }
    }

    if ( in_array($xml,array('link','option','a','form','img','meta','go','card','input','base','button','iframe','frame')) ){
        $xml = 'parse_xml_'.$xml;
        return $xml($str);
    }else{
        if ( $str ){
            if ( $browser->wap2wml==2 && $mime == 'text/vnd.wap.wml' && $xml == 'p'){
                return '<p>';
            }else{
                return '<'.$xml.' '.$str.'>';
            }
        }else{
            return '<'.$xml.'>';
        }
    }
}

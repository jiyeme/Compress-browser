<?php
/*
 *
 *	浏览器->共用的函数库
 *
 *	2019/4/17 星期三 @ jysafe.cn
 *
 */

Apache2Nginx();

$b_set['host'] = $_SERVER['HTTP_HOST'];

function Apache2Nginx(){
	if ( isset($_SERVER['SERVER_SOFTWARE']) && $_SERVER['SERVER_SOFTWARE'] == 'nginx' ){
		$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

		$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
		$_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'],1);
		$a = strpos($_SERVER['PATH_INFO'],'/');
		if ( $a === false ){
			$_SERVER['PATH_INFO'] = '';
		}else{
			$_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'],$a);
		}
	}
}

function http_error($url,$num,$str,$lite=false){
    if ( $num == '-3' ) {
        $error_str = 'Socket连接创建失败';
    }elseif ( $num == '-4' ) {
        $error_str = 'DNS定位失败';
    }elseif ( $num == '-5' ) {
        $error_str = '连接超时或被拒绝';
    }elseif ( $str == 'php_network_getaddresses: getaddrinfo failed: 不知道这样的主机。'){
        $error_str = '网址不可用';
    }else{
        $error_str = '访问失败';
    }
    if ( $lite ){
        if ( $error_str == '访问失败'){
            return $str;
        }else{
            return $error_str;
        }
    }
    $con = '提示：'.$error_str.'('.$num.')<br/>';
    $con .= '网址：'.htmlspecialchars($url).'<br/>';
    $con .= '详细：'.$str.'<br/>';
    error_show('访问失败',$con);
    exit;
}

function error_show($title,$content=false){
    if ( $content === false ){
        $content = '错误：'.$title;
    }
    global $browser;
	$browser->template_top($title);
	echo $content;
    if ($browser->template==1 ){
        echo '<br/><anchor>返回<prev/></anchor>';
    }elseif ( isset($_SERVER['HTTP_REFERER']) ){
    	echo '<br/><a href="'.$_SERVER['HTTP_REFERER'].'">返回</a>';
    }else{
    	echo '<br/><a href="/">返回</a>';
    }
    $browser->template_foot();
}

function error_book($title,$str){
	global $browser,$h;
	$browser->template_top($title);
	if ( $h!=''){
		echo '返回:<a href="/?h='.$h.'">网页</a>.';
	}else{
		echo '返回:';
	}
	echo '<a href="/?m='.$h.'">菜单</a>.';
	echo '<a href="copy.php?h='.$h.'">剪切板</a>';
	echo hr;
	echo $str;
	$browser->template_foot();
}



function get_file_mime($mime){
	$mime_array = array(
		'3gp'	=>	'video/3gpp',
		'aab'	=>	'application/x-authoware-bin',
		'aam'	=>	'application/x-authoware-map',
		'aas'	=>	'application/x-authoware-seg',
		'ai'	=>	'application/postscript',
		'aif'	=>	'audio/x-aiff',
		'aifc'	=>	'audio/x-aiff',
		'aiff'	=>	'audio/x-aiff',
		'als'	=>	'audio/X-Alpha5',
		'amc'	=>	'application/x-mpeg',
		'ani'	=>	'application/octet-stream',
		'asc'	=>	'text/plain',
		'asd'	=>	'application/astound',
		'asf'	=>	'video/x-ms-asf',
		'asn'	=>	'application/astound',
		'asp'	=>	'application/x-asap',
		'asx'	=>	'video/x-ms-asf',
		'au'	=>	'audio/basic',
		'avb'	=>	'application/octet-stream',
		'avi'	=>	'video/x-msvideo',
		'awb'	=>	'audio/amr-wb',
		'bcpio'	=>	'application/x-bcpio',
		'bin'	=>	'application/octet-stream',
		'bld'	=>	'application/bld',
		'bld2'	=>	'application/bld2',
		'bmp'	=>	'application/x-MS-bmp',
		'bpk'	=>	'application/octet-stream',
		'bz2'	=>	'application/x-bzip2',
		'cal'	=>	'image/x-cals',
		'ccn'	=>	'application/x-cnc',
		'cco'	=>	'application/x-cocoa',
		'cdf'	=>	'application/x-netcdf',
		'cgi'	=>	'magnus-internal/cgi',
		'chat'	=>	'application/x-chat',
		'class'	=>	'application/octet-stream',
		'clp'	=>	'application/x-msclip',
		'cmx'	=>	'application/x-cmx',
		'co'	=>	'application/x-cult3d-object',
		'cod'	=>	'image/cis-cod',
		'cpio'	=>	'application/x-cpio',
		'cpt'	=>	'application/mac-compactpro',
		'crd'	=>	'application/x-mscardfile',
		'csh'	=>	'application/x-csh',
		'csm'	=>	'chemical/x-csml',
		'csml'	=>	'chemical/x-csml',
		'css'	=>	'text/css',
		'cur'	=>	'application/octet-stream',
		'dcm'	=>	'x-lml/x-evm',
		'dcr'	=>	'application/x-director',
		'dcx'	=>	'image/x-dcx',
		'dhtml'	=>	'text/html',
		'dir'	=>	'application/x-director',
		'dll'	=>	'application/octet-stream',
		'dmg'	=>	'application/octet-stream',
		'dms'	=>	'application/octet-stream',
		'doc'	=>	'application/msword',
		'dot'	=>	'application/x-dot',
		'dvi'	=>	'application/x-dvi',
		'dwf'	=>	'drawing/x-dwf',
		'dwg'	=>	'application/x-autocad',
		'dxf'	=>	'application/x-autocad',
		'dxr'	=>	'application/x-director',
		'e'		=>	'application/octet-stream',
		'ebk'	=>	'application/x-expandedbook',
		'emb'	=>	'chemical/x-embl-dl-nucleotide',
		'embl'	=>	'chemical/x-embl-dl-nucleotide',
		'eps'	=>	'application/postscript',
		'eri'	=>	'image/x-eri',
		'es'	=>	'audio/echospeech',
		'esl'	=>	'audio/echospeech',
		'etc'	=>	'application/x-earthtime',
		'etx'	=>	'text/x-setext',
		'evm'	=>	'x-lml/x-evm',
		'evy'	=>	'application/x-envoy',
		'exe'	=>	'application/octet-stream',
		'fh4'	=>	'image/x-freehand',
		'fh5'	=>	'image/x-freehand',
		'fhc'	=>	'image/x-freehand',
		'fif'	=>	'image/fif',
		'fm'	=>	'application/x-maker',
		'fpx'	=>	'image/x-fpx',
		'fvi'	=>	'video/isivideo',
		'gau'	=>	'chemical/x-gaussian-input',
		'gca'	=>	'application/x-gca-compressed',
		'gdb'	=>	'x-lml/x-gdb',
		'gif'	=>	'image/gif',
		'gps'	=>	'application/x-gps',
		'gtar'	=>	'application/x-gtar',
		'gz'	=>	'application/x-gzip',
		'hdf'	=>	'application/x-hdf',
		'hdm'	=>	'text/x-hdml',
		'hdml'	=>	'text/x-hdml',
		'hlp'	=>	'application/winhlp',
		'hqx'	=>	'application/mac-binhex40',
		'htm'	=>	'text/html',
		'html'	=>	'text/html',
		'hts'	=>	'text/html',
		'ice'	=>	'x-conference/x-cooltalk',
		'ico'	=>	'application/octet-stream',
		'ief'	=>	'image/ief',
		'ifm'	=>	'image/gif',
		'ifs'	=>	'image/ifs',
		'imy'	=>	'audio/melody',
		'ins'	=>	'application/x-NET-Install',
		'ips'	=>	'application/x-ipscript',
		'ipx'	=>	'application/x-ipix',
		'it'	=>	'audio/x-mod',
		'itz'	=>	'audio/x-mod',
		'ivr'	=>	'i-world/i-vrml',
		'j2k'	=>	'image/j2k',
		'jad'	=>	'text/vnd.sun.j2me.app-descriptor',
		'jam'	=>	'application/x-jam',
		'jar'	=>	'application/java-archive',
		'jnlp'	=>	'application/x-java-jnlp-file',
		'jpe'	=>	'image/jpeg',
		'jpeg'	=>	'image/jpeg',
		'jpg'	=>	'image/jpeg',
		'jpz'	=>	'image/jpeg',
		'js'	=>	'application/x-javascript',
		'jwc'	=>	'application/jwc',
		'kjx'	=>	'application/x-kjx',
		'lak'	=>	'x-lml/x-lak',
		'latex'	=>	'application/x-latex',
		'lcc'	=>	'application/fastman',
		'lcl'	=>	'application/x-digitalloca',
		'lcr'	=>	'application/x-digitalloca',
		'lgh'	=>	'application/lgh',
		'lha'	=>	'application/octet-stream',
		'lml'	=>	'x-lml/x-lml',
		'lmlpack'	=>	'x-lml/x-lmlpack',
		'lsf'	=>	'video/x-ms-asf',
		'lsx'	=>	'video/x-ms-asf',
		'lzh'	=>	'application/x-lzh',
		'm13'	=>	'application/x-msmediaview',
		'm14'	=>	'application/x-msmediaview',
		'm15'	=>	'audio/x-mod',
		'm3u'	=>	'audio/x-mpegurl',
		'm3url'	=>	'audio/x-mpegurl',
		'ma1'	=>	'audio/ma1',
		'ma2'	=>	'audio/ma2',
		'ma3'	=>	'audio/ma3',
		'ma5'	=>	'audio/ma5',
		'man'	=>	'application/x-troff-man',
		'map'	=>	'magnus-internal/imagemap',
		'mbd'	=>	'application/mbedlet',
		'mct'	=>	'application/x-mascot',
		'mdb'	=>	'application/x-msaccess',
		'mdz'	=>	'audio/x-mod',
		'me'	=>	'application/x-troff-me',
		'mel'	=>	'text/x-vmel',
		'mi'	=>	'application/x-mif',
		'mid'	=>	'audio/midi',
		'midi'	=>	'audio/midi',
		'mif'	=>	'application/x-mif',
		'mil'	=>	'image/x-cals',
		'mio'	=>	'audio/x-mio',
		'mmf'	=>	'application/x-skt-lbs',
		'mng'	=>	'video/x-mng',
		'mny'	=>	'application/x-msmoney',
		'moc'	=>	'application/x-mocha',
		'mocha'	=>	'application/x-mocha',
		'mod'	=>	'audio/x-mod',
		'mof'	=>	'application/x-yumekara',
		'mol'	=>	'chemical/x-mdl-molfile',
		'mop'	=>	'chemical/x-mopac-input',
		'mov'	=>	'video/quicktime',
		'movie'	=>	'video/x-sgi-movie',
		'mp2'	=>	'audio/x-mpeg',
		'mp3'	=>	'audio/x-mpeg',
		'mp4'	=>	'video/mp4',
		'mpc'	=>	'application/vnd.mpohun.certificate',
		'mpe'	=>	'video/mpeg',
		'mpeg'	=>	'video/mpeg',
		'mpg'	=>	'video/mpeg',
		'mpg4'	=>	'video/mp4',
		'mpga'	=>	'audio/mpeg',
		'mpn'	=>	'application/vnd.mophun.application',
		'mpp'	=>	'application/vnd.ms-project',
		'mps'	=>	'application/x-mapserver',
		'mrl'	=>	'text/x-mrml',
		'mrp'	=>	'application/mrp',
		'mrm'	=>	'application/x-mrm',
		'ms'	=>	'application/x-troff-ms',
		'mts'	=>	'application/metastream',
		'mtx'	=>	'application/metastream',
		'mtz'	=>	'application/metastream',
		'mzv'	=>	'application/metastream',
		'nar'	=>	'application/zip',
		'nbmp'	=>	'image/nbmp',
		'nc'	=>	'application/x-netcdf',
		'ndb'	=>	'x-lml/x-ndb',
		'ndwn'	=>	'application/ndwn',
		'nif'	=>	'application/x-nif',
		'nmz'	=>	'application/x-scream',
		'npx'	=>	'application/x-netfpx',
		'nsnd'	=>	'audio/nsnd',
		'nva'	=>	'application/x-neva1',
		'oda'	=>	'application/oda',
		'oom'	=>	'application/x-AtlasMate-Plugin',
		'pac'	=>	'audio/x-pac',
		'pae'	=>	'audio/x-epac',
		'pan'	=>	'application/x-pan',
		'pbm'	=>	'image/x-portable-bitmap',
		'pcx'	=>	'image/x-pcx',
		'pda'	=>	'image/x-pda',
		'pdb'	=>	'chemical/x-pdb',
		'pdf'	=>	'application/pdf',
		'pfr'	=>	'application/font-tdpfr',
		'pgm'	=>	'image/x-portable-graymap',
		'php'	=>	'application/x-httpd-php',
		'pict'	=>	'image/x-pict',
		'pm'	=>	'application/x-perl',
		'pmd'	=>	'application/x-pmd',
		'png'	=>	'image/png',
		'pnm'	=>	'image/x-portable-anymap',
		'pnz'	=>	'image/png',
		'pot'	=>	'application/vnd.ms-powerpoint',
		'ppm'	=>	'image/x-portable-pixmap',
		'pps'	=>	'application/vnd.ms-powerpoint',
		'ppt'	=>	'application/vnd.ms-powerpoint',
		'pqf'	=>	'application/x-cprplayer',
		'pqi'	=>	'application/cprplayer',
		'prc'	=>	'application/x-prc',
		'proxy'	=>	'application/x-ns-proxy-autoconfig',
		'ps'	=>	'application/postscript',
		'ptlk'	=>	'application/listenup',
		'pub'	=>	'application/x-mspublisher',
		'pvx'	=>	'video/x-pv-pvx',
		'qcp'	=>	'audio/vnd.qcelp',
		'qt'	=>	'video/quicktime',
		'qti'	=>	'image/x-quicktime',
		'qtif'	=>	'image/x-quicktime',
		'rng'	=>	'application/vnd.nokia.ringing-tone',
		'r3t'	=>	'text/vnd.rn-realtext3d',
		'ra'	=>	'audio/x-pn-realaudio',
		'ram'	=>	'audio/x-pn-realaudio',
		'rar'	=>	'application/x-rar-compressed',
		'ras'	=>	'image/x-cmu-raster',
		'rdf'	=>	'application/rdf+xml',
		'rf'	=>	'image/vnd.rn-realflash',
		'rgb'	=>	'image/x-rgb',
		'rlf'	=>	'application/x-richlink',
		'rm'	=>	'audio/x-pn-realaudio',
		'rmf'	=>	'audio/x-rmf',
		'rmm'	=>	'audio/x-pn-realaudio',
		'rmvb'	=>	'audio/x-pn-realaudio',
		'rnx'	=>	'application/vnd.rn-realplayer',
		'roff'	=>	'application/x-troff',
		'rp'	=>	'image/vnd.rn-realpix',
		'rpm'	=>	'audio/x-pn-realaudio-plugin',
		'rt'	=>	'text/vnd.rn-realtext',
		'rte'	=>	'x-lml/x-gps',
		'rtf'	=>	'application/rtf',
		'rtg'	=>	'application/metastream',
		'rtx'	=>	'text/richtext',
		'rv'	=>	'video/vnd.rn-realvideo',
		'rwc'	=>	'application/x-rogerwilco',
		's3m'	=>	'audio/x-mod',
		's3z'	=>	'audio/x-mod',
		'sca'	=>	'application/x-supercard',
		'scd'	=>	'application/x-msschedule',
		'sdf'	=>	'application/e-score',
		'sea'	=>	'application/x-stuffit',
		'sgm'	=>	'text/x-sgml',
		'sgml'	=>	'text/x-sgml',
		'sh'	=>	'application/x-sh',
		'shar'	=>	'application/x-shar',
		'shtml'	=>	'magnus-internal/parsed-html',
		'shw'	=>	'application/presentations',
		'si6'	=>	'image/si6',
		'si7'	=>	'image/vnd.stiwap.sis',
		'si9'	=>	'image/vnd.lgtwap.sis',
		'sis'	=>	'application/vnd.symbian.install',
		'sisx'	=>	'x-epoc/x-sisx-app',
		'sit'	=>	'application/x-stuffit',
		'skd'	=>	'application/x-Koan',
		'skm'	=>	'application/x-Koan',
		'skp'	=>	'application/x-Koan',
		'skt'	=>	'application/x-Koan',
		'slc'	=>	'application/x-salsa',
		'smd'	=>	'audio/x-smd',
		'smi'	=>	'application/smil',
		'smil'	=>	'application/smil',
		'smp'	=>	'application/studiom',
		'smz'	=>	'audio/x-smd',
		'snd'	=>	'audio/basic',
		'spc'	=>	'text/x-speech',
		'spl'	=>	'application/futuresplash',
		'spr'	=>	'application/x-sprite',
		'sprite'=>	'application/x-sprite',
		'spt'	=>	'application/x-spt',
		'src'	=>	'application/x-wais-source',
		'stk'	=>	'application/hyperstudio',
		'stm'	=>	'audio/x-mod',
		'sv4cpio'=>	'application/x-sv4cpio',
		'sv4crc'=>	'application/x-sv4crc',
		'svf'	=>	'image/vnd',
		'svg'	=>	'image/svg-xml',
		'svh'	=>	'image/svh',
		'svr'	=>	'x-world/x-svr',
		'swf'	=>	'application/x-shockwave-flash',
		'swfl'	=>	'application/x-shockwave-flash',
		't'		=>	'application/x-troff',
		'tad'	=>	'application/octet-stream',
		'talk'	=>	'text/x-speech',
		'tar'	=>	'application/x-tar',
		'taz'	=>	'application/x-tar',
		'tbp'	=>	'application/x-timbuktu',
		'tbt'	=>	'application/x-timbuktu',
		'tcl'	=>	'application/x-tcl',
		'tex'	=>	'application/x-tex',
		'texi'	=>	'application/x-texinfo',
		'texinfo'=>	'application/x-texinfo',
		'tgz'	=>	'application/x-tar',
		'thm'	=>	'application/vnd.eri.thm',
		'tif'	=>	'image/tiff',
		'tiff'	=>	'image/tiff',
		'tki'	=>	'application/x-tkined',
		'tkined'=>	'application/x-tkined',
		'toc'	=>	'application/toc',
		'toy'	=>	'image/toy',
		'tr'	=>	'application/x-troff',
		'trk'	=>	'x-lml/x-gps',
		'trm'	=>	'application/x-msterminal',
		'tsi'	=>	'audio/tsplayer',
		'tsp'	=>	'application/dsptype',
		'tsv'	=>	'text/tab-separated-values',
		'tsv'	=>	'text/tab-separated-values',
		'ttf'	=>	'application/octet-stream',
		'ttz'	=>	'application/t-time',
		'txt'	=>	'text/plain',
		'ult'	=>	'audio/x-mod',
		'ustar'	=>	'application/x-ustar',
		'uu'	=>	'application/x-uuencode',
		'uue'	=>	'application/x-uuencode',
		'vcd'	=>	'application/x-cdlink',
		'vcf'	=>	'text/x-vcard',
		'vdo'	=>	'video/vdo',
		'vib'	=>	'audio/vib',
		'viv'	=>	'video/vivo',
		'vivo'	=>	'video/vivo',
		'vmd'	=>	'application/vocaltec-media-desc',
		'vmf'	=>	'application/vocaltec-media-file',
		'vmi'	=>	'application/x-dreamcast-vms-info',
		'vms'	=>	'application/x-dreamcast-vms',
		'vox'	=>	'audio/voxware',
		'vqe'	=>	'audio/x-twinvq-plugin',
		'vqf'	=>	'audio/x-twinvq',
		'vql'	=>	'audio/x-twinvq',
		'vre'	=>	'x-world/x-vream',
		'vrml'	=>	'x-world/x-vrml',
		'vrt'	=>	'x-world/x-vrt',
		'vrw'	=>	'x-world/x-vream',
		'vts'	=>	'workbook/formulaone',
		'wav'	=>	'audio/x-wav',
		'wax'	=>	'audio/x-ms-wax',
		'wbmp'	=>	'image/vnd.wap.wbmp',
		'web'	=>	'application/vnd.xara',
		'wi'	=>	'image/wavelet',
		'wis'	=>	'application/x-InstallShield',
		'wm'	=>	'video/x-ms-wm',
		'wma'	=>	'audio/x-ms-wma',
		'wmd'	=>	'application/x-ms-wmd',
		'wmf'	=>	'application/x-msmetafile',
		'wml'	=>	'text/vnd.wap.wml',
		'wmlc'	=>	'application/vnd.wap.wmlc',
		'wmls'	=>	'text/vnd.wap.wmlscript',
		'wmlsc'	=>	'application/vnd.wap.wmlscriptc',
		'wmlscript'	=>	'text/vnd.wap.wmlscript',
		'wmv'	=>	'audio/x-ms-wmv',
		'wmx'	=>	'video/x-ms-wmx',
		'wmz'	=>	'application/x-ms-wmz',
		'wpng'	=>	'image/x-up-wpng',
		'wpt'	=>	'x-lml/x-gps',
		'wri'	=>	'application/x-mswrite',
		'wrl'	=>	'x-world/x-vrml',
		'wrz'	=>	'x-world/x-vrml',
		'ws'	=>	'text/vnd.wap.wmlscript',
		'wsc'	=>	'application/vnd.wap.wmlscriptc',
		'wv'	=>	'video/wavelet',
		'wvx'	=>	'video/x-ms-wvx',
		'wxl'	=>	'application/x-wxl',
		'x-gzip'=>	'application/x-gzip',
		'xar'	=>	'application/vnd.xara',
		'xbm'	=>	'image/x-xbitmap',
		'xdm'	=>	'application/x-xdma',
		'xdma'	=>	'application/x-xdma',
		'xdw'	=>	'application/vnd.fujixerox.docuworks',
		'xht'	=>	'application/xhtml+xml',
		'xhtm'	=>	'application/xhtml+xml',
		'xhtml'	=>	'application/xhtml+xml',
		'xla'	=>	'application/vnd.ms-excel',
		'xlc'	=>	'application/vnd.ms-excel',
		'xll'	=>	'application/x-excel',
		'xlm'	=>	'application/vnd.ms-excel',
		'xls'	=>	'application/vnd.ms-excel',
		'xlt'	=>	'application/vnd.ms-excel',
		'xlw'	=>	'application/vnd.ms-excel',
		'xm'	=>	'audio/x-mod',
		'xml'	=>	'text/xml',
		'xmz'	=>	'audio/x-mod',
		'xpi'	=>	'application/x-xpinstall',
		'xpm'	=>	'image/x-xpixmap',
		'xsit'	=>	'text/xml',
		'xsl'	=>	'text/xml',
		'xul'	=>	'text/xul',
		'xwd'	=>	'image/x-xwindowdump',
		'xyz'	=>	'chemical/x-pdb',
		'yz1'	=>	'application/x-yz1',
		'z'		=>	'application/x-compress',
		'zac'	=>	'application/x-zaurus-zac',
		'zip'	=>	'application/zip',
		'nokia-op-logo'	=>	'image/vnd.nok-oplogo-color',
	);
	if ( isset($mime_array[$mime]) ){
		return $mime_array[$mime];
	}else{
		return 'application/octet-stream';
	}
}


function unicode2utf8($str) {
	$str = rawurldecode($str);
	preg_match_all('/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U', $str,$r);
	$arr = $r[0];
	foreach($arr as $k=>$v) {
		if(substr($v,0,2) == "%u"){
			//$arr[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));
			$arr[$k] = mb_convert_encoding(pack("H4",substr($v,-4)),"UTF-8","UCS-2");
		}elseif(substr($v,0,3) == "&#x"){
			//$arr[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,3,-1))) ;
			$arr[$k] = mb_convert_encoding(pack("H4",substr($v,3,-1)),"UTF-8","UCS-2");
		}elseif(substr($v,0,2) == "&#"){
			//iconv ( string $in_charset , string $out_charset , string $str )
			//mb_convert_encoding ( string $str , string $to_encoding [, mixed $from_encoding ] )
			//$arr[$k] = iconv("UCS-2","UTF-8",pack("n",substr($v,2,-1)));
			$arr[$k] = mb_convert_encoding(pack("n",substr($v,2,-1)),"UTF-8","UCS-2");
		}
	}
	return implode('',$arr);
}


Function GetHost($h){
	$h = strtolower('.'.$h);
	$arr = array(
			'7'	=>	array('.org.cn','.gov.cn','.net.cn','.com.cn','.com.hk'),
			'4'	=>	array('.com','.net','.org','.tel'),
			'3'	=>	array('.la','.co','.cn','.me','.cc','.hk','.tk','.in','.gp','.us','.lc'),
			'5'	=>	array('.mobi','.info','.name','.asia'),
			);
	foreach($arr as $nn => $houzhui){
		$h_len = strlen($h)-$nn;
		foreach( $houzhui as $val){
			if ( substr($h,$h_len,$nn) == $val ){
				$temp = substr($h,0,$h_len);
				return substr($temp,strrpos($temp,'.')+1,$h_len).$val;
			}
		}
	}
	return $h;
}

function qqagent_init(){
    require_once(ROOT_DIR.'set_config/set_config.php');
	global $b_set;
	if ( !$b_set['switch']['qqua'] ){
		return ;
	}
	global $HTTP_Q_UA,$HTTP_Q_AUTH,$HTTP_Q_GUID;
	$HTTP_Q_UA = $HTTP_Q_AUTH = $HTTP_Q_GUID = '' ;
	
    @$result = cloud_memcache::get('qq_ua');
    	
	if ( $result === false ){
		$HTTP_Q = array();
	}else{
		$result = unserialize($result);
		if ( $result === false || $result === NULL ){
			$HTTP_Q = array();
		}else{
			$HTTP_Q = $result;
		}
	}
	if ( isset($_SERVER["HTTP_Q_UA"]) && isset($_SERVER["HTTP_Q_AUTH"]) ) {
		if ( $_SERVER["HTTP_Q_UA"]!=$HTTP_Q_UA || $_SERVER["HTTP_Q_AUTH"]!=$HTTP_Q_AUTH ){
			$HTTP_Q_UA = isset($_SERVER["HTTP_Q_UA"]) ? db_safe_dropstr($_SERVER['HTTP_Q_UA']) : '' ;
			$HTTP_Q_AUTH = isset($_SERVER["HTTP_Q_AUTH"]) ? db_safe_dropstr($_SERVER['HTTP_Q_AUTH']) : '' ;
			$HTTP_Q_GUID = isset($_SERVER["HTTP_Q_GUID"]) ? db_safe_dropstr($_SERVER['HTTP_Q_GUID']) : '' ;
			$HTTP_Q = array(
				'HTTP_Q_UA'=>$HTTP_Q_UA,
				'HTTP_Q_AUTH'=>$HTTP_Q_AUTH,
				'HTTP_Q_GUID'=>$HTTP_Q_GUID,
			);
			
			@cloud_memcache::set('qq_ua',serialize($HTTP_Q));
		}
	}
	foreach($HTTP_Q as $k=>$v){
		$$k = $v;
	}
}

function db_safe_dropstr($str){
	return str_replace(array('"','\''),'',$str);
}

function user_ip() {
	//该方法将首先获取HTTP_X_REAL_IP的IP地址，该IP地址是可以伪造的。
	if ( !empty($_SERVER['HTTP_X_REAL_IP'])){
		$ip = $_SERVER['HTTP_X_REAL_IP'] ;
	}elseif ( !empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'] ;
	}elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ;
	}elseif ( !empty($_SERVER['REMOTE_ADDR'])){
		$ip = $_SERVER['REMOTE_ADDR'] ;
	}else{
		$ip = '127.0.0.1' ;
	}
	if ( $ip == '::1' || $ip <> '127.0.0.1' ){
		$ip = '127.0.0.1' ;
	}
	return $ip;
}

function ubb_copy($str){
	if ( !is_string($str) ){
		return $str;
	}
	$a = strpos($str,'[copy=');
	if ( $a===false ){
		return $str;
	}
	$b = strpos($str,']');
	if ( $b===false || $a >= $b ){
		return $str;
	}
	$str = preg_replace('/\[copy=([0-9]{1,5})\]/ies', "ubb_copy_true('\\1')", $str);
	return $str;
}

function ubb_copy_true($id){
	global $browser;
	$arr = $browser->copy_look($id);
	if ( $arr ){
		return $arr['content'];
	}else{
		return '[copy='.$id.']';
	}
}


function str_pos($str,$start,$end){
	$i = stripos($str,$start);
	if ( $i === false){
		return '';
	}
	$t = substr($str,$i+strlen($start));
	$x = stripos($t,$end);
	if ( $x === false){
		return '';
	}
	$t = substr($t,0,$x);
	return $t;
}


function fix_r_n_t($str){
	$str = str_replace(array("\n","\r","\t",chr(0)),'',$str);
	return $str;
}

function str_fix_chinese($str){
	$str = iconv('utf-8','utf-8//IGNORE', $str);
	return $str;
}

function deldir($dir,$deldir=true) {
	if ( is_dir($dir) ) {
		$dh = opendir($dir);
		while ( $file = readdir ($dh) ) {
			if( $file!='.' && $file!='..' ) {
				$fullpath = $dir.'/'.$file;
				if( !is_dir($fullpath) ) {
					@unlink($fullpath);
				}else{
					deldir($fullpath,true);
				}
			}
		}
		closedir($dh);
		if ( $deldir ){
			rmdir($dir);
		}
	}
}


function getUTFString($string,&$code=''){
	$code = mb_detect_encoding($string, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
	return mb_convert_encoding($string, 'utf-8', $code);
}


function bitsize($num) {
	if (!preg_match("/^[0-9]+$/", $num)){
		return 0 ;
	}
	$type = array('B', 'KB', 'MB', 'GB', 'TB', 'PB') ;
	$j = 0 ;
	while ($num >= 1024) {
		if ($j >= 5){
			return $num.$type[$j] ;
		}
		$num = $num / 1024 ;
		$j++ ;
	}
	return number_format($num, 2) . $type[$j] ;
}

class funCrypt {
	private $way = 1 ;
	private $password1 = '' ;
	private $lockstream = 'lDEFABstCNOPyzghIJK6/7nopqr89LMmGHijQRSTUwVWXYZabcdexkf+013245uv=' ;
	function __construct($passwo='') {
		global $b_set;
		if ( $passwo== ''){
			$passwo = $b_set['key3'];
		}
		$this->password1 = md5($passwo) ;
		$this->lockstream = $b_set['key4'];
	}
	//加密
	function enCrypt($txtStream) {
		if ($this->way == 1) {
			$lockLen = strlen($this->lockstream) ;
			$lockCount = rand(0, $lockLen - 1) ;
			$randomLock = $this->lockstream[$lockCount] ;
			$password = md5($this->password1 . $randomLock) ;
			$txtStream = base64_encode($txtStream) ;
			for ($i = 0, $j = 0, $k = 0, $tmpStream = ''; $i < strlen($txtStream); $i++) {
				$k = $k == strlen($password) ? 0 : $k ;
				$j = (strpos($this->lockstream, $txtStream[$i]) + $lockCount + ord($password[$k])) % ($lockLen) ;
				$tmpStream .= $this->lockstream[$j] ;
				$k++ ;
			}
			return base64_encode($tmpStream . $randomLock) ;
		} else {
			srand((double)microtime() * 1000000) ;
			$encrypt_key = md5(rand(0, 32000)) ;
			$ctr = 0 ;
			$tmp = '' ;
			for ($i = 0; $i < strlen($txt); $i++) {
				$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr ;
				$tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]) ;
			}
			return base64_encode($this->passport_key($tmp, $key)) ;
		}
	}
	//解密
	function deCrypt($txtStream) {
		if ($this->way == 1) {
			$txtStream = base64_decode($txtStream) ;
			$lockLen = strlen($this->lockstream) ;
			$txtLen = strlen($txtStream) ;
			if ($txtLen < 1)
				return '' ;
			$randomLock = $txtStream[$txtLen - 1] ;
			$lockCount = strpos($this->lockstream, $randomLock) ;
			$password = md5($this->password1 . $randomLock) ;
			$txtStream = substr($txtStream, 0, $txtLen - 1) ;
			$tmpStream = '' ;
			$j = 0 ;
			$k = 0 ;
			for ($i = 0; $i < strlen($txtStream); $i++) {
				$k = $k == strlen($password) ? 0 : $k ;
				$j = strpos($this->lockstream, $txtStream[$i]) - $lockCount - ord($password[$k]) ;
				while ($j < 0) {
					$j += $lockLen ;
				}
				$tmpStream .= $this->lockstream[$j] ;
				$k++ ;
			}
			return base64_decode($tmpStream) ;
		} else {
			$txt = $this->passport_key(base64_decode($txt)) ;
			for ($i = 0, $tmp = ''; $i < strlen($txt); $i++) {
				$tmp .= $txt[++$i] ^ $txt[$i] ;
			}
			return $tmp ;
		}
	}

	function passport_key($txt) {
		$encrypt_key = $this->password1 ;
		for ($i = 0, $ctr = 0, $tmp = ''; $i < strlen($txt); $i++) {
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr ;
			$tmp .= $txt[$i] ^ $encrypt_key[$ctr++] ;
		}
		return $tmp ;
	}
}

function fix_disposition($str){
	$str = str_ireplace('filename*="utf8\'\'','filename="',$str);
	$str = str_ireplace('"','',$str);
	$str = str_ireplace('\'','',$str);
	$str = str_ireplace(' ','',$str);

	if ( substr($str,0,20) == 'attachment;filename=' ){
		$str = substr($str,20);
	}
	return fix_localbase($str);
}

function fix_localbase($str){
	$str = str_replace('*','_',$str);
	$str = str_replace('`','_',$str);
	$str = str_replace('!','_',$str);
	$str = str_replace('"','_',$str);
	$str = str_replace('\'','_',$str);
	$str = str_replace(':','_',$str);
	$str = str_replace('?','_',$str);
	$str = str_replace('|','_',$str);
	$str = str_replace('<','_',$str);
	$str = str_replace('>','_',$str);
	$str = str_replace('\'','_',$str);
	$str = str_replace('"','_',$str);
	return $str;
}

function fix_basename($str){
	$str = basename($str);
	$a = strpos($str,'?');
	if ( $a !== false ){
		$str = substr($str,0,$a);
	}
	return fix_localbase($str);
}


function str_encrypt($str,$code=''){
	if ( $code == ''){
		global $b_set;
		$code = $b_set['key2'];
	}
	$Crypt = new funCrypt($code);
	return $Crypt->enCrypt($str);
}

function str_decrypt($str,$code=''){
	if ( $code == ''){
		global $b_set;
		$code = $b_set['key2'];
	}
	$Crypt = new funCrypt($code);
	return $Crypt->deCrypt($str);
}


function num2short($num = 0){
	static $arr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if ( $num < 62 ){
		return $arr[$num];
	}elseif( $num <= 3905 ){
		$int = intval(floor($num/62));
		$mod = $num - $int * 62;
		return $arr[$int - 1] . $arr[$mod];
	}else{
		throw new Exception('暂时只支持小于3905',E_USER_ERROR);
		/*$int = floor($num/3905);
		$mod = $num - $int * 3905;
		return num2short($int-1) . num2short($mod+62);*/
	}
}

function short2num($str){
	static $arr = array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','a'=>'10','b'=>'11','c'=>'12','d'=>'13','e'=>'14','f'=>'15','g'=>'16','h'=>'17','i'=>'18','j'=>'19','k'=>'20','l'=>'21','m'=>'22','n'=>'23','o'=>'24','p'=>'25','q'=>'26','r'=>'27','s'=>'28','t'=>'29','u'=>'30','v'=>'31','w'=>'32','x'=>'33','y'=>'34','z'=>'35','A'=>'36','B'=>'37','C'=>'38','D'=>'39','E'=>'40','F'=>'41','G'=>'42','H'=>'43','I'=>'44','J'=>'45','K'=>'46','L'=>'47','M'=>'48','N'=>'49','O'=>'50','P'=>'51','Q'=>'52','R'=>'53','S'=>'54','T'=>'55','U'=>'56','V'=>'57','W'=>'58','X'=>'59','Y'=>'60','Z'=>'61');
	$num = 0;
	$n = strlen($str);
	if ( $n <= 0 ){
		throw new Exception('参数不能为空',E_USER_ERROR);
	}else if ( $n > 2 ){
		throw new Exception('暂时只支持2位字符的转换',E_USER_ERROR);
	}

	for($i=0;$i<$n;$i++){
		$num += pow(62,$n-$i-1) * $arr[$str[$i]] ;
	}
	if ( $n > 1 ){
		$num += 62;
	}
	return $num;
}

function getshortname($id){
    return ceil(($id+1)/250);
}

function getshortname_history($id){
    return ceil(($id+1)/5);
}

if (!function_exists('ImageCreateFromBMP')){
	function ImageCreateFromBMP($filename){
		if ( !$f1 = @fopen ($filename ,'rb')){
			return FALSE ;
		}
		$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread ($f1,14));
		if ($FILE['file_type'] != 19778 ){
			return FALSE ;
		}
		$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'. '/Vcompression/Vsize_bitmap/Vhoriz_resolution'. '/Vvert_resolution/Vcolors_used/Vcolors_important', fread ($f1 ,40 ));
		$BMP['colors'] = pow (2 ,$BMP['bits_per_pixel']);
		if ($BMP['size_bitmap'] == 0 ){
			$BMP['size_bitmap'] = $FILE ['file_size'] - $FILE ['bitmap_offset'];
		}
		unset($FILE);
		$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8 ;
		$BMP['bytes_per_pixel2'] = ceil ($BMP['bytes_per_pixel']);
		$BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4 );
		$BMP['decal'] -= floor ($BMP['width']*$BMP['bytes_per_pixel']/4 );
		$BMP['decal'] = 4 -(4 *$BMP['decal']);
		if ($BMP['decal'] == 4 ){
			$BMP['decal'] = 0 ;
		}
		$PALETTE = array ();
		if ($BMP['colors'] < 16777216 ){
			$PALETTE = unpack ('V'. $BMP['colors'], fread($f1 ,$BMP['colors']*4 ));
		}
		$IMG = fread ($f1 ,$BMP['size_bitmap']);
		fclose($f1);
		$VIDE = chr (0);
		$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
		$P = 0 ;
		$Y = $BMP['height']-1 ;
		while ($Y >= 0 ){
			$X =0 ;
			while ($X < $BMP['width']){
				if ($BMP['bits_per_pixel'] == 24 ){
					$COLOR = unpack ("V",substr ($IMG ,$P ,3 ). $VIDE );
				}elseif ($BMP['bits_per_pixel'] == 16 ){
					$COLOR = unpack ("n",substr ($IMG ,$P ,2 ));
					$COLOR [1] = $PALETTE [$COLOR [1]+1];
				} elseif ($BMP['bits_per_pixel'] == 8 ){
					$COLOR = unpack ("n",$VIDE.substr ($IMG ,$P ,1 ));
					$COLOR [1] = $PALETTE [$COLOR [1]+1];
				} elseif ($BMP['bits_per_pixel'] == 4 ){
					$COLOR = unpack ("n",$VIDE.substr ($IMG ,floor ($P ),1 ));
					if(($P *2 )%2 == 0 ){
						$COLOR [1] = ($COLOR [1] >> 4 ) ;
					}else{
						$COLOR [1] = ($COLOR [1] & 0x0F );
					}
					$COLOR [1] = $PALETTE [$COLOR [1]+1];
				} elseif ($BMP['bits_per_pixel'] == 1 ){
					$COLOR = unpack ("n",$VIDE.substr ($IMG ,floor ($P ),1 ));
					if (($P *8 )%8 == 0 ){
						$COLOR [1] = $COLOR [1] >>7 ;
					}elseif (($P *8 )%8 == 1 ){
						$COLOR [1] = ($COLOR [1] & 0x40 )>>6 ;
					}elseif (($P *8 )%8 == 2 ){
						$COLOR [1] = ($COLOR [1] & 0x20 )>>5 ;
					}elseif (($P *8 )%8 == 3 ){
						$COLOR [1] = ($COLOR [1] & 0x10 )>>4 ;
					}elseif (($P *8 )%8 == 4 ){
						$COLOR [1] = ($COLOR [1] & 0x8 )>>3 ;
					}elseif (($P *8 )%8 == 5 ){
						$COLOR [1] = ($COLOR [1] & 0x4 )>>2 ;
					}elseif (($P *8 )%8 == 6 ){
						$COLOR [1] = ($COLOR [1] & 0x2 )>>1 ;
					}elseif (($P *8 )%8 == 7 ){
						$COLOR [1] = ($COLOR [1] & 0x1 );
					}
					$COLOR [1] = $PALETTE [$COLOR [1]+1];
				} else{
					return FALSE ;
				}
				imagesetpixel($res ,$X ,$Y ,$COLOR [1]);
				$X ++;
				$P += $BMP['bytes_per_pixel'];
			}
			$Y --;
			$P +=$BMP['decal'];
		}
		return $res ;
	}
}

function returnJson($data){
	$data = serialize($data);
	return "<jiuwap_api time=\"".time_()."\" host=\"{$_SERVER['SERVER_NAME']}\">\r\n<return>$data</return>\r\n</jiuwap_api>";
	//exit;
}

function getJson($data){
	if ( strpos($data,'<jiuwap_api') === false || strpos($data,'<return>') === false  || strpos($data,'</return>') === false || strpos($data,'</jiuwap_api>') == false ){
		return false;
	}
	$a = strpos($data,'<return>');
	$b = strrpos($data,'</return>');

	$data = substr($data,$a+8,$b-$a-8);
	return unserialize($data);
}

function quick_connect($url){
	if ( !isset($_SERVER['SERVER_PORT'])){
		$_SERVER['SERVER_PORT'] = '80';
	}
	if ( !isset($_SERVER['SERVER_ADDR'])){
		$_SERVER['SERVER_ADDR'] = '127.0.0.1';
	}
	$out = "GET /{$url} HTTP/1.1\r\n";
	$out .= "Host: {$_SERVER['SERVER_NAME']}\r\n";
	$out .= "Connection: Close\r\n\r\n";

	//echo $_SERVER['SERVER_ADDR'],$_SERVER['SERVER_PORT'];
	$fp = @fsockopen($_SERVER['SERVER_ADDR'],$_SERVER['SERVER_PORT']);
	if ( $fp === false  ){
		return;
	}
	//echo $out;exit;
	fwrite($fp, $out);
	fclose($fp);
}

function file_get_content($url){
	if( function_exists('curl_init') ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result =  curl_exec($ch);
		curl_close($ch);
		return $result;
	}else{
		return file_get_contents($url);
	}
}

function IsWap() {
    if (isset($_SERVER['HTTP_VIA']) ||
		isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE']) ||
		isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID']) ||
		isset($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']),'vnd.wap.wml') !== false ||
		empty($_SERVER['HTTP_USER_AGENT'])
		) return true;

	$Browser = trim($_SERVER['HTTP_USER_AGENT']);

	$Wap = Array('noki','eric','wapi','mc21','aur','r380','up.B','winw',
				'upg1','upsi','qwap','jigs','java','alca','mits','mot-',
				'my S','wapj','fetc','alav','wapa','oper');

	return in_array(Substr($Browser,0,4),$Wap);
}

function IsWap2(){
	static $result = null;
	if ( $result !== null ){
		return $result;
	}
	$ua = isset($_SERVER['HTTP_ACCEPT']) ? '.'.strtolower($_SERVER['HTTP_ACCEPT']) : false;
	if ( $ua && (
		strpos($ua,'text/html') ||
		strpos($ua,'application/xhtml+xml')
		)){
		$result= true;
	}else{
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? '.'.$_SERVER['HTTP_USER_AGENT'] : false;
		if (
			$ua && (
				stripos($ua,'MSIE') ||
				stripos($ua,'Windows') ||
				stripos($ua,'Mozilla') ||
				stripos($ua,'Symbian') ||
				stripos($ua,'iPhone') ||
				stripos($ua,'KHTML') ||
				stripos($ua,'Chrome') ||
				stripos($ua,'ucweb') ||
				stripos($ua,'smartphone') ||
				stripos($ua,'blackberry') ||
				stripos($ua,'opera') ||
				stripos($ua,'AppleWebKit')
			)){
			$result= true;
		}else{
			$result= false;
		}
	}
	return $result;
}


function set_Cookie($name, $value='', $maxage=0, $path='',$domain='', $secure=false, $HTTPOnly=false){
	return Setcookie($name,$value,$maxage,$path,$domain,$secure,$HTTPOnly);

	/*以下方法有点问题*/
	if(is_array($name)){
		list($k,$v) = each($name);
			$name = $k.'['.$v.']';
	}
	$ob = ini_get('output_buffering');
	// Abort the method if headers have already been sent, except when output buffering has been enabled
	if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off' ) return false;
	if ( !empty($domain) ) {
		// Fix the domain to accept domains with and without 'www.'.
		if ( strtolower( substr($domain, 0, 4) ) == 'www.' ) $domain = substr($domain, 4);
		// Add the dot prefix to ensure compatibility with subdomains
		if ( substr($domain, 0, 1) != '.' ) $domain = '.'.$domain;
		// Remove port information.
		$port = strpos($domain, ':');
		if ( $port !== false ) $domain = substr($domain, 0, $port);
	}
	// Prevent "headers already sent" error with utf8 support (BOM)
	//if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');
	if(is_array($name)) {
		header('Set-Cookie: '.$name.'='.rawurlencode($value)
							.(empty($domain) ? '' : '; Domain='.$domain)
							.(empty($maxage) ? '' : '; Expires='.date('r',$maxage))
							.(empty($path) ? '' : '; Path='.$path)
							.(!$secure ? '' : '; Secure')
							.(!$HTTPOnly ? '' : '; HttpOnly'), false);
	}else{
		header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
							.(empty($domain) ? '' : '; Domain='.$domain)
							.(empty($maxage) ? '' : '; Expires='.date('D, d-M-Y h:i:s T',$maxage))
							.(empty($path) ? '' : '; Path='.$path)
							.(!$secure ? '' : '; Secure')
							.(!$HTTPOnly ? '' : '; HttpOnly'), false);
	}
	return true;
}

function loginfo($msg)
{
    $logSwitch = 1;         // 日志开关：1表示打开，0表示关闭
    $logFile    = 'temp/browser.log'; // 日志路径           
    if ($logSwitch == 0 ) return;
    date_default_timezone_set('Asia/Shanghai');
    file_put_contents($logFile, date('[Y-m-d H:i:s]: ') . $msg . PHP_EOL, FILE_APPEND);
    return $msg;
}

//验证码
    

//获取验证码
function traum_captcha_get() {
    session_start();
    $traum_captcha = new Traum_captcha();
    $traum_captcha_type = $_GET['traum_captcha_type'];
    $traum_captcha -> $traum_captcha_type();
    //echo $traum_captcha_type;
    exit;
}

//检验输入的验证码是否正确
function Traum_Captcha_question_validate() {

    /*
    eregi('[0-9]', $str) //数字
eregi('[a-zA-Z]', $str)//英文
*/
session_start();
    if (!empty($_POST['vcode1'])) {
        $vcode = $_POST['vcode1'];

    } else if (!empty($_POST['vcode2'])) {
        $vcode = $_POST['vcode2'];

    } else if (!empty($_POST['vcode3'])) {
        $vcode = $_POST['vcode3'];

    }

    //exit($vcode);
    
    $regex = '/^[0-9a-zA-Z]+$/i';
    if (!preg_match($regex, $vcode)) {
        exit('非法字符'.$_SESSION['Checknum']);
    }

    if ($vcode != $_SESSION['Checknum']) {
        exit('<strong>错误</strong>: 您的回答不正确。'.$_SESSION['Checknum']);

    }
}


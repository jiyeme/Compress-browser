<?php
//DNS代理服务
$dns = array();
$dns['example'] = '127.0.0.1';




/*

//当该DNS数据与用户自定义DNS冲突时，优先使用用户设置的DNS
//当然，也可以利用该功能实现广告、非法域名屏蔽
//支持非IP的DNS哦！类似于域名管理里的别名绑定
//对IP代理同样有效

三种格式：
第一种：完整域名DNS
	例如：  jiuwap.cn'=>'127.0.0.1',

第二种：一级泛解析
	例如:	[*].jiuwap.cn    则 xx.jiuwap.cn 有效，而 xx.xx.jiuwap.cn无效

第三种：多级泛解析
	例如：	*.jiuwap.cn		则 xx.jiuwap.cn 和 xx.xx.jiuwap.cn 均有效


*/
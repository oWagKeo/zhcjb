<?php
return array(
	//'配置项'=>'配置值'
	'LAYOUT_ON' => true, //开启
    'LAYOUT_NAME' => 'layout', //命名
    'DEFAULT_TIMEZONE'=>'PRC',
	
	'TMPL_PARSE_STRING'  =>array(
		'__JS__' => __ROOT__ .'/Public/js',
		'__CSS__'=> __ROOT__ .'/Public/css',
		'__IMG__'=> __ROOT__ .'/Public/img',
	),
);
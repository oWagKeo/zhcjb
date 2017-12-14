<?php
return [
    'user' => [
        'icon' => 'am-icon-child',
        'name' => '用户管理',
        'menu' => [
            ['user_list','用户列表',U('Index/user_list'),'am-icon-user'],
            ['help','帮助文档',U('Index/help'),'am-icon-user'],
        ]
    ],
    'goods' => [
        'icon' => 'am-icon-street-view',
        'name' => '优惠劵管理',
        'menu' => [
            ['goods_list','优惠劵列表',U('Index/goods_list'),'am-icon-medium'],
            ['goods_add','添加优惠劵',U('Index/goods_add'),'am-icon-plus'],
        ]
    ],
    'lottery' => [
        'icon' => 'am-icon-street-view',
        'name' => '抽奖设置',
        'menu' => [
            ['award_list','奖品列表',U('Index/award_list'),'am-icon-medium'],
            ['award_add','添加奖品',U('Index/award_add'),'am-icon-plus'],
//            ['award','设置抽奖概率',U('Index/award'),'am-icon-location-arrow'],
//            ['pool','设置奖池',U('Index/pool'),'am-icon-medium'],
//            ['overplus','剩余奖品',U('Index/overplus'),'am-icon-plus'],
            ['award_log','中奖纪录',U('Index/award_log'),'am-icon-plus'],
        ]
    ],
    'account' => [
        'icon' => 'am-icon-rmb',
        'name' => '数据统计',
        'menu' => [
            ['user_count','用户统计',U('Index/user_count'),'am-icon-line-chart'],
            ['exchange_count','兑换统计',U('Index/exchange_count'),'am-icon-line-chart'],
            ['exchange_pie','兑换比例',U('Index/exchange_pie'),'am-icon-pie-chart'],
            ['log','兑换记录',U('Index/log'),'am-icon-puzzle-piece']
        ],
    ],
];
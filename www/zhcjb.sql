/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : ycsh

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-12-14 11:26:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for g_3146_admin
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_admin`;
CREATE TABLE `g_3146_admin` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `add_time` bigint(11) NOT NULL DEFAULT '0',
  `last` bigint(11) DEFAULT '0',
  `login` bigint(11) DEFAULT NULL,
  `last_ip` varchar(15) DEFAULT '',
  `role_id` smallint(5) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_admin
-- ----------------------------
INSERT INTO `g_3146_admin` VALUES ('1', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1479550165', '1513146716', '1513146930', '0.0.0.0', '1', '18383113133');

-- ----------------------------
-- Table structure for g_3146_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_admin_log`;
CREATE TABLE `g_3146_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_admin_log
-- ----------------------------
INSERT INTO `g_3146_admin_log` VALUES ('1', '1511759882', '1', 'admin登录系统', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('2', '1511920579', '1', 'admin登录系统', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('3', '1512977407', '0', '尝试登录系统(账户:admin,密码:123456)', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('4', '1512977442', '1', 'admin登录系统', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('5', '1513146709', '0', '尝试登录系统(账户:admin,密码:123456)', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('6', '1513146716', '1', 'admin登录系统', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('7', '1513146916', '1', '修改密码', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('8', '1513146922', '1', '退出系统', '127.0.0.1');
INSERT INTO `g_3146_admin_log` VALUES ('9', '1513146930', '1', 'admin登录系统', '127.0.0.1');

-- ----------------------------
-- Table structure for g_3146_award
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_award`;
CREATE TABLE `g_3146_award` (
  `id` bigint(9) unsigned NOT NULL AUTO_INCREMENT,
  `awardid` int(10) unsigned DEFAULT NULL,
  `awardname` varchar(30) NOT NULL,
  `awardthum1` varchar(100) NOT NULL COMMENT '缩略图1',
  `awardthum2` varchar(100) NOT NULL COMMENT '缩略图2',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
  `chance` int(10) NOT NULL DEFAULT '0' COMMENT '中奖概率',
  `desc` varchar(200) DEFAULT NULL COMMENT '描述',
  `awardinfo` text COMMENT '详情',
  `awardthum3` varchar(100) DEFAULT NULL COMMENT '缩略图3',
  `awardurl` varchar(255) DEFAULT NULL COMMENT '优惠券链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_award
-- ----------------------------
INSERT INTO `g_3146_award` VALUES ('1', '330', '摩拜', '/Public/img/award/5a095c2696acd.png', '/Public/img/award/5a095c269729e.png', '20000', '1000', '摩拜单车', '<p>1、点击“免费领取”按钮即可获得1张月卡，免费骑行30天，不限次数。本活动领取月卡最后期限是9月30日。<br />\r\n2、免费骑行天数从领取当天开始计算，至免费骑行截止日的24时结束。<br />\r\n3、免费领取的月卡使用期间若退押金，月卡随即失效，无法继续享受免费骑行权益，且30天内仅可领取1次，无法重复领取。<br />\r\n4、每次行程前2小时免费，超出时间按照正常计费规则收费。</p>\r\n', '/Public/img/award/5a0a9552c9315.png', null);
INSERT INTO `g_3146_award` VALUES ('2', '325', '猎票', '/Public/img/award/5a094b1e85bc8.png', '/Public/img/award/5a094b1e86780.png', '9999', '1000', '满100减20，全品通用', '<p>券码使用：关注我们公众号：趣宝网活动号，发送代金券三字领取满100减20元代金券，购买产品可以直接使用，全品类</p>\r\n', '/Public/img/award/5a0a95af2a537.png', null);
INSERT INTO `g_3146_award` VALUES ('3', '325', '木鸟', '/Public/img/award/5a094b76984cd.png', '/Public/img/award/5a0a5cdd57eb3.png', '10000', '1000', '兑换优惠券礼包', '<p>1、优惠券自领取之日起三个月有效<br />\r\n2、使用木鸟短租客户端预定房源即可使用优惠券</p>', '/Public/img/award/5a0a957ada991.png', null);
INSERT INTO `g_3146_award` VALUES ('4', '316', '唯品会', '/Public/img/award/5a094c684d0fd.png', '/Public/img/award/5a095f35395b1.png', '19999', '1000', '新客券240减20', '<p>1、活动时间：2017年9月13日00:00-2017年9月30日23:59；<br />\r\n2、活动期间，用户通过活动页面领取一张30元优惠券，新老用户均可领取，新用户（无历史下单记录）可领取1张满500减30元券，老用户可领取1张满800减30元券；<br />\r\n3、优惠券适用于自营全场及唯品国际（供应商直发商品和部分特定商品除外）；<br />\r\n4、优惠券请在2017年9月30日23:59前激活，激活后7天内使用有效；优惠券均不退换、不折现；同一订单只可以使用一张优惠券，不与其他优惠券及口令同时使用；<br />\r\n5、活动期间，每个用户可在本活动页面领取一张优惠券；红包数量有限，先到先得，领完为止；<br />\r\n6、若同一个手机号同时绑定或注册了两个及以上唯品会账号，优惠券将无法到账；领券时请确保手机号只注册和绑定一个唯品会账号；<br />\r\n7、在本次活动期间，如用户存在违规行为（包括但不限于刷量、恶意套取红包、机器作弊），唯品会有权取消用户参与资格、作废所领优惠券等；<br />\r\n8、若因系统故障或其他不可抗力，唯品会有权调整活动或取消活动，唯品会再法律允许的范围内可对本活动进行解释。如有问题请联系唯品会客服400-6789-888.</p>\r\n', '/Public/img/award/5a0a9583994a5.png', null);
INSERT INTO `g_3146_award` VALUES ('5', '331', '携程', '/Public/img/award/5a094e0b424f8.png', '/Public/img/award/5a094e0b42cc8.png', '9996', '1000', '旅游礼包', '<p>1、活动时间即日起至2017年9月30日；<br />\r\n2、每位用户（同一用户名、手机号、设备号、订单联系人、同一支付银行卡号均为同一用户）限领取一次，数量有限，先到先得；<br />\r\n3、未注册过携程旅行网的用户，手机号领取优惠券同时注册为携程账户；<br />\r\n4、优惠券仅限在携程旅行客户端使用；优惠券有效期领取后30天内有效；<br />\r\n5、优惠券详细使用规则可至携程旅行客户端“我的-优惠券”中查看；<br />\r\n6、9.5折海外租车折扣券最高可抵扣500。<br />\r\n7、对于以不正当方式参与活动的用户，包括但不限于恶意套现，恶意下单，恶意注册，利用程序漏洞等，携程有权取消对应权益的领取资格；<br />\r\n8、在法律允许的范围内，携程旅行网可能对活动的规则/条款作出适当修改/调整。</p>\r\n', '/Public/img/award/5a0a958bc2aa0.png', null);
INSERT INTO `g_3146_award` VALUES ('6', '319', 'ofo', '/Public/img/award/5a094f1027312.png', '/Public/img/award/5a094f1027eca.png', '9998', '2000', '共享单车', '<p>1、每张月卡30天有效期，从获得日开始计时，本卡只可获得一次；获得多张时自动累加，最高累计100天<br />\r\n2、每天免费骑行20次，时长限2小时内免费，超出时长正常计费<br />\r\n3、退押金后无法继续骑车，已购买的月卡有效期不变；重新缴纳押金后，月卡权益后，月卡权益立即恢复<br />\r\n4、购买或领取的月卡可在最新版本客户端，个人中心-我的钱包-卡包中查看（更新app至2.2.1及以上版本）</p>\r\n', '/Public/img/award/5a0a959432fb9.png', null);

-- ----------------------------
-- Table structure for g_3146_card
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_card`;
CREATE TABLE `g_3146_card` (
  `id` bigint(8) DEFAULT NULL,
  `card` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `score` smallint(4) DEFAULT NULL,
  `lottery` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_card
-- ----------------------------

-- ----------------------------
-- Table structure for g_3146_exchange
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_exchange`;
CREATE TABLE `g_3146_exchange` (
  `e_id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `e_uid` int(9) unsigned DEFAULT NULL COMMENT 'uid',
  `e_gid` int(9) DEFAULT NULL COMMENT 'gid',
  `e_price` int(9) DEFAULT NULL COMMENT '兑换价格',
  `e_more` varchar(50) DEFAULT NULL COMMENT '备用',
  `e_use` tinyint(1) DEFAULT NULL COMMENT '是否使用',
  `e_create` bigint(11) NOT NULL COMMENT '兑换时间',
  `e_usetime` bigint(11) DEFAULT NULL COMMENT '使用时间',
  `e_password` varchar(100) DEFAULT NULL,
  `e_link` varchar(500) DEFAULT NULL,
  `e_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`e_id`),
  KEY `l_uId` (`e_uid`),
  KEY `eventId` (`e_gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_exchange
-- ----------------------------

-- ----------------------------
-- Table structure for g_3146_goods
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_goods`;
CREATE TABLE `g_3146_goods` (
  `g_id` bigint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `g_name` varchar(50) DEFAULT NULL COMMENT '名称',
  `g_desc` varchar(200) DEFAULT NULL COMMENT '描述',
  `g_thum` varchar(100) DEFAULT NULL COMMENT '缩略图',
  `g_info` text COMMENT '描述',
  `g_type` int(8) DEFAULT NULL,
  `g_num` int(9) DEFAULT NULL COMMENT '数量',
  `g_count` int(9) DEFAULT NULL COMMENT '余量',
  `g_price` int(9) NOT NULL DEFAULT '0' COMMENT '兑换所需积分',
  `g_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上下架1上架0下架',
  `g_start` bigint(10) DEFAULT NULL,
  `g_end` bigint(10) DEFAULT NULL,
  `g_create` bigint(10) DEFAULT NULL COMMENT '创建时间',
  `g_term` varchar(100) DEFAULT NULL,
  `g_usetype` tinyint(1) NOT NULL DEFAULT '0',
  `g_couponid` int(8) NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `g_free` bigint(9) DEFAULT '0',
  `g_claim` bigint(8) NOT NULL DEFAULT '0' COMMENT '限制领取奖券数（-1）为不限制',
  `g_url` varchar(255) DEFAULT NULL COMMENT '优惠券链接',
  PRIMARY KEY (`g_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_goods
-- ----------------------------
INSERT INTO `g_3146_goods` VALUES ('1', '唯品会', '新客券240减20', '/Public/img/goods/224922dkjga9ldwtkd4t0a.png', '<p>1、活动时间：2017年9月13日00:00-2017年9月30日23:59；<br />\r\n2、活动期间，用户通过活动页面领取一张30元优惠券，新老用户均可领取，新用户（无历史下单记录）可领取1张满500减30元券，老用户可领取1张满800减30元券；<br />\r\n3、优惠券适用于自营全场及唯品国际（供应商直发商品和部分特定商品除外）；<br />\r\n4、优惠券请在2017年9月30日23:59前激活，激活后7天内使用有效；优惠券均不退换、不折现；同一订单只可以使用一张优惠券，不与其他优惠券及口令同时使用；<br />\r\n5、活动期间，每个用户可在本活动页面领取一张优惠券；红包数量有限，先到先得，领完为止；<br />\r\n6、若同一个手机号同时绑定或注册了两个及以上唯品会账号，优惠券将无法到账；领券时请确保手机号只注册和绑定一个唯品会账号；<br />\r\n7、在本次活动期间，如用户存在违规行为（包括但不限于刷量、恶意套取红包、机器作弊），唯品会有权取消用户参与资格、作废所领优惠券等；<br />\r\n8、若因系统故障或其他不可抗力，唯品会有权调整活动或取消活动，唯品会再法律允许的范围内可对本活动进行解释。如有问题请联系唯品会客服400-6789-888.</p>\r\n', '6', '199920', '199905', '0', '1', '1505318400', '1530288000', '1506335658', '长期', '0', '316', null, '-1', '');
INSERT INTO `g_3146_goods` VALUES ('2', '小红书', '满减优惠券199-16', '/Public/img/goods/Cgo8PFWcoTKARPNOAABLnbTivc8708.png', '<p>【66元现金券介绍】<br />\r\n小红书，国内第一的移动社区跨境电商平台，6000万用户口碑推荐，带你发现全世界的好东西。</p>\r\n\r\n<p>现金券券含：<br />\r\n【新用户】新用户可领取16元无门槛券；299减20，399减30，福利社内使用。<br />\r\n【老用户】老用户可享受299减16，399减20，499减30优惠，福利社内使用</p>\r\n\r\n<p>【使用规则】<br />\r\n此券请在小红书app内使用，单张薯券只能使用一次，每个订单只能使用一张薯券；不可与其他优惠同享，包括限量价、限时特价、限时n折、任选优惠以及第二件n折等促销活动<br />\r\n有效期：券领取后保存账户7天，请在有效期内使用，过期无效此券不兑换现金，不设找零，不可累计使用<br />\r\n最终解释权归小红书所有</p>\r\n', '6', '199967', '199980', '0', '1', '1505318400', '1522166400', '1506335658', '长期', '0', '317', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('3', 'ofo共享单车', '免费骑行月卡30天', '/Public/img/goods/ofo.png', '<p>1、每张月卡30天有效期，从获得日开始计时，本卡只可获得一次；获得多张时自动累加，最高累计100天<br />\r\n2、每天免费骑行20次，时长限2小时内免费，超出时长正常计费<br />\r\n3、退押金后无法继续骑车，已购买的月卡有效期不变；重新缴纳押金后，月卡权益后，月卡权益立即恢复<br />\r\n4、购买或领取的月卡可在最新版本客户端，个人中心-我的钱包-卡包中查看（更新app至2.2.1及以上版本）</p>\r\n', '3', '199974', '199968', '0', '1', '1505318400', '1519833600', '1506335658', '长期', '0', '319', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('4', '途牛旅游', '旅游VIP礼包', '/Public/img/goods/tuniu.png', '<p>1、畅游卡领取时间截止2017年12月31日23:59:59，机票优惠券限领取后30天内使用；其余五款优惠券领取后使用有效期均截止今年年底；具体有效期以途牛旅游APP中<br />\r\n截止日期为准，过期失效。<br />\r\n2、该优惠可用于途牛旅游APP-机票&酒店&国内跟团游&出境游产品预定。详细规则请至“途牛旅游APP-我的-优惠券”中查看；<br />\r\n3、一个手机账户只可参与一次活动，且一次性使用，不折分，不转赠，不提现，不与其他优惠券红包同时使用；<br />\r\n4、若发现参加活动用户存在不正当方式（包括但不限于恶意套现、机器作弊等），途牛旅游网有权在不事先通知情况下禁止其参与活动，取消优惠券使用资格并收回；<br />\r\n5、参与活动订单若产生自愿退票，则不退还优惠券，优惠金额收回，优惠金额优先从税费中抵扣，抵扣不完再从票价中扣除；<br />\r\n6、在法律允许的范围内，途牛旅游网可能对活动的规则/条款作出适当修改或调整。</p>\r\n', '3', '199995', '199996', '0', '1', '1505318400', '1519747200', '1506335658', '长期', '0', '320', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('5', '同程旅游', '景区券', '/Public/img/goods/MU7SQIZONVT8.png', '', '3', '199990', '199989', '0', '1', '1505318400', '1525017600', '1506335658', '长期', '0', '321', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('7', 'QQ阅读', 'QQ阅读券', '/Public/img/goods/494353.png', '<p>1、用户凭激活码至http://code.book.qq.com/?ch=10021857进行激活兑换。<br />\r\n2、请于2017年8月31日前进行激活，逾期将作废。<br />\r\n3、激活成功后，阅读券将发放至用户账户，下载QQ阅读即可使用。<br />\r\n4、阅读券可用于QQ阅读内书籍订阅购买。<br />\r\n5、阅读券有效期为30天，请尽快使用，以免过期作废。</p>\r\n', '1', '5000', '5000', '0', '1', '1505404800', '1521561600', '1506335658', '长期', '0', '323', null, '-1', '');
INSERT INTO `g_3146_goods` VALUES ('8', '木鸟短租', '兑换优惠券礼包', '/Public/img/goods/122222908.png', '<p>1、优惠券自领取之日起三个月有效<br />\r\n2、使用木鸟短租客户端预定房源即可使用优惠券</p>\r\n', '2', '4998', '4999', '0', '1', '1505404800', '1522425600', '1506335658', '长期', '0', '325', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('9', '顺丰快递', '优惠券，新客和老客', '/Public/img/goods/1431322678471.png', '<p>活动期间，客户扫描指定二维码注册成为顺丰会员即可随机获得2-5元寄件优惠券（优惠券可立即使用），顺丰老会员进入活动页面即可获得100会员积分，<br />\r\n另分享推荐好友成功注册成为顺丰会员（限新会员）双方均可随机获得寄件优惠券，每人每天限推荐20人。<br />\r\n1、用户可通过微信平台、线下扫码进入活动页面参与活动。<br />\r\n2、活动所获得的优惠券，均不会发短信提醒，用户可在顺丰速运微信服务号-“我”-“我的钱包”-“优惠券”中查看。<br />\r\n3、活动所获得的会员积分，用户可在“顺丰速运”微信服务号-》“我”-》“会员福利”中查看。<br />\r\n4、电子优惠券均有使用期限，请在有效期截止前使用，否则自动失效。详情参考优惠券使用规则。<br />\r\n5、同一微信号、同一手机号，满足前述任一条件均视为同一用户。<br />\r\n6、针对恶意刷奖的用户，顺丰速运有权取消其活动资格，对于使用非正常手段刷奖行为，我司有权追究其法律责任及追回相关损失。<br />\r\n7、在法律允许的前提下，活动最终解释权归顺丰速运有限公司所有。</p>\r\n', '3', '4990', '4988', '0', '1', '1505404800', '1522425600', '1506335658', '长期', '0', '326', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('10', '百度外卖', '新用户满15减10，老用户满30减3', '/Public/img/goods/baidu.png', '<p>1、下载百度外卖App，用手机号注册或将手机号绑定至已注册的百度外卖账户后方可使用；<br />\r\n2、使用优惠时的下单手机号需与领取优惠时的手机号一致，领取后在有效期内使用；<br />\r\n3、仅限在线支付时使用，每个订单只能用一张券，不能与其他券叠加使用，且不找零；<br />\r\n4、在法律法规许可的范围内，百度外卖对本活动拥有解释权。如有问题，请联系百度外卖客服：10105777.</p>\r\n', '4', '4969', '4945', '0', '1', '1505404800', '1514649600', '1506335658', '长期', '0', '324', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('11', '趣宝网', '满100减20，全品通用', '/Public/img/goods/t01ea3fbb89341b602f.png', '<p>券码使用：关注我们公众号：趣宝网活动号，发送代金券三字领取满100减20元代金券，购买产品可以直接使用，全品类</p>\r\n', '6', '4996', '5000', '0', '1', '1505664000', '1514649600', '1506335658', '长期可用', '0', '327', '0', '1', '');
INSERT INTO `g_3146_goods` VALUES ('12', '当妈啦', '全商场通用券', '/Public/img/goods/dangmala.png', '<p>应用商店下载“当妈啦”APP</p>\r\n', '6', '4993', '5000', '0', '1', '1505664000', '1511971200', '1506335658', '长期可用', '0', '328', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('13', '成都好妈妈', '满99减20，满199减50，满299减100', '/Public/img/goods/ec3764a8be164d5.png', '<p>1、下载童城之星App进行领取。</p>\r\n', '6', '4993', '5000', '0', '1', '1505664000', '1511971200', '1506335658', '长期可用', '0', '329', '0', '-1', '');
INSERT INTO `g_3146_goods` VALUES ('14', '摩拜单车', '免费骑行月卡30天', '/Public/img/goods/mobike.png', '<p>1、点击“免费领取”按钮即可获得1张月卡，免费骑行30天，不限次数。本活动领取月卡最后期限是9月30日。<br />\r\n2、免费骑行天数从领取当天开始计算，至免费骑行截止日的24时结束。<br />\r\n3、免费领取的月卡使用期间若退押金，月卡随即失效，无法继续享受免费骑行权益，且30天内仅可领取1次，无法重复领取。<br />\r\n4、每次行程前2小时免费，超出时间按照正常计费规则收费。</p>\r\n', '5', '4999', '4999', '0', '1', '1505664000', '1514649600', '1506335658', '长期可用', '0', '330', null, '1', '');
INSERT INTO `g_3146_goods` VALUES ('15', '携程优惠券', '旅游礼包', '/Public/img/goods/xiechen.png', '<p>1、活动时间即日起至2017年9月30日；<br />\r\n2、每位用户（同一用户名、手机号、设备号、订单联系人、同一支付银行卡号均为同一用户）限领取一次，数量有限，先到先得；<br />\r\n3、未注册过携程旅行网的用户，手机号领取优惠券同时注册为携程账户；<br />\r\n4、优惠券仅限在携程旅行客户端使用；优惠券有效期领取后30天内有效；<br />\r\n5、优惠券详细使用规则可至携程旅行客户端“我的-优惠券”中查看；<br />\r\n6、9.5折海外租车折扣券最高可抵扣500。<br />\r\n7、对于以不正当方式参与活动的用户，包括但不限于恶意套现，恶意下单，恶意注册，利用程序漏洞等，携程有权取消对应权益的领取资格；<br />\r\n8、在法律允许的范围内，携程旅行网可能对活动的规则/条款作出适当修改/调整。</p>\r\n', '5', '4992', '4998', '0', '1', '1505664000', '1514649600', '1506335658', '长期可用', '0', '331', null, '1', '');
INSERT INTO `g_3146_goods` VALUES ('16', '猎票网', '门票兑换优惠券', '/Public/img/goods/liepiao.png', '<p>1.应用商店搜索下载：猎票app<br />\r\n2.注册／登录后在“兑换码兑换”处输入兑换码；<br />\r\n3.兑换成功后，代金券可前往“我的礼券”查看；<br />\r\n4.每个兑换码可兑换一张代金券；<br />\r\n5.兑换码不挂失、不找零、不兑换现金，不再开具发票；<br />\r\n6.如有任何疑问，请您咨询在线客服或拨打客服热线400-919-6667。</p>\r\n', '2', '4999', '4998', '0', '1', '1505664000', '1514649600', '1506335658', '长期可用', '0', '332', '0', '-1', '');

-- ----------------------------
-- Table structure for g_3146_log
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_log`;
CREATE TABLE `g_3146_log` (
  `l_id` bigint(9) unsigned NOT NULL AUTO_INCREMENT,
  `l_gid` bigint(9) unsigned DEFAULT NULL COMMENT 'gId',
  `l_uid` bigint(9) unsigned DEFAULT NULL COMMENT 'uId',
  `eventid` varchar(100) DEFAULT NULL,
  `value` varchar(500) DEFAULT '0',
  `l_score` decimal(9,2) DEFAULT '0.00',
  `l_more` varchar(500) DEFAULT '0',
  `l_updated` varchar(10) NOT NULL,
  PRIMARY KEY (`l_id`),
  KEY `l_gId` (`l_gid`),
  KEY `l_uId` (`l_uid`),
  KEY `eventId` (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_log
-- ----------------------------

-- ----------------------------
-- Table structure for g_3146_lottery
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_lottery`;
CREATE TABLE `g_3146_lottery` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `lottery` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_lottery
-- ----------------------------
INSERT INTO `g_3146_lottery` VALUES ('1', '[{\"min\":0,\"max\":\"1000\",\"id\":\"1\",\"award\":\"\\u6469\\u62dc\",\"b\":\"1000\"},{\"min\":\"1000\",\"max\":2000,\"id\":\"2\",\"award\":\"\\u730e\\u7968\",\"b\":\"1000\"},{\"min\":2000,\"max\":3000,\"id\":\"3\",\"award\":\"\\u6728\\u9e1f\",\"b\":\"1000\"},{\"min\":3000,\"max\":4000,\"id\":\"4\",\"award\":\"\\u552f\\u54c1\\u4f1a\",\"b\":\"1000\"},{\"min\":4000,\"max\":5000,\"id\":\"5\",\"award\":\"\\u643a\\u7a0b\",\"b\":\"1000\"},{\"min\":5000,\"max\":7000,\"id\":\"6\",\"award\":\"ofo\",\"b\":\"2000\"}]');

-- ----------------------------
-- Table structure for g_3146_user
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_user`;
CREATE TABLE `g_3146_user` (
  `u_id` int(9) unsigned NOT NULL AUTO_INCREMENT COMMENT 'uid',
  `u_openid` varchar(200) NOT NULL DEFAULT '0' COMMENT 'openid',
  `u_nick` varchar(100) DEFAULT NULL COMMENT '昵称',
  `u_headimg` varchar(200) DEFAULT '0' COMMENT '头像',
  `u_name` varchar(40) DEFAULT NULL COMMENT '姓名',
  `u_phone` varchar(20) DEFAULT NULL COMMENT '电话',
  `u_cardno` varchar(50) DEFAULT '0' COMMENT '卡号',
  `u_score` int(9) DEFAULT '100' COMMENT '积分',
  `u_more` varchar(10) DEFAULT NULL COMMENT '备注',
  `u_create` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `u_update` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `u_lottery` bigint(10) DEFAULT '50',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_user
-- ----------------------------
INSERT INTO `g_3146_user` VALUES ('1', '0', '15828230592', '0', '15828230592', '15828230592', '0', '100', null, '1511769365', '1511769365', '50');

-- ----------------------------
-- Table structure for g_3146_weinner
-- ----------------------------
DROP TABLE IF EXISTS `g_3146_weinner`;
CREATE TABLE `g_3146_weinner` (
  `id` bigint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(8) unsigned NOT NULL,
  `awardid` tinyint(2) unsigned NOT NULL,
  `awardname` varchar(100) NOT NULL,
  `got` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否兑奖',
  `onlyKey` varchar(50) NOT NULL,
  `updated` varchar(15) NOT NULL,
  `gid` int(10) DEFAULT NULL,
  `e_password` varchar(500) DEFAULT NULL,
  `e_link` varchar(500) DEFAULT NULL,
  `e_code` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `awardid` (`awardid`),
  KEY `updated` (`updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of g_3146_weinner
-- ----------------------------

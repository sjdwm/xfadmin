/*
Navicat MySQL Data Transfer

Source Server         : localhost2
Source Server Version : 50648
Source Host           : localhost:3306
Source Database       : xfadmin

Target Server Type    : MYSQL
Target Server Version : 50648
File Encoding         : 65001

Date: 2023-11-15 15:08:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xf_article
-- ----------------------------
DROP TABLE IF EXISTS `xf_article`;
CREATE TABLE `xf_article` (
  `aid` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL COMMENT '分类id',
  `uid` int(10) NOT NULL COMMENT '作者',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  `description` varchar(255) NOT NULL COMMENT '摘要',
  `thumbnail` varchar(255) NOT NULL COMMENT '缩略图',
  `content` text NOT NULL COMMENT '内容',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文章是否显示 1是 0否',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除 1是 0否',
  `is_top` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶 1是 0否',
  `is_s` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页 1是 0否',
  `click` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '点击数',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`aid`),
  KEY `cid` (`cid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_article
-- ----------------------------

-- ----------------------------
-- Table structure for xf_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `xf_auth_group`;
CREATE TABLE `xf_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_auth_group
-- ----------------------------
INSERT INTO `xf_auth_group` VALUES ('1', '超级管理员', '1', '1,2,58,65,59,60,61,62,3,56,4,6,5,7,8,9,10,51,52,53,57,11,54,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,63,48,49,50,55');
INSERT INTO `xf_auth_group` VALUES ('2', '管理员', '0', '1,2,58,59,60,61,62,3,56,4,6,5,7,8,9,10,51,52,53,57,11,54,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,63,48,49,50,55');
INSERT INTO `xf_auth_group` VALUES ('3', '普通用户', '1', '1');

-- ----------------------------
-- Table structure for xf_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `xf_auth_group_access`;
CREATE TABLE `xf_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_auth_group_access
-- ----------------------------
INSERT INTO `xf_auth_group_access` VALUES ('1', '1');
INSERT INTO `xf_auth_group_access` VALUES ('2', '1');

-- ----------------------------
-- Table structure for xf_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `xf_auth_rule`;
CREATE TABLE `xf_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `url` varchar(255) DEFAULT NULL COMMENT '前台菜单链接',
  `title` char(20) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `cate` int(10) unsigned DEFAULT '1' COMMENT '前台权限和后台权限区分',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `islink` tinyint(1) NOT NULL DEFAULT '1',
  `o` int(11) NOT NULL COMMENT '排序',
  `tips` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_auth_rule
-- ----------------------------
INSERT INTO `xf_auth_rule` VALUES ('1', '0', 'Admin/Index/index', null, '控制台', 'menu-icon fa fa-tachometer', '1', '1', '1', '', '1', '1', '友情提示：经常查看操作日志，发现异常以便及时追查原因。');
INSERT INTO `xf_auth_rule` VALUES ('2', '0', '', null, '系统设置', 'menu-icon fa fa-cog', '1', '1', '1', '', '1', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('3', '2', 'Admin/Setting/setting', null, '网站设置', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '3', '这是网站设置的提示。');
INSERT INTO `xf_auth_rule` VALUES ('4', '2', 'Admin/Menu/index', null, '后台菜单', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '4', '');
INSERT INTO `xf_auth_rule` VALUES ('5', '2', 'Admin/Menu/add', null, '新增菜单', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '5', '');
INSERT INTO `xf_auth_rule` VALUES ('6', '4', 'Admin/Menu/edit', null, '编辑菜单', '', '1', '1', '1', '', '0', '6', '');
INSERT INTO `xf_auth_rule` VALUES ('7', '2', 'Admin/Menu/update', null, '保存菜单', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '7', '');
INSERT INTO `xf_auth_rule` VALUES ('8', '2', 'Admin/Menu/del', null, '删除菜单', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '8', '');
INSERT INTO `xf_auth_rule` VALUES ('9', '2', 'Admin/Database/backup', null, '数据库备份', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '9', '');
INSERT INTO `xf_auth_rule` VALUES ('10', '9', 'Admin/Database/recovery', null, '数据库还原', '', '1', '1', '1', '', '0', '10', '');
INSERT INTO `xf_auth_rule` VALUES ('11', '2', 'Admin/Update/update', null, '在线升级', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '11', '');
INSERT INTO `xf_auth_rule` VALUES ('12', '2', 'Admin/Update/devlog', null, '开发日志', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '12', '');
INSERT INTO `xf_auth_rule` VALUES ('13', '0', '', null, '用户及组', 'menu-icon fa fa-users', '1', '1', '1', '', '1', '13', '');
INSERT INTO `xf_auth_rule` VALUES ('14', '13', 'Admin/Member/index', null, '用户管理', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '14', '');
INSERT INTO `xf_auth_rule` VALUES ('15', '13', 'Admin/Member/add', null, '新增用户', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '15', '');
INSERT INTO `xf_auth_rule` VALUES ('16', '13', 'Admin/Member/edit', null, '编辑用户', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '16', '');
INSERT INTO `xf_auth_rule` VALUES ('17', '13', 'Admin/Member/update', null, '保存用户', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '17', '');
INSERT INTO `xf_auth_rule` VALUES ('18', '13', 'Admin/Member/del', null, '删除用户', '', '1', '1', '1', '', '0', '18', '');
INSERT INTO `xf_auth_rule` VALUES ('19', '13', 'Admin/Group/index', null, '用户组管理', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '19', '');
INSERT INTO `xf_auth_rule` VALUES ('20', '13', 'Admin/Group/add', null, '新增用户组', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '20', '');
INSERT INTO `xf_auth_rule` VALUES ('21', '13', 'Admin/Group/edit', null, '编辑用户组', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '21', '');
INSERT INTO `xf_auth_rule` VALUES ('22', '13', 'Admin/Group/update', null, '保存用户组', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '22', '');
INSERT INTO `xf_auth_rule` VALUES ('23', '13', 'Admin/Group/del', null, '删除用户组', '', '1', '1', '1', '', '0', '23', '');
INSERT INTO `xf_auth_rule` VALUES ('24', '0', '', null, '网站内容', 'menu-icon fa fa-desktop', '1', '1', '1', '', '1', '24', '');
INSERT INTO `xf_auth_rule` VALUES ('25', '24', 'Admin/Article/index', null, '文章管理', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '25', '网站虽然重要，身体更重要，出去走走吧。');
INSERT INTO `xf_auth_rule` VALUES ('26', '24', 'Admin/Article/add', null, '新增文章', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '26', '');
INSERT INTO `xf_auth_rule` VALUES ('27', '24', 'Admin/Article/edit', null, '编辑文章', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '27', '');
INSERT INTO `xf_auth_rule` VALUES ('29', '24', 'Admin/Article/update', null, '保存文章', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '29', '');
INSERT INTO `xf_auth_rule` VALUES ('30', '24', 'Admin/Article/del', null, '删除文章', '', '1', '1', '1', '', '0', '30', '');
INSERT INTO `xf_auth_rule` VALUES ('31', '24', 'Admin/Category/index', null, '分类管理', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '31', '');
INSERT INTO `xf_auth_rule` VALUES ('32', '24', 'Admin/Category/add', null, '新增分类', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '32', '');
INSERT INTO `xf_auth_rule` VALUES ('33', '24', 'Admin/Category/edit', null, '编辑分类', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '33', '');
INSERT INTO `xf_auth_rule` VALUES ('34', '24', 'Admin/Category/update', null, '保存分类', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '34', '');
INSERT INTO `xf_auth_rule` VALUES ('36', '24', 'Admin/Category/del', null, '删除分类', '', '1', '1', '1', '', '0', '36', '');
INSERT INTO `xf_auth_rule` VALUES ('37', '0', '', null, '其它功能', 'menu-icon fa fa-legal', '1', '1', '1', '', '1', '37', '');
INSERT INTO `xf_auth_rule` VALUES ('38', '37', 'Admin/Link/index', null, '友情链接', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '38', '');
INSERT INTO `xf_auth_rule` VALUES ('39', '37', 'Admin/Link/add', null, '增加链接', '', '1', '1', '1', '', '1', '39', '');
INSERT INTO `xf_auth_rule` VALUES ('40', '37', 'Admin/Link/edit', null, '编辑链接', '', '1', '1', '1', '', '0', '40', '');
INSERT INTO `xf_auth_rule` VALUES ('41', '37', 'Admin/Link/update', null, '保存链接', '', '1', '1', '1', '', '0', '41', '');
INSERT INTO `xf_auth_rule` VALUES ('42', '37', 'Admin/Link/del', null, '删除链接', '', '1', '1', '1', '', '0', '42', '');
INSERT INTO `xf_auth_rule` VALUES ('43', '37', 'Admin/Flash/index', null, '焦点图', 'menu-icon fa fa-desktop', '1', '1', '1', '', '0', '43', '');
INSERT INTO `xf_auth_rule` VALUES ('44', '37', 'Admin/Flash/add', null, '新增焦点图', '', '1', '1', '1', '', '0', '44', '');
INSERT INTO `xf_auth_rule` VALUES ('45', '37', 'Admin/Flash/update', null, '保存焦点图', '', '1', '1', '1', '', '0', '45', '');
INSERT INTO `xf_auth_rule` VALUES ('46', '37', 'Admin/Flash/edit', null, '编辑焦点图', '', '1', '1', '1', '', '0', '46', '');
INSERT INTO `xf_auth_rule` VALUES ('47', '37', 'Admin/Flash/del', null, '删除焦点图', '', '1', '1', '1', '', '0', '47', '');
INSERT INTO `xf_auth_rule` VALUES ('48', '0', '', null, '个人中心', 'menu-icon fa fa-user', '1', '1', '1', '', '1', '48', '');
INSERT INTO `xf_auth_rule` VALUES ('49', '48', 'Admin/Personal/profile', null, '个人资料', 'menu-icon fa fa-user', '1', '1', '1', '', '0', '49', '');
INSERT INTO `xf_auth_rule` VALUES ('50', '48', 'Admin/Login/logout', null, '退出', '', '1', '1', '1', '', '1', '50', '');
INSERT INTO `xf_auth_rule` VALUES ('51', '9', 'Admin/Database/export', null, '备份', '', '1', '1', '1', '', '0', '51', '');
INSERT INTO `xf_auth_rule` VALUES ('52', '9', 'Admin/Database/optimize', null, '数据优化', '', '1', '1', '1', '', '0', '52', '');
INSERT INTO `xf_auth_rule` VALUES ('53', '9', 'Admin/Database/repair', null, '修复表', '', '1', '1', '1', '', '0', '53', '');
INSERT INTO `xf_auth_rule` VALUES ('54', '11', 'Admin/Update/updating', null, '升级安装', '', '1', '1', '1', '', '0', '54', '');
INSERT INTO `xf_auth_rule` VALUES ('55', '48', 'Admin/Personal/update', null, '资料保存', '', '1', '1', '1', '', '0', '55', '');
INSERT INTO `xf_auth_rule` VALUES ('56', '3', 'Admin/Setting/update', null, '设置保存', '', '1', '1', '1', '', '0', '56', '');
INSERT INTO `xf_auth_rule` VALUES ('57', '9', 'Admin/Database/del', null, '备份删除', '', '1', '1', '1', '', '0', '57', '');
INSERT INTO `xf_auth_rule` VALUES ('58', '2', 'Admin/variable/index', null, '自定义变量', '', '1', '1', '1', '', '1', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('59', '58', 'Admin/variable/add', null, '新增变量', '', '1', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('60', '58', 'Admin/variable/edit', null, '编辑变量', '', '1', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('61', '58', 'Admin/variable/update', null, '保存变量', '', '1', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('62', '58', 'Admin/variable/del', null, '删除变量', '', '1', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('64', '13', 'Admin/Group/auth', null, '后台权限', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '25', '');
INSERT INTO `xf_auth_rule` VALUES ('65', '13', 'Admin/Group/homeauth', null, '前台权限', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '26', '');
INSERT INTO `xf_auth_rule` VALUES ('66', '13', 'Admin/Member/show', null, '查看用户信息', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '18', '');
INSERT INTO `xf_auth_rule` VALUES ('67', '19', 'Admin/Group/usergroup', null, '组添加人员', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '1', '');
INSERT INTO `xf_auth_rule` VALUES ('68', '64', 'Admin/Group/authadd', null, '增加后台权限', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '1', '');
INSERT INTO `xf_auth_rule` VALUES ('69', '64', 'Admin/Group/authedit', null, '后台权限修改', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('70', '65', 'Admin/Group/homeauthadd', null, '前台权限添加', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '1', '');
INSERT INTO `xf_auth_rule` VALUES ('71', '65', 'Admin/Group/homeauthedit', null, '前台权限修改', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('72', '0', '', null, '公司管理', 'menu-icon fa fa-etsy', '1', '1', '1', '', '1', '14', '');
INSERT INTO `xf_auth_rule` VALUES ('73', '72', 'Admin/Company/index', null, '公司管理', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '1', '');
INSERT INTO `xf_auth_rule` VALUES ('74', '72', 'Admin/Company/add', null, '公司管理-添加', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('75', '72', 'Admin/Company/update', null, '公司管理-执行添加修改', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '3', '');
INSERT INTO `xf_auth_rule` VALUES ('76', '72', 'Admin/Company/edit', null, '公司管理-修改页面', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '4', '');
INSERT INTO `xf_auth_rule` VALUES ('77', '0', '', null, '日志管理', 'menu-icon fa fa-wpforms', '1', '1', '1', '', '1', '3', '');
INSERT INTO `xf_auth_rule` VALUES ('78', '77', 'Admin/Logs/user_log', null, '用户日志', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '1', '');
INSERT INTO `xf_auth_rule` VALUES ('79', '77', 'Admin/Logs/login_log', null, '访客日志', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '1', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('80', '77', 'Admin/Logs/systemlog', null, '系统日志', 'menu-icon fa fa-caret-right', '1', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('81', '48', 'Admin/Login/clear', null, '清除缓存', '', '1', '1', '1', '', '0', '51', '');

-- ----------------------------
-- Table structure for xf_category
-- ----------------------------
DROP TABLE IF EXISTS `xf_category`;
CREATE TABLE `xf_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '0正常，1单页，2外链',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 0是 1否',
  `pid` int(10) NOT NULL COMMENT '父ID',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `dir` varchar(100) NOT NULL COMMENT '目录名称',
  `seotitle` varchar(200) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `cattemplate` varchar(100) NOT NULL,
  `contemplate` varchar(100) NOT NULL,
  `o` int(10) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_category
-- ----------------------------
INSERT INTO `xf_category` VALUES ('1', '0', '0', '0', '公司新闻', '', '公司新闻', '公司新闻', '公司新闻', '', '', '', '', '1');

-- ----------------------------
-- Table structure for xf_company
-- ----------------------------
DROP TABLE IF EXISTS `xf_company`;
CREATE TABLE `xf_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT 'mid',
  `pid` int(11) NOT NULL,
  `name` char(80) DEFAULT NULL COMMENT '名称缩写',
  `cname` char(80) NOT NULL DEFAULT '' COMMENT '公司名称CN',
  `ename` char(80) NOT NULL DEFAULT '' COMMENT '公司名称EN',
  `sc_time` int(11) NOT NULL COMMENT '各地时差',
  `email` varchar(255) DEFAULT NULL COMMENT '部门主管',
  `bm` varchar(255) DEFAULT NULL COMMENT '部门代码',
  `bm_name` varchar(255) DEFAULT '' COMMENT '部门主管姓名',
  `bm_ename` varchar(255) DEFAULT '' COMMENT '部门主管姓名英文',
  `bm_post` varchar(255) DEFAULT '' COMMENT '部门主管职位',
  `bm_en_post` varchar(255) DEFAULT '' COMMENT '部门主管职位英文',
  `img` varchar(255) DEFAULT NULL COMMENT '部门主管照片',
  `address` varchar(255) DEFAULT NULL COMMENT '公司地址',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '电话号',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '公司状态',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '说明信息',
  `islink` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `o` int(11) NOT NULL COMMENT '排序',
  `tips` text NOT NULL,
  `time` int(11) NOT NULL COMMENT 'time',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_company
-- ----------------------------
INSERT INTO `xf_company` VALUES ('1', '0', '0', 'XF', '小风网络', 'XiaoFeng', '28800', '', '', '', '', '', '', '', '', '', '1', '1', '', '1', '1', '', '1699534202', '0');

-- ----------------------------
-- Table structure for xf_devlog
-- ----------------------------
DROP TABLE IF EXISTS `xf_devlog`;
CREATE TABLE `xf_devlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v` varchar(225) NOT NULL COMMENT '版本号',
  `y` int(4) NOT NULL COMMENT '年分',
  `t` int(10) NOT NULL COMMENT '发布日期',
  `log` text NOT NULL COMMENT '更新日志',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_devlog
-- ----------------------------
INSERT INTO `xf_devlog` VALUES ('1', '1.0.0', '2016', '1440259200', 'xfADMIN第一个版本发布。');
INSERT INTO `xf_devlog` VALUES ('2', '1.0.1', '2016', '1440259200', '修改cookie过于简单的安全风险。');
INSERT INTO `xf_devlog` VALUES ('3', '1.0.2', '0', '1699275963', '<li>新版本发布</li>\r\n<li>实例2</li>\r\n<li>实例3</li>\r\n            ');
INSERT INTO `xf_devlog` VALUES ('4', '1.0.3', '0', '1699276225', '<li>实例1</li>\r\n<li>实例2</li>\r\n<li>实例3</li>\r\n            ');
INSERT INTO `xf_devlog` VALUES ('6', '1.0.5', '2023', '1699276616', '<li>实例1</li>\r\n<li>实例2</li>\r\n<li>实例3</li>\r\n            ');

-- ----------------------------
-- Table structure for xf_flash
-- ----------------------------
DROP TABLE IF EXISTS `xf_flash`;
CREATE TABLE `xf_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_flash
-- ----------------------------

-- ----------------------------
-- Table structure for xf_links
-- ----------------------------
DROP TABLE IF EXISTS `xf_links`;
CREATE TABLE `xf_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_links
-- ----------------------------
INSERT INTO `xf_links` VALUES ('1', '小风博客', 'https://hotxf.com', '', '1');

-- ----------------------------
-- Table structure for xf_log
-- ----------------------------
DROP TABLE IF EXISTS `xf_log`;
CREATE TABLE `xf_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `t` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `log` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_log
-- ----------------------------
INSERT INTO `xf_log` VALUES ('1', 'admin', '1699274240', '127.0.0.1', '备份完成！');


-- ----------------------------
-- Table structure for xf_setting
-- ----------------------------
DROP TABLE IF EXISTS `xf_setting`;
CREATE TABLE `xf_setting` (
  `k` varchar(100) NOT NULL COMMENT '变量',
  `v` varchar(255) NOT NULL COMMENT '值',
  `type` tinyint(1) NOT NULL COMMENT '0系统，1自定义',
  `name` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY (`k`),
  KEY `k` (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xf_setting
-- ----------------------------
INSERT INTO `xf_setting` VALUES ('description', '网站描述', '0', '');
INSERT INTO `xf_setting` VALUES ('footer', '2017©小风网络', '0', '');
INSERT INTO `xf_setting` VALUES ('keywords', '关键词', '0', '');
INSERT INTO `xf_setting` VALUES ('sitename', '小风博客', '0', '');
INSERT INTO `xf_setting` VALUES ('test', '测试', '1', '测试变量');
INSERT INTO `xf_setting` VALUES ('title', '小风博客', '0', '');

-- ----------------------------
-- Table structure for xf_users
-- ----------------------------
DROP TABLE IF EXISTS `xf_users`;
CREATE TABLE `xf_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(64) NOT NULL COMMENT '真实姓名',
  `ename` varchar(64) NOT NULL COMMENT '英文姓名',
  `username` varchar(64) NOT NULL COMMENT '登录用户名',
  `password` varchar(128) NOT NULL COMMENT '密码',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号',
  `telphone` varchar(32) NOT NULL DEFAULT '' COMMENT '公司电话',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `userid` varchar(18) DEFAULT NULL COMMENT '身份证号',
  `job` int(10) NOT NULL COMMENT '工号',
  `mid` varchar(32) DEFAULT NULL COMMENT '公司ID',
  `gid` varchar(32) DEFAULT NULL COMMENT '部门ID',
  `post` varchar(64) DEFAULT NULL COMMENT '职位',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别 0保密  1男 2女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `address` varchar(100) DEFAULT NULL COMMENT '地址信息',
  `money` decimal(6,2) DEFAULT '0.00' COMMENT '可用金额',
  `wx_code` varchar(128) DEFAULT NULL COMMENT '微信ID',
  `reg_time` int(10) NOT NULL COMMENT '注册时间',
  `login_ip` varchar(32) NOT NULL COMMENT '登录IP',
  `login_time` int(10) NOT NULL COMMENT '登录时间',
  `lang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 英文 0 中文',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 离职 0 在职',
  `stop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 禁止 0 开启',
  `black_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间锁',
  `lock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '错误次数',
  `token` varchar(255) DEFAULT NULL COMMENT '找回密码用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户注册表';

-- ----------------------------
-- Records of xf_users
-- ----------------------------
INSERT INTO `xf_users` VALUES ('1', '小风', 'jd.she', 'admin', '4f95513c686a7c891392becdf407ba19', '', '86 021-64064227-887', 'admin@hotxf.com', '/upload/imger_user/user.png', '4211241990', '8220', '1', '1', 'Web Master', '1', '1990-05-25', '上海', '0.00', '', '1524706015', '127.0.0.1', '1699955283', '0', '0', '0', '1699341227', '0', '');

-- ----------------------------
-- Table structure for xf_user_log
-- ----------------------------
DROP TABLE IF EXISTS `xf_user_log`;
CREATE TABLE `xf_user_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '' COMMENT '操作用户名',
  `desc` varchar(55) DEFAULT '' COMMENT '操作内容',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `rank` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '日志类别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户日志';

-- ----------------------------
-- Records of xf_user_log
-- ----------------------------
INSERT INTO `xf_user_log` VALUES ('1', '记住密码用户', '后台登录成功,用户名:admin', '127.0.0.1', '1699338043', '3');


-- ----------------------------
-- Table structure for xf_user_login
-- ----------------------------
DROP TABLE IF EXISTS `xf_user_login`;
CREATE TABLE `xf_user_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '登录用户',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '访问url',
  `os` varchar(32) DEFAULT '' COMMENT '操作系统',
  `broswer` varchar(32) DEFAULT '' COMMENT '浏览器',
  `broswerb` varchar(32) DEFAULT '' COMMENT '浏览器版本号',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登陆IP',
  `date` date DEFAULT NULL COMMENT '访问日期',
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `rank` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '日志类别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户访问日志';

-- ----------------------------
-- Records of xf_user_login
-- ----------------------------
INSERT INTO `xf_user_login` VALUES ('1', 'admin', 'http://tp.com/Admin/Logs/user_log.html', 'Windows 10', 'Chrome', '117.0.0.0', '127.0.0.1', '2023-11-10', '1699589080', '1');

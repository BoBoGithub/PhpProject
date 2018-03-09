/* 数据表结构 */

/* 创建数据库 */
CREATE DATABASE `MG_DB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `MG_DB`;

/*管理用户表*/
CREATE TABLE `mg_admin` (
  `uid` 		bigint(20)  NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` 	varchar(20) NOT NULL DEFAULT ''  COMMENT '用户名',
  `password` 	varchar(32) NOT NULL DEFAULT ''  COMMENT '密码',
  `roleid` 		smallint(5) NOT NULL DEFAULT '0' COMMENT '所属角色id',
  `encrypt` 	varchar(6)  NOT NULL DEFAULT ''  COMMENT '加盐串',
  `email` 		varchar(40) NOT NULL DEFAULT ''  COMMENT '邮箱',
  `realname` 	varchar(50) NOT NULL DEFAULT ''  COMMENT '真实姓名',
  `ctime` 		datetime 	NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` 		int(3) 		NOT NULL DEFAULT '0' COMMENT '用户状态{-1已删除 0正常 1禁用}',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* 初始化超级管理员数据 */
INSERT INTO `mg_admin` (`uid`, `username`, `password`, `roleid`, `encrypt`, `email`, `realname`, `ctime`, `status`) VALUES
(1, 'admin001', '5efb380c733d3d71993c3f00fcfba641', 1, 'migang', 'admin@migang.com', 'MiGang_DB', '2017-08-24 16:46:03', 0);


/*管理员角色表*/
CREATE TABLE `mg_admin_role` (
  `roleid`		int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rolename`	varchar(50) 	NOT NULL COMMENT '角色名',
  `roledesc`	varchar(300) 	NOT NULL COMMENT '角色描述',
  `status`		tinyint(3)		NOT NULL COMMENT '是否启用{-1已删除 0:启用 1:禁止}' DEFAULT '0',
  PRIMARY KEY (roleid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* 初始化角色数据 */
INSERT INTO `mg_admin_role` (`roleid`, `rolename`, `roledesc`, `status`) VALUES
(1, '超级管理员', '超级管理员', 0);


/*角色权限表*/
CREATE TABLE `mg_admin_role_priv` (
  `roleid`	int(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色表id',
  `url` 	varchar(250) 	NOT NULL DEFAULT '0' COMMENT '拥有的访问url',
  KEY `roleid` (`roleid`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*菜单表*/
CREATE TABLE `mg_admin_menu` (
  `id` 			int(6) 	unsigned 	NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` 		char(40) 			NOT NULL DEFAULT '' 	COMMENT '菜单名称',
  `parentid` 	int(6) 				NOT NULL DEFAULT '0' 	COMMENT '父级菜单id',
  `url` 		varchar(300) 		NOT NULL DEFAULT '' 	COMMENT '访问菜单的url',
  `status`		tinyint(3)			NOT NULL DEFAULT '0'    COMMENT '是否显示菜单{-1已删除 0显示 1不显示}',
  PRIMARY KEY (`id`),
  KEY parentid (`parentid`),
  KEY url (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
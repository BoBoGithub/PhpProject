-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: MG_DB
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mg_admin`
--

DROP TABLE IF EXISTS `mg_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_admin` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `roleid` smallint(5) NOT NULL DEFAULT '0' COMMENT '所属角色id',
  `encrypt` varchar(6) NOT NULL DEFAULT '' COMMENT '加盐串',
  `email` varchar(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `ctime` int(10) DEFAULT '0',
  `status` int(3) NOT NULL DEFAULT '0' COMMENT '用户状态{-1已删除 0正常 1禁用}',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_admin`
--

LOCK TABLES `mg_admin` WRITE;
/*!40000 ALTER TABLE `mg_admin` DISABLE KEYS */;
INSERT INTO `mg_admin` VALUES (1,'admin001','5efb380c733d3d71993c3f00fcfba641',1,'migang','admin004@test.com','超级管理员',1520236733,0),(2,'admin003','406494ab67e4335316aa99bf3bb2bff0',1,'ABd8p2','admin003@migang.com','实姓',1520236733,0),(3,'admin003','8773e74e46ee58fad9c748ee1e46e884',1,'jjRZ95','admin003@migang.com','实姓',1520236733,-1),(4,'bo.cheng','9981cbb6810f8b8995c262683e437807',1,'XwiNj6','bo.cheng@migang.com','实姓名',1520236733,-1),(5,'admin004','f72e2e5884633c5bf4b3a1ced8fd7c03',1,'B6roZV','admin004@migang.com','测试004',0,1),(6,'admin005','7d2f15536f38738c48194b6282940c15',1,'NqmLKo','admin005@migang.com','测试005',0,0),(7,'admin007','6dd565783a5daea13f97b91c832ecf48',1,'GC3SMI','admin007@migang.com','测试007',0,0),(8,'admin008','a35c63d194a87426571f3cc439439fdd',1,'TTTud9','admin008@migang.com','测试008',0,0),(9,'admin009','84ddf998852fb18af536498b8425e6ba',1,'rZ4h5R','admin009@migang.com','测试009',0,0),(10,'admin010','d383358aeb606e3a9be3ad0643d9a112',1,'Kfd7go','admin010@migang.com','测试010',0,-1),(11,'admin011','70dad83cb572cf0bc0f62926f1e63c02',1,'G118HU','admin011@migang.com','测试011',1520237399,-1),(12,'admin01201','17073be860be2a4a38cf551de0643c80',6,'XkkMF5','admin012@migang.com','测试01201',1520239873,0),(13,'test110','4bb241e1258a35516368c560488c5010',6,'1GPMS8','test110@migang.com','测试110s',1520498251,0),(14,'test111','f0c7831bebbca4ffed50797fea619fd1',6,'Wx6zOs','test111@migang.com','测试111',1520576273,0),(15,'test112','66c90be0da82678b55522d6e1ade1efb',6,'8qNEky','test112@migang.com','测试112',1520576364,0),(16,'test113','c24012ca852478efed0a55ffdb02f88d',6,'6n3sc6','test113@migang.com','测试113',1520576418,-1);
/*!40000 ALTER TABLE `mg_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mg_admin_menu`
--

DROP TABLE IF EXISTS `mg_admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_admin_menu` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `parentid` int(6) NOT NULL DEFAULT '0' COMMENT '父级菜单id',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '访问菜单的url',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否显示菜单{-1已删除 0显示 1不显示}',
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `url` (`url`(255))
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_admin_menu`
--

LOCK TABLES `mg_admin_menu` WRITE;
/*!40000 ALTER TABLE `mg_admin_menu` DISABLE KEYS */;
INSERT INTO `mg_admin_menu` VALUES (1,'测试006sss',0,'/test/test006sss',-1),(2,'系统基础管理',0,'/admin',-1),(3,'运行管理',0,'/yunying/test',-1),(4,'资金管理',0,'/zijin/test',-1),(5,'个人信息管理',2,'/test/test1',0),(6,'技术管理',0,'/test/test',-1),(7,'测试菜单001',0,'/test/test001',-1),(8,'测试菜单002',0,'/test/test002',-1),(9,'测试菜单003s',0,'/test/test003s',-1),(10,'测试菜单004s',0,'/test/test004s',-1),(11,'测试菜单005',0,'/test/test005s',-1),(12,'修改个人信息页',5,'/test/test/updinfo',0),(13,'修改个人信息保存',5,'/test/test/save',1),(14,'测试006-001',1,'/test/test006/001',0),(15,'系统基础管理',0,'/setup/base/manager',0),(16,'个人信息管理',15,'/setup/sys/userinfo/manager',0),(17,'修改个人信息',16,'/setup/user/info',0),(18,'用户管理',15,'/setup/sys/user/manager',0),(19,'管理用户列表',18,'/setup/user/list',0),(20,'角色管理',15,'/setup/sys/role/manager',0),(21,'角色列表',20,'/setup/role/list',0),(22,'菜单管理',15,'/setup/sys/menu/manager',0),(23,'菜单列表',22,'/setup/menu/list',0),(24,'后台主页面',0,'/',1),(25,'欢迎页面',24,'/main/welcome',1),(26,'新增管理用户',19,'/setup/user/add',1),(27,'新增管理用户提交',26,'/setup/user/post/add',1),(28,'查询管理用户',19,'/setup/get/user/list',1),(29,'修改管理用户',19,'/setup/user/edit',1),(30,'禁用|删除管理用户',19,'/setup/del/user',1),(31,'查询角色数据',21,'/setup/get/role/list',1),(32,'新增角色',21,'/setup/role/add',1),(33,'提交新增角色',32,'/setup/role/post/add',1),(34,'权限设置',21,'/setup/role/permit',1),(35,'提交权限设置',34,'/setup/set/role/permit',1),(36,'角色成员列表',21,'/setup/role/user',1),(37,'角色修改',21,'/setup/role/edit',1),(38,'提交角色修改',37,'/setup/edit/role',1),(39,'删除角色',21,'/setup/del/role',1),(40,'新增菜单',23,'/setup/menu/add',1),(41,'tests',0,'/test/test',-1),(42,'提交新增菜单',40,'/setup/add/menu',1),(43,'修改菜单',23,'/setup/menu/edit',1),(44,'提交修改菜单',43,'/setup/edit/menu',1),(45,'删除菜单',23,'/setup/del/menu',1),(46,'菜单联动',24,'/setup/get/sub/menu',1);
/*!40000 ALTER TABLE `mg_admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mg_admin_role`
--

DROP TABLE IF EXISTS `mg_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_admin_role` (
  `roleid` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rolename` varchar(50) NOT NULL COMMENT '角色名',
  `roledesc` varchar(300) NOT NULL COMMENT '角色描述',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否启用{-1已删除 0:启用 1:禁止}',
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_admin_role`
--

LOCK TABLES `mg_admin_role` WRITE;
/*!40000 ALTER TABLE `mg_admin_role` DISABLE KEYS */;
INSERT INTO `mg_admin_role` VALUES (1,'超级管理员','超级管理员',0),(2,'风控总监','风控总监',0),(3,'市场总监1','市场总管1',-1),(4,'运营总监','运营总监',-1),(5,'技术总监','技术总监',0),(6,'财务总监','财务总监',0),(7,'测试角色001s','测试角色001s',-1);
/*!40000 ALTER TABLE `mg_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mg_admin_role_priv`
--

DROP TABLE IF EXISTS `mg_admin_role_priv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_admin_role_priv` (
  `roleid` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色表id',
  `url` varchar(250) NOT NULL DEFAULT '0' COMMENT '拥有的访问url',
  KEY `roleid` (`roleid`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_admin_role_priv`
--

LOCK TABLES `mg_admin_role_priv` WRITE;
/*!40000 ALTER TABLE `mg_admin_role_priv` DISABLE KEYS */;
INSERT INTO `mg_admin_role_priv` VALUES (2,'/setup/base/manager'),(2,'/setup/menu/list'),(2,'/setup/role/list'),(2,'/setup/sys/menu/manager'),(2,'/setup/sys/role/manager'),(2,'/setup/sys/user/manager'),(2,'/setup/sys/userinfo/manager'),(2,'/setup/user/info'),(2,'/setup/user/list'),(6,'/'),(6,'/main/welcome'),(6,'/setup/base/manager'),(6,'/setup/del/user'),(6,'/setup/get/role/list'),(6,'/setup/get/sub/menu'),(6,'/setup/get/user/list'),(6,'/setup/menu/list'),(6,'/setup/role/add'),(6,'/setup/role/edit'),(6,'/setup/role/list'),(6,'/setup/role/permit'),(6,'/setup/role/user'),(6,'/setup/sys/menu/manager'),(6,'/setup/sys/role/manager'),(6,'/setup/sys/user/manager'),(6,'/setup/sys/userinfo/manager'),(6,'/setup/user/add'),(6,'/setup/user/edit'),(6,'/setup/user/info'),(6,'/setup/user/list'),(7,'/'),(7,'/main/welcome');
/*!40000 ALTER TABLE `mg_admin_role_priv` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-09 16:21:25

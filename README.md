# PHP的Web项目框架实例 分层分模块实现

#### 备注:分模块开发 已完成管理后台的基本功能, 可在此基础上开始业务逻辑开发; 本项目是由[Java](https://github.com/BoBoGithub/JavaSSM)项目重写成PHP项目

## 1. 项目结构图如下:

<img src="https://raw.githubusercontent.com/BoBoGithub/PhpProject/master/doc/%E9%A1%B9%E7%9B%AE%E7%9B%AE%E5%BD%95%E7%BB%93%E6%9E%84%E5%9B%BE.png">

## 2. 项目搭建

#### 2.1 安装Nginx/PHP/Mysql/Redis
#### 2.2 按/doc/database.sql 新建库表
#### 2.3 修改Mysql/Redis的链接配置
#### 2.4 配置域名 admin.test.com 指向 admin模块下的单一入口文件index.php
#### 2.5 配置域名  data.test.com 指向 data 模块下的单一入口文件index.php


## 更新记录:

#### 2018/03/12 新增数据统计模块/后台模块和统计模块通过http通信/后台数据展示

<img src="https://github.com/BoBoGithub/PhpProject/blob/master/doc/git_img/2018-03-12%2017:17:09%E5%B1%8F%E5%B9%95%E6%88%AA%E5%9B%BE.png?raw=true">

#### 2018/03/19 新增数据分析模块采集脚本(定时任务/消息队列)


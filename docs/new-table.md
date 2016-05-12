
CREATE TABLE `host` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `idc` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=14953 DEFAULT CHARSET=utf8;

CREATE TABLE `idc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `creator` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE `host_group_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `creator` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8479 DEFAULT CHARSET=utf8;

CREATE TABLE `host_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` int(11) NOT NULL DEFAULT '0',
  `group` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_host_group` (`host`,`group`)
) ENGINE=InnoDB AUTO_INCREMENT=10045 DEFAULT CHARSET=utf8

######初始化idc
insert into idc(name,creator,created_at,updated_at)values('济阳机房',1,now(),now());
insert into idc(name,creator,created_at,updated_at)values('北京机房',1,now(),now());
insert into idc(name,creator,created_at,updated_at)values('上海机房',1,now(),now());

######初始化分组
######初始化主机
######初始化主机与分组关系
通过python读取zabbix数据

######更新机房信息
update host set idc=9 where id in(select host from host_group where `group` in(select id from host_group_info where name like '%jy%'));
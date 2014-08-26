
DROP TABLE IF EXISTS `fy_user`;
CREATE TABLE `fy_user` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(250) NOT NULL,
  `user_pw` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_phone` varchar(250) NOT NULL,
  `user_status` enum('1','0') NOT NULL,
  `user_type` tinyint(3) unsigned NOT NULL default 2 COMMENT '用户类型，1是后台管理员，2是普通用户',
  PRIMARY KEY (`id`),
  UNIQUE KEY user_id (user_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表';
INSERT INTO `fy_user` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员', '', '1', 1);


DROP TABLE IF EXISTS `fy_section`;
CREATE TABLE `fy_section` (
  `id` int(11) NOT NULL auto_increment,
  `section_name` varchar(250) NOT NULL,
  `section_image` varchar(250) NOT NULL,
  `section_type` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='板块表';
INSERT INTO `fy_section` VALUES (1, '雅思', 'fa fa-paper-plane', '培训');
INSERT INTO `fy_section` VALUES (2, '托福', 'fa fa-flag', '培训');
INSERT INTO `fy_section` VALUES (3, 'SAT', 'fa fa-globe', '培训');
INSERT INTO `fy_section` VALUES (4, '4/6级', 'fa fa-book', '培训');
INSERT INTO `fy_section` VALUES (5, '小语种', 'fa fa-graduation-cap', '培训');
INSERT INTO `fy_section` VALUES (6, '留学', 'fa fa-pencil', '留学');


DROP TABLE IF EXISTS `fy_edu`;
CREATE TABLE `fy_edu` (
  `id` int(11) NOT NULL auto_increment,
  `edu_name` varchar(250) NOT NULL,
  `edu_star` tinyint(3) unsigned NOT NULL ,
  `edu_image` varchar(250) NOT NULL,
  `edu_discount` varchar(250) NOT NULL,
  `edu_desc` text NOT NULL,
  `edu_browse` int(11) unsigned NOT NULL,
  `edu_choose` int(11) unsigned NOT NULL,
  `edu_sign` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='教育机构表';


DROP TABLE IF EXISTS `fy_sectionedu`;
CREATE TABLE `fy_sectionedu` (
  `id` int(11) NOT NULL auto_increment,
  `edu_id` int(11) unsigned NOT NULL,
  `section_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='板块机构对应表';


DROP TABLE IF EXISTS `fy_order`;
CREATE TABLE `fy_order` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `edu_id` varchar(250) NOT NULL,
  `edu_name` varchar(250) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_phone` varchar(250) NOT NULL,
  `order_status` enum('1','2','3','4','5') NOT NULL COMMENT '待沟通/沟通中/待报名/已报名/退费',
  `order_discount` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单表';


DROP TABLE IF EXISTS `fy_jborder`;
CREATE TABLE `fy_jborder` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(250) NOT NULL,
  `user_jbname` varchar(250) NOT NULL,
  `user_jbphone` varchar(250) NOT NULL,
  `user_jbdesc` text NOT NULL,
  `order_date` datetime NOT NULL,
  `order_status` enum('1','2','3','4','5') NOT NULL COMMENT '待沟通/沟通中/待报名/已报名/退费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='结伴留学表';

DROP TABLE IF EXISTS `fy_jbedu`;
CREATE TABLE `fy_jbedu` (
  `id` int(11) NOT NULL auto_increment,
  `jborder_id` varchar(250) NOT NULL,
  `edu_id` varchar(250) NOT NULL,
  `edu_name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='结伴留学机构表';
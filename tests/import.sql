-- 表结构：shop
CREATE TABLE `__PREFIX__shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
-- 表数据：shop
INSERT INTO `__PREFIX__shop` VALUES ('1','可口可乐','2.99');
INSERT INTO `__PREFIX__shop` VALUES ('2','溜溜梅','5.99');
-- 表结构：user
CREATE TABLE `__PREFIX__user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `status` int(1) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
-- 表数据：user
INSERT INTO `__PREFIX__user` VALUES ('1','admin','admin','1');
INSERT INTO `__PREFIX__user` VALUES ('2','test','test','1');

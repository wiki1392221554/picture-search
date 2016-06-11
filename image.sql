CREATE TABLE `images` 
(  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ͼƬid',
  `md5` char(64) NOT NULL,
  `imagekey` char(64) NOT NULL COMMENT 'key',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`),
  KEY `imagekey` (`imagekey`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8
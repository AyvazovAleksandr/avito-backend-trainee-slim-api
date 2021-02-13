-- Adminer 4.7.9 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `cost` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cart` (`id`, `date`, `cost`) VALUES
(1,	'2021-02-09 11:10:05',	20.000000),
(2,	'2021-02-09 13:10:14',	30.000000);

DROP TABLE IF EXISTS `cart_product`;
CREATE TABLE `cart_product` (
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cart_product` (`cart_id`, `product_id`, `quantity`) VALUES
(1,	1,	1),
(1,	2,	1),
(1,	3,	2),
(2,	2,	3),
(2,	1,	1);

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `customer` (`id`, `firstname`, `lastname`, `email`, `tel`) VALUES
(1,	'Ivan',	'Ivanovich',	'ivan@mail.com',	'+79991234569'),
(2,	'Petr',	'Petrovich',	'petr@mail.com',	'+79189874561'),
(3,	'Oleg',	'Olegovich',	'oleg@mail.ru',	'+79004587852');

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `delivery_address` text,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_code` varchar(255) DEFAULT NULL,
  `memo` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `delivery` (`id`, `order_id`, `delivery_address`, `delivery_date`, `delivery_code`, `memo`) VALUES
(1,	1,	'Красная 5',	'2021-02-09 19:50:01',	'DAHFBECG',	''),
(2,	1,	'Красная 5',	'2021-02-09 19:50:01',	'CBEFAGDH',	'');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `total_cost` decimal(20,6) DEFAULT NULL,
  `delivery_cost` decimal(20,6) DEFAULT NULL,
  `cart_cost` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `orders` (`id`, `customer_id`, `cart_id`, `total_cost`, `delivery_cost`, `cart_cost`) VALUES
(1,	1,	1,	NULL,	NULL,	NULL),
(2,	2,	2,	NULL,	NULL,	NULL),
(3,	1,	2,	94.000000,	84.000000,	10.000000),
(4,	1,	2,	34.000000,	24.000000,	10.000000);

DROP TABLE IF EXISTS `orders_del`;
CREATE TABLE `orders_del` (
  `id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cart_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `product` (`id`, `name`, `price`) VALUES
(1,	'Juice',	10.000000),
(2,	'Snickers',	2.000000),
(3,	'Ice cream',	5.000000);

-- 2021-02-13 17:20:41

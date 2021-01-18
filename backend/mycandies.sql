-- BACKUP DATABASE MyCandies
-- TO DISK = 

-- DROP DATABASE IF EXISTS MyCandies;
-- CREATE DATABASE MyCandies;
-- USE MyCandies;

/* @name_size UNSIGNED int;
@description_size UNSIGNED int;

SET @name_size = 100;
SET @description_size = 300;
*/



DROP TABLE IF EXISTS `Images`;
CREATE TABLE `Images` (
	`id` int NOT NULL AUTO_INCREMENT,
	`img_path` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `Customers`;
CREATE TABLE `Customers` (
	`id` int NOT NULL AUTO_INCREMENT,
	`first_name` varchar(40) NOT NULL,
	`last_name` varchar(20) NOT NULL,
	`email` varchar(50) NOT NULL,
	`telephone` char(10),
	`password` varchar(255) NOT NULL,
	`sex` enum('M', 'F', 'O'),
	`date_of_birth` date NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- TO DO: 
/* Consider using different tables for countries, regions, province, cities */
DROP TABLE IF EXISTS `Addresses`;
CREATE TABLE `Addresses` (
	`id` int NOT NULL AUTO_INCREMENT,
	`country` varchar(20) NOT NULL,
	`region` varchar(20) NOT NULL,
	`province` varchar(20) NOT NULL,
	`city` varchar(20) NOT NULL,
	`CAP` varchar(5) NOT NULL,
	`street` varchar(30) NOT NULL,
	`street_number` varchar(10) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Categories`;
CREATE TABLE `Categories` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL UNIQUE,
	`description` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- INSERT INTO Categories(name, description) VALUES
-- 	(Prova categorie, Categoria di prova),
-- 	(Prova categorie 2, Categoria di prova 2);


-- TO DO: check types
DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
	`id` int NOT NULL AUTO_INCREMENT,
	`category_id` int NOT NULL,
	`name` varchar(100) NOT NULL UNIQUE,
	`description` text,
	`price` float(10) NOT NULL,
	-- money NOT NULL,
	`availability` float(20),
	PRIMARY KEY (`id`),
	FOREIGN KEY (`category_id`) REFERENCES `Categories`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- INSERT INTO 'Products'('category_id', 'name', 'price', 'avaiability', 'linked_category', 'rating', 'description') VALUES
-- 	(1, 'Nome prodotto', 69.0, 420, 2, 5, 'Prodotto di prova');


DROP TABLE IF EXISTS `ActivePrinciples`;
CREATE TABLE `ActivePrinciples` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL UNIQUE,
	-- img_id int NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- INSERT INTO ActivePrinciples(name, chemical_formula) VALUES 
-- 	(THC, 'C_{21}H_{30}/O_{2}'),
-- 	('CBD', 'C_{21}H_{30}/O_{2}');


DROP TABLE IF EXISTS `Effects`;
CREATE TABLE `Effects` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`description` text,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `SideEffects`;
CREATE TABLE `SideEffects` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`description` text,
	-- link varchar(512),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- INSERT INTO 'SideEffects'('name', 'description') VALUES
-- 	('Tachicardia', 'La tachicardia è una forma di accelerazione del battito cardiaco, con aumento della frequenza dei battiti cardiaci e pulsazioni sopra i 3 battiti al secondo a riposo o senza alcuna forma di stress psicofisico.');
-- , 'https://it.wikipedia.org/wiki/Tachicardia'),
/* ('', null) */


DROP TABLE IF EXISTS `CustomersAddresses`;
CREATE TABLE `CustomersAddresses` (
	`customer_id` int,
	`address_id` int,
	`address_type` enum('Delivery', 'Billing'),
	PRIMARY KEY (`customer_id`, `address_id`),
	FOREIGN KEY (`customer_id`) REFERENCES `Customers`(`id`),
	FOREIGN KEY (`address_id`) REFERENCES `Addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `Carts`;
CREATE TABLE `Carts` (
	`id` int NOT NULL AUTO_INCREMENT,
	`total` int NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `ProductsActivePrinciples`;
CREATE TABLE `ProductsActivePrinciples` (
	`product_id` int NOT NULL,
	`active_principle_id` int NOT NULL,
	`percentage` float(10) NOT NULL,
	PRIMARY KEY (`product_id`, `active_principle_id`),
	FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`),
	FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `ActivePrinciplesSideEffects`;
CREATE TABLE `ActivePrinciplesSideEffects` (
	`active_principle_id` int NOT NULL,
	`side_effect_id` int NOT NULL,
	PRIMARY KEY (`active_principle_id`, `side_effect_id`),
	FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples`(`id`),
	FOREIGN KEY (`side_effect_id`) REFERENCES `SideEffects`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `ActivePrinciplesEffects`;
CREATE TABLE `ActivePrinciplesEffects` (
	`active_principle_id` int NOT NULL,
	`effect_id` int NOT NULL,
	PRIMARY KEY (`active_principle_id`, `effect_id`),
	FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples`(`id`),
	FOREIGN KEY (`effect_id`) REFERENCES `Effects`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `Transactions`;
CREATE TABLE `Transactions` (
	`customer_id` int NOT NULL,
	`cart_id` int NOT NULL,
	`datetime` datetime NOT NULL,
	`address_id` int NOT NULL,
	PRIMARY KEY (`customer_id`, `cart_id`),
	FOREIGN KEY (`customer_id`) REFERENCES `Customers`(`id`),
	FOREIGN KEY (`cart_id`) REFERENCES `Carts`(`id`),
	FOREIGN KEY (`address_id`) REFERENCES `Addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `ProductsImages`;
CREATE TABLE `ProductsImages` (
	`product_id` int NOT NULL,
	`img_id` int NOT NULL,
	PRIMARY KEY (`product_id`, `img_id`),
	FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`),
	FOREIGN KEY (`img_id`) REFERENCES `Images`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `ProductsInCarts`;
CREATE TABLE `ProductsInCarts` (
	`cart_id` int NOT NULL,
	`product_id` int NOT NULL,
	`quantity` float(24) NOT NULL,
	PRIMARY KEY (`cart_id`, `product_id`),
	FOREIGN KEY (`cart_id`) REFERENCES `Carts`(`id`),
	FOREIGN KEY (`product_id`) REFERENCES `Products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

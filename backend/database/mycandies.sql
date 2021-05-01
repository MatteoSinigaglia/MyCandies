-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 22, 2021 alle 11:15
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mycandies`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `activeprinciples`
--

CREATE TABLE `activeprinciples` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `activeprincipleseffects`
--

CREATE TABLE `activeprincipleseffects` (
  `active_principle_id` int(11) NOT NULL,
  `effect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `activeprinciplessideeffects`
--

CREATE TABLE `activeprinciplessideeffects` (
  `active_principle_id` int(11) NOT NULL,
  `side_effect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `CAP` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `admins`
--

CREATE TABLE `admins` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('M','F','A') COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `telephone`, `password`, `gender`, `birthdate`) VALUES
(1, 'My', 'Candies', 'user@gmail.com', NULL, 'user', 'A', '1900-01-01');

-- --------------------------------------------------------

--
-- Struttura della tabella `customersaddresses`
--

CREATE TABLE `customersaddresses` (
  `customer_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `address_type` enum('Delivery','Billing') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `effects`
--

CREATE TABLE `effects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `img_path` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float NOT NULL,
  `availability` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `productsactiveprinciples`
--

CREATE TABLE `productsactiveprinciples` (
  `product_id` int(11) NOT NULL,
  `active_principle_id` int(11) NOT NULL,
  `percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `productsimages`
--

CREATE TABLE `productsimages` (
  `product_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `productsincarts`
--

CREATE TABLE `productsincarts` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sideeffects`
--

CREATE TABLE `sideeffects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `transactions` (
  `customer_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `activeprinciples`
--
ALTER TABLE `activeprinciples`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `activeprincipleseffects`
--
ALTER TABLE `activeprincipleseffects`
  ADD PRIMARY KEY (`active_principle_id`,`effect_id`),
  ADD KEY `effect_id` (`effect_id`);

--
-- Indici per le tabelle `activeprinciplessideeffects`
--
ALTER TABLE `activeprinciplessideeffects`
  ADD PRIMARY KEY (`active_principle_id`,`side_effect_id`),
  ADD KEY `side_effect_id` (`side_effect_id`);

--
-- Indici per le tabelle `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`);

--
-- Indici per le tabelle `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `customersaddresses`
--
ALTER TABLE `customersaddresses`
  ADD PRIMARY KEY (`customer_id`,`address_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indici per le tabelle `effects`
--
ALTER TABLE `effects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indici per le tabelle `productsactiveprinciples`
--
ALTER TABLE `productsactiveprinciples`
  ADD PRIMARY KEY (`product_id`,`active_principle_id`),
  ADD KEY `active_principle_id` (`active_principle_id`);

--
-- Indici per le tabelle `productsimages`
--
ALTER TABLE `productsimages`
  ADD PRIMARY KEY (`product_id`,`img_id`),
  ADD KEY `img_id` (`img_id`);

--
-- Indici per le tabelle `productsincarts`
--
ALTER TABLE `productsincarts`
  ADD PRIMARY KEY (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indici per le tabelle `sideeffects`
--
ALTER TABLE `sideeffects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`customer_id`,`cart_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `address_id` (`address_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `activeprinciples`
--
ALTER TABLE `activeprinciples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `effects`
--
ALTER TABLE `effects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `sideeffects`
--
ALTER TABLE `sideeffects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `activeprincipleseffects`
--
ALTER TABLE `activeprincipleseffects`
  ADD CONSTRAINT `activeprincipleseffects_ibfk_1` FOREIGN KEY (`active_principle_id`) REFERENCES `activeprinciples` (`id`),
  ADD CONSTRAINT `activeprincipleseffects_ibfk_2` FOREIGN KEY (`effect_id`) REFERENCES `effects` (`id`);

--
-- Limiti per la tabella `activeprinciplessideeffects`
--
ALTER TABLE `activeprinciplessideeffects`
  ADD CONSTRAINT `activeprinciplessideeffects_ibfk_1` FOREIGN KEY (`active_principle_id`) REFERENCES `activeprinciples` (`id`),
  ADD CONSTRAINT `activeprinciplessideeffects_ibfk_2` FOREIGN KEY (`side_effect_id`) REFERENCES `sideeffects` (`id`);

--
-- Limiti per la tabella `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`);

--
-- Limiti per la tabella `customersaddresses`
--
ALTER TABLE `customersaddresses`
  ADD CONSTRAINT `customersaddresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customersaddresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Limiti per la tabella `productsactiveprinciples`
--
ALTER TABLE `productsactiveprinciples`
  ADD CONSTRAINT `productsactiveprinciples_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productsactiveprinciples_ibfk_2` FOREIGN KEY (`active_principle_id`) REFERENCES `activeprinciples` (`id`);

--
-- Limiti per la tabella `productsimages`
--
ALTER TABLE `productsimages`
  ADD CONSTRAINT `productsimages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productsimages_ibfk_2` FOREIGN KEY (`img_id`) REFERENCES `images` (`id`);

--
-- Limiti per la tabella `productsincarts`
--
ALTER TABLE `productsincarts`
  ADD CONSTRAINT `productsincarts_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `productsincarts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

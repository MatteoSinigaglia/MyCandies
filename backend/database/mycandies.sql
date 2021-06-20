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
-- Struttura della tabella `ActivePrinciples`
--

CREATE TABLE `ActivePrinciples` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ActivePrinciplesEffects`
--

CREATE TABLE `ActivePrinciplesEffects` (
  `active_principle_id` int(11) NOT NULL,
  `effect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ActivePrinciplesSideEffects`
--

CREATE TABLE `ActivePrinciplesSideEffects` (
  `active_principle_id` int(11) NOT NULL,
  `side_effect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Addresses`
--

CREATE TABLE `Addresses` (
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
-- Struttura della tabella `Admins`
--

CREATE TABLE `Admins` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Carts`
--

CREATE TABLE `Carts` (
  `id` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Categories`
--

CREATE TABLE `Categories` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Customers`
--

CREATE TABLE `Customers` (
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
-- Struttura della tabella `CustomersAddresses`
--

CREATE TABLE `CustomersAddresses` (
  `customer_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `address_type` enum('Delivery','Billing') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `effects`
--

CREATE TABLE `Effects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `images`
--

CREATE TABLE `Images` (
  `id` int(11) NOT NULL,
  `img_path` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `Products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float NOT NULL,
  `availability` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductsActivePrinciples`
--

CREATE TABLE `ProductsActivePrinciples` (
  `product_id` int(11) NOT NULL,
  `active_principle_id` int(11) NOT NULL,
  `percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductsImages`
--

CREATE TABLE `ProductsImages` (
  `product_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ProductsinCarts`
--

CREATE TABLE `ProductsInCarts` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `sideEffects`
--

CREATE TABLE `SideEffects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `Transactions` (
  `customer_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ActivePrinciples`
--
ALTER TABLE `ActivePrinciples`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `ActivePrinciplesEffects`
--
ALTER TABLE `ActivePrinciplesEffects`
  ADD PRIMARY KEY (`active_principle_id`,`effect_id`),
  ADD KEY `effect_id` (`effect_id`);

--
-- Indici per le tabelle `ActivePrinciplesSideEffects`
--
ALTER TABLE `ActivePrinciplesSideEffects`
  ADD PRIMARY KEY (`active_principle_id`,`side_effect_id`),
  ADD KEY `side_effect_id` (`side_effect_id`);

--
-- Indici per le tabelle `Addresses`
--
ALTER TABLE `Addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Admins`
--
ALTER TABLE `Admins`
  ADD PRIMARY KEY (`user_id`);

--
-- Indici per le tabelle `Carts`
--
ALTER TABLE `Carts`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `CustomersAddresses`
--
ALTER TABLE `CustomersAddresses`
  ADD PRIMARY KEY (`customer_id`,`address_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indici per le tabelle `Effects`
--
ALTER TABLE `Effects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indici per le tabelle `ProductsActivePrinciples`
--
ALTER TABLE `ProductsActivePrinciples`
  ADD PRIMARY KEY (`product_id`,`active_principle_id`),
  ADD KEY `active_principle_id` (`active_principle_id`);

--
-- Indici per le tabelle `ProductsImages`
--
ALTER TABLE `ProductsImages`
  ADD PRIMARY KEY (`product_id`,`img_id`),
  ADD KEY `img_id` (`img_id`);

--
-- Indici per le tabelle `ProductsInCarts`
--
ALTER TABLE `ProductsInCarts`
  ADD PRIMARY KEY (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indici per le tabelle `SideEffects`
--
ALTER TABLE `SideEffects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `Transactions`
--
ALTER TABLE `Transactions`
  ADD PRIMARY KEY (`customer_id`,`cart_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `address_id` (`address_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ActivePrinciples`
--
ALTER TABLE `ActivePrinciples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Addresses`
--
ALTER TABLE `Addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Carts`
--
ALTER TABLE `Carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Categories`
--
ALTER TABLE `Categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Customers`
--
ALTER TABLE `Customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `Effects`
--
ALTER TABLE `Effects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Images`
--
ALTER TABLE `Images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Products`
--
ALTER TABLE `Products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `SideEffects`
--
ALTER TABLE `SideEffects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ActivePrinciplesEffects`
--
ALTER TABLE `ActivePrinciplesEffects`
  ADD CONSTRAINT `ActivePrinciplesEffects_ibfk_1` FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples` (`id`),
  ADD CONSTRAINT `ActivePrinciplesEffects_ibfk_2` FOREIGN KEY (`effect_id`) REFERENCES `Effects` (`id`);

--
-- Limiti per la tabella `ActivePrinciplesSideEffects`
--
ALTER TABLE `ActivePrinciplesSideEffects`
  ADD CONSTRAINT `ActivePrinciplesSideEffects_ibfk_1` FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples` (`id`),
  ADD CONSTRAINT `ActivePrinciplesSideEffects_ibfk_2` FOREIGN KEY (`side_effect_id`) REFERENCES `SideEffects` (`id`);

--
-- Limiti per la tabella `Admins`
--
ALTER TABLE `Admins`
  ADD CONSTRAINT `Admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Customers` (`id`);

--
-- Limiti per la tabella `CustomersAddresses`
--
ALTER TABLE `CustomersAddresses`
  ADD CONSTRAINT `CustomersAddresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `CustomersAddresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `Addresses` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `Products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`id`);

--
-- Limiti per la tabella `ProductsActivePrinciples`
--
ALTER TABLE `ProductsActivePrinciples`
  ADD CONSTRAINT `ProductsActivePrinciples_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ProductsActivePrinciples_ibfk_2` FOREIGN KEY (`active_principle_id`) REFERENCES `ActivePrinciples` (`id`);

--
-- Limiti per la tabella `ProductsImages`
--
ALTER TABLE `ProductsImages`
  ADD CONSTRAINT `ProductsImages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ProductsImages_ibfk_2` FOREIGN KEY (`img_id`) REFERENCES `Images` (`id`);

--
-- Limiti per la tabella `ProductsInCarts`
--
ALTER TABLE `ProductsInCarts`
  ADD CONSTRAINT `ProductsInCarts_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `Carts` (`id`),
  ADD CONSTRAINT `ProductsInCarts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `Transactions`
--
ALTER TABLE `Transactions`
  ADD CONSTRAINT `Transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`id`),
  ADD CONSTRAINT `Transactions_ibfk_2` FOREIGN KEY (`cart_id`) REFERENCES `Carts` (`id`),
  ADD CONSTRAINT `Transactions_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `Addresses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

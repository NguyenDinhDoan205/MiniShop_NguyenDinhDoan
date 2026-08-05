-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 09:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nguyendinhdoan_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brandname` varchar(100) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brandname`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'apple', 'apple.png', 'Thương hiệu Apple', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(2, 'Samsung', 'samsung', 'samsung.png', 'Thương hiệu Samsung', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(3, 'Acer', 'acer', 'acer.png', 'Thương hiệu Acer', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(4, 'Asus', 'asus', 'asus.png', 'Thương hiệu Asus', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(5, 'MSI', 'msi', 'msi.png', 'Thương hiệu MSI', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(6, 'Kingston', 'kingston', 'kingston.png', 'Thương hiệu Kingston', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51'),
(7, 'Xiaomi', 'xiaomi', 'xiaomi.png', 'Thương hiệu Xiaomi', 1, '2026-08-05 14:12:51', '2026-08-05 14:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `catename`, `slug`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Màn hình', 'manhinhmaytinh', 'manhinh.jpg', 'Danh mục màn hình', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41'),
(2, 'Bàn di chuột', 'bandichuot', 'bandichuot.jpg', 'Danh mục chuột máy tính', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41'),
(3, 'Ghế gaming', 'ghegaming', 'ghegaming.jpg', 'Danh mục ghế ngồi gaming', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41'),
(4, 'Bộ lưu điện', 'boluudien', 'boluudien.jpg', 'Danh mục bộ lưu điện', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41'),
(5, 'Ổ cứng di động', 'ocungdidong', 'ocungdidong.jpg', 'Danh mục ổ cứng di động', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41'),
(6, 'USB', 'usbthenho', 'usbthenho.jpg', 'Danh mục USB và thẻ nhớ', 1, '2026-08-05 14:09:41', '2026-08-05 14:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `phone`, `email`, `address`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Đặng Thị Thu Trang', '0906666666', 'thutrang.dang@gmail.com', 'Vũng Tàu', NULL, 1, '2026-08-05 14:29:29', '2026-08-05 14:29:29'),
(2, 'Vũ Hoàng Phúc', '0907777777', 'hoangphuc.vu@gmail.com', 'Hà Nội', 'Khách thân thiết', 1, '2026-08-05 14:29:29', '2026-08-05 14:29:29'),
(3, 'Bùi Thị Kim Ngân', '0908888888', 'kimngan.bui@gmail.com', 'Đà Nẵng', NULL, 1, '2026-08-05 14:29:29', '2026-08-05 14:29:29'),
(4, 'Ngô Văn Tài', '0909999999', 'vantai.ngo@gmail.com', 'Nha Trang', NULL, 1, '2026-08-05 14:29:29', '2026-08-05 14:29:29'),
(5, 'Đỗ Thị Bích Ngọc', '0900123456', 'bichngoc.do@gmail.com', 'Đồng Tháp', 'Khách VIP', 0, '2026-08-05 14:29:29', '2026-08-05 14:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_code` varchar(30) NOT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `user_id`, `order_code`, `total_amount`, `note`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 'DH006', 950000.00, 'Giao trong ngày', 1, '2026-08-05 14:34:29', '2026-08-05 14:34:29'),
(7, 2, 2, 'DH007', 590000.00, NULL, 0, '2026-08-05 14:34:29', '2026-08-05 14:34:29'),
(8, 3, 3, 'DH008', 1890000.00, 'Gọi trước khi giao', 1, '2026-08-05 14:34:29', '2026-08-05 14:34:29'),
(9, 4, 4, 'DH009', 1290000.00, NULL, 2, '2026-08-05 14:34:29', '2026-08-05 14:34:29'),
(10, 5, 5, 'DH010', 1650000.00, 'Khách VIP', 1, '2026-08-05 14:34:29', '2026-08-05 14:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(1, 6, 31, 1, 390000.00, 390000.00, '2026-08-05 14:34:49'),
(2, 7, 32, 1, 290000.00, 290000.00, '2026-08-05 14:34:49'),
(3, 8, 33, 1, 990000.00, 990000.00, '2026-08-05 14:34:49'),
(4, 9, 34, 1, 1890000.00, 1890000.00, '2026-08-05 14:34:49'),
(5, 10, 35, 1, 10900000.00, 10900000.00, '2026-08-05 14:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `proname` varchar(200) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `discount_price` decimal(10,0) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `proname`, `slug`, `price`, `discount_price`, `quantity`, `image`, `description`, `status`, `created_at`, `updated_at`) VALUES
(31, 1, 4, 'Chuột Asus TUF Gaming M3', 'chuot-asus-tuf-gaming-m3', 450000, 390000, 20, 'tuf-m3.jpg', 'Chuột gaming', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(32, 1, 7, 'Chuột Xiaomi Mi Dual Mode', 'chuot-xiaomi-mi-dual-mode', 350000, 290000, 25, 'mi-dual-mode.jpg', 'Chuột không dây 2 chế độ', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(33, 2, 6, 'Bàn phím Kingston HyperX Alloy Core', 'ban-phim-kingston-alloy-core', 1200000, 990000, 12, 'alloy-core.jpg', 'Bàn phím cơ gaming', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(34, 2, 4, 'Bàn phím Asus ROG Strix Scope', 'ban-phim-asus-rog-strix-scope', 2100000, 1890000, 10, 'rog-strix-scope.jpg', 'Bàn phím cơ RGB', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(35, 3, 1, 'Tai nghe Apple AirPods Max', 'tai-nghe-apple-airpods-max', 12000000, 10900000, 5, 'airpods-max.jpg', 'Tai nghe chống ồn cao cấp', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(36, 3, 7, 'Tai nghe Xiaomi Mi Headphones', 'tai-nghe-xiaomi-mi-headphones', 890000, 790000, 18, 'mi-headphones.jpg', 'Tai nghe âm thanh trong trẻo', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(37, 4, 2, 'Webcam Samsung HD Pro', 'webcam-samsung-hd-pro', 1300000, 1150000, 9, 'samsung-hd-pro.jpg', 'Webcam học tập, họp trực tuyến', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(38, 4, 3, 'Webcam Acer ConfCam', 'webcam-acer-confcam', 1500000, 1350000, 7, 'acer-confcam.jpg', 'Webcam Full HD 1080p', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(39, 5, 5, 'Loa MSI Immerse GS30', 'loa-msi-immerse-gs30', 3200000, 2900000, 6, 'gs30.jpg', 'Loa gaming âm thanh sống động', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12'),
(40, 5, 4, 'Loa Asus ROG Strix', 'loa-asus-rog-strix', 2800000, 2600000, 8, 'rog-strix.jpg', 'Loa gaming thiết kế hiện đại', 1, '2026-08-05 14:21:12', '2026-08-05 14:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `sort_order`, `created_at`) VALUES
(11, 31, 'tuf-m3-1.jpg', 1, '2026-08-05 14:33:12'),
(12, 32, 'mi-dual-mode-1.jpg', 1, '2026-08-05 14:33:12'),
(13, 33, 'alloy-core-1.jpg', 1, '2026-08-05 14:33:12'),
(14, 34, 'rog-strix-scope-1.jpg', 1, '2026-08-05 14:33:12'),
(15, 35, 'airpods-max-1.jpg', 1, '2026-08-05 14:33:12'),
(16, 36, 'mi-headphones-1.jpg', 1, '2026-08-05 14:33:12'),
(17, 37, 'samsung-hd-pro-1.jpg', 1, '2026-08-05 14:33:12'),
(18, 38, 'acer-confcam-1.jpg', 1, '2026-08-05 14:33:12'),
(19, 39, 'gs30-1.jpg', 1, '2026-08-05 14:33:12'),
(20, 40, 'rog-strix-1.jpg', 1, '2026-08-05 14:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `email`, `phone`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Thị Ngọc Ánh', 'ngocanh.nguyen', 'Ngocanh@2026', 'ngocanh.nguyen@gmail.com', '0955666777', 'Cần Thơ', 0, 1, '2026-08-05 14:28:52', '2026-08-05 14:28:52'),
(2, 'Đặng Minh Khoa', 'minhkhoa.dang', 'Minhkhoa@2026', 'minhkhoa.dang@gmail.com', '0966777888', 'Vũng Tàu', 0, 1, '2026-08-05 14:28:52', '2026-08-05 14:28:52'),
(3, 'Bùi Thị Thanh Huyền', 'thanhhuyen.bui', 'Thanhhuyen@2026', 'thanhhuyen.bui@gmail.com', '0977888999', 'Hà Nội', 0, 1, '2026-08-05 14:28:52', '2026-08-05 14:28:52'),
(4, 'Vũ Đức Anh', 'ducanh.vu', 'Ducanh@2026', 'ducanh.vu@gmail.com', '0988999000', 'Đà Nẵng', 0, 1, '2026-08-05 14:28:52', '2026-08-05 14:28:52'),
(5, 'Đỗ Thị Mỹ Linh', 'mylinh.do', 'Mylinh@2026', 'mylinh.do@gmail.com', '0999000111', 'TP.HCM', 0, 0, '2026-08-05 14:28:52', '2026-08-05 14:28:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal_order` date NOT NULL,
  `total_harga` float NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `tanggal_order`, `total_harga`, `created_at`, `updated_at`, `customer_id`) VALUES
(1, '2024-05-09', 4209120, '2024-05-09 08:41:30', '2024-08-17 16:31:22', 29);
INSERT INTO `orders` (`id`, `tanggal_order`, `total_harga`, `created_at`, `updated_at`, `customer_id`) VALUES
(2, '2024-05-01', 381840, '2024-05-09 12:26:01', '2024-08-17 16:31:22', 1);
INSERT INTO `orders` (`id`, `tanggal_order`, `total_harga`, `created_at`, `updated_at`, `customer_id`) VALUES
(3, '2024-05-09', 1343100, '2024-05-09 19:07:57', '2024-08-17 16:31:22', 32);
INSERT INTO `orders` (`id`, `tanggal_order`, `total_harga`, `created_at`, `updated_at`, `customer_id`) VALUES
(4, '2024-08-17', 155400, '2024-08-17 16:25:14', '2024-08-17 16:25:14', 33),
(5, '2024-08-17', 1542900, '2024-08-17 21:10:11', '2024-08-17 23:54:15', 2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
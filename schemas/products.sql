/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` float NOT NULL,
  `ppn` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `gambar`, `harga`, `ppn`, `created_at`, `updated_at`) VALUES
(9, 'Kuda Poni 2', 'Kuda poni 2', 'uploads/66393d81884361715027329.5581.jpg', 35000, 11, '2024-05-07 03:28:49', '2024-05-07 16:13:17');
INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `gambar`, `harga`, `ppn`, `created_at`, `updated_at`) VALUES
(10, 'Pisang', 'Ini pisang', 'uploads/663b1be5731f11715149797.4715.jpeg', 20000, 11, '2024-05-08 13:29:57', '2024-05-08 13:29:57');
INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `gambar`, `harga`, `ppn`, `created_at`, `updated_at`) VALUES
(11, 'Anggur', 'Ini anggur', 'uploads/663b1bfe382b41715149822.2301.jpeg', 15000, 11, '2024-05-08 13:30:22', '2024-05-08 13:30:22');
INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `gambar`, `harga`, `ppn`, `created_at`, `updated_at`) VALUES
(12, 'Bawang Merah', 'bawang merag', 'uploads/663b1c1fc8af21715149855.822.jpeg', 28000, 11, '2024-05-08 13:30:55', '2024-05-08 13:30:55'),
(13, 'Baju Bola', 'baju bola', 'uploads/663b1c3695ee81715149878.6141.jpeg', 150000, 11, '2024-05-08 13:31:18', '2024-05-08 13:31:18'),
(14, 'Anggur Merah', 'anggur merah', 'uploads/663b1c5f752381715149919.4798.jpeg', 14000, 11, '2024-05-08 13:31:59', '2024-05-08 13:31:59'),
(15, 'Celana Jeans', 'celana', 'uploads/663b1ccea20831715150030.6637.jpeg', 80000, 11, '2024-05-08 13:33:50', '2024-05-08 13:33:50'),
(18, 'Shampoo Lifebuoy', 'sampo', 'uploads/663b1d42570ae1715150146.3565.jpeg', 15000, 11, '2024-05-08 13:35:46', '2024-05-08 13:35:46'),
(19, 'Shampoo Head And Shoulder', 'sampo', 'uploads/663b1d65775181715150181.4887.jpg', 21000, 11, '2024-05-08 13:36:21', '2024-05-08 13:36:21'),
(21, 'Shampoo Garnier Pure Clean', 'haha', 'uploads/663b4f190b7b81715162905.047.jpg', 26500, 11, '2024-05-08 17:08:25', '2024-05-08 17:08:25'),
(22, 'Shampoo Garnier Fructics', 'shampoo', 'uploads/663b4f54be12c1715162964.7785.png', 28000, 11, '2024-05-08 17:09:24', '2024-05-08 17:09:24'),
(23, 'Shampoo Clear', 'shampoo', 'uploads/663b643e093351715168318.0377.jpg', 19000, 11, '2024-05-08 18:38:38', '2024-05-08 18:38:38'),
(24, 'Shampoo Emeron Black And Shine', 'sampoo', 'uploads/663b64622f61c1715168354.1941.jpeg', 21500, 11, '2024-05-08 18:39:14', '2024-05-08 18:39:14'),
(25, 'Shampoo Emeron', 'shampoo', 'uploads/663b649201bcd1715168402.0071.jpg', 15500, 11, '2024-05-08 18:40:02', '2024-05-08 18:40:02'),
(26, 'Shampoo Emeron Soft And Smooth', 'sampo soft ', 'uploads/663b64b405db61715168436.024.jpg', 20000, 11, '2024-05-08 18:40:36', '2024-08-18 01:14:38');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
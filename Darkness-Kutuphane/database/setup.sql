-- Database creation
CREATE DATABASE IF NOT EXISTS `ci_bsms_db`;
USE `ci_bsms_db`;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `book`
CREATE TABLE `book` (
  `book_code` int(11) NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `category_code` int(11) NOT NULL,
  `price` int(25) NOT NULL,
  `book_img` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `writer` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`book_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert default admin user
INSERT INTO `users` (`username`, `password`, `email`, `status`) 
VALUES ('admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@example.com', 1);

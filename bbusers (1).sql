-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 30 nov 2015 kl 05:23
-- Serverversion: 5.6.15-log
-- PHP-version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `bbusers`
--

DELIMITER $$
--
-- Procedurer
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addComment`(IN `_postId` INT, IN `_author` VARCHAR(50), IN `_content` VARCHAR(200))
    NO SQL
INSERT INTO comments (postId, author, content) VALUES (_postId, _author, _content)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addPost`(IN `_author` VARCHAR(50), IN `_title` VARCHAR(100), IN `_content` VARCHAR(200), IN `_picture` VARCHAR(100))
    NO SQL
INSERT INTO blogposts (author, title, content, picture) VALUES (_author, _title, _content, _picture)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addUser`(IN `_username` VARCHAR(50), IN `_password` VARCHAR(150), IN `_salt` VARCHAR(50))
    NO SQL
INSERT INTO users (Username, Password, Salt) VALUES (_username, _password, _salt)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkLogIn`(IN `_username` VARCHAR(50), IN `_password` VARCHAR(150))
    NO SQL
SELECT Id FROM  `users` WHERE `Username`= _username AND `Password`= _password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `editComment`(IN `_commentId` INT, IN `_content` VARCHAR(200))
    NO SQL
UPDATE comments SET content = _content WHERE commentId = _commentId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `editPost`(IN `_postId` INT, IN `_title` VARCHAR(100), IN `_content` VARCHAR(200), IN `_picture` VARCHAR(100))
    NO SQL
UPDATE blogposts SET title = _title, content = _content, picture = _picture WHERE postId = _postId$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllComments`()
    NO SQL
SELECT * FROM comments$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllPosts`()
    NO SQL
SELECT * FROM blogposts$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSaltOnUser`(IN `_username` VARCHAR(50))
    NO SQL
SELECT SALT FROM users WHERE _username = Username$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `isUserFree`(IN `_username` VARCHAR(50))
    NO SQL
SELECT Id FROM  `users` WHERE `Username`= _username$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeComment`(IN `_commentId` INT)
    NO SQL
DELETE FROM comments WHERE commentId = _commentId$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur `blogposts`
--

CREATE TABLE IF NOT EXISTS `blogposts` (
  `postId` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` varchar(200) NOT NULL,
  `picture` varchar(100) NOT NULL,
  PRIMARY KEY (`postId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- Tabellstruktur `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `postId` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `content` varchar(200) NOT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(150) NOT NULL,
  `Salt` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

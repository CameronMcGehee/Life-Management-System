	-- Adds all tables needed for UltiScape to function. Not in any particular order, but related tables are generally put together.

	--
	-- Table structure for table `user`
	--

	CREATE TABLE IF NOT EXISTS `user` (
	`userId` varchar(17) NOT NULL,
	`username` varchar(200) NOT NULL,
	`password` varchar(64) NOT NULL,
	`email` varchar(200) NOT NULL,
	`firstName` text NOT NULL,
	`lastName` text NOT NULL,
	`profilePicture` varchar(17) DEFAULT NULL,
	`allowSignIn` tinyint(1) NOT NULL,
	`dateTimeJoined` datetime NOT NULL,
	`dateTimeLeft` datetime NULL DEFAULT NULL,
	PRIMARY KEY (`userId`),
	UNIQUE KEY `userUsername` (`username`) USING BTREE,
	UNIQUE KEY `userEmail` (`email`) USING BTREE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

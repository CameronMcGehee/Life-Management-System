<?php

    $currentUpdateStatus = 'run';

    $updateQuery = "
        CREATE TABLE IF NOT EXISTS `note` (
        `noteId` varchar(17) NOT NULL,
        `businessId` varchar(17) NULL DEFAULT NULL,
        `title` text NOT NULL,
        `bodyMarkdown` LONGTEXT NOT NULL,
        `bodyHtml` LONGTEXT NOT NULL,
        `viewPrivacy` varchar(10) NOT NULL DEFAULT 'private',
        `viewPass` varchar(200) NULL DEFAULT NULL,
        `editPrivacy` varchar(10) NOT NULL DEFAULT 'private',
        `editPass` varchar(200) NULL DEFAULT NULL,
        `dateTimeAdded` datetime NOT NULL,
        `lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`noteId`),
        KEY `noteBusinessId` (`businessId`),
        CONSTRAINT `noteBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE IF NOT EXISTS `noteTag` (
        `noteTagId` varchar(17) NOT NULL,
        `businessId` varchar(17) NOT NULL,
        `name` text NOT NULL,
        `description` text NULL DEFAULT NULL,
        `color` varchar(15) NOT NULL DEFAULT 'gray',
        `imgFile` varchar(17) NULL DEFAULT NULL,
        `dateTimeAdded` datetime NOT NULL,
        PRIMARY KEY (`noteTagId`),
        KEY `noteTagBusinessId` (`businessId`),
        CONSTRAINT `noteTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
        CREATE TABLE IF NOT EXISTS `noteNoteTagBridge` (
        `noteNoteTagId` int(11) NOT NULL AUTO_INCREMENT,
        `businessId` varchar(17) NOT NULL,
        `noteId` varchar(17) NOT NULL,
        `noteTagId` varchar(17) NOT NULL,
        `dateTimeAdded` datetime NOT NULL,
        PRIMARY KEY (`noteNoteTagId`),
        KEY `noteNoteTagBusinessId` (`businessId`),
        KEY `noteNoteTagCustomerId` (`noteId`),
        KEY `noteNoteTagCustomerTagId` (`noteTagId`),
        CONSTRAINT `noteNoteTagCustomerId` FOREIGN KEY (`noteId`) REFERENCES `note` (`noteId`) ON DELETE CASCADE,
        CONSTRAINT `noteNoteTagCustomerTagId` FOREIGN KEY (`noteTagId`) REFERENCES `noteTag` (`noteTagId`) ON DELETE CASCADE,
        CONSTRAINT `noteNoteTagBusinessId` FOREIGN KEY (`businessId`) REFERENCES `business` (`businessId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

    $updateResult = $database->mysqlQuery($updateQuery);

    if ($updateResult !== false) {
        $database->delete("systemInfo", "WHERE var = 'currentVersion'", 1);
        $database->insert("systemInfo", [
            "var" => "currentVersion",
            "val" => "0.1.3b"
        ]);
        $currentUpdateStatus = 'success';
    } else {
        // Get the SQL error
        $sqlErrorMessage = htmlspecialchars(strval($database->getLastError()));
        $currentUpdateStatus = 'Could not create the table. MySQL Error: '.$sqlErrorMessage;
    }

?>

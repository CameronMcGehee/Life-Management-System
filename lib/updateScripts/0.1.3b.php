<?php

    $currentUpdateStatus = 'run';

    $updateQuery1 = "
        CREATE TABLE IF NOT EXISTS `note` (
        `noteId` varchar(17) NOT NULL,
        `workspaceId` varchar(17) NULL DEFAULT NULL,
        `title` text NOT NULL,
        `bodyMarkdown` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `bodyHtml` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `viewPrivacy` varchar(10) NOT NULL DEFAULT 'private',
        `viewPass` varchar(200) NULL DEFAULT NULL,
        `editPrivacy` varchar(10) NOT NULL DEFAULT 'private',
        `editPass` varchar(200) NULL DEFAULT NULL,
        `dateTimeAdded` datetime NOT NULL,
        `lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`noteId`),
        KEY `noteWorkspaceId` (`workspaceId`),
        CONSTRAINT `noteWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $updateQuery2 = "
        CREATE TABLE IF NOT EXISTS `noteTag` (
        `noteTagId` varchar(17) NOT NULL,
        `workspaceId` varchar(17) NOT NULL,
        `name` text NOT NULL,
        `description` text NULL DEFAULT NULL,
        `color` varchar(15) NOT NULL DEFAULT 'gray',
        `imgFile` varchar(17) NULL DEFAULT NULL,
        `dateTimeAdded` datetime NOT NULL,
        PRIMARY KEY (`noteTagId`),
        KEY `noteTagWorkspaceId` (`workspaceId`),
        CONSTRAINT `noteTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $updateQuery3 = "
        CREATE TABLE IF NOT EXISTS `noteNoteTagBridge` (
        `noteNoteTagId` int(11) NOT NULL AUTO_INCREMENT,
        `workspaceId` varchar(17) NOT NULL,
        `noteId` varchar(17) NOT NULL,
        `noteTagId` varchar(17) NOT NULL,
        `dateTimeAdded` datetime NOT NULL,
        PRIMARY KEY (`noteNoteTagId`),
        KEY `noteNoteTagWorkspaceId` (`workspaceId`),
        KEY `noteNoteTagContactId` (`noteId`),
        KEY `noteNoteTagContactTagId` (`noteTagId`),
        CONSTRAINT `noteNoteTagContactId` FOREIGN KEY (`noteId`) REFERENCES `note` (`noteId`) ON DELETE CASCADE,
        CONSTRAINT `noteNoteTagContactTagId` FOREIGN KEY (`noteTagId`) REFERENCES `noteTag` (`noteTagId`) ON DELETE CASCADE,
        CONSTRAINT `noteNoteTagWorkspaceId` FOREIGN KEY (`workspaceId`) REFERENCES `workspace` (`workspaceId`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $updateResult1 = $database->mysqlQuery($updateQuery1);
    $updateResult2 = $database->mysqlQuery($updateQuery2);
    $updateResult3 = $database->mysqlQuery($updateQuery3);

    if ($updateResult1 !== false && $updateResult2 !== false && $updateResult3 !== false) {
        $database->delete("systemInfo", "WHERE var = 'currentVersion'", 1);
        $database->insert("systemInfo", [
            "var" => "currentVersion",
            "val" => "0.1.3b"
        ]);
        $currentUpdateStatus = 'success';
    } else {
        // Get the SQL error
        $sqlErrorMessage = htmlspecialchars(strval($database->getLastError()));
        $currentUpdateStatus = 'Error creating table(s). MySQL Error: '.$sqlErrorMessage;
    }

?>

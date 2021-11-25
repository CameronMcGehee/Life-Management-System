DROP TABLE `businessPlanBridge`;

ALTER TABLE `business`
ADD `plan` varchar(20) NOT NULL DEFAULT 'free';

ALTER TABLE `business`
ADD `planUntilDateTime` datetime NULL DEFAULT NULL;

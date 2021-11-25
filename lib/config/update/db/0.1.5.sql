DROP TABLE `businessPlanBridge`;

ALTER TABLE `business`
ADD `plan` varchar(20) NOT NULL DEFAULT 'free';

ALTER TABLE `business`
ADD `planUntilDateTime` datetime NULL DEFAULT NULL;

ALTER TABLE `businessPlanPayment` DROP INDEX `businessPlanPaymentLinkedToBusinessPlanId`;

ALTER TABLE `businessPlanPayment` DROP `linkedToBusinessPlanId`;

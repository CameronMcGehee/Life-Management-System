ALTER TABLE `business` DROP `modEstExtName`;
ALTER TABLE `business` DROP `modPropExtName`;
ALTER TABLE `business` DROP `modStaffExtName`;
ALTER TABLE `business` DROP `modCrewsExtName`;

ALTER TABLE `admin` DROP `surname`;
ALTER TABLE `customer` DROP `surname`;
ALTER TABLE `staff` DROP `surname`;

-- Data encryption and extra space for UX - So many changes (changing varchars to text) you must drop and rebuild the entire database. I'm not writing all of this in alter statements.

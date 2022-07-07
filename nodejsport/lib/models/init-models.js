var DataTypes = require("sequelize").DataTypes;
var _admin = require("./admin");
var _adminbusinessbridge = require("./adminbusinessbridge");
var _admincustomerservicemessage = require("./admincustomerservicemessage");
var _adminloginattempt = require("./adminloginattempt");
var _adminsavedlogin = require("./adminsavedlogin");
var _authtoken = require("./authtoken");
var _blogpost = require("./blogpost");
var _blogpostblogtagbridge = require("./blogpostblogtagbridge");
var _blogpostreadtoken = require("./blogpostreadtoken");
var _blogtag = require("./blogtag");
var _business = require("./business");
var _businessplanpayment = require("./businessplanpayment");
var _chemical = require("./chemical");
var _chemicalapplication = require("./chemicalapplication");
var _chemicalchemicaltagbridge = require("./chemicalchemicaltagbridge");
var _chemicalimage = require("./chemicalimage");
var _chemicaltag = require("./chemicaltag");
var _completedjob = require("./completedjob");
var _crew = require("./crew");
var _crewcrewtag = require("./crewcrewtag");
var _crewleaderbridge = require("./crewleaderbridge");
var _crewstaffbridge = require("./crewstaffbridge");
var _crewtag = require("./crewtag");
var _customer = require("./customer");
var _customercustomerservicemessage = require("./customercustomerservicemessage");
var _customercustomertagbridge = require("./customercustomertagbridge");
var _customeremailaddress = require("./customeremailaddress");
var _customeremailaddressemailsendbridge = require("./customeremailaddressemailsendbridge");
var _customerloginattempt = require("./customerloginattempt");
var _customerphonenumber = require("./customerphonenumber");
var _customerphonenumbersmssendbridge = require("./customerphonenumbersmssendbridge");
var _customersavedlogin = require("./customersavedlogin");
var _customerserviceticket = require("./customerserviceticket");
var _customertag = require("./customertag");
var _docid = require("./docid");
var _emailpixel = require("./emailpixel");
var _emailqueuemessage = require("./emailqueuemessage");
var _emailsend = require("./emailsend");
var _emailsubscriptionbridge = require("./emailsubscriptionbridge");
var _emailtemplate = require("./emailtemplate");
var _equipment = require("./equipment");
var _equipmentchemicalbridge = require("./equipmentchemicalbridge");
var _equipmentequipmenttagbridge = require("./equipmentequipmenttagbridge");
var _equipmentimage = require("./equipmentimage");
var _equipmentmaintenancelog = require("./equipmentmaintenancelog");
var _equipmenttag = require("./equipmenttag");
var _estimate = require("./estimate");
var _estimateitem = require("./estimateitem");
var _fileupload = require("./fileupload");
var _invoice = require("./invoice");
var _invoiceitem = require("./invoiceitem");
var _job = require("./job");
var _jobcrewbridge = require("./jobcrewbridge");
var _jobinstanceexception = require("./jobinstanceexception");
var _jobstaffbridge = require("./jobstaffbridge");
var _payment = require("./payment");
var _paymentmethod = require("./paymentmethod");
var _payrolldue = require("./payrolldue");
var _payrollsatisfaction = require("./payrollsatisfaction");
var _property = require("./property");
var _quoterequest = require("./quoterequest");
var _quoterequestservice = require("./quoterequestservice");
var _servicelisting = require("./servicelisting");
var _smssend = require("./smssend");
var _smssubscriptionbridge = require("./smssubscriptionbridge");
var _smstemplate = require("./smstemplate");
var _staff = require("./staff");
var _staffemailaddress = require("./staffemailaddress");
var _staffloginattempt = require("./staffloginattempt");
var _staffphonenumber = require("./staffphonenumber");
var _staffsavedlogin = require("./staffsavedlogin");
var _staffstafftagbridge = require("./staffstafftagbridge");
var _stafftag = require("./stafftag");
var _timelog = require("./timelog");

function initModels(sequelize) {
  var admin = _admin(sequelize, DataTypes);
  var adminbusinessbridge = _adminbusinessbridge(sequelize, DataTypes);
  var admincustomerservicemessage = _admincustomerservicemessage(sequelize, DataTypes);
  var adminloginattempt = _adminloginattempt(sequelize, DataTypes);
  var adminsavedlogin = _adminsavedlogin(sequelize, DataTypes);
  var authtoken = _authtoken(sequelize, DataTypes);
  var blogpost = _blogpost(sequelize, DataTypes);
  var blogpostblogtagbridge = _blogpostblogtagbridge(sequelize, DataTypes);
  var blogpostreadtoken = _blogpostreadtoken(sequelize, DataTypes);
  var blogtag = _blogtag(sequelize, DataTypes);
  var business = _business(sequelize, DataTypes);
  var businessplanpayment = _businessplanpayment(sequelize, DataTypes);
  var chemical = _chemical(sequelize, DataTypes);
  var chemicalapplication = _chemicalapplication(sequelize, DataTypes);
  var chemicalchemicaltagbridge = _chemicalchemicaltagbridge(sequelize, DataTypes);
  var chemicalimage = _chemicalimage(sequelize, DataTypes);
  var chemicaltag = _chemicaltag(sequelize, DataTypes);
  var completedjob = _completedjob(sequelize, DataTypes);
  var crew = _crew(sequelize, DataTypes);
  var crewcrewtag = _crewcrewtag(sequelize, DataTypes);
  var crewleaderbridge = _crewleaderbridge(sequelize, DataTypes);
  var crewstaffbridge = _crewstaffbridge(sequelize, DataTypes);
  var crewtag = _crewtag(sequelize, DataTypes);
  var customer = _customer(sequelize, DataTypes);
  var customercustomerservicemessage = _customercustomerservicemessage(sequelize, DataTypes);
  var customercustomertagbridge = _customercustomertagbridge(sequelize, DataTypes);
  var customeremailaddress = _customeremailaddress(sequelize, DataTypes);
  var customeremailaddressemailsendbridge = _customeremailaddressemailsendbridge(sequelize, DataTypes);
  var customerloginattempt = _customerloginattempt(sequelize, DataTypes);
  var customerphonenumber = _customerphonenumber(sequelize, DataTypes);
  var customerphonenumbersmssendbridge = _customerphonenumbersmssendbridge(sequelize, DataTypes);
  var customersavedlogin = _customersavedlogin(sequelize, DataTypes);
  var customerserviceticket = _customerserviceticket(sequelize, DataTypes);
  var customertag = _customertag(sequelize, DataTypes);
  var docid = _docid(sequelize, DataTypes);
  var emailpixel = _emailpixel(sequelize, DataTypes);
  var emailqueuemessage = _emailqueuemessage(sequelize, DataTypes);
  var emailsend = _emailsend(sequelize, DataTypes);
  var emailsubscriptionbridge = _emailsubscriptionbridge(sequelize, DataTypes);
  var emailtemplate = _emailtemplate(sequelize, DataTypes);
  var equipment = _equipment(sequelize, DataTypes);
  var equipmentchemicalbridge = _equipmentchemicalbridge(sequelize, DataTypes);
  var equipmentequipmenttagbridge = _equipmentequipmenttagbridge(sequelize, DataTypes);
  var equipmentimage = _equipmentimage(sequelize, DataTypes);
  var equipmentmaintenancelog = _equipmentmaintenancelog(sequelize, DataTypes);
  var equipmenttag = _equipmenttag(sequelize, DataTypes);
  var estimate = _estimate(sequelize, DataTypes);
  var estimateitem = _estimateitem(sequelize, DataTypes);
  var fileupload = _fileupload(sequelize, DataTypes);
  var invoice = _invoice(sequelize, DataTypes);
  var invoiceitem = _invoiceitem(sequelize, DataTypes);
  var job = _job(sequelize, DataTypes);
  var jobcrewbridge = _jobcrewbridge(sequelize, DataTypes);
  var jobinstanceexception = _jobinstanceexception(sequelize, DataTypes);
  var jobstaffbridge = _jobstaffbridge(sequelize, DataTypes);
  var payment = _payment(sequelize, DataTypes);
  var paymentmethod = _paymentmethod(sequelize, DataTypes);
  var payrolldue = _payrolldue(sequelize, DataTypes);
  var payrollsatisfaction = _payrollsatisfaction(sequelize, DataTypes);
  var property = _property(sequelize, DataTypes);
  var quoterequest = _quoterequest(sequelize, DataTypes);
  var quoterequestservice = _quoterequestservice(sequelize, DataTypes);
  var servicelisting = _servicelisting(sequelize, DataTypes);
  var smssend = _smssend(sequelize, DataTypes);
  var smssubscriptionbridge = _smssubscriptionbridge(sequelize, DataTypes);
  var smstemplate = _smstemplate(sequelize, DataTypes);
  var staff = _staff(sequelize, DataTypes);
  var staffemailaddress = _staffemailaddress(sequelize, DataTypes);
  var staffloginattempt = _staffloginattempt(sequelize, DataTypes);
  var staffphonenumber = _staffphonenumber(sequelize, DataTypes);
  var staffsavedlogin = _staffsavedlogin(sequelize, DataTypes);
  var staffstafftagbridge = _staffstafftagbridge(sequelize, DataTypes);
  var stafftag = _stafftag(sequelize, DataTypes);
  var timelog = _timelog(sequelize, DataTypes);

  adminbusinessbridge.belongsTo(admin, { as: "admin", foreignKey: "adminId"});
  admin.hasMany(adminbusinessbridge, { as: "adminbusinessbridges", foreignKey: "adminId"});
  admincustomerservicemessage.belongsTo(admin, { as: "admin", foreignKey: "adminId"});
  admin.hasMany(admincustomerservicemessage, { as: "admincustomerservicemessages", foreignKey: "adminId"});
  adminloginattempt.belongsTo(admin, { as: "admin", foreignKey: "adminId"});
  admin.hasMany(adminloginattempt, { as: "adminloginattempts", foreignKey: "adminId"});
  adminsavedlogin.belongsTo(admin, { as: "admin", foreignKey: "adminId"});
  admin.hasMany(adminsavedlogin, { as: "adminsavedlogins", foreignKey: "adminId"});
  businessplanpayment.belongsTo(admin, { as: "admin", foreignKey: "adminId"});
  admin.hasMany(businessplanpayment, { as: "businessplanpayments", foreignKey: "adminId"});
  blogpostblogtagbridge.belongsTo(blogpost, { as: "blogPost", foreignKey: "blogPostId"});
  blogpost.hasMany(blogpostblogtagbridge, { as: "blogpostblogtagbridges", foreignKey: "blogPostId"});
  blogpostreadtoken.belongsTo(blogpost, { as: "blogPost", foreignKey: "blogPostId"});
  blogpost.hasMany(blogpostreadtoken, { as: "blogpostreadtokens", foreignKey: "blogPostId"});
  blogpostblogtagbridge.belongsTo(blogtag, { as: "blogTag", foreignKey: "blogTagId"});
  blogtag.hasMany(blogpostblogtagbridge, { as: "blogpostblogtagbridges", foreignKey: "blogTagId"});
  adminbusinessbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(adminbusinessbridge, { as: "adminbusinessbridges", foreignKey: "businessId"});
  admincustomerservicemessage.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(admincustomerservicemessage, { as: "admincustomerservicemessages", foreignKey: "businessId"});
  blogpost.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(blogpost, { as: "blogposts", foreignKey: "businessId"});
  blogpostblogtagbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(blogpostblogtagbridge, { as: "blogpostblogtagbridges", foreignKey: "businessId"});
  blogpostreadtoken.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(blogpostreadtoken, { as: "blogpostreadtokens", foreignKey: "businessId"});
  blogtag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(blogtag, { as: "blogtags", foreignKey: "businessId"});
  businessplanpayment.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(businessplanpayment, { as: "businessplanpayments", foreignKey: "businessId"});
  chemical.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(chemical, { as: "chemicals", foreignKey: "businessId"});
  chemicalapplication.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(chemicalapplication, { as: "chemicalapplications", foreignKey: "businessId"});
  chemicalchemicaltagbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(chemicalchemicaltagbridge, { as: "chemicalchemicaltagbridges", foreignKey: "businessId"});
  chemicalimage.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(chemicalimage, { as: "chemicalimages", foreignKey: "businessId"});
  chemicaltag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(chemicaltag, { as: "chemicaltags", foreignKey: "businessId"});
  completedjob.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(completedjob, { as: "completedjobs", foreignKey: "businessId"});
  crew.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(crew, { as: "crews", foreignKey: "businessId"});
  crewcrewtag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(crewcrewtag, { as: "crewcrewtags", foreignKey: "businessId"});
  crewleaderbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(crewleaderbridge, { as: "crewleaderbridges", foreignKey: "businessId"});
  crewstaffbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(crewstaffbridge, { as: "crewstaffbridges", foreignKey: "businessId"});
  crewtag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(crewtag, { as: "crewtags", foreignKey: "businessId"});
  customer.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customer, { as: "customers", foreignKey: "businessId"});
  customercustomerservicemessage.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customercustomerservicemessage, { as: "customercustomerservicemessages", foreignKey: "businessId"});
  customercustomertagbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customercustomertagbridge, { as: "customercustomertagbridges", foreignKey: "businessId"});
  customeremailaddress.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customeremailaddress, { as: "customeremailaddresses", foreignKey: "businessId"});
  customeremailaddressemailsendbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customeremailaddressemailsendbridge, { as: "customeremailaddressemailsendbridges", foreignKey: "businessId"});
  customerloginattempt.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customerloginattempt, { as: "customerloginattempts", foreignKey: "businessId"});
  customerphonenumber.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customerphonenumber, { as: "customerphonenumbers", foreignKey: "businessId"});
  customerphonenumbersmssendbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customerphonenumbersmssendbridge, { as: "customerphonenumbersmssendbridges", foreignKey: "businessId"});
  customersavedlogin.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customersavedlogin, { as: "customersavedlogins", foreignKey: "businessId"});
  customerserviceticket.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customerserviceticket, { as: "customerservicetickets", foreignKey: "businessId"});
  customertag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(customertag, { as: "customertags", foreignKey: "businessId"});
  docid.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(docid, { as: "docids", foreignKey: "businessId"});
  emailpixel.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(emailpixel, { as: "emailpixels", foreignKey: "businessId"});
  emailsend.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(emailsend, { as: "emailsends", foreignKey: "businessId"});
  emailsubscriptionbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(emailsubscriptionbridge, { as: "emailsubscriptionbridges", foreignKey: "businessId"});
  emailtemplate.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(emailtemplate, { as: "emailtemplates", foreignKey: "businessId"});
  equipment.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipment, { as: "equipments", foreignKey: "businessId"});
  equipmentchemicalbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipmentchemicalbridge, { as: "equipmentchemicalbridges", foreignKey: "businessId"});
  equipmentequipmenttagbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipmentequipmenttagbridge, { as: "equipmentequipmenttagbridges", foreignKey: "businessId"});
  equipmentimage.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipmentimage, { as: "equipmentimages", foreignKey: "businessId"});
  equipmentmaintenancelog.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipmentmaintenancelog, { as: "equipmentmaintenancelogs", foreignKey: "businessId"});
  equipmenttag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(equipmenttag, { as: "equipmenttags", foreignKey: "businessId"});
  estimate.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(estimate, { as: "estimates", foreignKey: "businessId"});
  estimateitem.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(estimateitem, { as: "estimateitems", foreignKey: "businessId"});
  fileupload.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(fileupload, { as: "fileuploads", foreignKey: "businessId"});
  invoice.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(invoice, { as: "invoices", foreignKey: "businessId"});
  invoiceitem.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(invoiceitem, { as: "invoiceitems", foreignKey: "businessId"});
  job.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(job, { as: "jobs", foreignKey: "businessId"});
  jobcrewbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(jobcrewbridge, { as: "jobcrewbridges", foreignKey: "businessId"});
  jobinstanceexception.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(jobinstanceexception, { as: "jobinstanceexceptions", foreignKey: "businessId"});
  jobstaffbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(jobstaffbridge, { as: "jobstaffbridges", foreignKey: "businessId"});
  payment.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(payment, { as: "payments", foreignKey: "businessId"});
  paymentmethod.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(paymentmethod, { as: "paymentmethods", foreignKey: "businessId"});
  payrolldue.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(payrolldue, { as: "payrolldues", foreignKey: "businessId"});
  payrollsatisfaction.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(payrollsatisfaction, { as: "payrollsatisfactions", foreignKey: "businessId"});
  property.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(property, { as: "properties", foreignKey: "businessId"});
  quoterequest.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(quoterequest, { as: "quoterequests", foreignKey: "businessId"});
  quoterequestservice.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(quoterequestservice, { as: "quoterequestservices", foreignKey: "businessId"});
  servicelisting.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(servicelisting, { as: "servicelistings", foreignKey: "businessId"});
  smssend.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(smssend, { as: "smssends", foreignKey: "businessId"});
  smssubscriptionbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(smssubscriptionbridge, { as: "smssubscriptionbridges", foreignKey: "businessId"});
  smstemplate.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(smstemplate, { as: "smstemplates", foreignKey: "businessId"});
  staff.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staff, { as: "staffs", foreignKey: "businessId"});
  staffemailaddress.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staffemailaddress, { as: "staffemailaddresses", foreignKey: "businessId"});
  staffloginattempt.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staffloginattempt, { as: "staffloginattempts", foreignKey: "businessId"});
  staffphonenumber.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staffphonenumber, { as: "staffphonenumbers", foreignKey: "businessId"});
  staffsavedlogin.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staffsavedlogin, { as: "staffsavedlogins", foreignKey: "businessId"});
  staffstafftagbridge.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(staffstafftagbridge, { as: "staffstafftagbridges", foreignKey: "businessId"});
  stafftag.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(stafftag, { as: "stafftags", foreignKey: "businessId"});
  timelog.belongsTo(business, { as: "business", foreignKey: "businessId"});
  business.hasMany(timelog, { as: "timelogs", foreignKey: "businessId"});
  chemicalapplication.belongsTo(chemical, { as: "chemical", foreignKey: "chemicalId"});
  chemical.hasMany(chemicalapplication, { as: "chemicalapplications", foreignKey: "chemicalId"});
  chemicalchemicaltagbridge.belongsTo(chemical, { as: "chemical", foreignKey: "chemicalId"});
  chemical.hasMany(chemicalchemicaltagbridge, { as: "chemicalchemicaltagbridges", foreignKey: "chemicalId"});
  chemicalimage.belongsTo(chemical, { as: "chemical", foreignKey: "chemicalId"});
  chemical.hasMany(chemicalimage, { as: "chemicalimages", foreignKey: "chemicalId"});
  equipmentchemicalbridge.belongsTo(chemical, { as: "chemical", foreignKey: "chemicalId"});
  chemical.hasMany(equipmentchemicalbridge, { as: "equipmentchemicalbridges", foreignKey: "chemicalId"});
  chemicalchemicaltagbridge.belongsTo(chemicaltag, { as: "chemicalTag", foreignKey: "chemicalTagId"});
  chemicaltag.hasMany(chemicalchemicaltagbridge, { as: "chemicalchemicaltagbridges", foreignKey: "chemicalTagId"});
  crewcrewtag.belongsTo(crew, { as: "crew", foreignKey: "crewId"});
  crew.hasMany(crewcrewtag, { as: "crewcrewtags", foreignKey: "crewId"});
  crewleaderbridge.belongsTo(crew, { as: "crew", foreignKey: "crewId"});
  crew.hasMany(crewleaderbridge, { as: "crewleaderbridges", foreignKey: "crewId"});
  crewstaffbridge.belongsTo(crew, { as: "crew", foreignKey: "crewId"});
  crew.hasMany(crewstaffbridge, { as: "crewstaffbridges", foreignKey: "crewId"});
  jobcrewbridge.belongsTo(crew, { as: "crew", foreignKey: "crewId"});
  crew.hasMany(jobcrewbridge, { as: "jobcrewbridges", foreignKey: "crewId"});
  crewcrewtag.belongsTo(crewtag, { as: "crewTag", foreignKey: "crewTagId"});
  crewtag.hasMany(crewcrewtag, { as: "crewcrewtags", foreignKey: "crewTagId"});
  customercustomertagbridge.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(customercustomertagbridge, { as: "customercustomertagbridges", foreignKey: "customerId"});
  customeremailaddress.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(customeremailaddress, { as: "customeremailaddresses", foreignKey: "customerId"});
  customerloginattempt.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(customerloginattempt, { as: "customerloginattempts", foreignKey: "customerId"});
  customerphonenumber.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(customerphonenumber, { as: "customerphonenumbers", foreignKey: "customerId"});
  customersavedlogin.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(customersavedlogin, { as: "customersavedlogins", foreignKey: "customerId"});
  customerserviceticket.belongsTo(customer, { as: "linkedToCustomer", foreignKey: "linkedToCustomerId"});
  customer.hasMany(customerserviceticket, { as: "customerservicetickets", foreignKey: "linkedToCustomerId"});
  estimate.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(estimate, { as: "estimates", foreignKey: "customerId"});
  invoice.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(invoice, { as: "invoices", foreignKey: "customerId"});
  payment.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(payment, { as: "payments", foreignKey: "customerId"});
  property.belongsTo(customer, { as: "customer", foreignKey: "customerId"});
  customer.hasMany(property, { as: "properties", foreignKey: "customerId"});
  quoterequest.belongsTo(customer, { as: "linkedToCustomer", foreignKey: "linkedToCustomerId"});
  customer.hasMany(quoterequest, { as: "quoterequests", foreignKey: "linkedToCustomerId"});
  customeremailaddressemailsendbridge.belongsTo(customeremailaddress, { as: "customerEmailAddress", foreignKey: "customerEmailAddressId"});
  customeremailaddress.hasMany(customeremailaddressemailsendbridge, { as: "customeremailaddressemailsendbridges", foreignKey: "customerEmailAddressId"});
  emailsubscriptionbridge.belongsTo(customeremailaddress, { as: "customerEmailAddress", foreignKey: "customerEmailAddressId"});
  customeremailaddress.hasMany(emailsubscriptionbridge, { as: "emailsubscriptionbridges", foreignKey: "customerEmailAddressId"});
  customerphonenumbersmssendbridge.belongsTo(customerphonenumber, { as: "customerPhoneNumber", foreignKey: "customerPhoneNumberId"});
  customerphonenumber.hasMany(customerphonenumbersmssendbridge, { as: "customerphonenumbersmssendbridges", foreignKey: "customerPhoneNumberId"});
  smssubscriptionbridge.belongsTo(customerphonenumber, { as: "customerPhoneNumber", foreignKey: "customerPhoneNumberId"});
  customerphonenumber.hasMany(smssubscriptionbridge, { as: "smssubscriptionbridges", foreignKey: "customerPhoneNumberId"});
  admincustomerservicemessage.belongsTo(customerserviceticket, { as: "customerServiceTicket", foreignKey: "customerServiceTicketId"});
  customerserviceticket.hasMany(admincustomerservicemessage, { as: "admincustomerservicemessages", foreignKey: "customerServiceTicketId"});
  customercustomerservicemessage.belongsTo(customerserviceticket, { as: "customerServiceTicket", foreignKey: "customerServiceTicketId"});
  customerserviceticket.hasMany(customercustomerservicemessage, { as: "customercustomerservicemessages", foreignKey: "customerServiceTicketId"});
  customercustomertagbridge.belongsTo(customertag, { as: "customerTag", foreignKey: "customerTagId"});
  customertag.hasMany(customercustomertagbridge, { as: "customercustomertagbridges", foreignKey: "customerTagId"});
  customerserviceticket.belongsTo(docid, { as: "docId", foreignKey: "docIdId"});
  docid.hasMany(customerserviceticket, { as: "customerservicetickets", foreignKey: "docIdId"});
  estimate.belongsTo(docid, { as: "docId", foreignKey: "docIdId"});
  docid.hasMany(estimate, { as: "estimates", foreignKey: "docIdId"});
  fileupload.belongsTo(docid, { as: "docId", foreignKey: "docIdId"});
  docid.hasMany(fileupload, { as: "fileuploads", foreignKey: "docIdId"});
  invoice.belongsTo(docid, { as: "docId", foreignKey: "docIdId"});
  docid.hasMany(invoice, { as: "invoices", foreignKey: "docIdId"});
  customeremailaddressemailsendbridge.belongsTo(emailsend, { as: "emailSend", foreignKey: "emailSendId"});
  emailsend.hasMany(customeremailaddressemailsendbridge, { as: "customeremailaddressemailsendbridges", foreignKey: "emailSendId"});
  emailpixel.belongsTo(emailsend, { as: "emailSend", foreignKey: "emailSendId"});
  emailsend.hasMany(emailpixel, { as: "emailpixels", foreignKey: "emailSendId"});
  emailsubscriptionbridge.belongsTo(emailtemplate, { as: "emailTemplate", foreignKey: "emailTemplateId"});
  emailtemplate.hasMany(emailsubscriptionbridge, { as: "emailsubscriptionbridges", foreignKey: "emailTemplateId"});
  equipmentchemicalbridge.belongsTo(equipment, { as: "equipment", foreignKey: "equipmentId"});
  equipment.hasMany(equipmentchemicalbridge, { as: "equipmentchemicalbridges", foreignKey: "equipmentId"});
  equipmentequipmenttagbridge.belongsTo(equipment, { as: "equipment", foreignKey: "equipmentId"});
  equipment.hasMany(equipmentequipmenttagbridge, { as: "equipmentequipmenttagbridges", foreignKey: "equipmentId"});
  equipmentimage.belongsTo(equipment, { as: "equipment", foreignKey: "equipmentId"});
  equipment.hasMany(equipmentimage, { as: "equipmentimages", foreignKey: "equipmentId"});
  equipmentmaintenancelog.belongsTo(equipment, { as: "equipment", foreignKey: "equipmentId"});
  equipment.hasMany(equipmentmaintenancelog, { as: "equipmentmaintenancelogs", foreignKey: "equipmentId"});
  equipmentequipmenttagbridge.belongsTo(equipmenttag, { as: "equipmentTag", foreignKey: "equipmentTagId"});
  equipmenttag.hasMany(equipmentequipmenttagbridge, { as: "equipmentequipmenttagbridges", foreignKey: "equipmentTagId"});
  estimateitem.belongsTo(estimate, { as: "estimate", foreignKey: "estimateId"});
  estimate.hasMany(estimateitem, { as: "estimateitems", foreignKey: "estimateId"});
  invoiceitem.belongsTo(invoice, { as: "invoice", foreignKey: "invoiceId"});
  invoice.hasMany(invoiceitem, { as: "invoiceitems", foreignKey: "invoiceId"});
  jobcrewbridge.belongsTo(job, { as: "job", foreignKey: "jobId"});
  job.hasMany(jobcrewbridge, { as: "jobcrewbridges", foreignKey: "jobId"});
  jobinstanceexception.belongsTo(job, { as: "job", foreignKey: "jobId"});
  job.hasMany(jobinstanceexception, { as: "jobinstanceexceptions", foreignKey: "jobId"});
  jobstaffbridge.belongsTo(job, { as: "job", foreignKey: "jobId"});
  job.hasMany(jobstaffbridge, { as: "jobstaffbridges", foreignKey: "jobId"});
  chemicalapplication.belongsTo(property, { as: "property", foreignKey: "propertyId"});
  property.hasMany(chemicalapplication, { as: "chemicalapplications", foreignKey: "propertyId"});
  quoterequestservice.belongsTo(quoterequest, { as: "quoteRequest", foreignKey: "quoteRequestId"});
  quoterequest.hasMany(quoterequestservice, { as: "quoterequestservices", foreignKey: "quoteRequestId"});
  customerphonenumbersmssendbridge.belongsTo(smssend, { as: "smsSend", foreignKey: "smsSendId"});
  smssend.hasMany(customerphonenumbersmssendbridge, { as: "customerphonenumbersmssendbridges", foreignKey: "smsSendId"});
  smssubscriptionbridge.belongsTo(smstemplate, { as: "smsTemplate", foreignKey: "smsTemplateId"});
  smstemplate.hasMany(smssubscriptionbridge, { as: "smssubscriptionbridges", foreignKey: "smsTemplateId"});
  crewleaderbridge.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(crewleaderbridge, { as: "crewleaderbridges", foreignKey: "staffId"});
  crewstaffbridge.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(crewstaffbridge, { as: "crewstaffbridges", foreignKey: "staffId"});
  jobstaffbridge.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(jobstaffbridge, { as: "jobstaffbridges", foreignKey: "staffId"});
  payrolldue.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(payrolldue, { as: "payrolldues", foreignKey: "staffId"});
  payrollsatisfaction.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(payrollsatisfaction, { as: "payrollsatisfactions", foreignKey: "staffId"});
  staffemailaddress.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(staffemailaddress, { as: "staffemailaddresses", foreignKey: "staffId"});
  staffloginattempt.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(staffloginattempt, { as: "staffloginattempts", foreignKey: "staffId"});
  staffphonenumber.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(staffphonenumber, { as: "staffphonenumbers", foreignKey: "staffId"});
  staffsavedlogin.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(staffsavedlogin, { as: "staffsavedlogins", foreignKey: "staffId"});
  staffstafftagbridge.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(staffstafftagbridge, { as: "staffstafftagbridges", foreignKey: "staffId"});
  timelog.belongsTo(staff, { as: "staff", foreignKey: "staffId"});
  staff.hasMany(timelog, { as: "timelogs", foreignKey: "staffId"});
  staffstafftagbridge.belongsTo(stafftag, { as: "staffTag", foreignKey: "staffTagId"});
  stafftag.hasMany(staffstafftagbridge, { as: "staffstafftagbridges", foreignKey: "staffTagId"});

  return {
    admin,
    adminbusinessbridge,
    admincustomerservicemessage,
    adminloginattempt,
    adminsavedlogin,
    authtoken,
    blogpost,
    blogpostblogtagbridge,
    blogpostreadtoken,
    blogtag,
    business,
    businessplanpayment,
    chemical,
    chemicalapplication,
    chemicalchemicaltagbridge,
    chemicalimage,
    chemicaltag,
    completedjob,
    crew,
    crewcrewtag,
    crewleaderbridge,
    crewstaffbridge,
    crewtag,
    customer,
    customercustomerservicemessage,
    customercustomertagbridge,
    customeremailaddress,
    customeremailaddressemailsendbridge,
    customerloginattempt,
    customerphonenumber,
    customerphonenumbersmssendbridge,
    customersavedlogin,
    customerserviceticket,
    customertag,
    docid,
    emailpixel,
    emailqueuemessage,
    emailsend,
    emailsubscriptionbridge,
    emailtemplate,
    equipment,
    equipmentchemicalbridge,
    equipmentequipmenttagbridge,
    equipmentimage,
    equipmentmaintenancelog,
    equipmenttag,
    estimate,
    estimateitem,
    fileupload,
    invoice,
    invoiceitem,
    job,
    jobcrewbridge,
    jobinstanceexception,
    jobstaffbridge,
    payment,
    paymentmethod,
    payrolldue,
    payrollsatisfaction,
    property,
    quoterequest,
    quoterequestservice,
    servicelisting,
    smssend,
    smssubscriptionbridge,
    smstemplate,
    staff,
    staffemailaddress,
    staffloginattempt,
    staffphonenumber,
    staffsavedlogin,
    staffstafftagbridge,
    stafftag,
    timelog,
  };
}
module.exports = initModels;
module.exports.initModels = initModels;
module.exports.default = initModels;

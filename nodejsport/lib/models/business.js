const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('business', {
    businessId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    displayName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    adminDisplayName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    fullLogoFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    address1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    address2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    state: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    city: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    zipCode: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    phonePrefix: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    phone1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    phone2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    phone3: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    email: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    currencySymbol: {
      type: DataTypes.STRING(1),
      allowNull: false,
      defaultValue: "$"
    },
    areaSymbol: {
      type: DataTypes.STRING(5),
      allowNull: false,
      defaultValue: "ft"
    },
    distanceSymbol: {
      type: DataTypes.STRING(5),
      allowNull: false,
      defaultValue: "mi"
    },
    timeZone: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    plan: {
      type: DataTypes.STRING(20),
      allowNull: false,
      defaultValue: "free"
    },
    planUntilDateTime: {
      type: DataTypes.DATE,
      allowNull: true
    },
    modCust: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modEmail: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modInv: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modInvIncludePastBal: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modEst: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modProp: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modJobs: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modEquip: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modChem: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modStaff: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modCrews: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modPayr: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modPayrSatLinkedToDue: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    modPayrSalDefaultType: {
      type: DataTypes.STRING(10),
      allowNull: false,
      defaultValue: "none"
    },
    modPayrSalBaseHourlyRate: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    modPayrSalBaseJobPercent: {
      type: DataTypes.INTEGER,
      allowNull: false
    },
    modPayrSalBasePerJob: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    docIdMin: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 1
    },
    docIdIsRandom: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    invoiceTerm: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    estimateValidity: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    creditAlertIsEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    creditAlertAmount: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    autoApplyCredit: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    balanceAlertIsEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    balanceAlertAmount: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    SZEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: false
    },
    SZModInfoForStaffPage: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModInfoForStaffPageShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModInfoForStaffPageBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    SZModPersInfo: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditName: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditPhone: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditEmail: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditAddress: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditUsername: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPersInfoAllowEditPassword: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModCrews: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModJobs: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModJobsShowCrewJobs: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPayr: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModPayrShowDetails: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModContactAdmin: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModQuit: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    SZModQuitNoticeTerm: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 0
    },
    CPEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModHomeShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModHomeBodyFile: {
      type: DataTypes.BOOLEAN,
      allowNull: true
    },
    CPModTopBar: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTopBarShowLogo: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTopBarLogoFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModTopBarShowQuote: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTopBarShowNav: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModServices: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModServicesShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModServicesBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModServicesShowList: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModContact: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModContactShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModContactBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModContactShowForm: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModContactShowInfo: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModAbout: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModAboutShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModAboutBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModQuote: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModQuoteShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModQuoteBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModQuoteShowForm: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModBlog: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModBlogShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModBlogBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModBlogShowPosts: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTOS: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTOSShowBody: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTOSBodyFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    CPModTOSShowInvTerm: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModTOSShowEstTerm: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZ: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZJobs: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZInvoices: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZEstimates: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfo: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditName: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditPhone: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditEmail: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditAddress: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditUsername: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZPersInfoAllowEditPassword: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZContactStaff: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZContactStaffAllowOwnerContact: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZContactStaffAllowAdminContact: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    CPModCZServiceRequest: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isArchived: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'business',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

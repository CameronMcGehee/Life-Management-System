const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('customer', {
    customerId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    businessId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'business',
        key: 'businessId'
      }
    },
    firstName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    lastName: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    nameIndex: {
      type: DataTypes.STRING(3),
      allowNull: false
    },
    billAddress1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    billAddress2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    billCity: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    billState: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    billZipCode: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    creditCache: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    overrideCreditAlertIsEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: true
    },
    overrideCreditAlertAmount: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    overrideAutoApplyCredit: {
      type: DataTypes.BOOLEAN,
      allowNull: true
    },
    balanceCache: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    overrideBalanceAlertIsEnabled: {
      type: DataTypes.BOOLEAN,
      allowNull: true
    },
    overrideBalanceAlertAmount: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    allowCZSignIn: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    password: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    discountPercent: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    overridePaymentTerm: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    notes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customer',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
      {
        name: "customerBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

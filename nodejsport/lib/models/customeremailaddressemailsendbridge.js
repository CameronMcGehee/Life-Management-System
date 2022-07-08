const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customeremailaddressemailsendbridge', {
    customerEmailAddressEmailSendId: {
      autoIncrement: true,
      type: DataTypes.INTEGER,
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
    customerEmailAddressId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customeremailaddress',
        key: 'customerEmailAddressId'
      }
    },
    emailSendId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'emailsend',
        key: 'emailSendId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customeremailaddressemailsendbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerEmailAddressEmailSendId" },
        ]
      },
      {
        name: "customerEmailAddressEmailSendBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerEmailAddressEmailSendBridgeCustomerEmailAddressId",
        using: "BTREE",
        fields: [
          { name: "customerEmailAddressId" },
        ]
      },
      {
        name: "customerEmailAddressEmailSendBridgeEmailSendId",
        using: "BTREE",
        fields: [
          { name: "emailSendId" },
        ]
      },
    ]
  });
};

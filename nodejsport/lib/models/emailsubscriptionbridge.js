const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('emailsubscriptionbridge', {
    emailSubscriptionId: {
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
    emailTemplateId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'emailtemplate',
        key: 'emailTemplateId'
      }
    },
    frequencyInterval: {
      type: DataTypes.STRING(10),
      allowNull: false,
      defaultValue: "none"
    },
    frequency: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 0
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'emailsubscriptionbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "emailSubscriptionId" },
        ]
      },
      {
        name: "emailSubscriptionBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "emailSubscriptionBridgeCustomerEmailAddressId",
        using: "BTREE",
        fields: [
          { name: "customerEmailAddressId" },
        ]
      },
      {
        name: "emailSubscriptionBridgeEmailTemplateId",
        using: "BTREE",
        fields: [
          { name: "emailTemplateId" },
        ]
      },
    ]
  });
};

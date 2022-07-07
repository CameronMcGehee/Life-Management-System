const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('smssubscriptionbridge', {
    smsSubscriptionId: {
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
    customerPhoneNumberId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customerphonenumber',
        key: 'customerPhoneNumberId'
      }
    },
    smsTemplateId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'smstemplate',
        key: 'smsTemplateId'
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
    tableName: 'smssubscriptionbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "smsSubscriptionId" },
        ]
      },
      {
        name: "smsSubscriptionBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "smsSubscriptionBridgeCustomerPhoneNumberId",
        using: "BTREE",
        fields: [
          { name: "customerPhoneNumberId" },
        ]
      },
      {
        name: "smsSubscriptionBridgeSmsTemplateId",
        using: "BTREE",
        fields: [
          { name: "smsTemplateId" },
        ]
      },
    ]
  });
};

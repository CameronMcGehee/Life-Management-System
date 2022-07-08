const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customerphonenumbersmssendbridge', {
    customerPhoneNumberSmsSendId: {
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
    smsSendId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'smssend',
        key: 'smsSendId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customerphonenumbersmssendbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerPhoneNumberSmsSendId" },
        ]
      },
      {
        name: "customerPhoneNumberSmsSendBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerPhoneNumberSmsSendBridgeCustomerPhoneNumberId",
        using: "BTREE",
        fields: [
          { name: "customerPhoneNumberId" },
        ]
      },
      {
        name: "customerPhoneNumberSmsSendBridgeSmsSendId",
        using: "BTREE",
        fields: [
          { name: "smsSendId" },
        ]
      },
    ]
  });
};

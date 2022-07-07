const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('customercustomertagbridge', {
    customerCustomerTagId: {
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
    customerId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    customerTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customertag',
        key: 'customerTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customercustomertagbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerCustomerTagId" },
        ]
      },
      {
        name: "customerCustomerTagBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerCustomerTagCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
      {
        name: "customerCustomerTagCustomerTagId",
        using: "BTREE",
        fields: [
          { name: "customerTagId" },
        ]
      },
    ]
  });
};

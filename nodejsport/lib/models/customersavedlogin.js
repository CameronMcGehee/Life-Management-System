const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('customersavedlogin', {
    customerSavedLoginId: {
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
    customerId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customersavedlogin',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerSavedLoginId" },
        ]
      },
      {
        name: "customerSavedLoginBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerSavedLoginCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
    ]
  });
};

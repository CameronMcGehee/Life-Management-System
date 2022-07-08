const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customerloginattempt', {
    customerLoginAttemptId: {
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
      allowNull: true,
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    clientIp: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    result: {
      type: DataTypes.STRING(5),
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'customerloginattempt',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerLoginAttemptId" },
        ]
      },
      {
        name: "customerLoginAttemptBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerLoginAttemptCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
    ]
  });
};

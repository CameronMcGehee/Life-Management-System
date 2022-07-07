const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('payment', {
    paymentId: {
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
    linkedToInvoiceId: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    linkedToPaymentMethodId: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    methodName: {
      type: DataTypes.STRING(20),
      allowNull: false
    },
    methodPercentCut: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    methodAmountCut: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    amount: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    notes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    excessWasAddedToCredit: {
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
    tableName: 'payment',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "paymentId" },
        ]
      },
      {
        name: "paymentBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "paymentCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
      {
        name: "paymentLinkedToInvoiceInvoiceId",
        using: "BTREE",
        fields: [
          { name: "linkedToInvoiceId" },
        ]
      },
    ]
  });
};

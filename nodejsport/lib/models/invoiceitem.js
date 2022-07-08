const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('invoiceitem', {
    invoiceItemId: {
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
    invoiceId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'invoice',
        key: 'invoiceId'
      }
    },
    name: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    price: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    taxIsPercent: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    tax: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    quantity: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 1
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'invoiceitem',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "invoiceItemId" },
        ]
      },
      {
        name: "invoiceItemBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "invoiceItemInvoiceId",
        using: "BTREE",
        fields: [
          { name: "invoiceId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('invoice', {
    invoiceId: {
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
    docIdId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'docid',
        key: 'docIdId'
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
    discountIsPercent: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    discount: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    customJobDetails: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    comments: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    privateNotes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    isManualPaid: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isViewed: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isEmailed: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isOverdueNotified: {
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
    tableName: 'invoice',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "invoiceId" },
        ]
      },
      {
        name: "invoiceBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "invoiceCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
      {
        name: "invoiceDocIdId",
        using: "BTREE",
        fields: [
          { name: "docIdId" },
        ]
      },
    ]
  });
};

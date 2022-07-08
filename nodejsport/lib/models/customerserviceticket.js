const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customerserviceticket', {
    customerServiceTicketId: {
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
    linkedToCustomerId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK",
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    linkedToInvoiceId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToEstimateId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToQuoteRequestId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    customerName: {
      type: DataTypes.TEXT,
      allowNull: true,
      comment: "NULL if linkedToCustomerId"
    },
    customerEmail: {
      type: DataTypes.TEXT,
      allowNull: true,
      comment: "NULL if linkedToCustomerId"
    },
    subject: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    isResolved: {
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
    tableName: 'customerserviceticket',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerServiceTicketId" },
        ]
      },
      {
        name: "customerServiceTicketBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerServiceTicketDocIdId",
        using: "BTREE",
        fields: [
          { name: "docIdId" },
        ]
      },
      {
        name: "customerServiceTicketLinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
      {
        name: "customerServiceTicketLinkedToInvoiceId",
        using: "BTREE",
        fields: [
          { name: "linkedToInvoiceId" },
        ]
      },
      {
        name: "customerServiceTicketLinkedToEstimateId",
        using: "BTREE",
        fields: [
          { name: "linkedToEstimateId" },
        ]
      },
      {
        name: "customerServiceTicketLinkedToQuoteRequestId",
        using: "BTREE",
        fields: [
          { name: "linkedToQuoteRequestId" },
        ]
      },
    ]
  });
};

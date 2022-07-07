const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('quoterequest', {
    quoteRequestId: {
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
    linkedToCustomerId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK",
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    name: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    email: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    address1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    address2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    state: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    zipCode: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'quoterequest',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "quoteRequestId" },
        ]
      },
      {
        name: "quoteRequestBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "quoteRequestlinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
    ]
  });
};

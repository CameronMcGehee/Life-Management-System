const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('property', {
    propertyId: {
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
    address1: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    address2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    city: {
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
    lawnSize: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    mulchQuantity: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    pricePerMow: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'property',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "propertyId" },
        ]
      },
      {
        name: "propertyBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "propertyCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
    ]
  });
};

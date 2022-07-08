const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('chemicalapplication', {
    chemicalApplicationId: {
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
    chemicalId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'chemical',
        key: 'chemicalId'
      }
    },
    propertyId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'property',
        key: 'propertyId'
      }
    },
    linkedToCrewId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToStaffId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToJobCompletedId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    weatherDescription: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    amountApplied: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    wasSubtractedFromStock: {
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
    tableName: 'chemicalapplication',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "chemicalApplicationId" },
        ]
      },
      {
        name: "chemicalApplicationBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "chemicalApplicationChemicalId",
        using: "BTREE",
        fields: [
          { name: "chemicalId" },
        ]
      },
      {
        name: "chemicalApplicationPropertyId",
        using: "BTREE",
        fields: [
          { name: "propertyId" },
        ]
      },
      {
        name: "chemicalApplicationLinkedToCrewId",
        using: "BTREE",
        fields: [
          { name: "linkedToCrewId" },
        ]
      },
      {
        name: "chemicalApplicationLinkedToStaffId",
        using: "BTREE",
        fields: [
          { name: "linkedToStaffId" },
        ]
      },
      {
        name: "chemicalApplicationLinkedToJobCompletedId",
        using: "BTREE",
        fields: [
          { name: "linkedToJobCompletedId" },
        ]
      },
    ]
  });
};

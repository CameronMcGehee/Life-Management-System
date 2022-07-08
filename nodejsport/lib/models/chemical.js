const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('chemical', {
    chemicalId: {
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
    name: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    epa: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    ingeredients: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    manufacturer: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dilution: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    targets: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    applicationMethod: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    applicationRate: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    defaultAmountApplied: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    defaultAmountAppliedUnit: {
      type: DataTypes.STRING(10),
      allowNull: false,
      defaultValue: "ml\/ft²"
    },
    amountInStock: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    amountInStockUnit: {
      type: DataTypes.STRING(10),
      allowNull: false,
      defaultValue: "ml"
    },
    notesToCustomer: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    notesToStaff: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    condition: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    purchaseDate: {
      type: DataTypes.DATE,
      allowNull: true
    },
    purchasePrice: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    storageLocation: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'chemical',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "chemicalId" },
        ]
      },
      {
        name: "chemicalBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "chemicalLinkedToCrewId",
        using: "BTREE",
        fields: [
          { name: "linkedToCrewId" },
        ]
      },
      {
        name: "chemicalLinkedToStaffId",
        using: "BTREE",
        fields: [
          { name: "linkedToStaffId" },
        ]
      },
    ]
  });
};

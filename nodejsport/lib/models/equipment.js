const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('equipment', {
    equipmentId: {
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
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    condition: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    model: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    serialNumber: {
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
    tableName: 'equipment',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "equipmentId" },
        ]
      },
      {
        name: "equipmentBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "equipmentLinkedToCrewId",
        using: "BTREE",
        fields: [
          { name: "linkedToCrewId" },
        ]
      },
      {
        name: "equipmentLinkedToStaffId",
        using: "BTREE",
        fields: [
          { name: "linkedToStaffId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('equipmentchemicalbridge', {
    equipmentChemicalId: {
      autoIncrement: true,
      type: DataTypes.INTEGER,
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
    equipmentId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'equipment',
        key: 'equipmentId'
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
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'equipmentchemicalbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "equipmentChemicalId" },
        ]
      },
      {
        name: "equipmentChemicalBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "equipmentChemicalBridgeEquipmentId",
        using: "BTREE",
        fields: [
          { name: "equipmentId" },
        ]
      },
      {
        name: "equipmentChemicalBridgeChemicalId",
        using: "BTREE",
        fields: [
          { name: "chemicalId" },
        ]
      },
    ]
  });
};

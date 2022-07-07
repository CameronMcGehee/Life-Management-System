const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('equipmentequipmenttagbridge', {
    equipmentEquipmentTagId: {
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
    equipmentTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'equipmenttag',
        key: 'equipmentTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'equipmentequipmenttagbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "equipmentEquipmentTagId" },
        ]
      },
      {
        name: "equipmentEquipmentTagBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "equipmentEquipmentTagEquipmentId",
        using: "BTREE",
        fields: [
          { name: "equipmentId" },
        ]
      },
      {
        name: "equipmentEquipmentTagEquipmentTagId",
        using: "BTREE",
        fields: [
          { name: "equipmentTagId" },
        ]
      },
    ]
  });
};

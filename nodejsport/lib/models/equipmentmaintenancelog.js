const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('equipmentmaintenancelog', {
    equipmentMaintenanceLogId: {
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
    equipmentId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'equipment',
        key: 'equipmentId'
      }
    },
    title: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    details: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'equipmentmaintenancelog',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "equipmentMaintenanceLogId" },
        ]
      },
      {
        name: "equipmentMaintenanceLogBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "equipmentMaintenanceLogEquipmentId",
        using: "BTREE",
        fields: [
          { name: "equipmentId" },
        ]
      },
    ]
  });
};

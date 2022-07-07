const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('equipmentimage', {
    equipmentImageId: {
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
    imageFile: {
      type: DataTypes.STRING(17),
      allowNull: false
    },
    caption: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'equipmentimage',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "equipmentImageId" },
        ]
      },
      {
        name: "equipmentImageBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "equipmentImageEquipmentId",
        using: "BTREE",
        fields: [
          { name: "equipmentId" },
        ]
      },
    ]
  });
};

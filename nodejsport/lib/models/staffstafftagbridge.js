const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('staffstafftagbridge', {
    staffStaffTagId: {
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
    staffId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    staffTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'stafftag',
        key: 'staffTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staffstafftagbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffStaffTagId" },
        ]
      },
      {
        name: "staffStaffTagBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "staffStaffTagStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
      {
        name: "staffStaffTagStaffTagId",
        using: "BTREE",
        fields: [
          { name: "staffTagId" },
        ]
      },
    ]
  });
};

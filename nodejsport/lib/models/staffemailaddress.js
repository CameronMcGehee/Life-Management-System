const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('staffemailaddress', {
    staffEmailAddressId: {
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
    staffId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    email: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staffemailaddress',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffEmailAddressId" },
        ]
      },
      {
        name: "staffEmailAddressBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "staffEmailAddressStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
    ]
  });
};

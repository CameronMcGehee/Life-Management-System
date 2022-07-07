const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('staffsavedlogin', {
    staffSavedLoginId: {
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
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staffsavedlogin',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffSavedLoginId" },
        ]
      },
      {
        name: "staffSavedLoginBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "staffSavedLoginStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('staffloginattempt', {
    staffLoginAttemptId: {
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
      allowNull: true,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    clientIp: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    result: {
      type: DataTypes.STRING(5),
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staffloginattempt',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffLoginAttemptId" },
        ]
      },
      {
        name: "staffLoginAttemptBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "staffLoginAttemptStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
    ]
  });
};

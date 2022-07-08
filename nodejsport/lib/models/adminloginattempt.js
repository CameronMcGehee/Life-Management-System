const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('adminloginattempt', {
    adminLoginAttemptId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      references: {
        model: 'admin',
        key: 'adminId'
      }
    },
    clientIp: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    result: {
      type: DataTypes.STRING(20),
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'adminloginattempt',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "adminLoginAttemptId" },
        ]
      },
      {
        name: "adminLoginAttemptAdminId",
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
    ]
  });
};

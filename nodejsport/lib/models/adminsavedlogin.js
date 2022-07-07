const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('adminsavedlogin', {
    adminSavedLoginId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'admin',
        key: 'adminId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'adminsavedlogin',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "adminSavedLoginId" },
        ]
      },
      {
        name: "adminSavedLoginAdminId",
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
    ]
  });
};

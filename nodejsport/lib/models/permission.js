const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('permission', {
    permissionId: {
      type: DataTypes.STRING(37),
      allowNull: false,
      primaryKey: true
    },
    apiKeyId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'apiKey',
        key: 'apiKeyId'
      }
    },
    permissionName: {
      type: DataTypes.STRING(50),
      allowNull: true
    },
    expiration: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'permission',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "permissionId" },
        ]
      },
      // HERE,
    ]
  });
};

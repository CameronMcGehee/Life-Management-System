const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('admin', {
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    username: {
      type: DataTypes.STRING(200),
      allowNull: false,
      unique: "adminUsername"
    },
    password: {
      type: DataTypes.STRING(64),
      allowNull: false
    },
    email: {
      type: DataTypes.STRING(200),
      allowNull: false,
      unique: "adminEmail"
    },
    firstName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    lastName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    profilePicture: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    allowSignIn: {
      type: DataTypes.BOOLEAN,
      allowNull: false
    },
    dateTimeJoined: {
      type: DataTypes.DATE,
      allowNull: false
    },
    dateTimeLeft: {
      type: DataTypes.DATE,
      allowNull: true
    }
  }, {
    sequelize,
    tableName: 'admin',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
      {
        name: "adminUsername",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "username" },
        ]
      },
      {
        name: "adminEmail",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "email" },
        ]
      },
    ]
  });
};

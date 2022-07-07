const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('emailpixel', {
    emailPixelId: {
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
    emailSendId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'emailsend',
        key: 'emailSendId'
      }
    },
    pixelFile: {
      type: DataTypes.STRING(17),
      allowNull: false
    },
    dateTimeRead: {
      type: DataTypes.DATE,
      allowNull: true
    },
    clientIpRead: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'emailpixel',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "emailPixelId" },
        ]
      },
      {
        name: "emailPixelBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "emailPixelEmailSendId",
        using: "BTREE",
        fields: [
          { name: "emailSendId" },
        ]
      },
    ]
  });
};

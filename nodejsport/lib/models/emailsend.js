const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('emailsend', {
    emailSendId: {
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
    linkedToEmailSubscriptionId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToEmailTemplateId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    toEmail: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    subject: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    contentHtmlFile: {
      type: DataTypes.STRING(17),
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'emailsend',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "emailSendId" },
        ]
      },
      {
        name: "emailSendBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "emailSendLinkedToEmailSubscriptionId",
        using: "BTREE",
        fields: [
          { name: "linkedToEmailSubscriptionId" },
        ]
      },
      {
        name: "emailSendLinkedToEmailTemplateId",
        using: "BTREE",
        fields: [
          { name: "linkedToEmailTemplateId" },
        ]
      },
    ]
  });
};

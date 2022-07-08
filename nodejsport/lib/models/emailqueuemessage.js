const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('emailqueuemessage', {
    emailQueueMessageId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      primaryKey: true
    },
    linkedToBusinessId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
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
    messageType: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    templateVarInputs: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    toEmails: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    ccEmails: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    bccEmails: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    fromName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    subject: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    contentHtml: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'emailqueuemessage',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "emailQueueMessageId" },
        ]
      },
      {
        name: "emailQueueMessageLinkedToBusinessId",
        using: "BTREE",
        fields: [
          { name: "linkedToBusinessId" },
        ]
      },
      {
        name: "emailQueueMessageLinkedToEmailSubscriptionId",
        using: "BTREE",
        fields: [
          { name: "linkedToEmailSubscriptionId" },
        ]
      },
      {
        name: "emailQueueMessageLinkedToEmailTemplateId",
        using: "BTREE",
        fields: [
          { name: "linkedToEmailTemplateId" },
        ]
      },
    ]
  });
};

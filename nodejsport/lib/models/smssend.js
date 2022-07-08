const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('smssend', {
    smsSendId: {
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
    linkedToSmsSubscriptionId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToSmsCampaignTemplateId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    message: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'smssend',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "smsSendId" },
        ]
      },
      {
        name: "smsSendBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "smsSendLinkedToSmsSubscriptionId",
        using: "BTREE",
        fields: [
          { name: "linkedToSmsSubscriptionId" },
        ]
      },
      {
        name: "smsSendLinkedToSmsCampaignTemplateId",
        using: "BTREE",
        fields: [
          { name: "linkedToSmsCampaignTemplateId" },
        ]
      },
    ]
  });
};

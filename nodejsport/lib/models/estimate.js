const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('estimate', {
    estimateId: {
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
    docIdId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'docid',
        key: 'docIdId'
      }
    },
    customerId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customer',
        key: 'customerId'
      }
    },
    discountIsPercent: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    discount: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    customJobDetails: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    comments: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    privateNotes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    isViewed: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isEmailed: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    approvedByAdminId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    adminReason: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeApproved: {
      type: DataTypes.DATE,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'estimate',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "estimateId" },
        ]
      },
      {
        name: "estimateBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "estimateCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
      {
        name: "estimateDocIdId",
        using: "BTREE",
        fields: [
          { name: "docIdId" },
        ]
      },
    ]
  });
};

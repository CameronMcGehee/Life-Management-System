const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('fileupload', {
    fileUploadId: {
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
    linkedToStaffId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToCustomerId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'fileupload',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "fileUploadId" },
        ]
      },
      {
        name: "fileUploadBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "fileUploadLinkedToStaffId",
        using: "BTREE",
        fields: [
          { name: "linkedToStaffId" },
        ]
      },
      {
        name: "fileUploadLinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
      {
        name: "fileUploadDocIdId",
        using: "BTREE",
        fields: [
          { name: "docIdId" },
        ]
      },
    ]
  });
};

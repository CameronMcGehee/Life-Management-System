const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('payrolldue', {
    payrollDueId: {
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
    staffId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    linkedToTimeLogId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToJobCompletedId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    amount: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    notes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    isManualPaid: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'payrolldue',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "payrollDueId" },
        ]
      },
      {
        name: "payrollDueBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "payrollDueStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
      {
        name: "payrollDueLinkedToTimeLogId",
        using: "BTREE",
        fields: [
          { name: "linkedToTimeLogId" },
        ]
      },
      {
        name: "payrollDueLinkedToJobCompletedId",
        using: "BTREE",
        fields: [
          { name: "linkedToJobCompletedId" },
        ]
      },
    ]
  });
};

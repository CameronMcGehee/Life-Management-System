const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('businessplanpayment', {
    businessPlanPaymentId: {
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
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'admin',
        key: 'adminId'
      }
    },
    method: {
      type: DataTypes.STRING(20),
      allowNull: false
    },
    amount: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    notes: {
      type: DataTypes.STRING(50),
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'businessplanpayment',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "businessPlanPaymentId" },
        ]
      },
      {
        name: "businessPlanPaymentBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "businessPlanPaymentAdminId",
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
    ]
  });
};

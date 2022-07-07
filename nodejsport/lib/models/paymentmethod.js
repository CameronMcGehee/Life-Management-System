const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('paymentmethod', {
    paymentMethodId: {
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
    name: {
      type: DataTypes.STRING(20),
      allowNull: false
    },
    percentCut: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    amountCut: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    notes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'paymentmethod',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "paymentMethodId" },
        ]
      },
      {
        name: "paymentMethodBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

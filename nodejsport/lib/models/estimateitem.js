const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('estimateitem', {
    estimateItemId: {
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
    estimateId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'estimate',
        key: 'estimateId'
      }
    },
    name: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    price: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    taxIsPercent: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    tax: {
      type: DataTypes.FLOAT,
      allowNull: false,
      defaultValue: 0
    },
    quantity: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 1
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'estimateitem',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "estimateItemId" },
        ]
      },
      {
        name: "estimateItemBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "estimateItemEstimateId",
        using: "BTREE",
        fields: [
          { name: "estimateId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('servicelisting', {
    serviceListingId: {
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
      type: DataTypes.TEXT,
      allowNull: false
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    imgFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    price: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    minPrice: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    maxPrice: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    isRequestable: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'servicelisting',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "serviceListingId" },
        ]
      },
      {
        name: "serviceListingBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

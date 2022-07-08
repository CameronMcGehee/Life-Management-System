const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('chemicalimage', {
    chemicalImageId: {
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
    chemicalId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'chemical',
        key: 'chemicalId'
      }
    },
    imageFile: {
      type: DataTypes.STRING(17),
      allowNull: false
    },
    caption: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'chemicalimage',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "chemicalImageId" },
        ]
      },
      {
        name: "chemicalImageBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "chemicalImageChemicalId",
        using: "BTREE",
        fields: [
          { name: "chemicalId" },
        ]
      },
    ]
  });
};

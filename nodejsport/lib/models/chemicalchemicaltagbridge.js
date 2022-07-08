const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('chemicalchemicaltagbridge', {
    chemicalChemicalTagId: {
      autoIncrement: true,
      type: DataTypes.INTEGER,
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
    chemicalTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'chemicaltag',
        key: 'chemicalTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'chemicalchemicaltagbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "chemicalChemicalTagId" },
        ]
      },
      {
        name: "chemicalChemicalTagBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "chemicalChemicalTagChemicalId",
        using: "BTREE",
        fields: [
          { name: "chemicalId" },
        ]
      },
      {
        name: "chemicalChemicalTagChemicalTagId",
        using: "BTREE",
        fields: [
          { name: "chemicalTagId" },
        ]
      },
    ]
  });
};

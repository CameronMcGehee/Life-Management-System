const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('crewcrewtag', {
    crewCrewTagId: {
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
    crewId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'crew',
        key: 'crewId'
      }
    },
    crewTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'crewtag',
        key: 'crewTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'crewcrewtag',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "crewCrewTagId" },
        ]
      },
      {
        name: "crewCrewTagBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "crewCrewTagCrewId",
        using: "BTREE",
        fields: [
          { name: "crewId" },
        ]
      },
      {
        name: "crewCrewTagCrewTagId",
        using: "BTREE",
        fields: [
          { name: "crewTagId" },
        ]
      },
    ]
  });
};

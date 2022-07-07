const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('jobcrewbridge', {
    jobCrewId: {
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
    jobId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'job',
        key: 'jobId'
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
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'jobcrewbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "jobCrewId" },
        ]
      },
      {
        name: "jobCrewBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "jobCrewBridgeJobId",
        using: "BTREE",
        fields: [
          { name: "jobId" },
        ]
      },
      {
        name: "jobCrewBridgecrewId",
        using: "BTREE",
        fields: [
          { name: "crewId" },
        ]
      },
    ]
  });
};

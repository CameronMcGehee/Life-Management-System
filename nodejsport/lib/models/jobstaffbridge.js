const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('jobstaffbridge', {
    jobStaffId: {
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
    staffId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'jobstaffbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "jobStaffId" },
        ]
      },
      {
        name: "jobStaffBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "jobStaffBridgeJobId",
        using: "BTREE",
        fields: [
          { name: "jobId" },
        ]
      },
      {
        name: "jobStaffBridgeStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
    ]
  });
};

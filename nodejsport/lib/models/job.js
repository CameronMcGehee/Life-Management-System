const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('job', {
    jobId: {
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
    linkedToJobId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToCustomerId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    linkedToPropertyId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    name: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    privateNotes: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    price: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    estHours: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    isPrepaid: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    frequencyInterval: {
      type: DataTypes.STRING(10),
      allowNull: false,
      defaultValue: "none"
    },
    frequency: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 0
    },
    weekday: {
      type: DataTypes.STRING(20),
      allowNull: true
    },
    startDateTime: {
      type: DataTypes.DATE,
      allowNull: false
    },
    endDateTime: {
      type: DataTypes.DATE,
      allowNull: true
    },
    isCancelled: {
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
    tableName: 'job',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "jobId" },
        ]
      },
      {
        name: "jobBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "jobLinkedToJobId",
        using: "BTREE",
        fields: [
          { name: "linkedToJobId" },
        ]
      },
      {
        name: "jobLinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
      {
        name: "jobLinkedToPropertyId",
        using: "BTREE",
        fields: [
          { name: "linkedToPropertyId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('jobinstanceexception', {
    jobInstanceExceptionId: {
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
    jobId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'job',
        key: 'jobId'
      }
    },
    instanceDate: {
      type: DataTypes.DATEONLY,
      allowNull: false
    },
    isRescheduled: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isCancelled: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    isCompleted: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    linkedToCompletedJobId: {
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
    startDateTime: {
      type: DataTypes.DATE,
      allowNull: false
    },
    endDateTime: {
      type: DataTypes.DATE,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'jobinstanceexception',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "jobInstanceExceptionId" },
        ]
      },
      {
        name: "jobInstanceExceptionBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "jobInstanceExceptionJobId",
        using: "BTREE",
        fields: [
          { name: "jobId" },
        ]
      },
      {
        name: "jobInstanceExceptionLinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
      {
        name: "jobInstanceExceptionLinkedToPropertyId",
        using: "BTREE",
        fields: [
          { name: "linkedToPropertyId" },
        ]
      },
    ]
  });
};

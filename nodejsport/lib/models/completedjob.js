const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('completedjob', {
    completedJobId: {
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
    customerFirstName: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    customerLastName: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    propertyAddress1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    propertyAddress2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    propertyCity: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    propertyState: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    propertyZipCode: {
      type: DataTypes.INTEGER,
      allowNull: true
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
    instanceDate: {
      type: DataTypes.DATE,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'completedjob',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "completedJobId" },
        ]
      },
      {
        name: "completedJobBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "completedJobLinkedToJobId",
        using: "BTREE",
        fields: [
          { name: "linkedToJobId" },
        ]
      },
      {
        name: "completedJobLinkedToCustomerId",
        using: "BTREE",
        fields: [
          { name: "linkedToCustomerId" },
        ]
      },
      {
        name: "completedJobLinkedToPropertyId",
        using: "BTREE",
        fields: [
          { name: "linkedToPropertyId" },
        ]
      },
    ]
  });
};

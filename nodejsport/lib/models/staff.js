const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('staff', {
    staffId: {
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
    firstName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    lastName: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    profilePicture: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    jobTitle: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    bio: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    payrollAddress1: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    payrollAddress2: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    payrollState: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    payrollCity: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    payrollZipCode: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    overridePayrollType: {
      type: DataTypes.STRING(10),
      allowNull: true
    },
    overrideHourlyRate: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    overridePerJobRate: {
      type: DataTypes.FLOAT,
      allowNull: true
    },
    overrideJobPercentage: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    payrollDueCache: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    advancePaymentCache: {
      type: DataTypes.FLOAT,
      allowNull: false
    },
    allowSignIn: {
      type: DataTypes.BOOLEAN,
      allowNull: false
    },
    password: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staff',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
      {
        name: "staffBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

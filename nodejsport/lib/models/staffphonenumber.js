const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('staffphonenumber', {
    staffPhoneNumberId: {
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
    staffId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'staff',
        key: 'staffId'
      }
    },
    phonePrefix: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    phone1: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    phone2: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    phone3: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'staffphonenumber',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "staffPhoneNumberId" },
        ]
      },
      {
        name: "staffPhoneNumberBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "staffPhoneNumberStaffId",
        using: "BTREE",
        fields: [
          { name: "staffId" },
        ]
      },
    ]
  });
};

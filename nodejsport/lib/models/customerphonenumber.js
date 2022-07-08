const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customerphonenumber', {
    customerPhoneNumberId: {
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
    customerId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customer',
        key: 'customerId'
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
      allowNull: true
    },
    phone3: {
      type: DataTypes.TEXT,
      allowNull: true
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
    tableName: 'customerphonenumber',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerPhoneNumberId" },
        ]
      },
      {
        name: "customerPhoneNumberBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerPhoneNumberCustomerId",
        using: "BTREE",
        fields: [
          { name: "customerId" },
        ]
      },
    ]
  });
};

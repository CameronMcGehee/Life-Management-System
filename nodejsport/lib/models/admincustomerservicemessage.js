const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('admincustomerservicemessage', {
    adminCustomerServiceMessageId: {
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
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'admin',
        key: 'adminId'
      }
    },
    customerServiceTicketId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'customerserviceticket',
        key: 'customerServiceTicketId'
      }
    },
    message: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    isReadByCustomer: {
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
    tableName: 'admincustomerservicemessage',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "adminCustomerServiceMessageId" },
        ]
      },
      {
        name: "adminCustomerServiceMessageBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "adminCustomerServiceMessageAdminId",
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
      {
        name: "adminCustomerServiceMessageCustomerServiceTicketId",
        using: "BTREE",
        fields: [
          { name: "customerServiceTicketId" },
        ]
      },
    ]
  });
};

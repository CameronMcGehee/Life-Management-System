const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('customercustomerservicemessage', {
    customerCustomerServiceMessageId: {
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
    isReadByAdmin: {
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
    tableName: 'customercustomerservicemessage',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "customerCustomerServiceMessageId" },
        ]
      },
      {
        name: "customerCustomerServiceMessageBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "customerCustomerServiceMessageCustomerServiceTicketId",
        using: "BTREE",
        fields: [
          { name: "customerServiceTicketId" },
        ]
      },
    ]
  });
};

const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('adminbusinessbridge', {
    adminBusinessId: {
      autoIncrement: true,
      type: DataTypes.INTEGER,
      allowNull: false,
      primaryKey: true
    },
    adminId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'admin',
        key: 'adminId'
      }
    },
    businessId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'business',
        key: 'businessId'
      }
    },
    adminIsOwner: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    adminCanManageTag: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanUploadDocument: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageBlog: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageSMS: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageEmail: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageServiceListing: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageQuoteRequest: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageCustomerService: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageTimeLog: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManagePayrollDue: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManagePayrollSatisfaction: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageCustomer: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageStaff: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageCrew: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageEquipment: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageChemical: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageJob: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageInvoice: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManagePayment: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanManageEstimate: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    adminCanApproveEstimate: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 1
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'adminbusinessbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "adminBusinessId" },
        ]
      },
      {
        name: "adminBusinessBridgeAdminId",
        using: "BTREE",
        fields: [
          { name: "adminId" },
        ]
      },
      {
        name: "adminBusinessBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

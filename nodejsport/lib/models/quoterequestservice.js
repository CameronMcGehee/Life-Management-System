const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('quoterequestservice', {
    quoteRequestServiceId: {
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
    quoteRequestId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'quoterequest',
        key: 'quoteRequestId'
      }
    },
    linkedToServiceListingId: {
      type: DataTypes.STRING(17),
      allowNull: true,
      comment: "Optional FK"
    },
    currentName: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    currentDescription: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    currentImgFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    currentPrice: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    currentMinPrice: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    currentMaxPrice: {
      type: DataTypes.INTEGER,
      allowNull: true
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'quoterequestservice',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "quoteRequestServiceId" },
        ]
      },
      {
        name: "quoteRequestServiceBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "quoteRequestServiceQuoteRequestId",
        using: "BTREE",
        fields: [
          { name: "quoteRequestId" },
        ]
      },
      {
        name: "quoteRequestServicelinkedToServiceListingId",
        using: "BTREE",
        fields: [
          { name: "linkedToServiceListingId" },
        ]
      },
    ]
  });
};

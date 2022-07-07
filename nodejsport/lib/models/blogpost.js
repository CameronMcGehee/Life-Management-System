const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes) {
  return sequelize.define('blogpost', {
    blogPostId: {
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
    author: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    title: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    description: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    bodyFile: {
      type: DataTypes.STRING(17),
      allowNull: false
    },
    imgFile: {
      type: DataTypes.STRING(17),
      allowNull: true
    },
    published: {
      type: DataTypes.BOOLEAN,
      allowNull: false,
      defaultValue: 0
    },
    numViewsCache: {
      type: DataTypes.INTEGER,
      allowNull: false,
      defaultValue: 0
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    },
    dateTimeEdited: {
      type: DataTypes.DATE,
      allowNull: true
    }
  }, {
    sequelize,
    tableName: 'blogpost',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "blogPostId" },
        ]
      },
      {
        name: "blogPostBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
    ]
  });
};

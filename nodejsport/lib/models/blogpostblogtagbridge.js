const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('blogpostblogtagbridge', {
    blogPostBlogTagId: {
      autoIncrement: true,
      type: DataTypes.INTEGER,
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
    blogPostId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'blogpost',
        key: 'blogPostId'
      }
    },
    blogTagId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'blogtag',
        key: 'blogTagId'
      }
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'blogpostblogtagbridge',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "blogPostBlogTagId" },
        ]
      },
      {
        name: "blogPostBlogTagBridgeBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "blogPostBlogTagBridgeBlogPostId",
        using: "BTREE",
        fields: [
          { name: "blogPostId" },
        ]
      },
      {
        name: "blogPostBlogTagBridgeBlogTagId",
        using: "BTREE",
        fields: [
          { name: "blogTagId" },
        ]
      },
    ]
  });
};

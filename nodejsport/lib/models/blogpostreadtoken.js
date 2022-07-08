const Sequelize = require('sequelize');
module.exports = function(sequelize, DataTypes = Sequelize.DataTypes) {
  return sequelize.define('blogpostreadtoken', {
    blogPostReadTokenId: {
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
    blogPostId: {
      type: DataTypes.STRING(17),
      allowNull: false,
      references: {
        model: 'blogpost',
        key: 'blogPostId'
      }
    },
    clientIP: {
      type: DataTypes.TEXT,
      allowNull: false
    },
    dateTimeAdded: {
      type: DataTypes.DATE,
      allowNull: false
    }
  }, {
    sequelize,
    tableName: 'blogpostreadtoken',
    timestamps: false,
    indexes: [
      {
        name: "PRIMARY",
        unique: true,
        using: "BTREE",
        fields: [
          { name: "blogPostReadTokenId" },
        ]
      },
      {
        name: "blogPostReadTokenBusinessId",
        using: "BTREE",
        fields: [
          { name: "businessId" },
        ]
      },
      {
        name: "blogPostReadTokenBlogPostId",
        using: "BTREE",
        fields: [
          { name: "blogPostId" },
        ]
      },
    ]
  });
};

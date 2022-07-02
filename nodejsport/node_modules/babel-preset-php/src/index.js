const plugins = require('./lib/plugins'),
      syntaxPlugin = require('./lib/syntax');

Object.defineProperty(exports, "__esModule", {
  value: true
});

/**
 * Support options (see lib/trans.js)
 * - default_map = true
 *   Suffixes identifier among var,break,case,catch,class,default,extends,function,import,try
 *
 * - map = {}
 *   Additional identifiers to suffix using '_r'
 *
 * - encapsulate_ns = false
 *   Whether namespaced classes are enclosed inside a { } javascript block.
 */
module.exports = function(api, options) {
    return {
        plugins: [
            [syntaxPlugin, options],
            plugins.defineToConstant,
            plugins.isDefined,
            plugins.functionExists,
            plugins.arrayFunctions,
            plugins.stringFunctions,
            plugins.mathFunctions,
            plugins.otherFunctions,
            plugins.renameException,
            plugins.superglobals,
        ].filter(Boolean),
    };
};

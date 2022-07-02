const Engine = require('php-parser'),
      translateProgram = require('./trans').default;

Object.defineProperty(exports, "__esModule", {
  value: true
});

exports.default = function(api, options) {
    api.assertVersion(7);
    return {
        name: "syntax-php",
        manipulateOptions: (opts, parserOpts) => {
            parserOpts.tokens = true;
        },
        parserOverride: (code, opts) => {
            const parser = new Engine({
                parser: {
                    extractDoc: true
                },
                ast: {
                    withPositions: true
                }
            });

            const ast = parser.parseCode(code, {filename: opts.sourceFileName});
            // console.log(`Parse with options ${JSON.stringify(options)}`);

            return translateProgram(ast, options);
        }
    };
};

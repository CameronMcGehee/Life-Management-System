/**
 * Recursively replace identifiers within an AST tree.
 *
 * Handy when used for providing javascript complex replacements to PHP expressions:
 * We just have to compile javascript inline-code as AST and substitute placeholder-identifiers
 * by required ones.
 *
 * @param node The AST, most likely resulting from parseExpression().
 * @param name String representing one of the placeholder in the original expression.
 * @param value Any AST value of substitution.
 *
 * @return The substitued AST.
 */
var ident = 0;
function ast_recursive_replace(node, name, value) {
    ident++;
    if ('name' in node && node.name === name) {
        // console.log(' '.repeat(ident) + `FOUND ${name}`);
        ident--;
        return value;
    }
    for (const p of ['callee','object','body','left','right']) {
        if (p in node) {
            // console.log(' '.repeat(ident) + `entering ${p}`);
            node[p] = ast_recursive_replace(node[p], name, value);
        }
    }
    if ('arguments' in node) {
        for (var i = 0; i < node.arguments.length; i++) {
            // console.log(' '.repeat(ident) + `entering argument ${i}`);
            node.arguments[i] = ast_recursive_replace(node.arguments[i], name, value);
        }
    }
    ident--;
    return node;
};

exports.renameException = function renameException(babel) {
    const t = babel.types;
    return {
        visitor: {
            Identifier(p) {
                if (p.node.name != 'Exception' || p.scope.hasBinding("Exception")) {
                    return;
                }
                if (p.parent.type === "NewExpression" || (
                    p.parent.type === "ClassDeclaration" && p.node === p.parent.superClass
                    )) {
                    p.replaceWith(t.identifier("Error"));
                }
            }
        },
    };
}

exports.isDefined = function isDefined(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (p.node.arguments.length != 1 || p.node.callee.type != 'Identifier' || p.node.callee.name != 'defined') {
                    return;
                }
                if (p.scope.hasBinding('defined')) {
                    return;
                }

                const key = p.node.arguments[0];
                const keyIsIdentifierString = key.type == 'StringLiteral' && /^[a-z_][a-z0-9_]*$/i.test(key.value);

                let rval, lval;

                if (keyIsIdentifierString) {
                    lval = t.stringLiteral('undefined');
                    rval = t.unaryExpression("typeof", t.identifier(key.value), true);
                } else {
                    lval = t.identifier('undefined');
                    rval = t.memberExpression(t.identifier("global"), key, true);
                }
                p.replaceWith(t.binaryExpression('!==', lval, rval));
            }
        },
    };
}

exports.functionExists = function functionExists(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (p.node.arguments.length != 1 || p.node.callee.type != 'Identifier' ||
                    (p.node.callee.name != 'function_exists' && p.node.callee.name != 'class_exists')) {
                    return;
                }
                if (p.scope.hasBinding(p.node.callee.name)) {
                    return;
                }

                const key = p.node.arguments[0];
                const keyIsIdentifierString = key.type == 'StringLiteral' && /^[a-z_][a-z0-9_]*$/i.test(key.value);

                const rval = keyIsIdentifierString? t.identifier(key.value) : t.memberExpression(t.identifier("global"), key, true);
                p.replaceWith(t.binaryExpression('===', t.stringLiteral('function'), t.unaryExpression("typeof", rval, true)));
            }
        },
    };
}

exports.arrayFunctions = function arrayFunctions(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (!p.node.arguments.length || p.node.callee.type != 'Identifier') {
                    return;
                }
                const name = p.node.callee.name;
                if (p.scope.hasBinding(name)) {
                    return;
                }
                const val = p.node.arguments[0];
                switch(name) {
                    case 'count':
                    case 'sizeof':
                        p.replaceWith(t.memberExpression(val, t.identifier('length'), false));
                        break;
                    case 'array_values':
                    case 'array_keys':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('Object'), t.identifier(name.substring(6)), false), p.node.arguments)
                        );
                        break;
                    case 'array_map':
                        p.replaceWith(t.callExpression(t.memberExpression(p.node.arguments[1], t.identifier(name.substring(6)), false), [p.node.arguments[0]]));
                        break;

                    case 'array_pop':
                    case 'array_reverse':
                    case 'array_slice':
                    case 'array_splice':
                    case 'array_reduce':
                    case 'array_filter':
                    case 'array_push':
                    case 'array_shift':
                    case 'array_unshift':
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier(name.substring(6)), false), p.node.arguments.slice(1)));
                        break;
                    case 'array_walk':
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('forEach'), false), p.node.arguments.slice(1)));
                        break;
                    case 'array_unique': {
                        p.replaceWith(t.callExpression(t.memberExpression(t.identifier('Array'), t.identifier('from'), false),
                                                       [t.newExpression(t.identifier('Set'), [p.node.arguments[0]])]));
                        break;
                    }
                }
            }
        },
    };
}

exports.stringFunctions = function stringFunctions(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (!p.node.arguments.length || p.node.callee.type != 'Identifier') {
                    return;
                }
                const name = p.node.callee.name;
                if (p.scope.hasBinding(name)) {
                    return;
                }
                const val = p.node.arguments[0];
                switch(name) {
                    case 'preg_replace':
                    case 'preg_replace_callback':
                        if (p.node.arguments.length === 3 && val.type === 'StringLiteral') {
                            // regexp to match a regexp+flags.
                            const m = val.value.match(/^[^a-z](.*?)[^a-z]([a-z]*)$/i);
                            if (m) {
                                p.replaceWith(t.callExpression(
                                    t.memberExpression(p.node.arguments[2], t.identifier('replace'), false),
                                    [t.regExpLiteral(m[1].replace(/\//g, '\\/'), 'g'+m[2].replace(/[sD]/g, '')), p.node.arguments[1]]
                                ));
                            }
                        }
                        break;
                    case 'chr':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('String'), t.identifier('fromCharCode'), false), [val])
                        );
                        break;
                    case 'ord':
                        if (val.type === "MemberExpression" && val.computed) {
                            p.replaceWith(t.callExpression(t.memberExpression(val.object, t.identifier('charCodeAt'), false), [val.property]));
                        } else {
                            p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('charCodeAt'), false), [t.numericLiteral(0)]));
                        }
                        break;
                    case 'strlen':
                        p.replaceWith(t.memberExpression(val, t.identifier('length'), false));
                        break;
                    case 'explode':
                    case 'implode': {
                        const args = [val].concat(p.node.arguments.slice(2));
                        p.replaceWith(t.callExpression(t.memberExpression(p.node.arguments[1], t.identifier(name == 'implode' ? 'join' : 'split'), false), args));
                        break;
                    }
                    case 'trim':
                    case 'ltrim':
                    case 'rtrim': {
                        if (p.node.arguments.length == 1) {
                            var ident;
                            switch(name) {
                            case 'trim':
                                ident = t.identifier('trim');
                                break;
                            case 'ltrim':
                                ident = t.identifier('trimLeft');
                                break;
                            case 'rtrim':
                                ident = t.identifier('trimRight');
                                break;
                            }
                            p.replaceWith(t.callExpression(t.memberExpression(val, ident, false), []));
                        } else {
                            var regex, regstr, charListNode = p.node.arguments[1];
                            if (charListNode.type === 'StringLiteral') {
                                regstr = charListNode.value.replace(/([\[\]\.\/])/g, '\\$1');
                                if (regstr.length > 1) {
                                    regstr = `[${regstr}]`;
                                }
                                switch(name) {
                                case 'trim':
                                    regex = t.regExpLiteral(`^${regstr}*|${regstr}*$`, 'g');
                                    break;
                                case 'ltrim':
                                    regex = t.regExpLiteral(`^${regstr}*`, '');
                                    break;
                                case 'rtrim':
                                    regex = t.regExpLiteral(`${regstr}*$`, '');
                                    break;
                                }
                            } else if (charListNode.type === 'Identifier') {
                                switch(name) {
                                case 'trim':
                                    regstr = "new Regexp('^[' + _var_char + ']|[' + _var_char + ']*$', 'g')";
                                    break;
                                case 'ltrim':
                                    regstr = "new Regexp('^[' + _var_char + ']*')";
                                    break;
                                case 'rtrim':
                                    regstr = "new Regexp('[' + _var_char + ']*$')";
                                    break;
                                }
                                var template = require("@babel/parser").parseExpression(regstr);
                                regex = ast_recursive_replace(template, '_var_char', charListNode);
                            }

                            p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('replace'), false), [regex, t.stringLiteral('')]));
                        }
                        break;
                    }

                    case 'str_replace':
                        if (p.node.arguments.length == 4 && t.isNumericLiteral(p.node.arguments[3],{value:1})) {
                            p.replaceWith(t.callExpression(t.memberExpression(p.node.arguments[2], t.identifier('replace'), false), p.node.arguments.slice(0,2)));
                        }
                        break;
                    case 'substr':
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('substr'), false), p.node.arguments.slice(1)));
                        break;
                    case 'strtoupper':
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('toUpperCase'), false), []));
                        break;
                    case 'strtolower':
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('toLowerCase'), false), []));
                        break;
                    case 'rawurlencode':
                        p.replaceWith(t.callExpression(t.identifier('encodeURIComponent'), p.node.arguments));
                        break;
                    case 'json_encode':
                        if (p.node.arguments.length === 1) {
                            p.replaceWith(
                                t.callExpression(t.memberExpression(t.identifier('JSON'), t.identifier('stringify'), false), [val])
                            );
                        }
                        break;
                    case 'json_decode':
                        if (p.node.arguments.length <= 3 && (p.node.arguments.length < 2 || p.node.arguments[1].value === false)) {
                            p.replaceWith(
                                t.callExpression(t.memberExpression(t.identifier('JSON'), t.identifier('parse'), false), [val])
                            );
                        }
                        break;
                }
            }
        },
    };
}

exports.mathFunctions = function mathFunctions(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (!p.node.arguments.length || p.node.callee.type != 'Identifier') {
                    return;
                }
                const name = p.node.callee.name;
                if (p.scope.hasBinding(name)) {
                    return;
                }
                switch(name) {
                    case 'is_nan':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('Number'), t.identifier('isNaN'), false), p.node.arguments)
                        );
                        break;
                    case 'floatval':
                        p.replaceWith(t.unaryExpression("+", p.node.arguments[0], true));
                        break;
                    case 'intval':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('Math'), t.identifier('round'), false), p.node.arguments)
                        );
                        break;
                    case 'abs':
                    case 'sin':
                    case 'cos':
                    case 'pow':
                    case 'floor':
                    case 'ceil':
                    case 'round':
                    case 'max':
                    case 'min':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('Math'), t.identifier(name), false), p.node.arguments)
                        );
                        break;
                }
            }
        },
    };
}

exports.superglobals = function superglobals(babel) {
    const t = babel.types;
    return {
        visitor: {
            Identifier(p) {
                if (p.parent.type !== "MemberExpression") {
                    return;
                }
                const name = p.node.name;
                if (p.scope.hasBinding(name)) {
                    return;
                }
                switch(name) {
                    case '_ENV':
                        p.replaceWith(t.memberExpression(t.identifier("process"),t.identifier("env"),false));
                }
            }
        }
    }
}

exports.otherFunctions = function otherFunctions(babel) {
    const t = babel.types;
    return {
        visitor: {
            CallExpression(p) {
                if (p.node.callee.type != 'Identifier') {
                    return;
                }
                const name = p.node.callee.name;
                if (p.scope.hasBinding(name)) {
                    return;
                }
                const val = p.node.arguments[0];
                switch(name) {
                    case 'func_get_args':
                        var replacement = t.identifier('arguments');
                        if (p.container.type === 'VariableDeclarator') {
                            replacement = t.callExpression(t.memberExpression(t.identifier('Array'), t.identifier('from'), false), [replacement]);
                        }
                        p.replaceWith(replacement);
                        break;
                    case 'create_function':
                        p.parentPath.replaceWith(t.newExpression(t.identifier('Function'), p.node.arguments));
                        break;
                    case 'trigger_error': {
                        const throwNode = t.throwStatement(t.newExpression(t.identifier('Error'), p.node.arguments));
                        if (p.parentPath.node.type === "ExpressionStatement") {
                            p.parentPath.replaceWith(throwNode);
                        } else {
                            p.replaceWith(throwNode);
                        }
                        break;
                    }
                    case 'microtime':
                    case 'time':
                        p.replaceWith(t.binaryExpression("/", t.callExpression(t.memberExpression(t.identifier('Date'), t.identifier('now'), false), []), t.numericLiteral(1000)));
                        break;
                    case 'is_string':
                        p.replaceWith(t.binaryExpression('===', t.stringLiteral('string'), t.unaryExpression("typeof", val, true)));
                        break;
                    case 'is_bool':
                        p.replaceWith(t.binaryExpression('===', t.stringLiteral('boolean'), t.unaryExpression("typeof", val, true)));
                        break;
                    case 'is_int':
                    case 'is_float':
                        p.replaceWith(t.binaryExpression('===', t.stringLiteral('number'), t.unaryExpression("typeof", val, true)));
                        break;
                    case 'is_object':
                        p.replaceWith(t.binaryExpression('===', t.stringLiteral('object'), t.unaryExpression("typeof", val, true)));
                        break;
                    case 'property_exists':
                    case 'key_exists':
                    case 'array_key_exists':
                        p.replaceWith(t.binaryExpression('in', p.node.arguments[0], p.node.arguments[1]));
                        break;
                    case 'in_array':
                        p.replaceWith(t.binaryExpression('!==', t.numericLiteral(-1),
                            t.callExpression(t.memberExpression(p.node.arguments[1], t.identifier('indexOf'), false), [p.node.arguments[0]])))
                        break;
                    case 'is_array':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('Array'), t.identifier('isArray'), false), [val])
                        );
                        break;
                    case 'var_dump':
                        p.replaceWith(
                            t.callExpression(t.memberExpression(t.identifier('console'), t.identifier('log'), false), p.node.arguments)
                        );
                        break;
                    case 'print_r':
                        if (p.node.arguments.length === 1) {
                            p.replaceWith(
                                t.callExpression(t.memberExpression(t.identifier('console'), t.identifier('log'), false), [val])
                            );
                        }
                        break;
                    case 'gettype':
                        p.replaceWith(t.unaryExpression("typeof", val, true));
                        break;
                    case 'get_class':
                        p.replaceWith(t.memberExpression(t.memberExpression(val, t.identifier('constructor')), t.identifier('name')));
                        break;
                    case 'sort': if (p.node.arguments.length === 1) {
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('sort'), false), []));
                    }
                    break;
                    case 'usort': if (p.node.arguments.length === 2) {
                        p.replaceWith(t.callExpression(t.memberExpression(val, t.identifier('sort'), false), [p.node.arguments[1]]));
                    }
                    break;
                    case 'range': {
                        // Array(Math.ceil((stop - start) / step)).fill(start).map((x, y) => x + y * step)
                        var [start, stop, step] = p.node.arguments;
                        var template = require("@babel/parser").parseExpression("Array(Math.ceil((_var_stop - _var_start) / _var_step)).fill(_var_start).map((x, y) => x + y * _var_step)");
                        ast_recursive_replace(template, '_var_start', start);
                        ast_recursive_replace(template, '_var_stop', stop);
                        ast_recursive_replace(template, '_var_step', step);
                        p.replaceWith(template);
                    }
                    break;
                }
            }
        },
    };
}

exports.defineToConstant = function defineToConstant(babel) {
    const t = babel.types;
    let inFunction = 0;
    return {
        visitor: {
            Function: {
                enter() {
                    inFunction++;
                },
                exit() {
                    inFunction--;
                }
            },

            CallExpression(p) {
                if (p.node.arguments.length != 2 || p.node.callee.type != 'Identifier' || p.node.callee.name != 'define') {
                    return;
                }
                if (p.scope.hasBinding('define')) {
                    return;
                }
                const key = p.node.arguments[0];
                const val = p.node.arguments[1];
                const keyIsIdentifierString = key.type == 'StringLiteral' && /^[a-z_][a-z0-9_]*$/i.test(key.value);

                if (!inFunction && keyIsIdentifierString && p.parentPath.node.type === "ExpressionStatement") {
                    p.parentPath.replaceWith(t.variableDeclaration("const", [t.variableDeclarator(t.identifier(key.value), val)]));
                } else {
                    const lval = keyIsIdentifierString ?
                        t.memberExpression(t.identifier("global"), t.identifier(key.value), false) :
                        t.memberExpression(t.identifier("global"), key, true);
                    p.replaceWith(t.assignmentExpression("=", lval, val));
                }
            }
        },
    };
}

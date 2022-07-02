const assert = require('assert');
const translates = require('./helper');

describe('AST modification', function() {
    it('Expressions', function() {
        translates('is_string($x);', '"string" === typeof x;');
        translates('is_bool($x);', '"boolean" === typeof x;');
    });

    it('Builtins', function() {
        translates('is_nan($x);', 'Number.isNaN(x);');
        translates('is_float($x);', '"number" === typeof x;');
        translates('is_object($x);', '"object" === typeof x;');
        translates('is_array($x);', 'Array.isArray(x);');
        translates('trim($x);', 'x.trim();');
        translates('function trim(){}; trim($x);', 'function trim(){}; trim(x);');
        translates('ord($x);', 'x.charCodeAt(0);');
        translates('ord($x["z"]);', 'x.z.charCodeAt(0);');
        translates('ord($x[5]);', 'x.charCodeAt(5);');
        translates('chr($x);', 'String.fromCharCode(x);');
        translates('substr($x,1,2);', 'x.substr(1,2);');
        translates('str_replace(1,2,$x);');
        translates('str_replace(1,2,$x,1);', 'x.replace(1,2);');
        translates('str_replace(1,2,$x,$z);');
        translates('trim($x,"x");', 'x.replace(/^x*|x*$/g, "");');
        translates('ltrim($x, char);', "x.replace(new Regexp('^[' + char + ']*'), \"\");");
        translates('ltrim($x,"]\\d");', 'x.replace(/^[\\]\\d]*/, "");');
        translates('explode(".", ltrim($name,"."))', 'name.replace(/^[\\.]*/, "").split(".");');
        translates('explode(".", rtrim($name))', 'name.trimRight().split(".");');
        translates('$_ENV["X"];', 'process.env.X;');
    });

    it('Preg', function() {
        translates('preg_replace("#t\\*ex*t#i", $y, $z);', 'z.replace(/t\\*ex*t/gi,y);');
        translates('preg_replace("!http://foo/!iuDs", $y, $z);', 'z.replace(/http:\\/\\/foo\\//giu,y);');
        translates('preg_replace_callback("/(reg)/", $y, $z);', 'z.replace(/(reg)/g,y);');
        translates('preg_replace("$dynamic", $y, $z);', 'preg_replace(`${dynamic}`, y, z);');
    });

    it('Array', function() {
        translates("array_key_exists($k,$a);", 'k in a;');
        translates("in_array($needle, $haystack);", '-1 !== haystack.indexOf(needle);');
    });

    it('Define', function() {
        translates("defined('foo');", '"undefined" !== typeof foo;');
        translates("defined('%z');", 'undefined !== global["%z"];');
        translates("define('foo', 2);", "const foo = 2;");
        translates("define('1foo', 2);", 'global["1foo"] = 2;');
        translates("function b(){define('foo', 2);}", "function b(){global.foo = 2;};");
        translates("function b(){define('foo bar', 2);}", 'function b(){global["foo bar"] = 2;};');
        translates('define("foo$bar", "$baz quz");', "global[`foo${bar}`] = `${baz} quz`;");
    });

    it('Functions', function() {
        translates("function bar() {$a = func_get_args();}", 'function bar() {var a = Array.from(arguments);};');
        translates("function bar() {func_get_args();}", 'function bar() {arguments;};');
    });

    it('Class', function() {
        translates('class Foo extends Exception {}', 'class Foo extends Error {};');
    });

    it('Standard funcs', function() {
        translates("count($a);", 'a.length;');
        translates("function count() {}; count($a);");
        translates("sort($a);", 'a.sort();');
        translates("usort($a, function($a,$b){return 1;});", 'a.sort((a,b)=>{return 1;});');
        translates("array_pop($a);", 'a.pop();');
        translates("array_keys($a);", 'Object.keys(a);');
        translates("array_values($a);", 'Object.values(a);');
        translates("array_shift($a);", 'a.shift();');
        translates("pow(1);", 'Math.pow(1);');
        translates("max(1,2);", 'Math.max(1,2);');
        translates("$max = function(){}; max(1,2);", 'var max = ()=>{}; max(1,2);');
        translates("array_unshift($a,$b,$c);", 'a.unshift(b,c);');
        translates("array_push($a,1,2+2,3);", 'a.push(1,2+2,3);');
        translates("array_slice($a,1,2);", 'a.slice(1,2);');
        translates("array_reverse($a);", 'a.reverse();');
        translates("array_splice($a,1,2,$z);", 'a.splice(1,2,z);');
        translates("array_walk($a,$z,1);", 'a.forEach(z,1);');
        translates("array_reduce($a,function(){});", 'a.reduce(() => {});');
        translates("array_filter($a,function(){});", 'a.filter(() => {});');
        translates("array_map(function(){},$a);", 'a.map(() => {});');
        translates("array_unique([1,2]);", 'Array.from(new Set([1,2]));');
        translates("implode('str', $a);", 'a.join("str");');
        translates("explode('str', $a, 2);", 'a.split("str", 2);');
        translates("function_exists('bla');", '"function" === typeof bla;');
        translates("function_exists($z);", '"function" === typeof global[z];');
        translates("class_exists($z);", '"function" === typeof global[z];');
        translates("json_encode($z);", 'JSON.stringify(z);');
        translates("json_decode($z);", 'JSON.parse(z);');
        translates("json_decode($z, false);", 'JSON.parse(z);');
        translates("range(2, 16/2, $step);", 'Array(Math.ceil((16 / 2 - 2) / step)).fill(2).map((x, y) => x + y * step);');
    });

});

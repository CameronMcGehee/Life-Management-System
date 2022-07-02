const assert = require('assert'),
      babel = require("@babel/core"),
      phpPreset = require("../src/index");

module.exports = function translates(phpSrc, expected = undefined, preset_options = {}) {
    let ignoreSemi = false;
    if (undefined === expected) {
        expected = phpSrc.replace(/\$/g, '').replace(/;/g, '');
        ignoreSemi = true;
    }
    let out;
    try {
        out = babel.transform(`<?php ${phpSrc}`, {
            presets: [[phpPreset, preset_options]],
        }).code;
    } catch(e) {
        e.message += `\nin ${phpSrc}`;
        throw e;
    }
    if (ignoreSemi) {
        out = out.replace(/;/g, '');
    }
    if ('string' !== typeof out || expected.replace(/\s/g, '') !== out.replace(/\s/g, '')) {
        assert.equal(out, expected);
    }
}

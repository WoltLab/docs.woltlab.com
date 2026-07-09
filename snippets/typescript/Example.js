define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.run = void 0;
    function run() {
        alert((0, Language_1.getPhrase)("wcf.foo.bar"));
    }
    exports.run = run;
});
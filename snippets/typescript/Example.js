define(["require", "exports", "tslib", "WoltLabSuite/Core/Language"], function (require, exports, tslib_1, Language) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.run = void 0;
    Language = tslib_1.__importStar(Language);
    function run() {
        alert(Language.get("wcf.foo.bar"));
    }
    exports.run = run;
});
# TypeScript

## Consuming WoltLab Suite’s Types

To consume the types of WoltLab Suite, you will need to install the [WoltLab/WCF](https://github.com/WoltLab/WCF) using npm using a git URL that refers to the appropriate branch.

A full `package.json` that includes WoltLab Suite, TypeScript, eslint and Prettier could look like the following.

```json
{
  "devDependencies": {
    "@typescript-eslint/eslint-plugin": "^4.6.1",
    "@typescript-eslint/parser": "^4.6.1",
    "eslint": "^7.12.1",
    "eslint-config-prettier": "^6.15.0",
    "prettier": "^2.1.2",
    "tslib": "^2.0.3",
    "typescript": "^4.1.3"
  },
  "dependencies": {
    "@woltlab/wcf": "https://github.com/WoltLab/WCF.git#master"
  }
}
```

After installing the types using npm, you will also need to configure `tsconfig.json` to take the types into account.
To do so, you will need to add them to the `compilerOptions.paths` option.
A complete `tsconfig.json` file that matches the configuration of WoltLab Suite could look like the following.

```json
{
  "include": [
    "node_modules/@woltlab/wcf/global.d.ts",
    "ts/**/*"
  ],
  "compilerOptions": {
    "target": "es2017",
    "module": "amd",
    "rootDir": "ts/",
    "outDir": "files/js/",
    "lib": [
      "dom",
      "es2017"
    ],
    "strictNullChecks": true,
    "moduleResolution": "node",
    "esModuleInterop": true,
    "noImplicitThis": true,
    "strictBindCallApply": true,
    "baseUrl": ".",
    "paths": {
      "*": [
        "node_modules/@woltlab/wcf/ts/*"
      ]
    },
    "importHelpers": true
  }
}
```

After this initial set-up, you would place your TypeScript source files into the `ts/` folder of your project.
The generated JavaScript target files will be placed into `files/js/` and thus will be installed by the [file PIP](../package/pip/file.md).

## Additional Tools

WoltLab Suite uses additional tools to ensure the high quality and a consistent code style of the TypeScript modules.
The current configuration of these tools is as follows.
It is recommended to re-use this configuration as is.

### .prettierrc

```yml
trailingComma: all
printWidth: 120
```

### .eslintrc.js

```javascript
module.exports = {
  root: true,
  parser: "@typescript-eslint/parser",
  parserOptions: {
    tsconfigRootDir: __dirname,
    project: ["./tsconfig.json"]
  },
  plugins: ["@typescript-eslint"],
  extends: [
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:@typescript-eslint/recommended-requiring-type-checking",
    "prettier",
    "prettier/@typescript-eslint"
  ],
  rules: {
    "@typescript-eslint/ban-types": [
      "error", {
        types: {
          "object": false
        },
        extendDefaults: true
      }
    ],
    "@typescript-eslint/no-explicit-any": 0,
    "@typescript-eslint/no-non-null-assertion": 0,
    "@typescript-eslint/no-unsafe-assignment": 0,
    "@typescript-eslint/no-unsafe-call": 0,
    "@typescript-eslint/no-unsafe-member-access": 0,
    "@typescript-eslint/no-unsafe-return": 0,
    "@typescript-eslint/no-unused-vars": [
      "error", {
        "argsIgnorePattern": "^_"
      }
    ]
  }
};
```

### .eslintignore

```gitignore
**/*.js
```

### .gitattributes

This `.gitattributes` configuration will automatically collapse the generated JavaScript target files in GitHub’s Diff view.
You will not need it if you do not use git or GitHub.

```gitattributes
files/js/**/*.js linguist-generated
```

## Writing a simple module

After completing this initial set-up you can start writing your first TypeScript module.
The TypeScript compiler can be launched in Watch Mode by running `npx tsc -w`.

WoltLab Suite’s modules can be imported using the standard ECMAScript module import syntax by specifying the full module name.
The public API of the module can also be exported using the standard ECMASCript module export syntax.

```typescript
import * as Language from "WoltLabSuite/Core/Language";

export function run() {
  alert(Language.get("wcf.foo.bar"));
}
```

This simple example module will compile to plain JavaScript that is compatible with the AMD loader that is used by WoltLab Suite.

```javascript
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
```

Within templates it can be consumed as follows.

```html
<script data-relocate="true">
  require(["Example"], (Example) => {
    Example.run(); // Alerts the contents of the `wcf.foo.bar` phrase.
  });
</script>
```

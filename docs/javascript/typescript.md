# TypeScript

## Consuming WoltLab Suite’s Types

To consume the types of WoltLab Suite, you will need to install the `@woltlab/wcf` npm package using a git URL that refers to the appropriate branch of [WoltLab/WCF](https://github.com/WoltLab/WCF).

A full `package.json` that includes WoltLab Suite, TypeScript, eslint and Prettier could look like the following.

{jinja{ codebox(
  title="package.json",
  language="json",
  filepath="typescript/package.json"
) }}

After installing the types using npm, you will also need to configure `tsconfig.json` to take the types into account.
To do so, you will need to add them to the `compilerOptions.paths` option.
A complete `tsconfig.json` file that matches the configuration of WoltLab Suite could look like the following.

{jinja{ codebox(
  title="tsconfig.json",
  language="json",
  filepath="typescript/tsconfig.json"
) }}

After this initial set-up, you would place your TypeScript source files into the `ts/` folder of your project.
The generated JavaScript target files will be placed into `files/js/` and thus will be installed by the [file PIP](../package/pip/file.md).

## Additional Tools

WoltLab Suite uses additional tools to ensure the high quality and a consistent code style of the TypeScript modules.
The current configuration of these tools is as follows.
It is recommended to re-use this configuration as is.

{jinja{ codebox(
  title=".prettierrc",
  language="yml",
  filepath="typescript/.prettierrc"
) }}

{jinja{ codebox(
  title=".eslintrc.js",
  language="javascript",
  filepath="typescript/.eslintrc.js"
) }}

{jinja{ codebox(
  title=".eslintignore",
  language="gitignore",
  filepath="typescript/.eslintignore"
) }}

This `.gitattributes` configuration will automatically collapse the generated JavaScript target files in GitHub’s Diff view.
You will not need it if you do not use git or GitHub.

{jinja{ codebox(
  title=".gitattributes",
  language="gitattributes",
  filepath="typescript/.gitattributes"
) }}

## Writing a simple module

After completing this initial set-up you can start writing your first TypeScript module.
The TypeScript compiler can be launched in Watch Mode by running `npx tsc -w`.

WoltLab Suite’s modules can be imported using the standard ECMAScript module import syntax by specifying the full module name.
The public API of the module can also be exported using the standard ECMAScript module export syntax.

{jinja{ codebox(
  title="ts/Example.ts",
  language="typescript",
  filepath="typescript/Example.ts"
) }}

This simple example module will compile to plain JavaScript that is compatible with the AMD loader that is used by WoltLab Suite.

{jinja{ codebox(
  title="files/js/Example.js",
  language="javascript",
  filepath="typescript/Example.js"
) }}

Within templates it can be consumed as follows.

```html
<script data-relocate="true">
  require(["Example"], (Example) => {
    Example.run(); // Alerts the contents of the `wcf.foo.bar` phrase.
  });
</script>
```

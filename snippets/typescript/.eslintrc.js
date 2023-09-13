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
    "plugin:@typescript-eslint/recommended-type-checked",
    "plugin:@typescript-eslint/strict",
    "plugin:@typescript-eslint/strict-type-checked",
    "plugin:@typescript-eslint/stylistic",
    "plugin:@typescript-eslint/stylistic-type-checked",
    "prettier"
  ],
  rules: {
    "@typescript-eslint/no-non-null-assertion": 0,
    "@typescript-eslint/consistent-type-definitions": 0,
    "@typescript-eslint/prefer-nullish-coalescing": 0,
    "@typescript-eslint/no-unused-vars": [
      "error", {
        "argsIgnorePattern": "^_"
      }
    ],
    "@typescript-eslint/strict-boolean-expressions": [
      "error", {
        "allowNullableBoolean": true
      }
    ],
  }
};

import globals from "globals";
import pluginJs from "@eslint/js";
import pluginVue from "eslint-plugin-vue";


export default [
  {files: ["**/*.{js,mjs,cjs,vue}"]},
  {files: ["**/*.js"], languageOptions: {sourceType: "commonjs"}},
  {languageOptions: { globals: globals.browser }},
  pluginJs.configs.recommended,
  ...pluginVue.configs["flat/essential"],
  {
    rules: {
      // "no-console": "off",
      // "no-debugger": "off",
      // "no-undef": "off",
      // "no-unused-vars": "off", 
      // "no-redeclare": "off",
      // "vue/no-unused-vars": "off",
      // "vue/no-parsing-error": "off",
      // "vue/multi-word-component-names": "off", 
    }
  }
];
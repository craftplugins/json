import resolve from "@rollup/plugin-node-resolve";
import scss from "rollup-plugin-scss";

import pkg from "./package.json";

export default {
  input: pkg.source,
  output: {
    file: pkg.main,
    format: "iife",
    sourcemap: true,
  },
  plugins: [
    scss({
      sass: require("sass"),
    }),
    resolve(),
  ],
};

module.exports = {
  presets: [
    '@vue/cli-plugin-babel/preset'
  ],
  plugins: [
    ["@babel/plugin-transform-spread", {"loose": true}],
    ["@babel/plugin-proposal-object-rest-spread", {"loose": true}],
    ["@babel/plugin-proposal-optional-chaining", {"loose": true}]
  ]
}

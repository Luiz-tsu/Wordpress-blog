const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry,
    frontend: path.resolve(__dirname, 'src/frontend.tsx'),
  },
  output: {
    ...defaultConfig.output,
    filename: '[name].js',
    path: path.resolve(__dirname, 'build'),
  },
  resolve: {
    ...defaultConfig.resolve,
    extensions: ['.tsx', '.ts', '.js', '.jsx'],
  },
};

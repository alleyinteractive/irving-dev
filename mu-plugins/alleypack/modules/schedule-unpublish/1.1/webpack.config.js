const path = require('path');

module.exports = (env, argv) => {
  const { mode } = argv;

  return {
    devtool: 'development' === mode
      ? 'cheap-module-eval-source-map'
      : 'source-map',
    entry: {
      scheduleUnpublish: './client/js/index.js',
    },
    module: {
      rules: [
        {
          test: /\.s?css$/,
          exclude: /node_modules/,
          use: [
            'style-loader',
            {
              loader: 'css-loader',
            },
          ],
        },
        {
          exclude: /node_modules/,
          test: /.js$/,
          use: [
            'babel-loader',
            'eslint-loader',
          ],
        },
      ],
    },
    output: {
      filename: '[name].js',
      path: path.join(__dirname, 'build'),
    },
  };
};

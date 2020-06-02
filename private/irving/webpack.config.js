module.exports = (config) => {
  const newConfig = {
    module: {
      rules: [
        ...config.module.rules,
        {
          test: /\.jsx?$/,
          include: /node_modules\/@wordpress\/[^/]*/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: [ '@wordpress/default' ],
              },
            },
          ],
        },
      ],
    },
  };

  return newConfig;
};

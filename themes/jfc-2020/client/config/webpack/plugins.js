/* eslint-disable import/no-extraneous-dependencies */
const webpack = require('webpack');
const StylelintPlugin = require('stylelint-bare-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const StatsPlugin = require('webpack-stats-plugin').StatsWriterPlugin;
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const createWriteWpAssetManifest = require('./wpAssets');

module.exports = function getPlugins(mode) {
  const plugins = [
    new StylelintPlugin(),
    new StatsPlugin({
      transform: createWriteWpAssetManifest,
      fields: ['assetsByChunkName', 'hash'],
      filename: 'assetMap.json',
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
  ];

  switch (mode) {
    case 'development':
      return [
        new webpack.HotModuleReplacementPlugin({
          multiStep: true,
        }),
        ...plugins,
      ];

    case 'production':
    default:
      return [
        new MiniCssExtractPlugin({
          filename: 'css/[name].min.css',
          chunkFilename: 'css/[name].chunk.min.css',
        }),
        new CleanWebpackPlugin(),
        new OptimizeCssAssetsPlugin({}),
        ...plugins,
      ];
  }
};

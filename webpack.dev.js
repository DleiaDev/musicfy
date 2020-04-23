const path = require('path')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = {
  mode: 'development',
  entry: './resources/index.js',
  output: {
    filename: 'app.js',
    publicPath: 'http://localhost:8080'
  },
  plugins: [
    new VueLoaderPlugin()
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.(css|scss)$/,
        use: [
          'vue-style-loader',
          'css-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader'
          }
        ]
      }
    ]
  },
  devServer: {
    contentBase: false,
    headers: {
      'Access-Control-Allow-Origin': '*'
    }
  }
}

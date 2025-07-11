const path = require('path')

const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const LiveReloadPlugin = require('webpack-livereload-plugin')
const PolyfillInjectorPlugin = require('webpack-polyfill-injector')

const themename = 'astra-child-noshibari'
const destdir = path.join(__dirname, themename)
const themeplublic = '/wp-content/themes/' + themename

module.exports = env => { 

  const input = Object.keys(env)[2] || ''
  const params = input.split('-')
  const section = params[0] || 'app' // jitsi

  const paths ={
    entryjs: `./src/${ section }/js/main.js`,
    entryscss: `./src/${ section }/scss/main.scss`,
    output: destdir  + `/front/${ section }`,
    public: themeplublic,
    cssfilename: 'main.css'
  }

  return {
    context: __dirname,
    stats: 'minimal',
    watch: true,
    name: 'blank',
    entry: {
      main: `webpack-polyfill-injector?${JSON.stringify({
        modules: [
          paths.entryjs,
          paths.entryscss
        ]
      })}!`
    },
    output: {
      path: paths.output,
      publicPath: paths.public,
      filename: '[name].js'
    },
    mode: 'development',
    devtool: 'source-map',
    module: {
      rules: [
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          use: [          
            { 
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        },
        {
          test: /\.scss$/,
          exclude: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader'
            },
            {
              loader: 'sass-loader'
            }
          ]
        },
        {
          test: /\.css$/,
          include: /node_modules/,
          use: [
            'style-loader',
            'css-loader'
          ]
        },
        // Assets
        {
          test: /\.(jpg|jpeg|png|gif|svg|woff|ttf|eot|mp3)$/,
          type: 'asset/resource',
          generator: {
            emit: false,
            filename: content => { 

              return content.filename.replace(themename, '')
            }
          }
        }
      ]
    },
    plugins: [
      new PolyfillInjectorPlugin({
        singleFile: true,
        polyfills: [
          'Array.prototype.fill',
          'Array.prototype.find',
          'Array.prototype.findIndex',
          'Array.prototype.includes',
          'String.prototype.startsWith',
          'Array.from',
          'Object.entries',
          'Object.values',
          'Object.assign', 
          'fetch',
          'Promise',
        ]
      }),
      new MiniCssExtractPlugin({
        filename: paths.cssfilename
      }),
      new LiveReloadPlugin({
        protocol: 'http',
        hostname: 'localhost',
        delay: 10000,
        appendScriptTag: false
      })
    ],
    resolve: {
      extensions: ['.js', '.jsx'],
      alias: {
        assets: path.join(destdir, 'assets')
      }
    }
  }
}
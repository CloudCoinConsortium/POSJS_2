// webpack.config.js
var webpack = require('webpack')


var path = require( 'path' );
var { CleanWebpackPlugin } = require('clean-webpack-plugin');
var UglifyJsPlugin = require('uglifyjs-webpack-plugin');
var assign = require('object-assign');

module.exports = function(options) {
	console.log(options)

	let isProd = options.production == 'prod'
	let isWeb = options.web == 'web'
	let outdir, filename;
	if (isWeb) {
		outdir = "dist"
		filename = 'posjs2.min.js'
	} else {
		outdir = "lib"
		filename = 'posjs2.js'
	}

	let xplugins
	if (isProd) {
		xplugins = [new webpack.DefinePlugin({'process.env': {'NODE_ENV': '"production"'}})]
	//	if (isWeb) {
	//		xplugins.push(new UglifyJsPlugin())
	//	}
	} else {
		xplugins = [new webpack.DefinePlugin({'process.env': {'NODE_ENV': '"development"'}})]
	}

	let config = {
		context: __dirname,
		entry: path.join(__dirname, "src/posjs2.js"),
		output: {
			path: path.resolve( __dirname, outdir),
			filename: filename,
		},
		resolve: {
			extensions: ['.js']
		},
		devtool: isProd ? false : '#eval-source-map',
		plugins: xplugins,
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: ['babel-loader', 'eslint-loader'],
				}
			]
		}
	}


	config.plugins.push(new CleanWebpackPlugin());


	if (isWeb) {
		config['target'] = 'web'
		config['output']['library'] = "posjs2"
		config['output']['libraryTarget'] = "umd"
		config['output']['umdNamedDefine'] = true

	} else {
		config['target'] = 'node'
		config['node'] = {
			__dirname: true,
			__filename: true
		}
		config['output']['library'] = "posjs2"
		config['output']['libraryTarget'] = "commonjs2"
	}	

	return config;
};

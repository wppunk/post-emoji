const Encore = require( '@symfony/webpack-encore' );

if ( ! Encore.isRuntimeEnvironmentConfigured() ) {
	Encore.configureRuntimeEnvironment( process.env.NODE_ENV || 'dev' );
}

Encore.setOutputPath( 'assets/build' )
	.setPublicPath( '/' )
	.addEntry( 'js/main', './assets/.src/js/main.js' )
	.addStyleEntry(
		'css/main',
		'./assets/.src/scss/main.scss'
	)
	.splitEntryChunks()
	.disableSingleRuntimeChunk()
	.cleanupOutputBeforeBuild()
	.enableBuildNotifications()
	.enableSourceMaps( ! Encore.isProduction() )
	.enableSassLoader()
	.copyFiles( {
		from: './assets/.src/img',
		to: './img/[path][name].[ext]'
	} )
	.copyFiles( {
		from: './assets/.src/audio',
		to: './audio/[path][name].[ext]'
	} )
	.enableEslintLoader();

module.exports = Encore.getWebpackConfig();

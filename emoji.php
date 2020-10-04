<?php
/**
 * Bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:         Emoji
 * Description:         The plugin adds information about the games to the site posts.
 * Version:             1.0.0
 * Author:              WP Punk
 * Text Domain:         emoji
 * @link                https://github.com/wppunk/emoji
 * @package             Emoji
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Emoji\Plugin;
use Emoji\Vendor\Symfony\Component\Config\FileLocator;
use Emoji\Vendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use Emoji\Vendor\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;



if ( version_compare( phpversion(), '5.6', '<' ) ) {

	/**
	 * Display the notice after deactivation.
	 *
	 * @since {VERSION}
	 */
	function emoji_php_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				echo wp_kses(
					__( 'The minimum version of PHP is <strong>5.6</strong>. Please update the PHP on your server and try again.', 'emoji' ),
					[
						'strong' => [],
					]
				);
				?>
			</p>
		</div>

		<?php
		// In case this is on plugin activation.
		if ( isset( $_GET['activate'] ) ) { //phpcs:ignore
			unset( $_GET['activate'] ); //phpcs:ignore
		}
	}

	add_action( 'admin_notices', 'plugin_name_php_notice' );

	// Don't process the plugin code further.
	return;
}

if ( ! defined( 'EMOJI_DEBUG' ) ) {
	/**
	 * Enable plugin debug mod.
	 */
	define( 'EMOJI_DEBUG', false );
}

/**
 * Url to the plugin root directory.
 */
define( 'EMOJI_URL', plugin_dir_url( __FILE__ ) );

/**
 * Path to the plugin root directory.
 */
define( 'EMOJI_PATH', __DIR__ . '/' );

add_action( 'plugins_loaded', 'run_emoji_plugin' );

/**
 * Run plugin
 *
 * @throws \Exception Invalid service name.
 */
function run_emoji_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	$container_builder = new ContainerBuilder();
	$loader            = new PhpFileLoader( $container_builder, new FileLocator( __DIR__ ) );
	$loader->load( EMOJI_PATH . 'dependencies/services.php' );

	$emoji = new Plugin( $container_builder );
	$emoji->run();
	do_action( 'emoji_init', $emoji );
}

register_activation_hook( EMOJI_PATH . 'src/DB.php', [ 'Emoji\DB', 'create_table' ] );

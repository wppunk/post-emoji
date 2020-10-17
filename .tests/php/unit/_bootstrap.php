<?php
/**
 * Bootstrap file for unit tests that run before all tests.
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package PluginName
 * @author  WPPunk
 */

use WorDBless\Load;
use tad\FunctionMocker\FunctionMocker;

define( 'EMOJI_DEBUG', true );
define( 'EMOJI_PATH', realpath( __DIR__ . '/../../../' ) . '/' );
define( 'ABSPATH', realpath( EMOJI_PATH . '../../../' ) . '/' );
define( 'EMOJI_URL', 'https://site.com/wp-content/plugins/emoji/' );

Load::load();
FunctionMocker::init(
	[
		'whitelist'             => EMOJI_PATH . '/src',
		'blacklist'             => EMOJI_PATH,
		'redefinable-internals' => [ 'filter_input' ],
	]
);

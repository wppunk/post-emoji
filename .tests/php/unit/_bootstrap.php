<?php
/**
 * Bootstrap file for unit tests that run before all tests.
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package PluginName
 * @author  {AUTHOR}
 */

define( 'EMOJI_DEBUG', true );
define( 'EMOJI_PATH', realpath( __DIR__ . '/../../../' ) . '/' );
define( 'ABSPATH', realpath( EMOJI_PATH . '../../' ) . '/' );
define( 'EMOJI_URL', 'https://site.com/wp-content/plugins/plugin-name/' );

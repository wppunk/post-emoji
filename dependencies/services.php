<?php
/**
 * Describe plugin dependencies.
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package PluginName
 * @author  WPPunk
 */

// Exit if accessed directly.
use Emoji\Vendor\Symfony\Component\DependencyInjection\Reference;
use Emoji\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return function ( ContainerConfigurator $configurator ) {
	$services = $configurator->services();

	$services
		->set( 'plugin', 'Emoji\Plugin' )
		->args( [ new Reference( 'service_container' ) ] );
	$services->set( 'db', 'Emoji\DB' );
	$services->set( 'settings', 'Emoji\Settings' );
	$services->set( 'user_uuid', 'Emoji\UserUuid' );
	$services
		->set( 'emoji', 'Emoji\Emoji' )
		->args( [ new Reference( 'db' ), new Reference( 'user_uuid' ), new Reference( 'settings' ) ] );
	$services
		->set( 'front', 'Emoji\Front' )
		->args( [ new Reference( 'emoji' ), new Reference( 'settings' ) ] );
	$services
		->set( 'admin', 'Emoji\Admin' )
		->args( [ new Reference( 'emoji' ), new Reference( 'settings' ) ] );
	$services
		->set( 'shortcode', 'Emoji\Shortcode' )
		->args( [ new Reference( 'emoji' ), new Reference( 'settings' ) ] );
};

<?php
/**
 * Describe plugin dependencies.
 *
 * @since   {VERSION}
 * @link    {URL}
 * @license GPLv2 or later
 * @package PluginName
 * @author  {AUTHOR}
 */

// Exit if accessed directly.
use Emoji\Vendor\Symfony\Component\DependencyInjection\Reference;
use Emoji\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return function ( ContainerConfigurator $configurator ) {
	$services = $configurator->services();
	$services->set( 'db', 'Emoji\DB' );
	$services
		->set( 'emoji', 'Emoji\Emoji' )
		->args( [ new Reference( 'db' ) ] );
	$services
		->set( 'front', 'Emoji\Front' )
		->args( [ new Reference( 'emoji' ) ] );
	$services
		->set( 'admin', 'Emoji\Admin' )
		->args( [ new Reference( 'emoji' ) ] );
	$services
		->set( 'shortcode', 'Emoji\Shortcode' )
		->args( [ new Reference( 'emoji' ) ] );
};

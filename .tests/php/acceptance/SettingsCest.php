<?php
/**
 * SettingsCest
 *
 * @since   1.0.0
 * @link    https://github.com/wppunk/emoji/
 * @license GPLv2 or later
 * @package PluginName
 * @author  WPPunk
 */

/**
 * Class SettingsCest.
 *
 * phpcs:ignoreFile WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
 *
 * @since 1.0.0
 */
class SettingsCest {

	/**
	 * Check a Settings Page
	 *
	 * @since        1.0.0
	 *
	 * @param \AcceptanceTester $I Actor.
	 *
	 * @throws \Exception Something when wrong.
	 */
	public function visitSettingsPage( AcceptanceTester $I ) {
		$I->loginAsAdmin();
	}

}

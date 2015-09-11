<?php
/**
 * ImportArticles
 * ImportArticles MediaWiki hooks
 *
 * @author: Kris Blair (Cblair91)
 * @license: GPL-3.0 http://www.gnu.org/licenses/gpl.html
 * @package: ImportArticles
 * @link: https://mediawiki.org/wiki/Extension:ImportArticles
 */

class ImportArticles {
	/**
	 * Hooks initialized
	 *
	 * @var	boolean
	 */
	private static $initialized = false;

	/**
	 * Initiates the loading of the JavaScript
	 *
	 * @access	public
	 * @return	void
	 */
	public static function onBeforePageDisplay() {
		global $wgOut;
		if ( !self::$initialized ) {
			$wgOut->addModules( 'ext.importarticles' );
			self::$initialized = true;
		}
	}
}
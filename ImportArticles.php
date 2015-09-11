<?php
/**
 * ImportArticles
 * ImportArticles MediaWiki main loader
 *
 * @author: Kris Blair (Cblair91)
 * @license: GPL-3.0 http://www.gnu.org/licenses/gpl.html
 * @package: ImportArticles
 * @link: https://mediawiki.org/wiki/Extension:ImportArticles
 */

# Only run if included by MediaWiki
if ( !defined( 'MEDIAWIKI' ) ) die( 'Invalid entry point.' );

$wgExtensionCredits['other'][] = array(
	'path'				=> __FILE__,
    'name'				=> 'ImportArticles',
	'descriptionmsg'	=> 'importarticles-desc',
    'author'			=> 'Kris Blair (Cblair91)', 
	'url'				=> 'https://mediawiki.org/wiki/Extension:ImportArticles',
    'description'		=> 'Allow the usage of ImportArticles JavaScript to import multiple pages at once',
    'version'			=> '1.3',
	'licence-name'		=> 'GPL-3.0'
);

$dir = dirname( __FILE__ ) . '/';

# Register hooks
$wgAutoloadClasses['ImportArticles']		= $dir . 'ImportArticles.hooks.php';
$wgHooks['BeforePageDisplay'][]				= 'ImportArticles::onBeforePageDisplay';

# Register messages
$wgMessagesDir['ImportArticles']			= $dir . 'i18n';
$wgExtensionMessagesFiles['ImportArticles']	= $dir . 'ImportArticles.i18n.php';

# Register modules
$wgResourceModules['ext.importarticles'] = array(
	'scripts'		=> array( 'resources/importarticles.js' ),
	'localBasePath'	=> __DIR__,
	'remoteExtPath'	=> 'ImportArticles',
	'dependencies'	=> array()
);
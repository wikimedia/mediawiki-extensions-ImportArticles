<?php
/* This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

# Only run if included by MediaWiki
if ( !defined( 'MEDIAWIKI' ) ) die();

$wgExtensionCredits['other'][] = array(
    'name' => 'ImportArticles',
    'author' => 'Kris Blair', 
    'description' => 'Allow the usage of ImportArticles JavaScript to import multiple pages at once',
    'version'  => 1.2,
);
$wgHooks['BeforePageDisplay'][] = 'BeforePageDisplay';

public static function BeforePageDisplay( &$out, &$skin ) {
	$script = <<<SCRIPT
/**
 * Import JavaScript and Stylesheet articles.
 * 
 * @version 1.2 (02-11-2015)
 * @licence GPL v2 or later
 * @author Cblair91 <kblair@curse.com>
 */

/**
 * Call the api and send the data then output the return
 *
 * @param {Array} The data to use.
 * @param String The method to use: either 'GET' or 'POST'.
 * @param Function The function to call back to.
 */
function callAPI( data, method, callback )
{
	data.format = 'json';
	$.ajax(
	{
		data: data,
		dataType: 'json',
		url: '/api.php',
		type: method,
		success: function( response )
		{
			if( response.error ) {
				console.log( 'API error: ' + response.error.info );
				return false;
			} else if( response.warnings ) {
				console.log( 'API Warning: ' + response.warnings.main['*'] );
			} else {
				callback( response );
			}
		},
		error: function( xhr, error )
		{
			console.log( 'AJAX error: ' + error );
		}
	});
}

/*
 *
 * >> Examples:
 *
 * // Importing multiple JavaScript files
 *	importArticles(
 *		'script', [
 *			'Mediawiki:MyCustomJavaScript.js',
 *			'Mediawiki:MyCustomJavaScript.js2',
 *		]
 *	});
 *
 * @param String The type to load: Either 'script' or 'style'.
 * @param (String/{Array}) The file(s) to load.
 * @returns Boolean Whether success or fail
 */
function importArticles( type, pages ) {
	var output,
		page,
		outputText = '',
		query = {
			'action': 'query',
			'prop': 'revisions',
			'rvprop': 'content',
			'indexpageids': 'true'
		};
	if ( $.isArray( pages ) ) {
		query.titles = pages.join( '|' );
	} else {
		query.titles = pages;
	}
	if ( type == 'script' ) {
		window.output = document.createElement( 'script' );
		window.output.type = 'text/javascript';
	} else if ( type == 'style' ) {
		window.output = document.createElement( 'style' );
	} else {
		console.log( 'Invalid article type: ' + type );
		return false;
	}
	callAPI(
		query,
		'GET',
		function( response )
		{
			for( var i = 0; i < response.query.pageids.length; i++ ) {
				page = response.query.pages[response.query.pageids[i]];
				if( response.query.pageids[i] == '-1' ) {
					console.log( 'The page does not exist: ' + page.title );
				} else {
					outputText = outputText + "\n" + page.revisions[0]['*'].replace( "'", "\'" );
				}
			}
			try {
				window.output.appendChild( document.createTextNode( outputText ) );
			} catch ( e ) {
				window.output.text = outputText;
			}
			document.body.appendChild( window.output );
		}
	);
}
SCRIPT;
	if (version_compare( $wgVersion, '1.16', '>=' ) ) {
		$out->addInlineScript( $script );
	} else {
		$out->addScript( '<script type="text/javascript">\n' . $script . '\n</script>' );
	}
}


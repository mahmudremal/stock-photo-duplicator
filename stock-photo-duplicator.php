<?php
/**
 * This plugin ordered by a client and done by Remal Mahmud (fiverr.com/mahmud_remal). Authority dedicated to that cient.
 *
 * @wordpress-plugin
 * Plugin Name:       Stock Photo Duplicator
 * Plugin URI:        https://github.com/mahmudremal/stock-photo-duplicator/
 * Description:       Stock photo duplicate & random product upload following a sample product. Copy post contents & meta data with replacing thumbnails & downloadable file.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Remal Mahmud
 * Author URI:        https://github.com/mahmudremal/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       stock-photo-duplicator
 * Domain Path:       /languages
 * 
 * @package FutureWordPressScratchProject
 * @author  Remal Mahmud (https://github.com/mahmudremal)
 * @version 1.0.2
 * @link https://github.com/mahmudremal/stock-photo-duplicator/
 * @category	WooComerce Plugin
 * @copyright	Copyright (c) 2023-25
 * 
 */

/**
 * Bootstrap the plugin.
 */



defined( 'STOCK_PHOTO_DUPLICATOR__FILE__' ) || define( 'STOCK_PHOTO_DUPLICATOR__FILE__', untrailingslashit( __FILE__ ) );
defined( 'STOCK_PHOTO_DUPLICATOR_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_DIR_PATH', untrailingslashit( plugin_dir_path( STOCK_PHOTO_DUPLICATOR__FILE__ ) ) );
defined( 'STOCK_PHOTO_DUPLICATOR_DIR_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_DIR_URI', untrailingslashit( plugin_dir_url( STOCK_PHOTO_DUPLICATOR__FILE__ ) ) );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/js' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build/js' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_IMG_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_IMG_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/src/img' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/css' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build/css' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_LIB_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_LIB_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/library' );
defined( 'STOCK_PHOTO_DUPLICATOR_ARCHIVE_POST_PER_PAGE' ) || define( 'STOCK_PHOTO_DUPLICATOR_ARCHIVE_POST_PER_PAGE', 9 );
defined( 'STOCK_PHOTO_DUPLICATOR_SEARCH_RESULTS_POST_PER_PAGE' ) || define( 'STOCK_PHOTO_DUPLICATOR_SEARCH_RESULTS_POST_PER_PAGE', 9 );
defined( 'STOCK_PHOTO_DUPLICATOR_OPTIONS' ) || define( 'STOCK_PHOTO_DUPLICATOR_OPTIONS', get_option( 'stock-photo-duplicator' ) );

require_once STOCK_PHOTO_DUPLICATOR_DIR_PATH . '/inc/helpers/autoloader.php';
// require_once STOCK_PHOTO_DUPLICATOR_DIR_PATH . '/inc/helpers/template-tags.php';

if( ! function_exists( 'stockphotoduplicator_get_plugin_instance' ) ) {
	function stockphotoduplicator_get_plugin_instance() {\STOCK_PHOTO_DUPLICATOR\Inc\Project::get_instance();}
}
stockphotoduplicator_get_plugin_instance();




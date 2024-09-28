<?php
/**
 * Plugin Name:       Alt text generator
 * Description:       Automatically generate alt description text to your images.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Ramsés Del Rosario
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       alt-text-gen
 *
 * @package AltTextGenerator
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
require_once PLUGIN_DIR_PATH . '/inc/hooks.php';

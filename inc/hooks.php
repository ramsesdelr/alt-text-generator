<?php
/**
 * Hooks
 *
 * @since  1.0.0
 * @package  AltTextGenerator
 */

namespace AltTextGenerator;

/**
 * Adds "Add Alt Text" column to each displayed media file on the library.
 *
 * @param string[] $post_columns The Array of columns.
 * @param bool     $detached Whether the list table contains media not attached to any posts.
 * @since 0.0.1
 * @return array
 */
function modify_manage_media_columns_defaults( $post_columns, $detached ): array {
	$post_columns['get_alt_text'] = __( 'Add Alt Text' );
	return $post_columns;
}
add_filter( 'manage_media_columns', __NAMESPACE__ . '\\modify_manage_media_columns_defaults', 10, 2 );

/**
 * Adds "Add Alt Text" button to each displayed media file on the library.
 *
 * @param string $column_name Name of the custom column.
 * @param int    $post_id The current post ID.
 * @since 0.0.1
 * @return void
 */
function add_alt_text_button( $column_name, $post_id ): void {
	if ( 'get_alt_text' === $column_name ) {
		echo '<button class="button" type="button" onclick="process_image(' . esc_attr( $post_id ) . ')">Set Alt Text</button>';
	}
}
add_filter( 'manage_media_custom_column', __NAMESPACE__ . '\\add_alt_text_button', 10, 2 );

/**
 * Enqueues the required assets.
 *
 * @since 0.0.1
 * @return void
 */
function my_enqueue_scripts(): void {
	$bundle_path = PLUGIN_DIR_PATH . 'dist/bundle.js';
	wp_enqueue_script( 'my-ajax-script', plugin_dir_url( __FILE__ ) . '../dist/bundle.js', [], filemtime( $bundle_path ), false );
	wp_localize_script( 'my-ajax-script', 'ajax_object', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\my_enqueue_scripts' );

/**
 * Manages the POST data to be sent to the OpenAI API.
 *
 * @return void
 */
function add_image_alt_text() {
	require_once PLUGIN_DIR_PATH . 'inc/class-handle-image-alt-text.php';
	$assign_alt_tex_to_image = new HandleImageAltText();
	$alt_text                = $assign_alt_tex_to_image->assign_alt_tex_to_image( wp_unslash( $_POST['attachment_id'] ) ); // TODO: Let's make this beautiful.
	echo esc_html( $alt_text );
	wp_die();
}
add_action( 'wp_ajax_update_image_alt', __NAMESPACE__ . '\\add_image_alt_text' );
add_action( 'wp_ajax_nopriv_update_image_alt', __NAMESPACE__ . '\\add_image_alt_text' );

<?php
/**
 * Shadcn Child theme functions.
 *
 * @package Shadcn_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the compiled child-theme stylesheet.
 *
 * The parent theme owns its own assets. This child only adds its compiled
 * layer, so parent updates remain safe.
 *
 * @return void
 */
function shadcn_child_enqueue_assets() {
	$stylesheet_path = get_stylesheet_directory() . '/dist/app.css';
	$stylesheet_uri  = get_stylesheet_directory_uri() . '/dist/app.css';

	// The parent calls get_stylesheet_uri(), which resolves to the child
	// stylesheet in a child-theme context. Restore the parent's CSS under its
	// existing handle so all parent styles and dark-mode tokens are preserved.
	wp_dequeue_style( 'shadcn-style' );
	wp_deregister_style( 'shadcn-style' );
	wp_enqueue_style(
		'shadcn-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);

	if ( file_exists( $stylesheet_path ) ) {
		wp_enqueue_style(
			'shadcn-child-app',
			$stylesheet_uri,
			array( 'shadcn-style' ),
			(string) filemtime( $stylesheet_path )
		);
	}

	wp_enqueue_script(
		'shadcn-child-toggle',
		get_stylesheet_directory_uri() . '/assets/js/toggle.js',
		array( 'shadcn-dark-mode' ),
		(string) filemtime( get_stylesheet_directory() . '/assets/js/toggle.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'shadcn_child_enqueue_assets', 99 );

/**
 * Enqueue the same compiled styles in the block editor.
 *
 * @return void
 */
function shadcn_child_enqueue_editor_assets() {
	$stylesheet_path = get_stylesheet_directory() . '/dist/app.css';
	$stylesheet_uri  = get_stylesheet_directory_uri() . '/dist/app.css';

	if ( file_exists( $stylesheet_path ) ) {
		wp_enqueue_style(
			'shadcn-child-editor',
			$stylesheet_uri,
			array(),
			(string) filemtime( $stylesheet_path )
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'shadcn_child_enqueue_editor_assets' );

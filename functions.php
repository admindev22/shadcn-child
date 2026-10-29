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

/**
 * Wrap WooCommerce's original authentication forms for progressive enhancement.
 *
 * @return void
 */
function shadcn_child_account_tabs_start() {
	wp_enqueue_script(
		'shadcn-child-account-tabs',
		get_stylesheet_directory_uri() . '/assets/js/account-tabs.js',
		array(),
		(string) filemtime( get_stylesheet_directory() . '/assets/js/account-tabs.js' ),
		true
	);
	wp_localize_script(
		'shadcn-child-account-tabs',
		'shadcnChildAccount',
		array(
			'keepSignedIn'   => __( 'Keep me signed in', 'shadcn-child' ),
			'forgotPassword' => __( 'Forgot password?', 'shadcn-child' ),
		)
	);

	// This value selects a panel only; WooCommerce validates the submission.
	$initial_tab = isset( $_POST['register'] ) ? 'register' : 'login'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	?>
	<div class="shadcn-child-auth-tabs mx-auto w-full max-w-[460px]" data-initial-tab="<?php echo esc_attr( $initial_tab ); ?>">
		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
		<div data-auth-tablist hidden aria-label="<?php esc_attr_e( 'Account access', 'shadcn-child' ); ?>" class="flex w-full border-0 border-b border-solid border-border bg-transparent">
			<?php foreach ( array( 'login' => __( 'Sign in', 'shadcn-child' ), 'register' => __( 'Create Account', 'shadcn-child' ) ) as $key => $label ) : ?>
				<button type="button" data-auth-tab="<?php echo esc_attr( $key ); ?>" class="-mb-px inline-flex min-h-11 flex-1 items-center justify-center border-0 border-b-2 border-solid border-transparent bg-transparent px-1 pb-5 pt-1 text-base font-semibold text-muted-foreground hover:text-foreground aria-selected:border-foreground aria-selected:text-foreground focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	<?php
}
add_action( 'woocommerce_before_customer_login_form', 'shadcn_child_account_tabs_start', 20 );

/**
 * Close the authentication wrapper.
 *
 * @return void
 */
function shadcn_child_account_tabs_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_customer_login_form', 'shadcn_child_account_tabs_end', 5 );

/**
 * Offer the registration panel without replacing WooCommerce form controls.
 *
 * @return void
 */
function shadcn_child_account_register_prompt() {
	if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		return;
	}
	?>
	<p data-auth-register-prompt hidden class="mt-6 border-0 border-t border-solid border-border pt-7 text-center">
		<button type="button" data-auth-register-link class="min-h-11 cursor-pointer border-0 bg-transparent text-sm font-medium text-foreground underline underline-offset-4"><?php esc_html_e( 'New Customer? Create account', 'shadcn-child' ); ?></button>
	</p>
	<?php
}
add_action( 'woocommerce_login_form_end', 'shadcn_child_account_register_prompt' );

/**
 * Match password recovery to the authentication form layout.
 *
 * @return void
 */
function shadcn_child_password_form_start() {
	$title = doing_action( 'woocommerce_before_reset_password_form' )
		? __( 'Reset password', 'shadcn-child' )
		: __( 'Forgot password?', 'shadcn-child' );
	?>
	<div class="shadcn-child-password-form mx-auto w-full max-w-[460px]">
		<h2 class="mb-6 border-0 border-b border-solid border-border pb-5 text-center text-base font-semibold text-foreground"><?php echo esc_html( $title ); ?></h2>
	<?php
}
add_action( 'woocommerce_before_lost_password_form', 'shadcn_child_password_form_start' );
add_action( 'woocommerce_before_reset_password_form', 'shadcn_child_password_form_start' );

/**
 * Provide a site-aware return link and close the recovery wrapper.
 *
 * @return void
 */
function shadcn_child_password_form_end() {
	?>
		<p class="mt-6 text-center text-sm"><a class="text-foreground underline underline-offset-4" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Back to sign in', 'shadcn-child' ); ?></a></p>
	</div>
	<?php
}
add_action( 'woocommerce_after_lost_password_form', 'shadcn_child_password_form_end', 99 );
add_action( 'woocommerce_after_reset_password_form', 'shadcn_child_password_form_end', 99 );

/**
 * Render the guest account dialog using WooCommerce's original forms.
 *
 * @return void
 */
function shadcn_child_account_modal() {
	if ( is_user_logged_in() || ! function_exists( 'wc_get_template' ) || ! wc_get_page_id( 'myaccount' ) || wc_get_page_id( 'myaccount' ) < 0 ) {
		return;
	}

	wp_enqueue_script(
		'shadcn-child-account-modal',
		get_stylesheet_directory_uri() . '/assets/js/account-modal.js',
		array(),
		(string) filemtime( get_stylesheet_directory() . '/assets/js/account-modal.js' ),
		true
	);
	?>
	<dialog id="shadcn-child-account-modal" aria-labelledby="shadcn-child-account-modal-title" aria-describedby="shadcn-child-account-modal-description" data-account-url="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="woocommerce fixed inset-0 m-auto box-border max-h-[calc(100dvh-2rem)] w-[calc(100%-2rem)] max-w-lg overflow-y-auto overscroll-contain rounded-xl border border-solid border-border bg-background p-6 text-foreground shadow-lg backdrop:bg-black/50">
		<h2 id="shadcn-child-account-modal-title" class="sr-only"><?php esc_html_e( 'Sign in or create an account', 'shadcn-child' ); ?></h2>
		<p id="shadcn-child-account-modal-description" class="sr-only"><?php esc_html_e( 'Access your account or register using the forms below.', 'shadcn-child' ); ?></p>
		<button type="button" data-account-modal-close aria-label="<?php esc_attr_e( 'Close account dialog', 'shadcn-child' ); ?>" class="absolute end-3 top-3 z-10 flex h-8 w-8 cursor-pointer items-center justify-center rounded-sm border-0 bg-transparent p-0 text-foreground opacity-70 transition-opacity hover:opacity-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring">
			<svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 6-12 12M6 6l12 12" /></svg>
		</button>
		<div data-account-modal-content class="pt-6">
			<?php
			// Reuse the visible forms on the account landing page to avoid duplicate IDs.
			if ( ! is_account_page() || is_wc_endpoint_url() ) {
				wc_get_template( 'myaccount/form-login.php' );
			}
			?>
		</div>
	</dialog>
	<?php
}
add_action( 'wp_footer', 'shadcn_child_account_modal', 5 );

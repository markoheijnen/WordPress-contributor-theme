<?php
/**
 * Contributor Theme Customizer
 *
 * @package Contributor
 */


class Contributor_Theme_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'theme_menu' ), 1 );
	}

	/**
	 * Add menu item for theme options
	 */
	public function theme_menu() {
		add_theme_page( __( 'Theme Options', 'contributor' ), __( 'Theme Options', 'contributor' ), 'manage_options', 'theme-options', array( $this, 'theme_options' ) );  
	}

	public function theme_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to manage options for this theme.', 'contributor' ) );
		}

		wp_enqueue_style( 'contributor-admin',  get_template_directory_uri() . '/css/admin.css' );
		wp_enqueue_script( 'contributor-admin',  get_template_directory_uri() . '/js/admin.js', array( 'jquery' ) );
		?>

		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<?php
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				if ( isset( $_POST['contributor_username'] ) ) {
					set_theme_mod( 'contributor_username', sanitize_text_field( $_POST['contributor_username'] ) );
				}
			}
			?>

			<br />

			<form action="" method="post">
				<div class="contributor-search">
					<div class="contributor-search-input">
						<input type="text" name="contributor_username" value="<?php echo get_theme_mod('contributor_username'); ?>" placeholder="<?php _e( 'What is your WordPress username?', 'contributor' ); ?>" size="30">
					</div>

					<button type="button" class="button-primary"><?php _e( 'Search', 'contributor' ); ?></button>
				</div>

				<?php submit_button(); ?>
			</form>
		</div>

		<?php
	}

}
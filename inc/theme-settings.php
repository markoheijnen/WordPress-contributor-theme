<?php
/**
 * Contributor Theme Customizer
 *
 * @package Contributor
 */


class Contributor_Theme_Settings {
	private $template_data;

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

		$template_blocks = array(
			array(
				'title' => __( 'Core contributions', 'contributor' ),
				'list'  => 'core_contributions',
				'count' => 'core_contributions_count',
			),
			array(
				'title' => __( 'Codex contributions', 'contributor' ),
				'list'  => 'codex_items',
				'count' => 'codex_items_count',
			),
			array(
				'title' => __( 'Plugins', 'contributor' ),
				'list'  => 'plugins',
			),
			array(

				'title' => __( 'Themes', 'contributor' ),
				'list'  => 'themes',
			),
		);


		wp_enqueue_style( 'contributor-admin',  get_template_directory_uri() . '/css/admin.css' );
		wp_enqueue_script( 'contributor-admin',  get_template_directory_uri() . '/js/admin.js', array( 'jquery', 'wp-backbone' ) );
		wp_localize_script( 'contributor-admin', 'contributor_template_blocks', $template_blocks );
		?>

		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<?php
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				if ( isset( $_POST['contributor_username'] ) ) {
					set_theme_mod( 'contributor_username', sanitize_text_field( $_POST['contributor_username'] ) );
				}
			}

			$contributor_username = get_theme_mod('contributor_username');
			$profile = new Contributor_Profile_Builder( $contributor_username );
			$user_data = $profile->get_data();
			?>

			<br />

			<form id="contributor" action="" method="post">
				<div class="contributor-search contributor-block">
					<div class="contributor-search-input">
						<input type="text" name="contributor_username" value="<?php echo $contributor_username; ?>" placeholder="<?php _e( 'What is your WordPress username?', 'contributor' ); ?>" size="30">
					</div>

					<button type="button" class="button-primary"><?php _e( 'Search', 'contributor' ); ?></button>
				</div>

				<div id="contributions">
					<?php
					if ( $contributor_username && $user_data ) {
						echo $this->parse_backbone( 'profile_template', $user_data );

						foreach ( $template_blocks as $block ) {
							echo $this->parse_backbone( 'block_template', (object)$block );
						}
					}

					?>
				</div>

				<?php submit_button(); ?>
			</form>
		</div>


		<script type="text/template" id="tmpl-contributor_profile">
			<?php echo $this->profile_template(); ?>
		</script>

		<script type="text/template" id="tmpl-contributor_block">
			<?php echo $this->block_template(); ?>
		</script>

		<?php
	}


	private function parse_backbone( $function, $data ) {
		$this->template_data = $data;

		$html = call_user_func( array( $this, $function ) );
		$html = preg_replace_callback('#<\# \if\s(.+?) { \#>(.+?)\<\# } \#>#s', array( $this, 'parse_backbone_if' ), $html );
		$html = preg_replace_callback( '!\{\{ (data\.\w+) \}\}!', array( $this, 'parse_backbone_var' ), $html );

		$this->template_data = false;

		return $html;
	}

	private function parse_backbone_if( $matches ) {
		$key = substr( substr( $matches[1], 7 ), 0, -2 );

		if ( isset( $this->template_data->$key ) ) {
			return $matches[2];
		}
		
		return '';
	}

	private function parse_backbone_var( $matches ) {
		$key = substr( $matches[1], 5 );
		
		return $this->template_data->$key;
	}


	private function profile_template() {
		$html  = '<div class="contributor-profile contributor-block">';
		$html .= '<# if ( data.avatar ) { #>';
		$html .= '	<div class="profile-avatar">';
		$html .= '		<img src="{{ data.avatar }}" alt="" />';
		$html .= '	</div>';
		$html .= '<# } #>';

		$html .= '	<div class="contributor-info">';
		$html .= '		<h2>{{ data.name }}</h2>';
		$html .= '		<p>{{ data.company }}</p>';
		$html .= '		<p><strong>' . __( 'Website', 'contributor' ) . ':</strong> <a href="{{ data.website }}">{{ data.website }}</a></p>';
		$html .= '	</div>';

		$html .= '</div>';

		return $html;
	}

	private function block_template() {
		$html  = '<div class="contributor-contribution contributor-block">';
		$html .= '<h3>{{ data.title }}</h3>';

		$html .= '</div>';

		return $html;
	}

}
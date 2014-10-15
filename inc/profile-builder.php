<?php

class Contributor_Profile_Builder {
	private $username;
	private $wpcentral_data;

	public function __construct( $username ) {
		$this->username = $username;
		$this->wpcentral_data = $this->get_data( $username );
	}

	public function get_name() {
		return $this->wpcentral_data->name;
	}

	public function get_avatar( $size = 96 ) {
		return $this->wpcentral_data->avatar . '?s=' . $size;
	}

	public function show_stats() {
		?>

		<div class="row stats">
			<?php $this->get_panel( 'WordPress versions', count( (array) $this->wpcentral_data->core_contributed_to ), 'fa-wordpress', 'https://core.trac.wordpress.org/search?q=props+' . $this->username . '&amp;noquickjump=1&amp;changeset=on&amp;max=20' ); ?>
			<?php $this->get_panel( 'Core contributions', $this->wpcentral_data->core_contributions_count, 'fa-heart', 'https://core.trac.wordpress.org/search?q=props+' . $this->username . '&amp;noquickjump=1&amp;changeset=on&amp;max=20' ); ?>
			<?php $this->get_panel( 'Codex contributions', $this->wpcentral_data->codex_items_count, 'fa-pencil', 'http://codex.wordpress.org/Special:Contributions/' . $this->username, 'codex' ); ?>
			<?php $this->get_panel( 'Plugins', count( $this->wpcentral_data->plugins ), 'fa-wrench', 'http://profiles.wordpress.org/' . $this->username . '#content-plugins', 'plugins' ); ?>
		</div>

		<?php
	}


	private function get_data( $username ) {
		if ( ! $username ) {
			return $this->clean_data( $username );
		}

		if ( false !== ( $data = get_transient( 'profile_builder_' . $username ) ) ) {
			return $data;
		}

		$response = wp_remote_get( 'http://wpcentral.io/api/contributors/' . $username );
		$data     = wp_remote_retrieve_body( $response );

		if ( ! $data ) {
			return $this->clean_data( $username );
		}

		$data = json_decode( $data );

		set_transient( 'profile_builder_' . $username, $data, DAY_IN_SECONDS );

		return $data;
	}

	private function clean_data( $name = '' ) {
		return (object) array(
			'name' => $name,
			'avatar' => '',
			'core_contributed_to' => 0,
			'core_contributions_count' => 0,
			'codex_items_count' => 0,
			'plugins' => 0,
			'themes' => 0,
		);
	}

	private function get_panel( $title, $value, $icon, $link, $panel = 'primary' ) {
		?>

			<div class="col-lg-3 col-sm-6">
				<div class="panel panel-<?php echo $panel; ?>">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-5x <?php echo $icon; ?>"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?php echo $value; ?></div>
								<div><?php echo $title; ?></div>
							</div>
						</div>
					</div>

					<a href="<?php echo $link; ?>">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>

		<?php
	}

}

	<?php
	$profile = Contributor_Theme::get_profile();
	?>

	<header id="masthead" class="site-header block" role="banner">
		<div class="wrapper">
			<div class="site-branding">
				<img src="<?php echo $profile->get_avatar(); ?>" />

				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle"><?php _e( 'Primary Menu', 'contributor' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'wrapper' ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

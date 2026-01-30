<?php
/**
 * The header for our theme
 *
 * @package Basic_Starter_Dev
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php
	/**
	 * Helper: Get theme setting (fallback to customizer)
	 */
	if ( ! function_exists( 'bsd_get_theme_option' ) ) {
		function bsd_get_theme_option( $option_name, $customizer_setting = '', $default = '' ) {
			$option_value = get_option( $option_name );
			if ( ! empty( $option_value ) ) {
				return $option_value;
			}
			if ( $customizer_setting ) {
				$customizer_value = get_theme_mod( $customizer_setting );
				if ( ! empty( $customizer_value ) ) {
					return $customizer_value;
				}
			}
			return $default;
		}
	}

	// ===============================
	// Favicon (Theme Settings > Customizer)
	// ===============================
	$favicon = bsd_get_theme_option( 'bsd_header_favicon', 'site_icon' );

	// If customizer returned an attachment ID, get its URL
	if ( $favicon && is_numeric( $favicon ) ) {
		$favicon = wp_get_attachment_url( $favicon );
	}

	if ( $favicon ) :
	?>
		<link rel="icon" href="<?php echo esc_url( $favicon ); ?>" type="image/png">
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'basic-starter-dev' ); ?></a>

	<?php
	// Get sticky header setting
	$sticky_header = get_option( 'bsd_sticky_header', 'no' );
	$header_class = 'site-header';
	if ( 'yes' === $sticky_header ) {
		$header_class .= ' sticky-enabled';
	}
	?>
	<header id="masthead" class="<?php echo esc_attr( $header_class ); ?>">
		<div class="container header-inner">

			<div class="site-branding">
				<?php
				// ===============================
				// Site Logo (Theme Settings > Customizer)
				// ===============================
				$header_logo = bsd_get_theme_option( 'bsd_header_logo', 'custom_logo' );
				$logo_width  = absint( bsd_get_theme_option( 'bsd_logo_width', '', 0 ) );
				$logo_height = absint( bsd_get_theme_option( 'bsd_logo_height', '', 0 ) );
				$logo_styles = array();

				if ( $logo_width ) {
					$logo_styles[] = 'width:' . $logo_width . 'px';
					$logo_styles[] = 'max-width:' . $logo_width . 'px';
				}

				if ( $logo_height ) {
					$logo_styles[] = 'height:' . $logo_height . 'px';
					$logo_styles[] = 'max-height:' . $logo_height . 'px';
				}

				$logo_style_attr = $logo_styles ? ' style="' . esc_attr( implode( '; ', $logo_styles ) ) . '"' : '';

				if ( $header_logo && is_numeric( $header_logo ) ) {
					$header_logo = wp_get_attachment_url( $header_logo );
				}

				if ( $header_logo ) :
				?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
						<img src="<?php echo esc_url( $header_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="custom-logo"<?php echo $logo_style_attr; ?>>
					</a>
				<?php else : ?>
					<?php the_custom_logo(); ?>
				<?php endif; ?>

				<?php
				// ===============================
				// Site Title (Theme Settings > Customizer)
				// ===============================
				$site_title = bsd_get_theme_option( 'bsd_site_title', 'blogname', get_bloginfo( 'name' ) );
				?>
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php echo esc_html( $site_title ); ?>
						</a>
					</h1>
				<?php else : ?>
					<p class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php echo esc_html( $site_title ); ?>
						</a>
					</p>
				<?php endif; ?>

				<?php
				// ===============================
				// Site Description (Theme Settings > Customizer)
				// ===============================
				$site_desc = bsd_get_theme_option( 'bsd_site_des', 'blogdescription', get_bloginfo( 'description', 'display' ) );

				if ( $site_desc || is_customize_preview() ) :
				?>
					<p class="site-description"><?php echo esc_html( $site_desc ); ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Main menu', 'basic-starter-dev' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-icon"></span>
				</button>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_class'     => 'primary-menu',
					'container'      => false,
					'walker'         => class_exists( 'BSD_Walker_Nav_Menu' ) ? new BSD_Walker_Nav_Menu() : '',
				) );
				?>
			</nav><!-- #site-navigation -->

		</div><!-- .header-inner -->
	</header><!-- #masthead -->

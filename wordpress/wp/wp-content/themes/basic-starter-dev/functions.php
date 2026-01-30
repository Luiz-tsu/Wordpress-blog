<?php
/**
 * Basic Starter Theme functions and definitions
 *
 * @package Basic_Starter_Dev
 */

if ( ! defined( 'BSD_VERSION' ) ) {
	define( 'BSD_VERSION', '1.2.4' );
}

/**
 * Theme setup
 */
function bsd_setup() {
	load_theme_textdomain( 'basic-starter-dev', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'bsd_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'basic-starter-dev' ),
			'footer' => esc_html__( 'Footer Menu', 'basic-starter-dev' ),
		)
	);

	// Load editor styles
	add_editor_style( 'editor-style.css' );
}
add_action( 'after_setup_theme', 'bsd_setup' );

function bsd_register_block_styles() {
    register_block_style(
        'core/image',
        array(
            'name'  => 'fancy-border',
            'label' => __( 'Fancy Border', 'basic-starter-dev' ),
            'inline_style' => '.is-style-fancy-border { border: 5px double #000; padding: 10px; }',
        )
    );
    register_block_pattern(
        'basic-starter-dev/hero-text',
        array(
            'title'       => __( 'Hero Text Section', 'basic-starter-dev' ),
            'description' => _x( 'A centered hero section with text.', 'Block pattern description', 'basic-starter-dev' ),
            'content'     => "<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"large\"} --><p class=\"has-text-align-center has-large-font-size\">" . esc_html__('Welcome to Our Website!', 'basic-starter-dev') . "</p><!-- /wp:paragraph -->",
        )
    );
}
add_action( 'init', 'bsd_register_block_styles' );

/**
 * Set the content width
 */
function bsd_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bsd_content_width', 640 );
}
add_action( 'after_setup_theme', 'bsd_content_width', 0 );

/**
 * Register widget area
 */
function bsd_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'basic-starter-dev' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'basic-starter-dev' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area', 'basic-starter-dev' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'basic-starter-dev' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'bsd_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function bsd_scripts() {
	wp_enqueue_style( 'bsd-style', get_stylesheet_uri(), array(), BSD_VERSION );
	wp_style_add_data( 'bsd-style', 'rtl', 'replace' );

	wp_enqueue_script(
		'bsd-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		BSD_VERSION,
		true
	);

	wp_enqueue_script(
		'bsd-custom',
		get_template_directory_uri() . '/js/custom.js',
		array('jquery'), // if your JS depends on jQuery
		BSD_VERSION,
		true
	);

	// Customizer live preview
	if ( is_customize_preview() ) {
		wp_enqueue_script(
			'bsd-customizer',
			get_template_directory_uri() . '/js/customizer.js',
			array( 'jquery', 'customize-preview' ),
			BSD_VERSION,
			true
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bsd_scripts' );

/**
 * Custom Navigation Walker for better accessibility
 */
class BSD_Walker_Nav_Menu extends Walker_Nav_Menu {

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        // Ensure $args works as object or array
        $before      = is_array( $args ) ? ( $args['before'] ?? '' ) : ( $args->before ?? '' );
        $after       = is_array( $args ) ? ( $args['after'] ?? '' ) : ( $args->after ?? '' );
        $link_before = is_array( $args ) ? ( $args['link_before'] ?? '' ) : ( $args->link_before ?? '' );
        $link_after  = is_array( $args ) ? ( $args['link_after'] ?? '' ) : ( $args->link_after ?? '' );

        $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add has-children helper class
        $has_children = in_array( 'menu-item-has-children', $classes, true );
        if ( $has_children ) {
            $classes[] = 'has-children';
        }

        $class_names = implode(
            ' ',
            apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args )
        );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $item_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $item_id = $item_id ? ' id="' . esc_attr( $item_id ) . '"' : '';

        $output .= $indent . '<li' . $item_id . $class_names . '>';

        // Link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';

        // ARIA for submenu parents
        if ( $has_children ) {
            $attributes .= ' aria-haspopup="true" aria-expanded="false"';
        }

        // Build output
        $item_output  = $before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $link_after;

        // Submenu icon (decorative)
        if ( $has_children ) {
            $item_output .= ' <img src="' . esc_url( get_template_directory_uri() . '/images/down.png' ) . '" alt="" class="submenu-icon" />';
        }

        $item_output .= '</a>';
        $item_output .= $after;

        $output .= apply_filters(
            'walker_nav_menu_start_el',
            $item_output,
            $item,
            $depth,
            $args
        );
    }
}


/**
 * Include theme files
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add Theme Settings Page
 */
function bsd_add_theme_settings_page() {
	add_menu_page(
		__('Theme Settings', 'basic-starter-dev'),
		__('Theme Settings', 'basic-starter-dev'),
		'manage_options',
		'bsd-theme-settings',
		'bsd_render_theme_settings_page',
		'dashicons-admin-generic',
		3
	);
}
add_action('admin_menu', 'bsd_add_theme_settings_page');

/**
 * Render Theme Settings Page
 */
function bsd_render_theme_settings_page() {
	?>
	<div class="bsd-theme-settings-wrap">
		<h1><?php esc_html_e('Theme Settings', 'basic-starter-dev'); ?><span class="bsd-version"><?php echo esc_html( 'v' . BSD_VERSION ); ?></span></h1>

		<div class="bsd-settings-container">
			<!-- Sidebar -->
			<div class="bsd-settings-sidebar">
				<ul>
					<li><a href="#bsd-general" class="active"><?php esc_html_e('General', 'basic-starter-dev'); ?></a></li>
					<li><a href="#bsd-header"><?php esc_html_e('Header', 'basic-starter-dev'); ?></a></li>
					<li><a href="#bsd-footer"><?php esc_html_e('Footer', 'basic-starter-dev'); ?></a></li>
				</ul>
			</div>

			<!-- Main Content -->
			<div class="bsd-settings-content">
				<form method="post" action="options.php" id="bsd-theme-settings-form">
					<?php settings_fields('bsd_theme_options'); ?>
					<input type="hidden" name="bsd_active_tab" id="bsd-active-tab-input" value="">

					<!-- General -->
					<section id="bsd-general">
						<h2><?php esc_html_e('General Settings', 'basic-starter-dev'); ?></h2>
						<table class="form-table">
					        <tr>
					            <th scope="row"><?php esc_html_e('Favicon', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php 
					                    $bsd_favicon = esc_url(get_option('bsd_header_favicon', ''));
					                ?>
					                <div class="bsd-logo-upload-wrap">
					                    <img id="bsd-favicon-preview" src="<?php echo $bsd_favicon ? $bsd_favicon : ''; ?>" style="max-width:50px;<?php echo $bsd_favicon ? '' : 'display:none;'; ?>" />
					                    <input type="hidden" id="bsd_header_favicon" name="bsd_header_favicon" value="<?php echo $bsd_favicon; ?>" />
					                    <br>
					                    <button type="button" class="button bsd-upload-favicon"><?php esc_html_e('Upload Favicon', 'basic-starter-dev'); ?></button>
					                    <button type="button" class="button bsd-remove-favicon" style="<?php echo $bsd_favicon ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove', 'basic-starter-dev'); ?></button>
					                </div>
					            </td>
					        </tr>
					        <tr>
					            <th scope="row"><?php esc_html_e('Site Title', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php
					                $bsd_site_title = esc_html(get_option('bsd_site_title', ''));
					                ?>
					                <input type="text" 
					                       name="bsd_site_title" 
					                       value="<?php echo $bsd_site_title; ?>" 
					                       class="regular-text" 
					                       placeholder="">					             
					            </td>
					        </tr>

					        <tr>
					            <th scope="row"><?php esc_html_e('Site Description', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php
					                $bsd_site_des = esc_html(get_option('bsd_site_des', ''));
					                ?>
					                <textarea name="bsd_site_des" cols="50" rows="5"><?php echo $bsd_site_des; ?></textarea>			             
					            </td>
					        </tr>
					    </table>
					</section>

					<!-- Header -->
					<section id="bsd-header">
					    <h2><?php esc_html_e('Header Settings', 'basic-starter-dev'); ?></h2>

					    <table class="form-table">
					        <tr>
					            <th scope="row"><?php esc_html_e('Header Logo', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php 
					                    $header_logo = esc_url(get_option('bsd_header_logo', ''));
					                ?>
					                <div class="bsd-logo-upload-wrap">
					                    <img id="bsd-logo-preview" src="<?php echo $header_logo ? $header_logo : ''; ?>" style="max-width:150px;<?php echo $header_logo ? '' : 'display:none;'; ?>" />
					                    <input type="hidden" id="bsd_header_logo" name="bsd_header_logo" value="<?php echo $header_logo; ?>" />
					                    <br>
					                    <button type="button" class="button bsd-upload-logo"><?php esc_html_e('Upload Logo', 'basic-starter-dev'); ?></button>
					                    <button type="button" class="button bsd-remove-logo" style="<?php echo $header_logo ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove', 'basic-starter-dev'); ?></button>
					                </div>
					            </td>
					        </tr>
					        <tr>
					            <th scope="row"><?php esc_html_e('Logo Width (px)', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php $bsd_logo_width = absint( get_option( 'bsd_logo_width', '' ) ); ?>
					                <input type="number"
					                       name="bsd_logo_width"
					                       value="<?php echo esc_attr( $bsd_logo_width ); ?>"
					                       class="small-text"
					                       min="0"
					                       step="1"
					                       placeholder="<?php esc_attr_e( 'auto', 'basic-starter-dev' ); ?>">
					                <p class="description"><?php esc_html_e( 'Leave empty to use the image\'s intrinsic width.', 'basic-starter-dev' ); ?></p>
					            </td>
					        </tr>
					        <tr>
					            <th scope="row"><?php esc_html_e('Logo Height (px)', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php $bsd_logo_height = absint( get_option( 'bsd_logo_height', '' ) ); ?>
					                <input type="number"
					                       name="bsd_logo_height"
					                       value="<?php echo esc_attr( $bsd_logo_height ); ?>"
					                       class="small-text"
					                       min="0"
					                       step="1"
					                       placeholder="<?php esc_attr_e( 'auto', 'basic-starter-dev' ); ?>">
					                <p class="description"><?php esc_html_e( 'Leave empty to use the image\'s intrinsic height.', 'basic-starter-dev' ); ?></p>
					            </td>
					        </tr>
					        <tr>
					            <th scope="row"><?php esc_html_e('Is sticky header?', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php 
					                    $bsd_sticky_header = get_option( 'bsd_sticky_header', 'no' );
					                ?>
					                <label>
					                    <input type="radio" 
					                           name="bsd_sticky_header" 
					                           value="yes" 
					                           <?php checked( $bsd_sticky_header, 'yes' ); ?>>
					                    <?php esc_html_e( 'Yes', 'basic-starter-dev' ); ?>
					                </label>
					                <label style="margin-left: 20px;">
					                    <input type="radio" 
					                           name="bsd_sticky_header" 
					                           value="no" 
					                           <?php checked( $bsd_sticky_header, 'no' ); ?>>
					                    <?php esc_html_e( 'No', 'basic-starter-dev' ); ?>
					                </label>
					                <p class="description"><?php esc_html_e( 'Enable sticky header to keep the header visible when scrolling. Default is No.', 'basic-starter-dev' ); ?></p>
					            </td>
					        </tr>
					    </table>
					</section>

					<!-- Footer -->
					<section id="bsd-footer">
					    <h2><?php esc_html_e('Footer Settings', 'basic-starter-dev'); ?></h2>
					    <table class="form-table">
					        <tr>
					            <th scope="row"><?php esc_html_e('Copyright Text', 'basic-starter-dev'); ?></th>
					            <td>
					                <?php
					                $footer_copyright = esc_html(get_option('bsd_footer_copyright', ''));
					                ?>
					                <input type="text" 
					                       name="bsd_footer_copyright" 
					                       value="<?php echo $footer_copyright; ?>" 
					                       class="regular-text" 
					                       placeholder="© <?php echo date('Y'); ?> Your Site Name. All rights reserved.">
					                <p class="description"><?php esc_html_e('Enter your custom footer copyright text.', 'basic-starter-dev'); ?></p>
					            </td>
					        </tr>
					    </table>
					</section>

					<?php submit_button(__('Save Changes', 'basic-starter-dev')); ?>
				</form>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Enqueue admin styles
 */
function bsd_admin_styles($hook) {
	if ($hook !== 'toplevel_page_bsd-theme-settings') {
		return;
	}
	wp_enqueue_style('bsd-admin-style', get_template_directory_uri() . '/admin-style.css', [], BSD_VERSION);
	// Media uploader
	wp_enqueue_media();
	wp_enqueue_script('bsd-admin-js', get_template_directory_uri() . '/admin-js.js', [], BSD_VERSION, true);
}
add_action('admin_enqueue_scripts', 'bsd_admin_styles');

function bsd_register_theme_settings() {
	register_setting('bsd_theme_options', 'bsd_footer_copyright');
	register_setting('bsd_theme_options', 'bsd_header_logo');
	register_setting('bsd_theme_options', 'bsd_header_favicon');
	register_setting('bsd_theme_options', 'bsd_site_title');
	register_setting('bsd_theme_options', 'bsd_site_des');	
	register_setting(
		'bsd_theme_options',
		'bsd_logo_width',
		array(
			'sanitize_callback' => 'bsd_sanitize_logo_dimension',
		)
	);
	register_setting(
		'bsd_theme_options',
		'bsd_logo_height',
		array(
			'sanitize_callback' => 'bsd_sanitize_logo_dimension',
		)
	);
	register_setting(
		'bsd_theme_options',
		'bsd_sticky_header',
		array(
			'sanitize_callback' => 'bsd_sanitize_sticky_header',
			'default' => 'no',
		)
	);
}
add_action('admin_init', 'bsd_register_theme_settings');

/**
 * Sanitize numeric logo dimensions.
 *
 * @param mixed $value Input value.
 *
 * @return string Sanitized dimension or empty string.
 */
function bsd_sanitize_logo_dimension( $value ) {
	$value = absint( $value );
	return $value ? (string) $value : '';
}

/**
 * Sanitize sticky header option.
 *
 * @param mixed $value Input value.
 *
 * @return string Sanitized value ('yes' or 'no').
 */
function bsd_sanitize_sticky_header( $value ) {
	return in_array( $value, array( 'yes', 'no' ), true ) ? $value : 'no';
}

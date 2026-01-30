<?php
/**
 * Template for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Basic_Starter_Dev
 */

get_header();
?>

<main id="primary" class="site-main" role="main">

	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_type() );

		// Post navigation
		the_post_navigation( array(
			'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'basic-starter-dev' ) . '</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'basic-starter-dev' ) . '</span> <span class="nav-title">%title</span>',
		) );

		// Load comments
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile;
	?>

</main><!-- #primary -->

<?php
get_sidebar();
get_footer();

<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Basic_Starter_Dev
 */

?>

<footer id="colophon" class="site-footer">
	<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
		<div class="footer-widgets">
			<div class="container">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
		</div>
	<?php endif;
	
	// ===============================
	// Footer Copyright Text
	// ===============================
	$default_copyright = sprintf(
		'&copy; %s %s. %s',
		date_i18n( 'Y' ),
		get_bloginfo( 'name' ),
		esc_html__( 'All rights reserved.', 'basic-starter-dev' )
	);

	$footer_copyright = bsd_get_theme_option( 'bsd_footer_copyright', '', $default_copyright );
	?>
	<p class="copyright-text">
		<?php echo wp_kses_post( $footer_copyright ); ?>
	</p>
</footer><!-- #colophon -->

<a href="#page" class="back-to-top" title="Back to top" aria-label="Back to top">
    <span class="screen-reader-text"><?php esc_html_e('Back to top','basic-starter-dev');?></span>
    &#8679;
</a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

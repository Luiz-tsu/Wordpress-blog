<?php
/**
 * The template for displaying comments
 *
 * @package Basic_Starter_Dev
 */

if ( post_password_required() ) {
	return;
}

$basic_starter_comment_count = get_comments_number();
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				esc_html( _nx( '%1$s thought on “%2$s”', '%1$s thoughts on “%2$s”', $basic_starter_comment_count, 'comments title', 'basic-starter-dev' ) ),
				number_format_i18n( $basic_starter_comment_count ),
				'<span>' . get_the_title() . '</span>'
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>

		<?php the_comments_navigation(); ?>

	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, show a note.
	if ( ! comments_open() && $basic_starter_comment_count ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'basic-starter-dev' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->

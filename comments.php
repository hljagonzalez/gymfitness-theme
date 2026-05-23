<?php
/**
 * Plantilla de comentarios en entradas del blog.
 *
 * @package gymfitness
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="entrada-comentarios" aria-label="<?php esc_attr_e( 'Comentarios', 'gymfitness' ); ?>">
	<?php if ( have_comments() ) : ?>
		<h2 class="entrada-comentarios__titulo">
			<?php
			$gymfitness_comments_count = (int) get_comments_number();
			if ( 1 === $gymfitness_comments_count ) {
				esc_html_e( 'Un comentario', 'gymfitness' );
			} else {
				printf(
					/* translators: %s: number of comments */
					esc_html__( '%s comentarios', 'gymfitness' ),
					esc_html( number_format_i18n( $gymfitness_comments_count ) )
				);
			}
			?>
		</h2>

		<ol class="entrada-comentarios__lista">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => __( 'Comentarios anteriores', 'gymfitness' ),
				'next_text' => __( 'Comentarios siguientes', 'gymfitness' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="entrada-comentarios__cerrados" role="note">
			<?php esc_html_e( 'Los comentarios están cerrados.', 'gymfitness' ); ?>
		</p>
	<?php endif; ?>

	<?php gymfitness_render_comment_feedback_notice(); ?>

	<?php comment_form(); ?>
</section>

<?php
/**
 * Plantilla individual del tipo de contenido Clases.
 *
 * @package gymfitness
 */

get_header();
?>

<main id="primary" class="site-main site-main--clase">
	<div class="contenedor clase-single-layout">
		<?php
		while ( have_posts() ) :
			the_post();
			$horario_clase = gymfitness_clase_horario_line( get_the_ID() );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clase-single clase-single-layout__cabecera' ); ?>>
				<h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>

				<?php if ( $horario_clase ) : ?>
					<p class="clase-single__horario texto-centrado"><?php echo esc_html( $horario_clase ); ?></p>
				<?php endif; ?>
			</article>

			<div class="clase-single-layout__cuerpo">
				<div class="clase-single-layout__main">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'full', array( 'class' => 'imagen-destacada' ) ); ?>
					<?php endif; ?>

					<?php if ( get_the_content() ) : ?>
						<div class="clase-single__contenido">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php
				get_sidebar(
					null,
					array(
						'id'         => 'aside-clase',
						'class'      => 'clase-aside sidebar',
						'aria_label' => __( 'Información adicional de la clase', 'gymfitness' ),
					)
				);
				?>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();

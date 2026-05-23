<?php
/**
 * Plantilla de página: listado de clases (CPT `clases`).
 *
 * Asigna esta plantilla a una página desde el editor (atributos de página).
 *
 * @package gymfitness
 */

/**
 * Template Name: Listado de Clases
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="contenedor listado-clases__intro">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>

	<?php
	$clases_query = new WP_Query(
		array(
			'post_type'      => 'clases',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);
	?>

	<section class="listado-clases contenedor" aria-label="<?php esc_attr_e( 'Listado de clases', 'gymfitness' ); ?>">
		<?php if ( $clases_query->have_posts() ) : ?>
			<ul class="listado-clases__grid">
				<?php
				while ( $clases_query->have_posts() ) :
					$clases_query->the_post();
					$clase_id       = (int) $clases_query->post->ID;
					$listado_imagen = gymfitness_clase_listado_thumbnail_html( $clase_id );
					?>
					<li class="listado-clases__item">
						<article <?php post_class( 'listado-clases__card' ); ?>>
							<?php if ( $listado_imagen ) : ?>
								<a class="listado-clases__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
									<?php
									echo $listado_imagen; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</a>
							<?php else : ?>
								<div class="listado-clases__media listado-clases__media--placeholder" aria-hidden="true"></div>
							<?php endif; ?>

							<div class="listado-clases__body">
								<h2 class="listado-clases__titulo">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<?php
								$horario_clase = gymfitness_clase_horario_line( $clase_id );
								if ( $horario_clase ) :
									?>
									<p class="listado-clases__horario"><?php echo esc_html( $horario_clase ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p class="listado-clases__vacio"><?php esc_html_e( 'No hay clases publicadas todavía.', 'gymfitness' ); ?></p>
		<?php endif; ?>
	</section>

	<?php wp_reset_postdata(); ?>
</main>

<?php
get_footer();

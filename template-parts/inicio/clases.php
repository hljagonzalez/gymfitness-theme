<?php
/**
 * Partial: muestra 4 clases en la página de inicio.
 *
 * @package gymfitness
 */

$clases_query = new WP_Query(
	array(
		'post_type'      => 'clases',
		'posts_per_page' => 4,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	)
);

if ( ! $clases_query->have_posts() ) {
	wp_reset_postdata();
	return;
}

$pagina_clases = get_page_by_path( 'nuestras-clases' );
$url_clases    = $pagina_clases ? get_permalink( $pagina_clases ) : '';
?>

<section class="inicio-clases contenedor" aria-label="<?php esc_attr_e( 'Clases destacadas', 'gymfitness' ); ?>">
	<header class="inicio-clases__cabecera">
		<h2 class="inicio-clases__titulo texto-centrado texto-primario"><?php esc_html_e( 'Nuestras clases', 'gymfitness' ); ?></h2>
		<p class="inicio-clases__intro texto-centrado">
			<?php esc_html_e( 'Descubre algunas de las actividades que ofrecemos en el gimnasio.', 'gymfitness' ); ?>
		</p>
	</header>

	<ul class="listado-clases__grid inicio-clases__grid">
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
						<h3 class="listado-clases__titulo">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
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

	<?php if ( $url_clases ) : ?>
		<p class="inicio-clases__enlace-wrap texto-centrado">
			<a class="inicio-clases__enlace" href="<?php echo esc_url( $url_clases ); ?>">
				<?php esc_html_e( 'Ver todas las clases', 'gymfitness' ); ?>
			</a>
		</p>
	<?php endif; ?>
</section>

<?php
wp_reset_postdata();

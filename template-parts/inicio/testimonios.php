<?php
/**
 * Partial: testimonios en la página de inicio (carrusel).
 *
 * @package gymfitness
 */

$testimonios_query = new WP_Query(
	array(
		'post_type'      => 'testimonios',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	)
);

$total_testimonios = (int) $testimonios_query->post_count;
?>

<section class="inicio-testimonios" aria-label="<?php esc_attr_e( 'Testimonios de clientes', 'gymfitness' ); ?>">
	<div class="inicio-testimonios__contenido">
	<header class="inicio-testimonios__cabecera">
		<h2 class="inicio-testimonios__titulo texto-centrado texto-primario">
			<?php esc_html_e( 'Testimonios', 'gymfitness' ); ?>
		</h2>
		<p class="inicio-testimonios__intro texto-centrado">
			<?php esc_html_e( 'Lo que dicen quienes entrenan con nosotros', 'gymfitness' ); ?>
		</p>
	</header>

	<?php if ( $testimonios_query->have_posts() ) : ?>
		<div
			class="inicio-testimonios__carrusel<?php echo $total_testimonios < 2 ? ' inicio-testimonios__carrusel--estatico' : ''; ?>"
			data-testimonios-carrusel
			style="--slides-visibles: 1;"
		>
			<?php if ( $total_testimonios > 1 ) : ?>
				<div class="inicio-testimonios__carrusel-controles">
					<button
						type="button"
						class="inicio-testimonios__carrusel-btn inicio-testimonios__carrusel-btn--anterior"
						aria-label="<?php esc_attr_e( 'Testimonio anterior', 'gymfitness' ); ?>"
						aria-controls="inicio-testimonios-track"
					>
						<span aria-hidden="true">&#8249;</span>
					</button>
					<button
						type="button"
						class="inicio-testimonios__carrusel-btn inicio-testimonios__carrusel-btn--siguiente"
						aria-label="<?php esc_attr_e( 'Testimonio siguiente', 'gymfitness' ); ?>"
						aria-controls="inicio-testimonios-track"
					>
						<span aria-hidden="true">&#8250;</span>
					</button>
				</div>
			<?php endif; ?>

			<div class="inicio-testimonios__viewport" aria-live="polite">
				<ul id="inicio-testimonios-track" class="inicio-testimonios__track">
					<?php
					while ( $testimonios_query->have_posts() ) :
						$testimonios_query->the_post();
						$testimonio_id = (int) $testimonios_query->post->ID;
						$cita          = get_the_content();
						?>
						<li class="inicio-testimonios__slide">
							<article <?php post_class( 'inicio-testimonios__card' ); ?>>
								<?php if ( $cita ) : ?>
									<blockquote class="inicio-testimonios__cita">
										<?php echo wp_kses_post( wpautop( $cita ) ); ?>
									</blockquote>
								<?php endif; ?>

								<footer class="inicio-testimonios__autor">
									<?php if ( has_post_thumbnail( $testimonio_id ) ) : ?>
										<div class="inicio-testimonios__avatar">
											<?php
											echo get_the_post_thumbnail(
												$testimonio_id,
												'thumbnail',
												array(
													'class' => 'inicio-testimonios__avatar-imagen',
													'alt'   => '',
												)
											); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</div>
									<?php endif; ?>

									<div class="inicio-testimonios__datos">
										<cite class="inicio-testimonios__nombre">
											<?php the_title(); ?>
										</cite>
										<?php if ( has_excerpt( $testimonio_id ) ) : ?>
											<p class="inicio-testimonios__rol">
												<?php echo esc_html( get_the_excerpt( $testimonio_id ) ); ?>
											</p>
										<?php endif; ?>
									</div>
								</footer>
							</article>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>

			<?php if ( $total_testimonios > 1 ) : ?>
				<div class="inicio-testimonios__indicadores" role="tablist" aria-label="<?php esc_attr_e( 'Paginación del carrusel', 'gymfitness' ); ?>"></div>
			<?php endif; ?>
		</div>

	<?php else : ?>
		<p class="inicio-testimonios__vacio texto-centrado">
			<?php esc_html_e( 'No hay testimonios publicados todavía.', 'gymfitness' ); ?>
		</p>
	<?php endif; ?>
	</div>
</section>

<?php
wp_reset_postdata();

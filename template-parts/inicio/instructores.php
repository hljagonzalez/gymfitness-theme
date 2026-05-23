<?php
/**
 * Partial: instructores en la página de inicio.
 *
 * @package gymfitness
 */

$instructores_query = new WP_Query(
	array(
		'post_type'      => 'instructores',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	)
);

$archivo_instructores = get_post_type_archive_link( 'instructores' );
?>

<section class="inicio-instructores contenedor" aria-label="<?php esc_attr_e( 'Nuestros instructores', 'gymfitness' ); ?>">
	<header class="inicio-instructores__cabecera">
		<h2 class="inicio-instructores__titulo texto-centrado texto-primario">
			<?php esc_html_e( 'Nuestros Instructores', 'gymfitness' ); ?>
		</h2>
		<p class="inicio-instructores__intro texto-centrado">
			<?php esc_html_e( 'Instructores profesionales que te ayudarán a lograr tus objetivos', 'gymfitness' ); ?>
		</p>
	</header>

	<?php if ( $instructores_query->have_posts() ) : ?>
		<ul class="inicio-instructores__grid">
			<?php
			while ( $instructores_query->have_posts() ) :
				$instructores_query->the_post();
				$instructor_id = (int) $instructores_query->post->ID;
				?>
				<li class="inicio-instructores__item">
					<article <?php post_class( 'inicio-instructores__card' ); ?>>
						<?php
						$especialidades = gymfitness_instructor_especialidades( $instructor_id );
						if ( ! empty( $especialidades ) ) :
							?>
							<ul class="listado-blog__categorias" aria-label="<?php esc_attr_e( 'Especialidades', 'gymfitness' ); ?>">
								<?php foreach ( $especialidades as $especialidad ) : ?>
									<li>
										<span><?php echo esc_html( $especialidad ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( has_post_thumbnail( $instructor_id ) ) : ?>
							<a class="inicio-instructores__media" href="<?php the_permalink(); ?>">
								<?php
								echo get_the_post_thumbnail(
									$instructor_id,
									'medium_large',
									array(
										'class' => 'inicio-instructores__imagen',
										'alt'   => esc_attr( get_the_title( $instructor_id ) ),
									)
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</a>
						<?php else : ?>
							<a class="inicio-instructores__media inicio-instructores__media--placeholder" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"></a>
						<?php endif; ?>

						<div class="inicio-instructores__body">
							<h3 class="inicio-instructores__nombre">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php if ( has_excerpt( $instructor_id ) ) : ?>
								<div class="inicio-instructores__resumen">
									<?php the_excerpt(); ?>
								</div>
							<?php elseif ( get_the_content() ) : ?>
								<p class="inicio-instructores__resumen">
									<?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 28, '…' ) ); ?>
								</p>
							<?php endif; ?>
						</div>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>

		<?php if ( $archivo_instructores ) : ?>
			<p class="inicio-instructores__enlace-wrap texto-centrado">
				<a class="inicio-instructores__enlace" href="<?php echo esc_url( $archivo_instructores ); ?>">
					<?php esc_html_e( 'Ver todos los instructores', 'gymfitness' ); ?>
				</a>
			</p>
		<?php endif; ?>
	<?php else : ?>
		<p class="inicio-instructores__vacio texto-centrado">
			<?php esc_html_e( 'No hay instructores publicados todavía.', 'gymfitness' ); ?>
		</p>
	<?php endif; ?>
</section>

<?php
wp_reset_postdata();

<?php
/**
 * Partial: áreas del gimnasio (página de inicio).
 *
 * @package gymfitness
 */

$areas = function_exists( 'gymfitness_get_inicio_areas' )
	? gymfitness_get_inicio_areas()
	: array();

if ( empty( $areas ) ) {
	return;
}
?>

<section class="inicio-areas" aria-label="<?php esc_attr_e( 'Áreas del gimnasio', 'gymfitness' ); ?>">
	<ul class="inicio-areas__grid">
		<?php foreach ( $areas as $area ) : ?>
			<?php
			$texto_area  = isset( $area['texto'] ) ? trim( (string) $area['texto'] ) : '';
			$imagen_html = gymfitness_inicio_area_imagen_html(
				isset( $area['imagen'] ) ? $area['imagen'] : null,
				$texto_area
			);
			?>
			<li class="inicio-areas__item">
				<div class="inicio-areas__card" tabindex="0">
					<?php if ( $imagen_html ) : ?>
						<div class="inicio-areas__media">
							<?php
							echo $imagen_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
					<?php else : ?>
						<div class="inicio-areas__media inicio-areas__media--placeholder" aria-hidden="true"></div>
					<?php endif; ?>

					<?php if ( $texto_area ) : ?>
						<div class="inicio-areas__body">
							<p class="inicio-areas__titulo"><?php echo esc_html( $texto_area ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
